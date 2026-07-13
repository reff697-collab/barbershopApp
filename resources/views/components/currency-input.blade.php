@props(['name', 'value' => null, 'required' => false, 'placeholder' => 'Contoh: 20.000'])

@php
    $displayValue = $value !== null && $value !== ''
        ? number_format((float) $value, 0, ',', '.')
        : '';
@endphp

<div>
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
        <input
            type="text"
            inputmode="numeric"
            id="{{ $name }}_display"
            placeholder="{{ $placeholder }}"
            value="{{ old(str_replace('[]', '', $name), $displayValue) }}"
            oninput="
                let raw = this.value.replace(/[^\d]/g, '');
                this.value = raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                document.getElementById('{{ $name }}_hidden').value = raw;
            "
            {{ $attributes->merge(['class' => 'w-full border rounded-md pl-9 pr-3 py-2 text-sm']) }}
            {!! $required ? 'required' : '' !!}
        >
    </div>
    <input
        type="hidden"
        name="{{ $name }}"
        id="{{ $name }}_hidden"
        value="{{ old(str_replace('[]', '', $name), $value ?? '') }}"
    >
</div>