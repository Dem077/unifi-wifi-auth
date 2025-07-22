<?php
namespace Src;

class Config {
    private $data = [];
    public function __construct($envPath) {
        if (!file_exists($envPath)) throw new \Exception('.env file not found');
        foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
            [$key, $val] = explode('=', $line, 2);
            $this->data[trim($key)] = trim($val);
        }
    }
    public function get($key) {
        return $this->data[$key] ?? null;
    }
}
