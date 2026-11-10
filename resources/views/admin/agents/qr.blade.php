<x-layouts.admin title="QR de {{ $agent->name }}">
    <x-admin.page-header :title="$agent->name" :description="'Código QR de la tarjeta'.($agent->position ? ' · '.$agent->position : '')">
        <x-slot:actions>
            <a href="{{ route('admin.agents.edit', $agent) }}" class="btn-secondary">
                <x-icon name="pencil" class="size-4" /> Editar
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    @php($isLocalUrl = Str::contains(config('app.url'), ['localhost', '127.0.0.1']))

    @if ($isLocalUrl)
        <div class="mb-6 flex gap-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            <x-icon name="alert" class="mt-0.5 size-4.5 shrink-0" />
            <p>
                Este QR apunta a <strong>{{ config('app.url') }}</strong>, que solo abre en esta computadora.
                Antes de imprimirlo, cambia <code class="rounded bg-amber-100 px-1">APP_URL</code> en el archivo <code class="rounded bg-amber-100 px-1">.env</code> por el dominio público.
            </p>
        </div>
    @endif

    @unless ($agent->is_active)
        <div class="mb-6 flex gap-3 rounded-lg border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-700">
            <x-icon name="alert" class="mt-0.5 size-4.5 shrink-0" />
            <p>La tarjeta está inactiva: quien escanee este QR verá “no encontrado”.</p>
        </div>
    @endunless

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_auto]">
        <section class="self-start rounded-xl border border-slate-200 bg-white p-5 shadow-xs sm:p-8">
            <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start">
                <div class="w-full max-w-[260px] shrink-0 rounded-2xl border border-slate-200 p-3 [&>svg]:block [&>svg]:h-auto [&>svg]:w-full">
                    {!! $svg !!}
                </div>

                <div class="flex w-full min-w-0 flex-col gap-5">
                    <div>
                        <p class="text-sm font-medium text-slate-700">Enlace de la tarjeta</p>
                        <div class="mt-1.5 flex gap-2">
                            <input type="text" readonly value="{{ $agent->cardUrl() }}" class="admin-input bg-slate-50 text-slate-600" onclick="this.select()">
                            <button type="button" data-copy="{{ $agent->cardUrl() }}" class="btn-secondary shrink-0">
                                <x-icon name="copy" class="size-4" /> <span data-copy-label>Copiar</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-slate-700">Descargar</p>
                        <div class="mt-1.5 flex flex-wrap gap-2">
                            <a href="{{ route('admin.agents.qr.download', [$agent, 'png']) }}" class="btn-primary">
                                <x-icon name="download" class="size-4" /> PNG para imprimir
                            </a>
                            <a href="{{ route('admin.agents.qr.download', [$agent, 'svg']) }}" class="btn-secondary">
                                <x-icon name="download" class="size-4" /> SVG (diseño)
                            </a>
                        </div>
                    </div>

                    <div class="rounded-lg bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        Escanéalo con la cámara del celular para abrir la tarjeta de {{ Str::of($agent->name)->before(' ') }}.
                        <a href="{{ route('card.show', $agent) }}" target="_blank" class="inline-flex items-center gap-1 font-medium text-brand-600 hover:text-brand-700">
                            Abrir aquí <x-icon name="external" class="size-3.5" />
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Live phone preview --}}
        <section class="hidden lg:block">
            <p class="mb-2 text-center text-xs font-medium tracking-wide text-slate-500 uppercase">Vista en celular</p>
            <div class="h-[640px] w-[316px] rounded-[2.75rem] border-[8px] border-slate-900 bg-slate-900 shadow-xl">
                <iframe src="{{ route('card.show', $agent) }}" title="Vista previa de la tarjeta" class="size-full rounded-[2.2rem] bg-navy"></iframe>
            </div>
        </section>
    </div>
</x-layouts.admin>
