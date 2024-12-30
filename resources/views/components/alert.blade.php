@props(['type'])

@switch($type)
    @case('success')
        <div class="border-l-4 p-4 bg-green-100 text-green-800 border-green-300">
            <p class="font-medium">
                {{ $slot }}
            </p>
        </div>
        @break

    @case('danger')
        <div class="border-l-4 p-4 bg-red-100 text-red-800 border-red-300">
            <p class="font-medium">
                {{ $slot }}
            </p>
        </div>
        @break

    @case('warning')
        <div class="border-l-4 p-4 bg-yellow-100 text-yellow-800 border-yellow-300">
            <p class="font-medium">
                {{ $slot }}
            </p>
        </div>
        @break

    @default
        Default case...
@endswitch
