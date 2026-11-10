<x-layouts.admin title="Nuevo agente">
    <x-admin.page-header title="Nuevo agente" description="Completa los datos y se generará su tarjeta con código QR." />

    <form method="POST" action="{{ route('admin.agents.store') }}" class="max-w-3xl">
        @include('admin.agents._form')
    </form>
</x-layouts.admin>
