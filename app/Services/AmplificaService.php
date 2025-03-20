<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AmplificaService
{
    private const TOKEN_CACHE_KEY = 'amplifica_token';
    private const TOKEN_CACHE_TTL = 60;
    private const BASE_URL = 'https://postulaciones.amplifica.io';

    private $username;
    private $password;

    public function getToken(string $username = null, string $password = null): string
    {
        if ($username && $password) {
            $this->username = $username;
            $this->password = $password;
        }

        return Cache::remember(self::TOKEN_CACHE_KEY, self::TOKEN_CACHE_TTL, function () {
            return $this->fetchNewToken();
        });
    }

    private function fetchNewToken(): string
    {
        $response = Http::withoutVerifying()
            ->post(self::BASE_URL . '/auth', [
                'username' => $this->username,
                'password' => $this->password
            ]);

        if ($response->status() !== 200) {
            throw new \Exception('Failed to fetch token: ' . $response->body());
        }

        return $response->json('token');
    }

    public function getRegions(): array
    {
        $response = Http::withoutVerifying()->withToken($this->getToken())
            ->get(self::BASE_URL . '/regionalConfig');

            if ($response->status() !== 200) {
                $this->getToken();
                Log::info("respuesta Regiones:", [$response]);
                return $this->getRegions();
            }

        return $response->json();
    }

    public function getRate(string $comuna, array $products): array
    {
        $response = Http::withoutVerifying()->withToken($this->getToken())
            ->post(self::BASE_URL . '/getRate', [
                'comuna' => $comuna,
                'products' => $products
            ]);

            if ($response->status() !== 200) {
                $this->getToken();
                Log::info("respuesta Rate:", [$response]);
                // return $this->getRate($comuna, $products);
                throw new \Exception('Failed to fetch rate: ' . $response->body());
            }

        return $response->json();
    }

    public function submitOrder(array $orderData): array
    {
        return [
            'orderNumber' => 'ORD-' . time(),
            'items' => $orderData['products'],
            'region' => $orderData['region'],
            'status' => 'Pendiente',
            'message' => 'Tu pedido ha sido recibido y está siendo procesado.'
        ];
    }
} 