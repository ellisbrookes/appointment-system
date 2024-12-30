@props(['variant' => \App\Enums\AlertVariant::cases()])

@php
    die($variant)
@endphp

<div class="border-l-4 p-4 {{ $variant[0]->alertClasses() }}">
    <p class="font-medium">
        {{ $slot }}
    </p>
</div>
