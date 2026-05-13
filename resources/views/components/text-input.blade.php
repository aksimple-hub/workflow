@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#10b981] focus:ring-[#10b981] rounded-md shadow-sm']) }}>
