@extends("dashboard.layout")

@section("content")
    <h1 class="mb-2 text-center text-4xl font-bold">Settings</h1>
    <p class="mb-6 text-center text-lg font-semibold">
        Here you can find all the settings
    </p>

    <div class="flex items-center justify-center">
        <form
            action="{{ route("store") }}"
            method="POST"
            class="w-full max-w-7xl"
        >
            @csrf
            @method("PUT")

            <div>
                <h2 class="mb-4 text-2xl font-bold">Appearance</h2>
                <x-shared.input-label
                    for="navigation_style"
                    :value="__('Navigation Type')"
                />

                <select
                    name="settings[navigation_style]"
                    id="navigation_style"
                    class="mt-1 block w-full rounded-md bg-transparent focus:border-gray-500 focus:ring-gray-500 dark:bg-gray-800 dark:focus:border-gray-600 dark:focus:ring-gray-600"
                >
                    <option
                        value="sidebar"
                        {{ old("settings.navigation_style", $settings["navigation_style"] ?? "") === "sidebar" ? "selected" : "" }}
                    >
                        Sidebar
                    </option>
                    <option
                        value="top_nav"
                        {{ old("settings.navigation_style", $settings["navigation_style"] ?? "") === "top_nav" ? "selected" : "" }}
                    >
                        Top nav
                    </option>
                </select>
            </div>

            <x-shared.primary-button class="mt-4">
                {{ __("Save") }}
            </x-shared.primary-button>
        </form>
    </div>
@endsection
