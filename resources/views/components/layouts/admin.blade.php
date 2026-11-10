@props(['title' => null])

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' · ' : '' }}Panel {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_ps.png') }}">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-slate-50 font-sans text-slate-900 antialiased">
    @php
        $navigation = [
            ['admin.dashboard', 'admin.dashboard', 'dashboard', 'Inicio'],
            ['admin.agents.index', 'admin.agents.*', 'users', 'Agentes'],
            ['admin.leads.index', 'admin.leads.*', 'inbox', 'Datos recibidos'],
        ];
    @endphp

    <div class="lg:flex">
        {{-- Sidebar (desktop) / dropdown menu (mobile) --}}
        <aside class="border-b border-slate-200 bg-white lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col lg:border-r lg:border-b-0">
            <div class="flex h-16 items-center justify-between gap-3 px-4 lg:px-5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo_ps.png') }}" alt="" class="h-9 w-auto">
                    <span class="leading-tight">
                        <span class="block text-sm font-semibold">PS Group</span>
                        <span class="block text-xs text-slate-500">Tarjetas QR</span>
                    </span>
                </a>
                <button type="button" data-menu-toggle="admin-menu" class="btn-icon lg:hidden" aria-label="Abrir menú">
                    <x-icon name="menu" />
                </button>
            </div>

            <div id="admin-menu" class="hidden border-t border-slate-100 px-3 pb-3 lg:flex lg:flex-1 lg:flex-col lg:border-t-0 lg:pb-4">
                <nav class="flex flex-col gap-1 pt-3">
                    @foreach ($navigation as [$route, $pattern, $icon, $label])
                        <a href="{{ route($route) }}"
                           @class([
                               'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                               'bg-brand-50 text-brand-600' => request()->routeIs($pattern),
                               'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs($pattern),
                           ])>
                            <x-icon :name="$icon" class="size-4.5" />
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>

                <div class="mt-3 border-t border-slate-100 pt-3 lg:mt-auto">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <span class="grid size-8 shrink-0 place-items-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700">
                            {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-medium">{{ auth()->user()->name }}</span>
                            <span class="block truncate text-xs text-slate-500">{{ auth()->user()->email }}</span>
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-icon" title="Cerrar sesión" aria-label="Cerrar sesión">
                                <x-icon name="log-out" class="size-4.5" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <main class="min-w-0 flex-1 lg:pl-64">
            <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                @if (session('status'))
                    <div role="status" class="mb-6 flex items-center gap-2.5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        <x-icon name="check" class="size-4.5 shrink-0" />
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
