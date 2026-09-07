@props(['name', 'label' => null, 'options' => [], 'value' => null, 'placeholder' => null, 'required' => false, 'hint' => null])

@php($id = 'field_'.\Illuminate\Support\Str::snake($name))
@php($hasError = old('errors.'.$name) !== null)

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-white">{{ $label }} @if ($required)<span class="text-brand-magenta">*</span>@endif</label>
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        @if ($required) required @endif
        class="input-base {{ $hasError ? 'input-error' : '' }}"
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $option)
            <option value="{{ $option['value'] }}" {{ old($name, $value) == $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
        @endforeach
    </select>

    @if ($hint)
        <p id="hint_{{ $id }}" class="mt-1 text-xs text-brand-muted">{{ $hint }}</p>
    @endif

    @if ($hasError)
        <p class="mt-1 text-xs text-red-400">{{ old('errors.' . $name) }}</p>
    @endif
</div>