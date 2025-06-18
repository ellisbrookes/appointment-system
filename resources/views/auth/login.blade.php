@extends("layouts.layout")

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
                action="{{ route("login") }}"
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

                <div class="flex flex-col items-center justify-end space-y-2">
                    <x-shared.primary-button>
                        {{ __("Login") }}
                    </x-shared.primary-button>

                    <a
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                        href="{{ route("register") }}"
                    >
                        {{ __("Need an account?") }}
                    </a>

                    <a
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                        href="{{ route("password.request") }}"
                    >
                        {{ __("Forgot Password?") }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
