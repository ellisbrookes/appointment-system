@extends('layouts.layout')

@section('content')
  <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 h-full p-4">
    <x-shared.header type="sidebar" heading="Login" subheading="Use this page to login to your account" />

    <div class="md:flex md:justify-center md:items-center h-full md:w-full">
      <form method="POST" action="{{ route('login') }}" class="flex flex-col space-y-4 p-16 rounded-md border">
        @csrf

        <!-- Email address -->
        <div>
          <x-shared.input-label for="email" :value="__('Email')" />
          <x-shared.text-input id="email" type="email" name="email" :value="old('email')" required />
          
          <x-shared.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
          <x-shared.input-label for="password" :value="__('Password')" />
          <x-shared.text-input id="password" type="password" name="password" required />

          <x-shared.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex flex-col items-center justify-end space-y-2">
          <x-shared.primary-button>
            {{ __('Login') }}
          </x-shared.primary-button>

          <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
            {{ __('Need an account?') }}
          </a>

          <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
            {{ __('Forgot Password?') }}
          </a>

        </div>
      </form>
    </div>
  </div>
@endsection