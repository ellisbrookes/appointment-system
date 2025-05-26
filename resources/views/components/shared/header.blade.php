<div {{ $attributes->merge(['class' => 'header flex flex-col justify-center items-center rounded-md ' . $headerVariant]) }}>
  <h1 class="text-4xl font-bold mb-2">{{ $heading }}</h1>
  <p class="font-medium">
    {{ $subheading }}
  </p>
</div>
