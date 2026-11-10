<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · Panel {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_ps.png') }}">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-dvh place-items-center bg-slate-50 px-4 py-10 font-sans text-slate-900 antialiased">
    <div class="w-full max-w-sm">
        <div class="mb-8 flex flex-col items-center text-center">
            <img src="{{ asset('images/logo_ps.png') }}" alt="{{ config('card.company') }}" class="h-24 w-auto">
            <h1 class="mt-6 text-xl font-semibold tracking-tight">Panel de tarjetas QR</h1>
            <p class="mt-1 text-sm text-slate-500">Ingresa con tu cuenta de administrador.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <x-admin.field name="email" type="email" label="Correo" required autofocus autocomplete="username" />
            <x-admin.field name="password" type="password" label="Contraseña" required autocomplete="current-password" />

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="size-4 rounded border-slate-300 accent-brand-500">
                Mantener sesión iniciada
            </label>

            <button type="submit" class="btn-primary mt-2 w-full py-2.5">Ingresar</button>
        </form>
    </div>
</body>
</html>
