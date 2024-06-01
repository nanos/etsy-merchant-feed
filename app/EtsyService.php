<?php

namespace App;

use App\Dto\EtsyListingDto;
use App\Dto\EtsyShopDto;
use App\Models\Feed;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

readonly class EtsyService
{
    private array $token;
    private int $shopId;

    public function __construct(
        private string $keyString,
        private string $secret,
        private string $baseUrl,
    )
    {
    }

    /**
     * @throws RequestException
     */
    public function authenticateWithFeed(Feed $feed): self
    {
        if($feed->token_expires_at->isBefore(now()->addMinutes(5))) {
            $this->refreshToken($feed);
        }
        return $this
            ->authenticate($feed->auth_token)
            ->setShopId($feed->shop_id);
    }

    public function authenticate(array $auth_token): self
    {
        $this->token = $auth_token;
        return $this;
    }

    public function setShopId(int $shopId): self
    {
        $this->shopId = $shopId;
        return $this;
    }

    /**
     * @throws RequestException
     */
    public function getAccessToken(
        string $code,
        string $code_verifier
    ): array
    {
        return Http::post(
            'https://api.etsy.com/v3/public/oauth/token',
            [
                'grant_type' => 'authorization_code',
                'client_id' => $this->keyString,
                'redirect_uri' => route('etsy.callback'),
                'code' => $code,
                'code_verifier' => $code_verifier,
            ]
        )
            ->throw()
            ->json();
    }

    /**
     * @return Collection<EtsyListingDto>
     * @throws ConnectionException
     * @throws RequestException
     */
    public function listings(): Collection
    {
        $listings = collect();
        $offset = 0;

        while(!isset($result) || count($result['results']) > 0) {
            $result = $this->get('shops/' . $this->shopId .'/listings', [
                'offset' => $offset,
                'limit' => 100,
                'includes' => ['Images'],
            ]);
            foreach($result['results'] as $listing) {
                $listings->add(EtsyListingDto::make($listing));
            }
            $offset+= count($result['results']);
        }

        return $listings;
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function getShop(): EtsyShopDto
    {
        $response = $this->get('users/' . $this->shopId .'/shops');
        return new EtsyShopDto(
            $response['shop_id'],
            $response['shop_name'],
            $response['user_id'],
            $response['title'],
            $response['currency_code'],
            $response['url'],
        );
    }

    public function getConnectUrl(string $state, string $code_verifier): string
    {
        $query = [
            'scope' => 'listings_r shops_r',
            'client_id' => $this->keyString,
            'response_type' => 'code',
            'redirect_uri' => route('etsy.callback'),
            'state' => $state,
            'code_challenge_method' => 'S256',
            'code_challenge' => strtr(rtrim(
                base64_encode(hash('sha256', $code_verifier, true))
            , '='), '+/', '-_'),
        ];

        return 'https://www.etsy.com/oauth/connect?' . http_build_query($query);
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    private function get(
        string $endpoint,
        array $query = [],
    ): array
    {
        return Http
            ::withHeaders([
                'x-api-key' => $this->keyString,
                'Authorization' => 'Bearer ' . $this->token['access_token'],
            ])
            ->get($this->baseUrl . 'application/' . $endpoint, $query)
            ->throw()
            ->json();
    }

    /**
     * @throws RequestException
     */
    private function refreshToken(Feed $feed): self
    {
        $token = Http::post(
            'https://api.etsy.com/v3/public/oauth/token',
            [
                'grant_type' => 'refresh_token',
                'client_id' => $this->keyString,
                'refresh_token' => $feed->auth_token['refresh_token'],
            ]
        )
            ->throw()
            ->json();

        $feed->update([
            'auth_token' => $token,
            'token_expires_at' => now()->addSeconds($token['expires_in']),
        ]);

        return $this;
    }
}
