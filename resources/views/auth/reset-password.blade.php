@extends("layouts.main")

@section("content")
    <div
        class="flex h-full flex-col space-y-4 p-4 md:flex-row md:items-center md:space-y-0"
    >
        <x-shared.header
            type="sidebar"
            heading="Reset Password"
            subheading="Use this page to reset your password"
        />

        <div class="h-full md:flex md:w-full md:items-center md:justify-center">
            <form
                method="POST"
                action="{{ route("password.store") }}"
                class="flex flex-col space-y-4 rounded-md border p-16"
            >
                @csrf

                <input
                    type="hidden"
                    name="token"
                    value="{{ $request->route("token") }}"
                />

                <!-- Email address -->
                <div>
                    <x-shared.input-label for="email" :value="__('Email')" />
                    <x-shared.text-input
                        id="email"
                        type="email"
                        name="email"
                        :value="old('email', $request->email)"
                        required
                    />

                    <x-shared.input-error :messages="$errors->get('email')" />
                </div>

                <!-- Password -->
                <div>
                    <x-shared.input-label
                        for="password"
                        :value="__('Password')"
                    />
                    <x-shared.text-input
                        id="password"
                        type="password"
                        name="password"
                        required
                    />

                    <x-shared.input-error
                        :messages="$errors->get('password')"
                    />
                </div>

                <!-- Confirm password -->
                <div>
                    <x-shared.input-label
                        for="password_confirmation"
                        :value="__('Confirm Password')"
                    />
                    <x-shared.text-input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                    />

                    <x-shared.input-error
                        :messages="$errors->get('password_confirmation')"
                    />
                </div>

                <div class="flex items-center justify-end">
                    <a
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                        href="{{ route("dashboard") }}"
                    >
                        {{ __("Know your password?") }}
                    </a>

                    <x-shared.primary-button class="ms-4">
                        {{ __("Submit") }}
                    </x-shared.primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection
