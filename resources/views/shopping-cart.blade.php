@extends('layouts.app')

@section('title', 'Shopping Cart')

@include('partials.auth-check')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between p-4">
                    <h1 class="mt-2">Carrito de compras</h1>
                    <svg height="40px" width="40px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:#000000;} </style> <g> <path class="st0" d="M494.365,133.438c-11.528-15.738-29.35-25.678-48.804-27.219L126.245,80.918l-3.129-17.749 c-5.252-29.676-28.858-52.657-58.671-57.066L24.597,0.187C15.093-1.209,6.253,5.359,4.84,14.846 c-1.409,9.512,5.158,18.356,14.658,19.761v-0.008l39.861,5.907c14.97,2.218,26.868,13.785,29.501,28.71l54.009,306.134 c5.073,28.735,30.027,49.669,59.206,49.678h221.971c9.606,0,17.394-7.792,17.394-17.39c0-9.607-7.788-17.398-17.394-17.398H202.075 c-12.282,0.009-22.813-8.836-24.946-20.934l-5-28.349h244.879c31.234,0,58.346-21.55,65.367-52.005l23.258-100.767 c1.151-5,1.717-10.06,1.717-15.086C507.35,158.962,502.88,145.04,494.365,133.438z M319.692,125.33l72.469,5.745v180.898h-72.469 V125.33z M296.502,311.973h-72.47V117.752l72.47,5.746V311.973z M131.451,110.415l69.395,5.496v196.062h-33.833L131.451,110.415z M477.378,181.668l-23.245,100.776c-3.986,17.278-19.385,29.53-37.125,29.53h-1.657V132.916l27.926,2.208 c11.049,0.866,21.174,6.516,27.706,15.447c4.838,6.592,7.381,14.495,7.381,22.526C478.363,175.949,478.038,178.809,477.378,181.668 z"></path> <path class="st0" d="M215.346,450.165c-17.076,0-30.909,13.853-30.909,30.926c0,17.073,13.832,30.909,30.909,30.909 c17.078,0,30.927-13.836,30.927-30.909C246.273,464.018,232.423,450.165,215.346,450.165z"></path> <path class="st0" d="M381.539,450.165c-17.073,0-30.913,13.853-30.913,30.926c0,17.073,13.84,30.909,30.913,30.909 c17.072,0,30.922-13.836,30.922-30.909C412.461,464.018,398.612,450.165,381.539,450.165z"></path> </g> </g></svg>
                </div>

                <div class="card-body">
                    <div id="cart-items">
                    </div>

                    <div class="mt-4">
                        <h5>Información de envío</h5>
                        <div class="mb-3">
                            <label for="region" class="form-label">Selecciona una región</label>
                            <select id="region" class="form-select" onchange="cartService.calculateShippingRate()">
                                <option value="">Selecciona una región</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>Resumen de la orden</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total de productos:</span>
                            <span id="total-items">0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Peso total:</span>
                            <span id="total-weight">0g</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tarifa de envío:</span>
                            <span id="shipping-rate">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Precio total:</strong>
                            <strong id="total-price">$0.00</strong>
                        </div>
                        <button class="btn btn-primary m-1 w-100" onclick="cartService.submitOrder()">
                            Enviar pedido
                        </button>
                        <button class="btn btn-danger m-1 w-100" onclick="window.location.href='/logout'">
                            Salir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('js/services/cartService.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        cartService.loadRegions();
        cartService.renderCart();
    });
</script>
@endpush
