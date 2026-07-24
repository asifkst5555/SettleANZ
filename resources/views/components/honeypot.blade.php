@php
    // Generate a randomized honeypot field name and ID for each form render
    $fieldName = 'hp_field_' . bin2hex(random_bytes(4));
    $fieldId = 'hp_id_' . bin2hex(random_bytes(4));
    
    // Encrypt the randomized name so the backend can verify it securely
    $encryptedKey = Crypt::encryptString($fieldName);
@endphp

<div class="hp-input-container" style="position: absolute; left: -9999px; top: -9999px; width: 1px; height: 1px; overflow: hidden;" aria-hidden="true">
    <input type="hidden" name="honeypot_key" value="{{ $encryptedKey }}">
    <label for="{{ $fieldId }}">If you are human, leave this field blank</label>
    <input type="text" name="{{ $fieldName }}" id="{{ $fieldId }}" tabindex="-1" autocomplete="off">
</div>
