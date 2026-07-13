<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-coral-400 to-coral-500 rounded-xl font-medium text-xs text-white uppercase tracking-widest hover:from-coral-500 hover:to-coral-600 focus:outline-none focus:ring-2 focus:ring-coral-300 focus:ring-offset-2 transition']) }}>
    {{ $slot }}
</button>