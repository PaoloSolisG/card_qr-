@props(['name', 'label', 'value' => null, 'type' => 'text', 'hint' => null, 'prefix' => null])

<label class="flex flex-col gap-1.5">
    <span class="text-sm font-medium text-slate-700">{{ $label }}</span>

    @if ($prefix)
        <span class="flex rounded-lg shadow-xs">
            <span class="inline-flex items-center rounded-l-lg border border-r-0 border-slate-300 bg-slate-50 px-3 text-sm text-slate-500">{{ $prefix }}</span>
            <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
                   {{ $attributes->class(['admin-input rounded-l-none shadow-none', 'border-red-400' => $errors->has($name)]) }}>
        </span>
    @else
        <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
               {{ $attributes->class(['admin-input', 'border-red-400' => $errors->has($name)]) }}>
    @endif

    @if ($errors->has($name))
        <span class="text-sm text-red-600">{{ $errors->first($name) }}</span>
    @elseif ($hint)
        <span class="text-xs text-slate-500">{{ $hint }}</span>
    @endif
</label>
