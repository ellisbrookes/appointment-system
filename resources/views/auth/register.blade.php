@extends("layouts.main")

@section("content")
    <div
        class="flex h-full flex-col space-y-4 p-4 md:flex-row md:items-center md:space-y-0"
    >
        <x-shared.header
            type="sidebar"
            heading="{{ isset($companyInvite) && $companyInvite ? 'Join ' . $companyInvite->name : 'Register' }}"
            subheading="{{ isset($companyInvite) && $companyInvite ? 'Create your account to join ' . $companyInvite->name : 'Use this page to register your account' }}"
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
                    <x-shared.input-error :messages="$errors->get('name')" />
                </div>

                <!-- Email address -->
                <div>
                    <x-shared.input-label for="email" :value="__('Email')" />
                    <x-shared.text-input
                        id="email"
                        type="email"
                        name="email"
                        :value="old('email', $prefilledEmail ?? '')"
                        :disabled="isset($prefilledEmail) && $prefilledEmail"
                        required
                    />
                    @if(isset($prefilledEmail) && $prefilledEmail)
                        <p class="mt-1 text-sm text-gray-600">This email is pre-filled from your invitation.</p>
                    @endif
                    <x-shared.input-error :messages="$errors->get('email')" />
                </div>
                
                @if(isset($companyInvite) && $companyInvite)
                    <!-- Hidden company invitation field -->
                    <input type="hidden" name="company_invite" value="{{ $companyInvite->id }}">
                    
                    <!-- Company invitation notice -->
                    <div class="rounded-md bg-blue-50 p-4 border border-blue-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    You're joining {{ $companyInvite->name }}
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>After creating your account, you'll automatically become a member of this company.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Company Name (Optional) -->
                <div class="mt-4">
                    <x-shared.input-label
                        for="company_name"
                        :value="__('Company Name (Optional)')"
                    />
                    <x-shared.text-input
                        type="text"
                        name="company_name"
                        id="company_name"
                        class="mt-1 block w-full"
                        :value="old('company_name')"
                    />
                    <x-shared.input-error
                        :messages="$errors->get('company_name')"
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
