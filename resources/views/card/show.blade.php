<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#061a4d">
    <title>{{ $agent->name }} · {{ config('card.company') }}</title>
    <meta name="description" content="{{ $agent->name }}{{ $agent->position ? ', '.$agent->position : '' }} en {{ config('card.company') }}.">

    <meta property="og:title" content="{{ $agent->name }} · {{ config('card.company') }}">
    <meta property="og:description" content="{{ $agent->position }}">
    <meta property="og:image" content="{{ asset('images/logo_ps.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/logo_ps.png') }}">
    <link rel="preload" as="image" href="{{ asset('images/fondo_avion.webp') }}">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-navy-deep font-display text-white antialiased">
    <main class="relative mx-auto flex min-h-dvh max-w-[480px] flex-col overflow-hidden bg-navy shadow-2xl shadow-black/60">
        {{-- Background photo + legibility overlay --}}
        <img src="{{ asset('images/fondo_avion.webp') }}" alt="" class="absolute inset-0 size-full object-cover object-top" fetchpriority="high">
        <div class="absolute inset-0 bg-linear-to-b from-navy-deep/20 via-navy/55 to-navy-deep/30"></div>

        <div class="relative flex flex-1 flex-col items-center px-[clamp(1.25rem,7vw,1.75rem)] pt-[max(2rem,env(safe-area-inset-top))] pb-[max(4.5rem,calc(env(safe-area-inset-bottom)+3.5rem))]">
            {{-- Logo --}}
            <img src="{{ asset('images/logo_ps.png') }}" alt="{{ config('card.company') }}"
                 class="w-[64%] max-w-[290px] drop-shadow-[0_6px_24px_rgba(0,0,0,0.55)]">

            {{-- Name and position --}}
            <h1 class="mt-7 text-center text-[clamp(1.6rem,7.6vw,2.15rem)] leading-tight font-semibold tracking-tight drop-shadow-[0_2px_10px_rgba(0,0,0,0.6)]">
                {{ $agent->name }}
            </h1>

            @if ($agent->position)
                <div class="mt-2 flex w-full items-center gap-4">
                    <span class="h-0.5 flex-1 rounded-full bg-linear-to-r from-transparent to-brand-cyan"></span>
                    <p class="text-[clamp(1.1rem,5vw,1.4rem)] text-white/95">{{ $agent->position }}</p>
                    <span class="h-0.5 flex-1 rounded-full bg-linear-to-l from-transparent to-brand-cyan"></span>
                </div>
            @endif

            {{-- Main actions --}}
            <nav class="mt-8 flex w-full flex-col gap-4" aria-label="Acciones">
                <a href="{{ route('card.vcard', $agent) }}" class="card-pill">
                    <x-icon name="user" class="size-[clamp(1.6rem,7.5vw,2rem)] justify-self-center" />
                    <span class="h-9 bg-white/80"></span>
                    <span class="pl-[clamp(0.75rem,4vw,1.25rem)] text-[clamp(1.05rem,5.2vw,1.3rem)] font-medium whitespace-nowrap">Guardar contacto</span>
                </a>

                @if ($website = $agent->websiteUrl())
                    <a href="{{ $website }}" target="_blank" rel="noopener" class="card-pill">
                        <x-icon name="globe" class="size-[clamp(1.6rem,7.5vw,2rem)] justify-self-center" />
                        <span class="h-9 bg-white/80"></span>
                        <span class="pl-[clamp(0.75rem,4vw,1.25rem)] text-[clamp(1.05rem,5.2vw,1.3rem)] font-medium whitespace-nowrap">Sitio Web</span>
                    </a>
                @endif

                <button type="button" data-dialog-open="lead-dialog" class="card-pill text-left">
                    <x-icon name="file-text" class="size-[clamp(1.6rem,7.5vw,2rem)] justify-self-center" />
                    <span class="h-9 bg-white/80"></span>
                    <span class="pl-[clamp(0.75rem,4vw,1.25rem)] text-[clamp(1.05rem,5.2vw,1.3rem)] font-medium whitespace-nowrap">Déjame tus datos</span>
                </button>

                @if ($whatsapp = $agent->whatsappUrl())
                    <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="card-pill">
                        <x-icon name="whatsapp" class="size-[clamp(1.6rem,7.5vw,2rem)] justify-self-center" />
                        <span class="h-9 bg-white/80"></span>
                        <span class="pl-[clamp(0.75rem,4vw,1.25rem)] text-[clamp(1.05rem,5.2vw,1.3rem)] font-medium whitespace-nowrap">WhatsApp</span>
                    </a>
                @endif
            </nav>

            {{-- Social row --}}
            @php
                $socials = array_filter([
                    ['globe', $agent->websiteUrl(), 'Sitio web'],
                    ['mail', $agent->emailUrl(), 'Correo'],
                    ['phone', $agent->phoneUrl(), 'Llamar'],
                    ['whatsapp', $agent->whatsappUrl(), 'WhatsApp'],
                    ['instagram', $agent->instagramUrl(), 'Instagram'],
                    ['facebook', $agent->facebookUrl(), 'Facebook'],
                ], fn (array $social) => filled($social[1]));
            @endphp

            @if ($socials)
                <div class="mt-auto flex w-full flex-wrap items-center justify-center gap-x-[clamp(0.3rem,1.6vw,0.65rem)] gap-y-3 pt-10">
                    @foreach ($socials as [$icon, $url, $label])
                        @unless ($loop->first)
                            <span class="h-7 w-px bg-white/45" aria-hidden="true"></span>
                        @endunless
                        <a href="{{ $url }}" @if (str_starts_with($url, 'http')) target="_blank" rel="noopener" @endif
                           class="card-social" aria-label="{{ $label }}">
                            <x-icon :name="$icon" class="size-[52%]" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </main>

    {{-- "Déjame tus datos" form --}}
    <dialog id="lead-dialog" @if ($errors->any()) data-open-on-load @endif
            class="m-auto w-[calc(100%-2rem)] max-w-sm rounded-3xl border border-white/15 bg-navy/95 p-0 font-display text-white shadow-2xl shadow-black/60 backdrop:bg-navy-deep/70 backdrop:backdrop-blur-sm">
        <form method="POST" action="{{ route('card.leads.store', $agent) }}" class="flex flex-col gap-4 p-6">
            @csrf

            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold">Déjame tus datos</h2>
                    <p class="mt-1 text-sm text-white/70">{{ Str::of($agent->name)->before(' ') }} se pondrá en contacto contigo.</p>
                </div>
                <button type="button" data-dialog-close class="-mt-1 -mr-2 grid size-9 place-items-center rounded-full text-white/70 hover:bg-white/10 hover:text-white" aria-label="Cerrar">
                    <x-icon name="x" />
                </button>
            </div>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-medium text-white/85">Nombre</span>
                <input type="text" name="name" value="{{ old('name') }}" autofocus required maxlength="120" autocomplete="name" placeholder="Tu nombre completo" class="card-input">
                @error('name') <span class="text-sm text-red-300">{{ $message }}</span> @enderror
            </label>

            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-medium text-white/85">Número</span>
                <input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="20" inputmode="tel" autocomplete="tel" placeholder="Ej. 987 654 321" class="card-input">
                @error('phone') <span class="text-sm text-red-300">{{ $message }}</span> @enderror
            </label>

            <button type="submit" class="card-pill mt-2 flex! h-14 justify-center text-lg font-semibold">
                Enviar
            </button>
        </form>
    </dialog>

    @if (session('lead_saved'))
        <div role="status" class="toast fixed top-[max(1rem,env(safe-area-inset-top))] left-1/2 z-50 flex w-[calc(100%-2rem)] max-w-sm items-center gap-3 rounded-2xl border border-brand-cyan/50 bg-navy/95 px-4 py-3.5 shadow-2xl shadow-black/50">
            <x-icon name="check" class="size-6 shrink-0 text-brand-lime" />
            <p class="text-sm">¡Gracias! Recibimos tus datos, pronto te contactaremos.</p>
        </div>
    @endif
</body>
</html>
