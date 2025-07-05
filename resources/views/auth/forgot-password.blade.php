@extends("layouts.main")

@section("content")
    <div
        class="flex h-full flex-col space-y-4 p-4 md:flex-row md:items-center md:space-y-0"
    >
        <x-shared.header
            type="sidebar"
            heading="Login"
            subheading="Use this page to login to your account"
        />

        <div class="h-full md:flex md:w-full md:items-center md:justify-center">
            <form
                method="POST"
                action="{{ route("password.email") }}"
                class="flex flex-col space-y-4 rounded-md border p-16"
            >
                @csrf

                <!-- Email address -->
                <div>
                    <x-shared.input-label for="email" :value="__('Email')" />
                    <x-shared.text-input
                        id="email"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                    />

                    <x-shared.input-error :messages="$errors->get('email')" />
                </div>

                <div class="flex items-center justify-end">
                    <a
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                        href="{{ route("dashboard") }}"
                    >
                        {{ __("Remember Password?") }}
                    </a>

                    <x-shared.primary-button class="ms-4">
                        {{ __("Submit") }}
                    </x-shared.primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection
