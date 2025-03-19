@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <div id="cart-section">
        <h2>Shopping Cart</h2>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div id="cart-items">
                            <!-- Cart items will be dynamically added here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <div id="order-summary">
                            <p>Total Items: <span id="total-items">0</span></p>
                            <p>Total Price: $<span id="total-price">0.00</span></p>
                        </div>
                        <button class="btn btn-primary w-100" onclick="submitOrder()">Submit Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Check for JWT token on page load
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('jwt_token');
        if (!token) {
            window.location.href = '/';
            return;
        }
    });

    // Sample product data
    const products = [
        {
            id: 1,
            name: "Product 1",
            price: 29.99,
            quantity: 1
        },
        {
            id: 2,
            name: "Product 2",
            price: 39.99,
            quantity: 1
        },
        {
            id: 3,
            name: "Product 3",
            price: 49.99,
            quantity: 1
        }
    ];

    // Function to render cart items
    function renderCart() {
        const cartContainer = document.getElementById('cart-items');
        cartContainer.innerHTML = '';
        
        products.forEach(product => {
            const item = document.createElement('div');
            item.className = 'd-flex justify-content-between align-items-center mb-3';
            item.innerHTML = `
                <div>
                    <h5>${product.name}</h5>
                    <p class="mb-0">$${product.price.toFixed(2)}</p>
                </div>
                <div class="input-group" style="width: 150px;">
                    <button class="btn btn-outline-secondary" onclick="updateQuantity(${product.id}, -1)">-</button>
                    <input type="number" class="form-control text-center" value="${product.quantity}" 
                           onchange="updateQuantity(${product.id}, 0, this.value)">
                    <button class="btn btn-outline-secondary" onclick="updateQuantity(${product.id}, 1)">+</button>
                </div>
            `;
            cartContainer.appendChild(item);
        });
        
        updateSummary();
    }

    // Function to update quantity
    function updateQuantity(productId, change, newValue = null) {
        const product = products.find(p => p.id === productId);
        if (product) {
            if (newValue !== null) {
                product.quantity = parseInt(newValue) || 0;
            } else {
                product.quantity = Math.max(0, product.quantity + change);
            }
            renderCart();
        }
    }

    // Function to update summary
    function updateSummary() {
        const totalItems = products.reduce((sum, product) => sum + product.quantity, 0);
        const totalPrice = products.reduce((sum, product) => sum + (product.price * product.quantity), 0);
        
        document.getElementById('total-items').textContent = totalItems;
        document.getElementById('total-price').textContent = totalPrice.toFixed(2);
    }

    // Function to submit order
    async function submitOrder() {
        const token = localStorage.getItem('jwt_token');
        if (!token) {
            window.location.href = '/';
            return;
        }

        try {
            const orderData = {
                items: products.map(product => ({
                    id: product.id,
                    quantity: product.quantity
                }))
            };

            const response = await fetch('/api/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(orderData)
            });

            const result = await response.json();
            if (response.ok) {
                alert('Order submitted successfully!');
                // Reset quantities
                products.forEach(product => product.quantity = 0);
                renderCart();
            } else {
                alert('Error submitting order: ' + result.error);
            }
        } catch (error) {
            alert('Error submitting order: ' + error.message);
        }
    }

    // Initial render
    renderCart();
</script>
@endpush
