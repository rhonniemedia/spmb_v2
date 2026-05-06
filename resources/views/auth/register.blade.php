<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-700">
        <h2 class="text-3xl font-bold mb-6 text-center">Buat Akun</h2>
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Nama Lengkap" class="w-full p-3 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500">
            <input type="email" name="email" placeholder="Email" class="w-full p-3 rounded bg-gray-700 border border-gray-600">
            <input type="password" name="password" placeholder="Password" class="w-full p-3 rounded bg-gray-700 border border-gray-600">
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full p-3 rounded bg-gray-700 border border-gray-600">
            <button class="w-full bg-purple-600 hover:bg-purple-700 p-3 rounded font-bold transition">Daftar</button>
        </form>
        <div class="mt-6 border-t border-gray-700 pt-6">
            <a href="{{ route('google.login') }}" class="flex items-center justify-center gap-2 w-full bg-white text-black p-3 rounded font-bold hover:bg-gray-200">
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5"> Login dengan Google
            </a>
        </div>
    </div>
</body>

</html>