@extends('dashboard.layout')

@section('content')
  <div class="flex flex-col items-center w-full max-w-7xl mx-auto">
    <h1 class="text-4xl font-bold mb-2"> Welcome, {{ auth()->user()->name }}</h1>
    <p class="text-lg font-semi-bold mb-6">Here you will find all the information you need</p>
  </div>
@endsection
