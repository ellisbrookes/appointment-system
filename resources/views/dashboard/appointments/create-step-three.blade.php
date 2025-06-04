@php
    use Carbon\Carbon;
@endphp

@extends("dashboard.layout")

@section("content")
    <div class="mx-auto flex w-full max-w-2xl flex-col justify-center p-6">
        <h2 class="mb-4 text-3xl font-semibold dark:text-gray-300">
            Review Your Appointment
        </h2>

        <form
            action="{{ route("dashboard.appointments.create.step.three.post") }}"
            method="POST"
        >
            @csrf

            <!-- Appointment Information -->
            <div class="overflow-x-auto">
                <table class="w-full rounded-lg border border-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-800 text-left text-white uppercase">
                            <th class="px-6 py-3">Information</th>
                            <th class="px-6 py-3">Details</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-700 dark:text-gray-300">
                        <tr
                            class="transition duration-200 hover:bg-gray-200 dark:hover:bg-gray-600"
                        >
                            <td class="border px-6 py-4 font-medium">
                                Service
                            </td>
                            <td class="border px-6 py-4">
                                {{ $appointment["service"] ?? "" }}
                            </td>
                        </tr>

                        <tr
                            class="transition duration-200 hover:bg-gray-200 dark:hover:bg-gray-600"
                        >
                            <td class="border px-6 py-4 font-medium">Date</td>
                            <td class="border px-6 py-4">
                                {{ Carbon::parse($appointment["date"])->format("d/m/Y") ?? "" }}
                            </td>
                        </tr>

                        <tr
                            class="transition duration-200 hover:bg-gray-200 dark:hover:bg-gray-600"
                        >
                            <td class="border px-6 py-4 font-medium">Time</td>
                            <td class="border px-6 py-4">
                                {{ $appointment["timeslot"] ?? "" }}
                            </td>
                        </tr>

                        <tr
                            class="transition duration-200 hover:bg-gray-200 dark:hover:bg-gray-600"
                        >
                            <td class="border px-6 py-4 font-medium">User</td>
                            <td class="border px-6 py-4">
                                @if (Auth::user()->admin)
                                    <select
                                        name="user_id"
                                        id="user_id"
                                        class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-xs focus:border-blue-500 focus:ring-blue-500 focus:outline-hidden"
                                    >
                                        <option value="" disabled selected>
                                            Select a user
                                        </option>

                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    {{ Auth::user()->name }}
                                    <input
                                        type="hidden"
                                        name="user_id"
                                        id="user_id"
                                        value="{{ Auth::user()->id }}"
                                    />
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Previous and Submit Buttons -->
            <div class="mt-4 flex justify-between">
                <x-shared.primary-button
                    :href="route('dashboard.appointments.create.step.two')"
                    class="bg-gray-600 hover:bg-gray-700 focus:ring-gray-500"
                >
                    {{ __("Previous Step") }}
                </x-shared.primary-button>

                <x-shared.primary-button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500"
                >
                    {{ __("Confirm") }}
                </x-shared.primary-button>
            </div>
        </form>
    </div>
@endsection
