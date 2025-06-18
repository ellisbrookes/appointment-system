@extends("layouts.layout")

@section("content")
    <div
        class="flex h-full flex-col space-y-4 p-4 md:flex-row md:items-center md:space-y-0"
    >
        <x-shared.header
            type="sidebar"
            heading="Register"
            subheading="Use this page to register your account"
        />

        <div class="h-full md:flex md:w-full md:items-center md:justify-center">
            <form
                method="POST"
                action="{{ route("register") }}"
                class="flex flex-col space-y-4 rounded-md border p-16"
            >
                @csrf

                <!-- Name -->
                <div>
                    <x-shared.input-label for="name" :value="__('Name')" />
                    <x-shared.text-input
                        id="name"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                    />

                    <x-shared.input-error
                        :messages="$errors->get('name')"
                        class="mt-2"
                    />
                </div>

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

                    <x-shared.input-error
                        :messages="$errors->get('email')"
                        class="mt-2"
                    />
                </div>

         <div class="mt-4">
            <x-input-label for="company_name" :value="__('Company Name (Optional)')" />
            <x-text-input type="text" name="company_name" id="company_name" class="block mt-1 w-full"
                :value="old('company_name')" />
            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
        </div>


        <!-- Password -->
        <div>
          <x-shared.input-label for="password" :value="__('Password')" />
          <x-shared.text-input id="password" type="password" name="password" required />

                    <x-shared.input-error
                        :messages="$errors->get('telephone_number')"
                        class="mt-2"
                    />
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
                        class="mt-2"
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
                        class="mt-2"
                    />
                </div>

                <div class="flex items-center justify-end">
                    <a
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                        href="{{ route("login") }}"
                    >
                        {{ __("Already registered?") }}
                    </a>

                    <x-shared.primary-button class="ms-4">
                        {{ __("Register") }}
                    </x-shared.primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection
