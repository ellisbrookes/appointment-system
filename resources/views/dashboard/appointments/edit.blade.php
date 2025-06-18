@extends("dashboard.layout")

@section("content")
    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6">
            <div>
                <h2 class="text-2xl leading-tight font-semibold">
                    Edit Appointment
                </h2>
            </div>

            <div
                class="border border-gray-400 bg-white p-6 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-900"
            >
                <form
                    action="{{ route("dashboard.appointments.update", $appointment->id) }}"
                    method="POST"
                    class="mx-auto max-w-xl"
                >
                    @csrf
                    @method("PUT")

                    <!-- Service Field -->
                    <div class="mb-5">
                        <label
                            for="service"
                            class="mb-1 block font-semibold dark:text-white"
                        >
                            Service
                        </label>

                        <input
                            type="text"
                            id="service"
                            name="service"
                            value="{{ old("service", $appointment->service) }}"
                            class="w-full rounded-sm border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-hidden"
                        />

                        @error("service")
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Date Field -->
                    <div class="mb-5">
                        <label
                            for="date"
                            class="mb-1 block font-semibold dark:text-white"
                        >
                            Date
                        </label>

                        <input
                            type="date"
                            id="date"
                            name="date"
                            value="{{ old("date", $appointment->date) }}"
                            class="w-full rounded-sm border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-hidden"
                        />

                        @error("date")
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Timeslot Field -->
                    <div class="mb-5">
                        <label
                            for="timeslot"
                            class="mb-1 block font-semibold dark:text-white"
                        >
                            Timeslot
                        </label>

                        <input
                            type="time"
                            id="timeslot"
                            name="timeslot"
                            value="{{ old("timeslot", $appointment->timeslot) }}"
                            class="w-full rounded-sm border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-hidden"
                        />

                        @error("timeslot")
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- User Dropdown -->
                    <div class="mb-6">
                        <label
                            for="user_id"
                            class="mb-1 block font-semibold dark:text-white"
                        >
                            Select User
                        </label>
                        \

                        <select
                            id="user_id"
                            name="user_id"
                            class="w-full rounded-sm border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-hidden"
                        >
                            @foreach ($users as $user)
                                <option
                                    value="{{ $user->id }}"
                                    {{ $user->id == old("user_id", $appointment->user_id) ? "selected" : "" }}
                                >
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                        @error("user_id")
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="rounded-sm bg-blue-600 px-6 py-2 font-semibold text-white shadow-sm transition duration-150 ease-in-out hover:bg-blue-700"
                    >
                        Update Appointment
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
