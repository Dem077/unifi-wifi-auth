<?php
namespace Src;

class UniFiApi {
    private $config;
    private $cookieFile;
    public function __construct($config) {
        $this->config = $config;
        $this->cookieFile = tempnam(sys_get_temp_dir(), 'unifi_cookie');
    }
    private function request($method, $endpoint, $data = null) {
        $url = rtrim($this->config->get('UNIFI_URL'), '/') . $endpoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
        $resp = curl_exec($ch);
        if ($resp === false) throw new \Exception('UniFi API request failed: ' . curl_error($ch));
        curl_close($ch);
        return json_decode($resp, true);
    }
    private function login() {
        $this->request('POST', '/api/login', [
            'username' => $this->config->get('UNIFI_USERNAME'),
            'password' => $this->config->get('UNIFI_PASSWORD')
        ]);
    }
    public function authorizeMac($mac, $minutes) {
        $this->login();
        $site = $this->config->get('UNIFI_SITE_ID');
        $resp = $this->request('POST', "/api/s/$site/cmd/stamgr", [
            'cmd' => 'authorize-guest',
            'mac' => $mac,
            'minutes' => $minutes
        ]);
        if (!isset($resp['meta']['rc']) || $resp['meta']['rc'] !== 'ok') {
            throw new \Exception('Failed to authorize MAC.');
        }
    }
}
