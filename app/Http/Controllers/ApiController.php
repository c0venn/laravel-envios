<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class ApiController extends Controller
{
    private $url = 'https://postulaciones.amplifica.io/';
    private $token = null;

    public function token(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        Log::info($request->email);
        $response = Http::withOptions(['verify' => false])->post($this->url . 'auth', [
            'username' => $request->email,
            'password' => '12345'
        ]);
        Log::info('respuesta:',[$response]);

        if ($response->successful()) {
            $this->token = $response->json()['token'];
            return response()->json(['token' => $this->token]);
        }

        return response()->json(['error' => 'Error de autenticación'], 401);
    }

    public function submitOrder(Request $request)
    {
        if (!$this->token) {
            return response()->json(['error' => 'No authentication token available'], 401);
        }

        $response = Http::withToken($this->token)
            ->post($this->url . 'order', [
                'items' => $request->items
            ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Error submitting order'], $response->status());
    }
}
