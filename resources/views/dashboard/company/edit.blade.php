@extends("dashboard.layout")

@section("content")
    <h1 class="mb-2 text-center text-4xl font-bold">Edit Company</h1>
    <p class="mb-6 text-center text-lg font-semibold">
        Update your company information
    </p>

    <div class="flex items-center justify-center">
        <form
            action="{{ route('dashboard.companies.update', $company->id) }}"
            method="POST"
            class="w-full max-w-md space-y-4"
        >
            @csrf
            @method('PUT')

            <div class="mb-4">
                <x-shared.input-label for="name" :value="__('Name')" />
                <x-shared.text-input
                    id="name"
                    type="text"
                    name="name"
                    :value="old('name', $company->name)"
                    required
                    autofocus
                />
                <x-shared.input-error :messages="$errors->get('name')" />
            </div>

            <div class="mb-4">
                <x-shared.input-label for="email" :value="__('Email')" />
                <x-shared.text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email', $company->email)"
                />
                <x-shared.input-error :messages="$errors->get('email')" />
            </div>

            <div class="mb-4">
                <x-shared.input-label
                    for="phone_number"
                    :value="__('Phone Number')"
                />
                <x-shared.text-input
                    id="phone_number"
                    type="tel"
                    name="phone_number"
                    :value="old('phone_number', $company->phone_number)"
                    pattern="^(?:0|\+?44)(?:\d\s?){9,10}$"
                />
                <x-shared.input-error
                    :messages="$errors->get('phone_number')"
                />
            </div>

            <div class="mb-4">
                <x-shared.input-label for="address" :value="__('Address')" />
                <x-shared.text-input
                    id="address"
                    type="text"
                    name="address"
                    :value="old('address', $company->address)"
                />
                <x-shared.input-error :messages="$errors->get('address')" />
            </div>

            <div class="mb-4">
                <x-shared.input-label for="postcode" :value="__('Postcode')" />
                <x-shared.text-input
                    id="postcode"
                    type="text"
                    name="postcode"
                    :value="old('postcode', $company->postcode)"
                    class="uppercase"
                />
                <x-shared.input-error :messages="$errors->get('postcode')" />
            </div>

            <div class="mb-4">
                <x-shared.input-label
                    for="description"
                    :value="__('Description')"
                />
                <x-shared.text-input
                    id="description"
                    type="text"
                    name="description"
                    :value="old('description', $company->description)"
                />
                <x-shared.input-error
                    :messages="$errors->get('description')"
                />
            </div>

            <div class="flex space-x-4">
                <x-shared.primary-button>
                    {{ __("Update") }}
                </x-shared.primary-button>
                
                <a
                    href="{{ route('dashboard.companies.show', $company->id) }}"
                    class="rounded-lg bg-gray-600 px-4 py-2 text-white transition duration-200 hover:bg-gray-700"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
