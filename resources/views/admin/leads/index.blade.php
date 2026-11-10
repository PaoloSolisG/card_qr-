<x-layouts.admin title="Datos recibidos">
    <x-admin.page-header title="Datos recibidos" description="Personas que dejaron su nombre y número desde una tarjeta.">
        <x-slot:actions>
            <a href="{{ route('admin.leads.export', request()->only('agent', 'q')) }}" class="btn-secondary">
                <x-icon name="download" class="size-4" /> Exportar Excel (CSV)
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <form method="GET" class="mb-4 flex flex-col gap-2 sm:flex-row">
        <select name="agent" class="admin-input sm:max-w-56" onchange="this.form.submit()">
            <option value="">Todos los agentes</option>
            @foreach ($agents as $agentOption)
                <option value="{{ $agentOption->id }}" @selected($agentId === $agentOption->id)>{{ $agentOption->name }}</option>
            @endforeach
        </select>
        <div class="relative flex-1 sm:max-w-xs">
            <x-icon name="search" class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-slate-400" />
            <input type="search" name="q" value="{{ $search }}" placeholder="Buscar nombre o número" class="admin-input pl-9">
        </div>
        <button type="submit" class="btn-secondary">Filtrar</button>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xs">
        @if ($leads->isEmpty())
            <div class="px-5 py-14 text-center text-sm text-slate-500">
                {{ $agentId || $search ? 'No hay datos con esos filtros.' : 'Aún no hay datos recibidos.' }}
            </div>
        @else
            <table class="w-full text-sm">
                <thead class="hidden border-b border-slate-200 bg-slate-50 text-left text-xs font-medium tracking-wide text-slate-500 uppercase md:table-header-group">
                    <tr>
                        <th class="px-5 py-3">Nombre</th>
                        <th class="px-5 py-3">Número</th>
                        <th class="px-5 py-3">Agente</th>
                        <th class="px-5 py-3">Fecha</th>
                        <th class="px-5 py-3"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($leads as $lead)
                        <tr class="grid grid-cols-[1fr_auto] gap-x-4 gap-y-1 px-4 py-3.5 md:table-row md:p-0">
                            <td class="font-medium md:px-5 md:py-3.5">{{ $lead->name }}</td>
                            <td class="col-start-1 md:px-5 md:py-3.5">
                                <a href="{{ $lead->whatsappUrl() }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1.5 text-slate-700 hover:text-emerald-600" title="Escribir por WhatsApp">
                                    <x-icon name="whatsapp" class="size-4 text-emerald-500" /> {{ $lead->phone }}
                                </a>
                            </td>
                            <td class="col-start-1 text-xs text-slate-500 md:px-5 md:py-3.5 md:text-sm md:text-slate-600">
                                <span class="md:hidden">Para </span>{{ $lead->agent?->name }}
                            </td>
                            <td class="col-start-2 row-span-1 row-start-1 text-right text-xs text-slate-400 md:px-5 md:py-3.5 md:text-left md:text-sm md:text-slate-500">
                                <span title="{{ $lead->created_at->format('d/m/Y H:i') }}">{{ $lead->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="col-start-2 row-start-2 row-span-2 self-center justify-self-end md:justify-self-auto md:px-5 md:py-3.5 md:text-right">
                                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" data-confirm="¿Eliminar el contacto de {{ $lead->name }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon hover:bg-red-50 hover:text-red-600" title="Eliminar" aria-label="Eliminar">
                                        <x-icon name="trash" class="size-4" />
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-4">{{ $leads->links() }}</div>
</x-layouts.admin>
