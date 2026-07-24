@props(['trigger', 'align' => 'right'])

<div class="sz-dropdown" data-dropdown-wrapper style="position: relative; display: inline-block;">
    <div class="sz-dropdown-trigger" onclick="event.stopPropagation(); this.closest('[data-dropdown-wrapper]').classList.toggle('open')" style="cursor: pointer;">
        {{ $trigger }}
    </div>
    <div class="sz-dropdown-menu sz-dropdown-menu--{{ $align }}" style="position: absolute; display: none; z-index: var(--sz-z-dropdown);">
        {{ $slot }}
    </div>
</div>
