@extends("dashboard.layout")

@section("content")
    <div class="mx-auto flex w-full max-w-7xl flex-col items-center">
        <h1 class="mb-2 text-4xl font-bold">
            Welcome, {{ auth()->user()->name }}
        </h1>
        <p class="font-semi-bold mb-6 text-lg">
            Here you will find all the information you need
        </p>
    </div>
@endsection
