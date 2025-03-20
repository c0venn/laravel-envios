@extends('layouts.app')

<main>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Cerrando sesión...</h3>
                    </div>
                    <div class="card-body text-center">
                        <p>Gracias por usar nuestro servicio.</p>
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            localStorage.removeItem('jwt_token');
            setTimeout(() => {
                window.location.href = '/';
            }, 1500);
        });
    </script>
</main>
