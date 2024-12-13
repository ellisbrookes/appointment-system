@extends('partials.dashboard.layout')

@section('content')
            <form action="{{ route('appointments.create.step.one') }}" method="POST">
                @csrf
                <div class="mt-6">
                    <label for="service" class="block text-gray-700">Choose a Service:</label>
                    <select name="service" id="service" class="w-full p-3 mt-2 border rounded-md">
                        <option value="" disabled selected>Select a service</option>
                        <option value="1">Service 1</option>
                        <option value="2">Service 2</option>
                        <option value="3">Service 3</option>
                    </select>
                </div>
                <div class="mt-6 text-center">
                    <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded-md">Next Step</button>
                </div>
            </form>
        </div>
    </div>
@endsection
