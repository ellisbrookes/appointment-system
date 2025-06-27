@extends("dashboard.layout")

@section("content")
    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6">
            <h2 class="text-2xl leading-tight font-semibold">
                {{ __("Edit profile") }}
            </h2>

            <div
                class="border bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-900"
            >
                <div class="max-w-xl">
                    @include("dashboard.profile.partials.update-profile-information-form")
                </div>
            </div>

            <div
                class="border bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-900"
            >
                <div class="max-w-xl">
                    @include("dashboard.profile.partials.update-password-form")
                </div>
            </div>

            <div
                class="border bg-white p-4 sm:rounded-lg sm:p-8 dark:bg-gray-900"
            >
                <div class="max-w-xl">
                    @include("dashboard.profile.partials.delete-user-form")
                </div>
            </div>
        </div>
    </div>
@endsection
