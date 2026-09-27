@props(['name', 'label' => null, 'options' => [], 'value' => null, 'placeholder' => null, 'required' => false, 'hint' => null, 'disabled' => false])

@php($id = 'field_'.\Illuminate\Support\Str::snake($name))
@php($hasError = $errors->has($name))

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-white">{{ $label }} @if ($required)<span class="text-brand-magenta">*</span>@endif</label>
    @endif

    <select {{ $attributes->class(['input-base', 'input-error' => $hasError]) }}
        id="{{ $id }}"
        name="{{ $name }}"
        @if ($required) required @endif
        @if ($disabled) disabled @endif
        @if ($hasError) aria-invalid="true" @endif
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $option)
            @php($optValue = is_array($option) ? $option['value'] : (is_object($option) ? $option->value : $option))
            @php($optLabel = is_array($option) ? $option['label'] : (is_object($option) ? ($option->label ?? $optValue) : $optValue))
            <option value="{{ $optValue }}" @selected((string) old($name, $value) === (string) $optValue)>{{ $optLabel }}</option>
        @endforeach
    </select>

    @if ($hint)
        <p id="hint_{{ $id }}" class="mt-1 text-xs text-brand-muted">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
    @enderror
</div>