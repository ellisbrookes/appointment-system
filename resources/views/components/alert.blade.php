<div {{ $attributes->merge(['class' => 'border-l-4 p-4 my-2 ' . $alertVariant]) }}>
    <p class="font-medium">
        {{ $slot }}
    </p>
</div>
