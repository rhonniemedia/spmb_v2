<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <form action="{{ route('logout') }}" method="POST">@csrf <button class="text-red-500">Logout</button></form>
        </div>
        <div class="p-6 bg-purple-50 rounded-lg border border-purple-100">
            <h2 class="text-lg font-semibold">Profil User (UUID)</h2>
            <p class="text-gray-500 text-sm mt-1">ID: <span class="font-mono text-purple-700">{{ auth()->user()->id }}</span></p>
            <p class="mt-4">Nama: <strong>{{ auth()->user()->name }}</strong></p>
            <p>Email: <strong>{{ auth()->user()->email }}</strong></p>
            <p class="mt-2 text-xs text-green-600 bg-green-100 inline-block px-2 py-1 rounded">Akun Aktif & Terverifikasi</p>
        </div>
    </div>
</body>

</html>