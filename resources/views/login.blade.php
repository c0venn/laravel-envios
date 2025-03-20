@extends('layouts.app')

<main class="min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h3 class="text-center mb-0 fw-bold">Amplifica</h3>
                    </div>
                    <div class="card-body p-4">
                        <form id="loginForm">
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user text-primary"></i>
                                    </span>
                                    <input type="email" class="form-control form-control-lg" id="email" required 
                                           placeholder="Ingrese su email">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-lock text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control form-control-lg" id="password" required
                                           placeholder="Ingrese su contraseña">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 mt-3 shadow-sm">
                                <i class="fas fa-sign-in-alt me-2"> Ingresar</i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            try {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const response = await fetch('/api/token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ email, password })
                });

                if (!response.ok) {
                    throw new Error('Login failed');
                }

                const data = await response.json();
                
                localStorage.setItem('jwt_token', data.token);
                
                window.location.href = '/shopping-cart';
                
            } catch (error) {
                alert('Login failed. Please try again.');
                console.error('Error:', error);
            }
        });
    </script>
</main>
