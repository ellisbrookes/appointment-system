@extends('layouts.layout')

@section('content')

<div class="container mx-auto flex">
  <x-shared.header type="sidebar" heading="Login" subheading="Use this page to login" />

    <div>
      <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
         <div>
          <x-shared.input-label for="name" :value="__('Name')" />
          <x-shared.text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />

          <x-shared.input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
          <x-shared.input-label for="email" :value="__('Email')" />
          <x-shared.text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
          
          <x-shared.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Telephone number -->
        <div class="mt-4">
          <x-shared.input-label for="telephone_number" :value="__('Telephone number')" />
          <x-shared.text-input id="telephone_number" class="block mt-1 w-full" type="tel" name="telephone_number" :value="old('telephone_number')" required autocomplete="telephone_number" />
          
          <x-shared.input-error :messages="$errors->get('telephone_number')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
          <x-shared.input-label for="password" :value="__('Password')" />
          <x-shared.text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />

          <x-shared.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
          <x-shared.input-label for="password_confirmation" :value="__('Confirm Password')" />
          <x-shared.text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />

          <x-shared.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
          <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('dashboard') }}">
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