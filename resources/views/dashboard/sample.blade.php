<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $roleTitle }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 p-8 font-sans">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Dashboard {{ $roleTitle }}</h1>
                <p class="text-sm text-slate-500">Selamat datang kembali, <span class="font-semibold text-indigo-600">{{ Auth::user()->name }}</span> ({{ Auth::user()->email }})</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-semibold transition cursor-pointer">
                    Keluar / Logout
                </button>
            </form>
        </div>

        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
            Status: Autentikasi dan Role Middleware berhasil berfungsi normal.
        </div>
    </div>
</body>
</html>
