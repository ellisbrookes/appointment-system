@extends("dashboard.layout")

@section("content")
    <div class="mx-auto flex w-full max-w-2xl flex-col justify-center p-6">
        <h2 class="mb-4 text-3xl font-semibold dark:text-gray-200">
            Choose a Service
        </h2>

        <form
            action="{{ route("dashboard.appointments.create.step.one.post") }}"
            method="POST"
        >
            @csrf

            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="text-red-500">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Service Selection -->
            <div class="mt-4">
                <label
                    for="service"
                    class="block text-lg text-gray-800 dark:text-white"
                >
                    Choose a Service:
                </label>

                <select
                    name="service"
                    id="service"
                    class="mt-2 w-full rounded-md border border-gray-300 p-3 focus:ring-2 focus:ring-blue-500 focus:outline-hidden dark:border-gray-200 dark:bg-gray-800 dark:text-white"
                    value="{{ $appointment->service ?? "" }}"
                >
                    <option value="" disabled selected>Select a service</option>
                    <option value="hair" class="">Hair</option>
                    <option value="nails">Nails</option>
                    <option value="wax">Wax</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="mt-4 text-center">
                <button
                    type="submit"
                    class="focus:ring-opacity-50 w-full rounded-md bg-blue-600 px-4 py-3 text-white transition duration-200 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-hidden"
                >
                    Next Step
                </button>
            </div>
        </form>
    </div>
@endsection
