@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'placeholder' => null, 'required' => false, 'autocomplete' => null, 'hint' => null, 'readonly' => false, 'min' => null, 'max' => null, 'step' => null, 'pattern' => null, 'inputClass' => '', 'checked' => false, 'multiple' => false])

@php($id = 'field_'.\Illuminate\Support\Str::snake($name))
@php($hasError = old('errors.'.$name) !== null)

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-white">{{ $label }} @if ($required)<span class="text-brand-magenta">*</span>@endif</label>
    @endif

    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $value ?? old($name) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        {{ $checked ? 'checked' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if ($min !== null) min="{{ $min }}" @endif
        @if ($max !== null) max="{{ $max }}" @endif
        @if ($step !== null) step="{{ $step }}" @endif
        @if ($pattern) pattern="{{ $pattern }}" @endif
        class="input-base {{ $hasError ? 'input-error' : '' }} {{ $inputClass }}"
    >

    @if ($hint)
        <p id="hint_{{ $id }}" class="mt-1 text-xs text-brand-muted">{{ $hint }}</p>
    @endif

    @if ($hasError)
        <p class="mt-1 text-xs text-red-400">{{ old('errors.' . $name) }}</p>
    @endif
</div>