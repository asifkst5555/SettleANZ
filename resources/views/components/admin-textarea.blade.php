@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'sz-textarea']) }}>{{ $slot }}</textarea>
