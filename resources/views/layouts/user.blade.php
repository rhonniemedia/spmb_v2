<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Portal SPMB</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tailwind & Alpine -->

    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')

    <!-- Tailwind Config -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof tailwind !== 'undefined') {
                tailwind.config = {
                    theme: {
                        extend: {
                            fontFamily: {
                                sans: ['Plus Jakarta Sans', 'sans-serif']
                            },
                            colors: {
                                primary: {
                                    DEFAULT: '#FF1443',
                                    hover: '#D90F38',
                                    light: 'rgba(255,20,67,0.08)',
                                },
                            },
                            borderRadius: {
                                card: '20px',
                                btn: '50px',
                            }
                        }
                    }
                }
            }
        });
    </script>
</head>

<body class="user-dashboard font-sans bg-gray-50 text-[#080C1A] min-h-screen" x-data="{ mobileMenu: false }">

    @include('components.layout.user-topbar')

    <main class="max-w-[1200px] mx-auto p-6">
        @yield('content')
    </main>

    @include('components.layout.user-footbar')

    <script>
        // Gunakan document saja, tidak perlu document.body agar bisa diletakkan di head
        document.addEventListener('htmx:configRequest', (event) => {
            event.detail.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            // Tambahkan baris ini:
            event.detail.headers['X-Requested-With'] = 'XMLHttpRequest';
            event.detail.headers['Accept'] = 'application/json';
        });
    </script>

    @stack('scripts')

</body>

</html>