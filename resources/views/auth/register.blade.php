@extends('layouts.layout')

@section('content')
  <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 h-full p-4">
    <x-shared.header type="sidebar" heading="Register" subheading="Use this page to register your account" />

    <div class="md:flex md:justify-center md:items-center h-full md:w-full">
      <form method="POST" action="{{ route('register') }}" class="flex flex-col space-y-4 p-16 rounded-md border">
        @csrf

        <!-- Name -->
        <div>
          <x-shared.input-label for="name" :value="__('Name')" />
          <x-shared.text-input id="name" type="text" name="name" :value="old('name')" required autofocus />

          <x-shared.input-error :messages="$errors->get('name')"  bclass="mt-2" />
        </div>

        <!-- Email address -->
        <div>
          <x-shared.input-label for="email" :value="__('Email')" />
          <x-shared.text-input id="email" type="email" name="email" :value="old('email')" required />
          
          <x-shared.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Telephone number -->
        <div>
          <x-shared.input-label for="telephone_number" :value="__('Telephone number')" />
          <x-shared.text-input id="telephone_number" type="tel" name="telephone_number" :value="old('telephone_number')" />
          
          <x-shared.input-error :messages="$errors->get('telephone_number')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
          <x-shared.input-label for="password" :value="__('Password')" />
          <x-shared.text-input id="password" type="password" name="password" required />

          <x-shared.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm password -->
        <div>
          <x-shared.input-label for="password_confirmation" :value="__('Confirm Password')" />
          <x-shared.text-input id="password_confirmation" type="password" name="password_confirmation" required />

          <x-shared.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end">
          <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('dashboard') }}">
            {{ __('Already registered?') }}
          </a>

          <x-shared.primary-button class="ms-4">
            {{ __('Register') }}
          </x-shared.primary-button>
        </div>
      </form>
    </div>
  </div>
@endsection