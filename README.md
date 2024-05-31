# Etsy Merchant Feeds

This is a very simple tool to generate Google Merchant Data Feeds from an Etsy Shop.

It will use your own url as base url for all products, and redirect through this, so that it can be used for tagging products in Meta stories (these require you to use your own domain).

## Install

1. Clone this repo.
2. Configure at least the following in your `.env` file:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL={your url}`
2. Go to https://www.etsy.com/developers/register and register your app.
3. You'll need to provide a callback URL. This is `https://example.com/etsy/callback` (Replace `example.com` with your own domain).
4. You'll receive a `KEYSTRING`. Supply this in your `.env` file:
    `ETSY_KEYSTRING={YOUR_KEYSTRING}`
5. Run `composer install && php artisan optimize && npm run prod`.
6. Run `php artisan user:register` to register a user
7. Log in and follow the prompts.
