@props(['tableClass' => 'table-basic'])

<div {{ $attributes->merge(['class' => 'overflow-x-auto rounded-xl border border-brand-border bg-brand-card']) }}>
    <table class="{{ $tableClass }}">
        @isset($head)
            <thead>
                {{ $head }}
            </thead>
        @endisset

        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>