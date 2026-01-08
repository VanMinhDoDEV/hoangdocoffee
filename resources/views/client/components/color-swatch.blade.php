@php
$mode = $as ?? 'button';
$isActive = (bool)($active ?? false);
$colorValue = (string)($value ?? '');
$selectedIds = ($selectedIds ?? collect());
$inputName = $name ?? 'colors[]';
$inputValue = $id ?? null;
@endphp
@if($mode === 'filter')
<label class="{{ $selectedIds->contains($inputValue) ? 'active' : '' }}" title="{{ $colorValue }}" style="cursor: pointer; display: inline-block; margin-right: 6px; margin-bottom: 6px;">
    <input type="checkbox" name="{{ $inputName }}" value="{{ $inputValue }}" {{ $selectedIds->contains($inputValue) ? 'checked' : '' }} style="display:none;">
    <button type="button" class="ul-color-swatch {{ $selectedIds->contains($inputValue) ? 'active' : '' }}" data-color="{{ $colorValue }}" style="background-color: {{ $colorValue }};" aria-label="{{ $colorValue }}"></button>
</label>
@elseif($mode === 'button')
<button type="button" class="ul-color-swatch {{ $isActive ? 'active' : '' }}" data-color="{{ $colorValue }}" style="background-color: {{ $colorValue }};" aria-label="{{ $colorValue }}"></button>
@else
<span class="color-swatch {{ $isActive ? 'active' : '' }}" style="background-color: {{ $colorValue }};" title="{{ $colorValue }}"></span>
@endif
