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

            {{-- Appearance Section --}}
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

                <p class="mt-2 text-sm text-gray-500">
                    Navigation will be set on sidebar by default
                </p>
            </div>

            {{-- Timeslot Section --}}
            <div class="mt-4">
                <h2 class="mb-4 text-2xl font-bold">Timeslot</h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Start Time --}}
                    <div>
                        <x-shared.input-label
                            for="settings[timeslot_start]"
                            :value="'Start Time'"
                        />
                        <input
                            type="time"
                            name="settings[timeslot_start]"
                            value="{{ old("settings.timeslot_start", $settings["timeslot_start"]) }}"
                            class="w-full rounded-md border-gray-300 dark:bg-gray-800"
                        />
                    </div>

                    {{-- End Time --}}
                    <div>
                        <x-shared.input-label
                            for="settings[timeslot_end]"
                            :value="'End Time'"
                        />
                        <input
                            type="time"
                            name="settings[timeslot_end]"
                            value="{{ old("settings.timeslot_end", $settings["timeslot_end"]) }}"
                            class="w-full rounded-md border-gray-300 dark:bg-gray-800"
                        />
                    </div>

                    {{-- Interval --}}
                    <div>
                        <x-shared.input-label
                            for="settings[timeslot_interval]"
                            :value="'Gap (in minutes)'"
                        />
                        <input
                            type="number"
                            name="settings[timeslot_interval]"
                            min="5"
                            max="120"
                            step="5"
                            value="{{ old("settings.timeslot_interval", $settings["timeslot_interval"]) }}"
                            class="w-full rounded-md border-gray-300 dark:bg-gray-800"
                        />
                    </div>

                    {{-- Time Format --}}
                    <div>
                        <x-shared.input-label
                            for="settings[time_format]"
                            :value="'Time Format'"
                        />
                        <select
                            name="settings[time_format]"
                            id="time_format"
                            class="w-full rounded-md border-gray-300 dark:bg-gray-800"
                        >
                            <option
                                value="24"
                                {{ old("settings.time_format", $settings["time_format"]) === "24" ? "selected" : "" }}
                            >
                                24 Hour (14:30)
                            </option>
                            <option
                                value="12"
                                {{ old("settings.time_format", $settings["time_format"]) === "12" ? "selected" : "" }}
                            >
                                12 Hour (2:30 PM)
                            </option>
                        </select>
                    </div>

                    {{-- Timezone --}}
                    <div>
                        <x-shared.input-label
                            for="settings[timezone]"
                            :value="'Timezone'"
                        />
                        <select
                            name="settings[timezone]"
                            id="timezone"
                            class="w-full rounded-md border-gray-300 dark:bg-gray-800"
                        >
                            @foreach($timezones as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    {{ old("settings.timezone", $settings["timezone"]) === $value ? "selected" : "" }}
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <p class="mt-2 text-sm text-gray-500">
                    Timeslots will be automatically generated between start and
                    end time, using your specified interval. Timezone affects how times are displayed and stored.
                </p>
            </div>

            {{-- Submit --}}
            <x-shared.primary-button class="mt-6">
                {{ __("Save") }}
            </x-shared.primary-button>
        </form>
    </div>

    {{-- Script for Adding New Timeslot Inputs --}}
    <script>
        function addTimeslotInput() {
            const wrapper = document.getElementById('timeslot-fields');
            const input = document.createElement('input');
            input.type = 'time';
            input.name = 'settings[timeslots][]';
            input.className =
                'w-32 rounded-md border-gray-300 dark:bg-gray-800 mt-1';
            wrapper.appendChild(input);
        }
    </script>
@endsection
