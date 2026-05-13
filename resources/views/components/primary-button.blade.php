<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#214371] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#1a3660] focus:bg-[#1a3660] active:bg-[#152e50] focus:outline-none focus:ring-2 focus:ring-[#214371] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
