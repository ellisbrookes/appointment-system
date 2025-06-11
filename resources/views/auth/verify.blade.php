@extends('layouts.layout')

@section('content')
  <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 h-full p-4">
    <x-shared.header type="sidebar" heading="Verify Email" subheading="Verify your email to continue" />

    <div class="md:flex md:justify-center md:items-center h-full md:w-full">
      <div class="flex flex-col space-y-4 p-16 rounded-md border">
        <p class="text-gray-600 dark:text-gray-300">
          Before proceeding, please check your email for a verification link.
          If you did not receive the email, you can request another by clicking the button below.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
          @csrf

          <x-shared.primary-button>
            {{ __('Resend Verification Email') }}
          </x-shared.primary-button>
        </form>
      </div>
    </div>
  </div>
@endsection
