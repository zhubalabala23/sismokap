<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SISMOKAP') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Custom Navy/Military Style -->
        <style>
            :root {
                --primary-navy: #0F172A;
                --military-navy: #1E293B;
                --accent-gold: #C5A880;
                --accent-gold-hover: #E2C9A1;
                --text-muted: #94A3B8;
                --card-bg: rgba(30, 41, 59, 0.75);
            }
            
            body {
                font-family: 'Outfit', 'Montserrat', sans-serif;
                background: linear-gradient(135deg, var(--primary-navy) 0%, #020617 100%);
                min-height: 100vh;
                color: #F8FAFC;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow-x: hidden;
                position: relative;
            }

            /* Military Badge / Insignia Background Details */
            body::before {
                content: "";
                position: absolute;
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(197, 168, 128, 0.05) 0%, rgba(0,0,0,0) 70%);
                top: -100px;
                left: -100px;
                z-index: 0;
            }

            body::after {
                content: "";
                position: absolute;
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(15, 23, 42, 0.8) 0%, rgba(0,0,0,0) 70%);
                bottom: -100px;
                right: -100px;
                z-index: 0;
            }

            .auth-container {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 450px;
                padding: 15px;
            }

            .auth-card {
                background: var(--card-bg);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(197, 168, 128, 0.2);
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
                padding: 2.5rem 2rem;
            }

            .auth-logo {
                font-size: 2.2rem;
                font-weight: 800;
                letter-spacing: 2px;
                color: var(--accent-gold);
                text-shadow: 0 0 10px rgba(197, 168, 128, 0.3);
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
            }

            .auth-subtitle {
                font-size: 0.85rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 3px;
                color: var(--text-muted);
                text-align: center;
                margin-bottom: 2rem;
            }

            .form-control {
                background: rgba(15, 23, 42, 0.6);
                border: 1px solid rgba(148, 163, 184, 0.3);
                color: #F8FAFC;
                padding: 0.75rem 1rem;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .form-control:focus {
                background: rgba(15, 23, 42, 0.8);
                border-color: var(--accent-gold);
                box-shadow: 0 0 0 0.25rem rgba(197, 168, 128, 0.25);
                color: #F8FAFC;
            }

            .form-label {
                font-size: 0.9rem;
                font-weight: 500;
                color: #E2E8F0;
                margin-bottom: 0.5rem;
            }

            .btn-military {
                background: linear-gradient(135deg, var(--accent-gold) 0%, #A38865 100%);
                color: #0F172A;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                transition: all 0.3s ease;
                width: 100%;
            }

            .btn-military:hover {
                background: linear-gradient(135deg, var(--accent-gold-hover) 0%, var(--accent-gold) 100%);
                color: #020617;
                box-shadow: 0 0 15px rgba(197, 168, 128, 0.4);
                transform: translateY(-1px);
            }

            .auth-link {
                color: var(--accent-gold);
                text-decoration: none;
                font-size: 0.875rem;
                transition: all 0.3s ease;
            }

            .auth-link:hover {
                color: var(--accent-gold-hover);
                text-decoration: underline;
            }

            .text-muted-custom {
                color: var(--text-muted);
            }

            .checkbox-custom .form-check-input {
                background-color: rgba(15, 23, 42, 0.6);
                border-color: rgba(148, 163, 184, 0.3);
            }

            .checkbox-custom .form-check-input:checked {
                background-color: var(--accent-gold);
                border-color: var(--accent-gold);
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="text-center mb-4">
                @php
                    $logoUrl = \App\Models\Setting::getLogoUrl();
                    $namaInstansi = \App\Models\Setting::getValue('nama_instansi', 'SISMOKAP');
                @endphp
                <a href="/" class="auth-logo">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo" style="height: 48px; object-fit: contain; margin-right: 8px;">
                    @else
                        <i class="bi bi-shield-shaded"></i>
                    @endif
                    {{ $namaInstansi }}
                </a>
                <div class="auth-subtitle">Denzibang 3/V</div>
            </div>
            
            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>

        <!-- Bootstrap 5 Bundle JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
