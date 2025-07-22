<?php
namespace Src;

use League\OAuth2\Client\Provider\GenericProvider;
use TheNetworg\OAuth2\Client\Provider\Azure;

class Auth {
    private $provider;
    private $config;
    public function __construct($config) {
        $this->config = $config;
        $this->provider = new Azure([
            'clientId' => $config->get('MICROSOFT_CLIENT_ID'),
            'clientSecret' => $config->get('MICROSOFT_CLIENT_SECRET'),
            'redirectUri' => $config->get('MICROSOFT_REDIRECT_URI'),
            'tenant' => $config->get('MICROSOFT_TENANT_ID'),
        ]);
    }
    public function getAuthUrl($mac) {
        $options = [
            'scope' => ['openid', 'profile', 'email'],
            'state' => $mac
        ];
        return $this->provider->getAuthorizationUrl($options);
    }
    public function getAccessToken() {
        if (!isset($_GET['code'])) throw new \Exception('Missing code');
        return $this->provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);
    }
    public function getEmail($token) {
        $resourceOwner = $this->provider->getResourceOwner($token);
        $data = $resourceOwner->toArray();
        return $data['mail'] ?? $data['email'] ?? $data['userPrincipalName'] ?? null;
    }
}
