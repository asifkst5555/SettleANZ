@props(['checked' => false])

<input type="radio" {{ $checked ? 'checked' : '' }} {{ $attributes->merge(['class' => 'sz-radio']) }}>
