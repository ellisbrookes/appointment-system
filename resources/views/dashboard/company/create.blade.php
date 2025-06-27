@extends("dashboard.layout")

@section("content")
    <h1 class="mb-2 text-center text-4xl font-bold">Company Creation</h1>
    <p class="mb-6 text-center text-lg font-semibold">
        Here you can create a company
    </p>

    <div class="flex items-center justify-center">
        <form
            action="{{ route("dashboard.companies.store") }}"
            method="POST"
            class="w-full max-w-md space-y-4"
        >
            @csrf

            <div class="mb-4">
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

            <div class="mb-4">
                <x-shared.input-label for="email" :value="__('Email')" />
                <x-shared.text-input
                    id="name"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
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
                    :value="old('phone_number')"
                    pattern="^(?:0|\+?44)(?:\d\s?){9,10}$"
                    required
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
                    :value="old('address')"
                    required
                />
                <x-shared.input-error :messages="$errors->get('address')" />
            </div>

            <div class="mb-4">
                <x-shared.input-label for="postcode" :value="__('Postcode')" />
                <x-shared.text-input
                    id="postcode"
                    type="text"
                    name="postcode"
                    :value="old('postcode')"
                    required
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
                    :value="old('description')"
                    required
                />
                <x-shared.input-error
                    :messages="$errors->get('description')"
                />
            </div>

            <div class="mb-4">
                <x-shared.input-label for="url" :value="__('Company URL')" />
                <div class="flex items-center">
                    <x-shared.text-input
                        id="url"
                        type="text"
                        name="url"
                        :value="old('url')"
                        placeholder="company-name"
                        class="rounded-r-none"
                    />
                    <span class="px-3 py-2 bg-gray-50 border border-l-0 border-gray-300 rounded-r-md text-gray-500">localhost:8000/</span>
                </div>
                <p class="mt-1 text-sm text-gray-600">This will be your company's unique URL</p>
                <x-shared.input-error :messages="$errors->get('url')" />
            </div>

            <div>
                <x-shared.primary-button>
                    {{ __("Create") }}
                </x-shared.primary-button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const urlInput = document.getElementById('url');
            
            nameInput.addEventListener('input', function() {
                if (!urlInput.dataset.manuallyEdited) {
                    const slug = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                        .replace(/\s+/g, '-') // Replace spaces with hyphens
                        .replace(/-+/g, '-') // Replace multiple hyphens with single
                        .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
                    
                    urlInput.value = slug;
                }
            });
            
            urlInput.addEventListener('input', function() {
                this.dataset.manuallyEdited = 'true';
            });
        });
    </script>
@endsection
