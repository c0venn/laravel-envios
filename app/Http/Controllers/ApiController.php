<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Services\AmplificaService;


class ApiController extends Controller
{
    protected $amplificaService;

    public function __construct(AmplificaService $amplificaService)
    {
        $this->amplificaService = $amplificaService;
    }

    public function token(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            $token = $this->amplificaService->getToken($validated['email'], $validated['password']);
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRegions()
    {
        try {
            $regions = $this->amplificaService->getRegions();
            return response()->json($regions);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRate(Request $request)
    {
        try {
            $validated = $request->validate([
                'comuna' => 'required|string',
                'products' => 'required|array',
                'products.*.weight' => 'required|numeric',
                'products.*.quantity' => 'required|integer|min:1'
            ]);

            $rate = $this->amplificaService->getRate(
                $validated['comuna'],
                $validated['products']
            );

            return response()->json($rate);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function submitOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'products' => 'required|array',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'shipping_address' => 'required|array',
                'shipping_address.comuna' => 'required|string',
                'shipping_address.address' => 'required|string'
            ]);

            $order = $this->amplificaService->submitOrder($validated);
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
