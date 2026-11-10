@csrf

<div class="flex flex-col gap-6">
    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs sm:p-6">
        <h2 class="font-semibold">Datos del agente</h2>
        <p class="mt-1 text-sm text-slate-500">Así aparecerá en la tarjeta.</p>

        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <x-admin.field name="name" label="Nombre completo" :value="$agent->name" required maxlength="120" placeholder="Cristina Godoy Inciso" />
            <x-admin.field name="position" label="Cargo" :value="$agent->position" maxlength="120" placeholder="Controller" />
            <div class="sm:col-span-2">
                <x-admin.field name="slug" label="Enlace de la tarjeta" :value="$agent->slug" maxlength="120"
                               :prefix="Str::after(rtrim(config('app.url'), '/'), '://').'/c/'"
                               placeholder="se genera con el nombre"
                               :hint="$agent->exists ? 'Si lo cambias, los QR ya impresos dejarán de funcionar.' : 'Déjalo vacío para generarlo automáticamente.'" />
            </div>
        </div>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs sm:p-6">
        <h2 class="font-semibold">Contacto</h2>
        <p class="mt-1 text-sm text-slate-500">Se usan en “Guardar contacto”, WhatsApp y los íconos inferiores.</p>

        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <x-admin.field name="phone" type="tel" label="Teléfono" :value="$agent->phone" maxlength="30" placeholder="+51 987 654 321" />
            <x-admin.field name="whatsapp" type="tel" label="WhatsApp" :value="$agent->whatsapp" maxlength="30" placeholder="51987654321"
                           hint="Con código de país, sin espacios ni +." />
            <div class="sm:col-span-2">
                <x-admin.field name="email" type="email" label="Correo" :value="$agent->email" maxlength="255" placeholder="nombre@psgroup.com" />
            </div>
        </div>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs sm:p-6">
        <h2 class="font-semibold">Web y redes sociales</h2>
        <p class="mt-1 text-sm text-slate-500">Opcionales. Los íconos vacíos no se muestran en la tarjeta.</p>

        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <x-admin.field name="website" label="Sitio web" :value="$agent->website" maxlength="255" placeholder="https://psgroup.com" />
            </div>
            <x-admin.field name="instagram" label="Instagram" :value="$agent->instagram" maxlength="255" placeholder="@psgroup o enlace" />
            <x-admin.field name="facebook" label="Facebook" :value="$agent->facebook" maxlength="255" placeholder="psgroup o enlace" />
        </div>
    </section>

    <section class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-xs sm:p-6">
        <div>
            <h2 class="font-semibold">Tarjeta activa</h2>
            <p class="mt-1 text-sm text-slate-500">Si la desactivas, el QR mostrará “no encontrado”.</p>
        </div>
        <label class="relative inline-flex shrink-0 cursor-pointer items-center">
            <input type="checkbox" name="is_active" value="1" class="peer sr-only" @checked(old('is_active', $agent->is_active))>
            <span class="h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-brand-500 peer-focus-visible:ring-3 peer-focus-visible:ring-brand-500/30"></span>
            <span class="absolute left-0.5 size-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span>
        </label>
    </section>

    <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.agents.index') }}" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">{{ $agent->exists ? 'Guardar cambios' : 'Crear agente y generar QR' }}</button>
    </div>
</div>
