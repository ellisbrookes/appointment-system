@props(['variant' => \App\Enums\AlertVariant::Success])

<div class="border-l-4 p-4 {{ $variant->tailwindClasses() }}">
    <p class="font-medium">
        {{ $slot }}
    </p>
</div>
