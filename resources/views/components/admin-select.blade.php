@props(['disabled' => false])

<div class="sz-select-wrapper">
    <select {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'sz-select']) }}>
        {{ $slot }}
    </select>
</div>
