@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'placeholder' => null, 'required' => false, 'autocomplete' => null, 'hint' => null, 'readonly' => false, 'disabled' => false, 'min' => null, 'max' => null, 'step' => null, 'pattern' => null, 'inputClass' => '', 'checked' => false, 'multiple' => false])

@php($id = 'field_'.\Illuminate\Support\Str::snake($name))
@php($hasError = $errors->has($name))

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-white">{{ $label }} @if ($required)<span class="text-brand-magenta">*</span>@endif</label>
    @endif

    <input
        {{ $attributes->class(['input-base', 'input-error' => $hasError])->merge(['class' => $inputClass]) }}
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        @if ($readonly) readonly @endif
        @if ($disabled) disabled @endif
        @if ($checked) checked @endif
        @if ($multiple) multiple @endif
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if ($min !== null) min="{{ $min }}" @endif
        @if ($max !== null) max="{{ $max }}" @endif
        @if ($step !== null) step="{{ $step }}" @endif
        @if ($pattern) pattern="{{ $pattern }}" @endif
        @if ($hasError) aria-invalid="true" @endif
    >

    @if ($hint)
        <p id="hint_{{ $id }}" class="mt-1 text-xs text-brand-muted">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
    @enderror
</div>