<x-layouts.admin title="Editar agente">
    <x-admin.page-header :title="$agent->name" description="Editar los datos de la tarjeta.">
        <x-slot:actions>
            <a href="{{ route('admin.agents.qr', $agent) }}" class="btn-secondary">
                <x-icon name="qr" class="size-4" /> Ver QR
            </a>
            <a href="{{ route('card.show', $agent) }}" target="_blank" class="btn-secondary">
                <x-icon name="external" class="size-4" /> Ver tarjeta
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.agents.update', $agent) }}" class="max-w-3xl">
        @method('PUT')
        @include('admin.agents._form')
    </form>
</x-layouts.admin>
