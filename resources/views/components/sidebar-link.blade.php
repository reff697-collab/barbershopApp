@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-3 py-2.5 rounded-xl text-sm font-medium bg-gradient-to-r from-coral-400 to-coral-500 text-white shadow-sm'
            : 'flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>