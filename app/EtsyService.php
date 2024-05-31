<?php

namespace App;

use App\Dto\EtsyShopDto;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

readonly class EtsyService
{
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
     * @throws RequestException
     * @throws ConnectionException
     */
    public function getShop(array $token): EtsyShopDto
    {
        $response = $this->get('users/' . explode('.',$token['access_token'])[0] .'/shops', $token);
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
        array $token,
        array $query = [],
    ): array
    {
        return Http
            ::withHeaders([
                'x-api-key' => $this->keyString,
                'Authorization' => 'Bearer ' . $token['access_token'],
            ])
            ->get($this->baseUrl . 'application/' . $endpoint, $query)
            ->throw()
            ->json();
    }
}
