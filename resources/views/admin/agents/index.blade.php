<x-layouts.admin title="Agentes">
    <x-admin.page-header title="Agentes" description="Cada agente tiene su tarjeta digital y su propio código QR.">
        <x-slot:actions>
            <a href="{{ route('admin.agents.create') }}" class="btn-primary">
                <x-icon name="plus" class="size-4" /> Nuevo agente
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <form method="GET" class="mb-4 flex gap-2">
        <div class="relative flex-1 sm:max-w-xs">
            <x-icon name="search" class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-slate-400" />
            <input type="search" name="q" value="{{ $search }}" placeholder="Buscar por nombre, cargo o correo" class="admin-input pl-9">
        </div>
        <button type="submit" class="btn-secondary">Buscar</button>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xs">
        @if ($agents->isEmpty())
            <div class="px-5 py-14 text-center">
                <p class="text-sm text-slate-500">{{ $search ? 'No hay agentes que coincidan con la búsqueda.' : 'Todavía no hay agentes.' }}</p>
                @unless ($search)
                    <a href="{{ route('admin.agents.create') }}" class="btn-primary mt-4">
                        <x-icon name="plus" class="size-4" /> Crear el primero
                    </a>
                @endunless
            </div>
        @else
            <table class="w-full text-sm">
                <thead class="hidden border-b border-slate-200 bg-slate-50 text-left text-xs font-medium tracking-wide text-slate-500 uppercase md:table-header-group">
                    <tr>
                        <th class="px-5 py-3">Agente</th>
                        <th class="px-5 py-3">Contacto</th>
                        <th class="px-5 py-3">Datos</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($agents as $agent)
                        <tr class="flex flex-wrap items-center gap-x-4 gap-y-2 px-4 py-4 md:table-row md:p-0">
                            <td class="min-w-0 flex-1 md:px-5 md:py-3.5">
                                <p class="font-medium">{{ $agent->name }}</p>
                                <p class="text-xs text-slate-500">{{ $agent->position ?: 'Sin cargo' }}</p>
                            </td>
                            <td class="hidden text-slate-600 md:table-cell md:px-5 md:py-3.5">
                                <p>{{ $agent->email ?: '—' }}</p>
                                <p class="text-xs text-slate-500">{{ $agent->phone }}</p>
                            </td>
                            <td class="md:px-5 md:py-3.5">
                                <a href="{{ route('admin.leads.index', ['agent' => $agent->id]) }}" class="inline-flex items-center gap-1.5 rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 hover:bg-slate-200">
                                    <x-icon name="inbox" class="size-3.5" /> {{ $agent->leads_count }}
                                </a>
                            </td>
                            <td class="md:px-5 md:py-3.5">
                                @if ($agent->is_active)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700">
                                        <span class="size-1.5 rounded-full bg-emerald-500"></span> Activa
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                                        <span class="size-1.5 rounded-full bg-slate-400"></span> Inactiva
                                    </span>
                                @endif
                            </td>
                            <td class="w-full md:w-auto md:px-5 md:py-3.5">
                                <div class="flex items-center gap-1 md:justify-end">
                                    <a href="{{ route('admin.agents.qr', $agent) }}" class="btn-secondary px-2.5 py-1.5 text-xs">
                                        <x-icon name="qr" class="size-4" /> QR
                                    </a>
                                    <a href="{{ route('card.show', $agent) }}" target="_blank" class="btn-icon" title="Ver tarjeta" aria-label="Ver tarjeta">
                                        <x-icon name="external" class="size-4" />
                                    </a>
                                    <a href="{{ route('admin.agents.edit', $agent) }}" class="btn-icon" title="Editar" aria-label="Editar">
                                        <x-icon name="pencil" class="size-4" />
                                    </a>
                                    <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}" data-confirm="¿Eliminar a {{ $agent->name }}? También se borrarán sus datos recibidos y su QR dejará de funcionar.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon hover:bg-red-50 hover:text-red-600" title="Eliminar" aria-label="Eliminar">
                                            <x-icon name="trash" class="size-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-4">{{ $agents->links() }}</div>
</x-layouts.admin>
