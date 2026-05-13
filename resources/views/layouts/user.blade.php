<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Portal SPMB</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tailwind & Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')

    <!-- Tailwind Config -->
    <script>
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
    </script>
</head>

<body class="user-dashboard font-sans bg-gray-50 text-[#080C1A] min-h-screen" x-data="{ mobileMenu: false }">

    @include('components.layout.user-topbar')

    <main class="max-w-[1400px] mx-auto p-6">
        @yield('content')
    </main>

    @include('components.layout.user-footbar')

    @stack('scripts')

</body>

</html>