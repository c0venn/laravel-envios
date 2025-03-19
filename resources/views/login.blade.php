@extends('layouts.app')

<main>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('jwt_token');
            if (token) {
                window.location.href = '/shopping-cart';
            }
        });

        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            try {
                const email = document.getElementById('email').value;
                const response = await fetch('/api/token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ email })
                });

                if (!response.ok) {
                    throw new Error('Login failed');
                }

                const data = await response.json();
                
                // Store the JWT token
                localStorage.setItem('jwt_token', data.token);
                
                // Redirect to shopping cart page
                window.location.href = '/shopping-cart';
                
            } catch (error) {
                alert('Login failed. Please try again.');
                console.error('Error:', error);
            }
        });
    </script>
</main>
