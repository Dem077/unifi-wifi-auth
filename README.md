# UniFi Captive Portal with Microsoft Entra ID (Azure AD) Login

## Features
- Users connect to UniFi guest Wi-Fi and are redirected to a login page
- Authenticate using Microsoft Entra ID (Azure AD)
- Only allows emails from `@agronational.mv`
- Authorizes device MAC via UniFi API for 24 hours
- Friendly success/error messages

## Setup
1. Copy `.env.example` to `.env` and fill in your credentials
2. Run `composer install` to install dependencies
3. Deploy the `public/` folder as your web root

## Dependencies
- league/oauth2-client
- thenetworg/oauth2-azure

## Files
- `public/index.php` — Login page and OAuth2 start
- `public/callback.php` — Handles OAuth2 callback and UniFi authorization
- `src/Config.php` — Loads config from `.env`
- `src/UniFiApi.php` — Handles UniFi API calls
- `src/Auth.php` — Handles OAuth2 logic
