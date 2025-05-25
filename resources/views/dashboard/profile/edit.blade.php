@extends('layouts.dashboard.layout')

@section('content')
  <div class="py-12">
    <div class="max-w-7xl mx-auto space-y-6">
      <h2 class="font-semibold text-2xl leading-tight">
        {{ __('Edit profile') }}
      </h2>

      <div class="p-4 sm:p-8 bg-white border dark:bg-gray-900 sm:rounded-lg">
        <div class="max-w-xl">
          @include('dashboard.profile.partials.update-profile-information-form')
        </div>
      </div>

      <div class="p-4 sm:p-8 bg-white border dark:bg-gray-900 sm:rounded-lg">
        <div class="max-w-xl">
          @include('dashboard.profile.partials.update-password-form')
        </div>
      </div>

      <div class="p-4 sm:p-8 bg-white border dark:bg-gray-900 sm:rounded-lg">
        <div class="max-w-xl">
          @include('dashboard.profile.partials.delete-user-form')
        </div>
      </div>
    </div>
  </div>
@endsection
