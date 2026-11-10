<x-layouts.admin title="Inicio">
    <x-admin.page-header title="Hola, {{ Str::of(auth()->user()->name)->before(' ') }}" description="Resumen de tus tarjetas digitales y contactos recibidos.">
        <x-slot:actions>
            <a href="{{ route('admin.agents.create') }}" class="btn-primary">
                <x-icon name="plus" class="size-4" /> Nuevo agente
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    @php
        $stats = [
            ['Agentes', $agentsCount, 'users', 'bg-brand-50 text-brand-600'],
            ['Tarjetas activas', $activeAgentsCount, 'qr', 'bg-cyan-50 text-cyan-600'],
            ['Datos recibidos', $leadsCount, 'inbox', 'bg-lime-50 text-lime-700'],
            ['Últimos 7 días', $leadsThisWeek, 'calendar', 'bg-amber-50 text-amber-600'],
        ];
    @endphp

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">
        @foreach ($stats as [$label, $value, $icon, $tone])
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-xs sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-sm text-slate-500">{{ $label }}</p>
                    <span class="grid size-8 place-items-center rounded-lg {{ $tone }}">
                        <x-icon :name="$icon" class="size-4" />
                    </span>
                </div>
                <p class="mt-3 text-3xl font-semibold tracking-tight tabular-nums">{{ number_format($value) }}</p>
            </div>
        @endforeach
    </div>

    <section class="mt-6 rounded-xl border border-slate-200 bg-white shadow-xs">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold">Últimos datos recibidos</h2>
            <a href="{{ route('admin.leads.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Ver todos</a>
        </div>

        @forelse ($latestLeads as $lead)
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-3 last:border-b-0">
                <span class="grid size-9 shrink-0 place-items-center rounded-full bg-slate-100 text-sm font-semibold text-slate-600">
                    {{ Str::upper(Str::substr($lead->name, 0, 1)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium">{{ $lead->name }}</p>
                    <p class="truncate text-xs text-slate-500">{{ $lead->phone }} · para {{ $lead->agent?->name }}</p>
                </div>
                <span class="shrink-0 text-xs text-slate-400">{{ $lead->created_at->diffForHumans() }}</span>
            </div>
        @empty
            <div class="px-5 py-10 text-center text-sm text-slate-500">
                Aún no hay datos. Cuando alguien use “Déjame tus datos” en una tarjeta, aparecerá aquí.
            </div>
        @endforelse
    </section>
</x-layouts.admin>
