@extends("layouts.layout")

@section("content")
    <div
        class="flex h-full flex-col space-y-4 p-4 md:flex-row md:items-center md:space-y-0"
    >
        <x-shared.header
            type="sidebar"
            heading="Verify Email"
            subheading="Verify your email to continue"
        />

        <div class="h-full md:flex md:w-full md:items-center md:justify-center">
            <div class="flex flex-col space-y-4 rounded-md border p-16">
                <p class="text-gray-600 dark:text-gray-300">
                    Before proceeding, please check your email for a
                    verification link. If you did not receive the email, you can
                    request another by clicking the button below.
                </p>

                <form method="POST" action="{{ route("verification.send") }}">
                    @csrf

                    <x-shared.primary-button>
                        {{ __("Resend Verification Email") }}
                    </x-shared.primary-button>
                </form>
            </div>
        </div>
    </div>
@endsection
