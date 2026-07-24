@props(['checked' => false, 'name' => '', 'id' => ''])

<label class="sz-switch" for="{{ $id ?: $name }}">
    <input type="checkbox" id="{{ $id ?: $name }}" name="{{ $name }}" {{ $checked ? 'checked' : '' }} {{ $attributes->merge(['class' => 'sz-switch__input']) }}>
    <span class="sz-switch__slider"></span>
</label>
