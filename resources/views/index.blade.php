@extends('layouts.layout')

@section('content')
  <div class="py-24 text-center">
    <h1 class="text-4xl font-bold">Welcome to Appointment System</h1>
    <p class="mt-6 text-lg">Your reliable tool for managing appointments effectively.</p>
  </div>

  <section id="features" class="bg-blue-100 border-b-2 border-gray-200 py-12 px-4 pt-20">
    <div class="container mx-auto text-center">
      <h2 class="text-4xl font-bold mb-8">Features</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach (['Feature 1', 'Feature 2', 'Feature 3', 'Feature 4'] as $feature)
          <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold mb-4">{{ $feature }}</h3>
            <p>Description of {{ strtolower($feature) }}.</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section id="statistics" class="bg-gray-50 border-b-2 border-gray-200 py-12 px-4 pt-20">
    <div class="container mx-auto text-center">
      <h2 class="text-4xl font-bold mb-8">Our Achievements</h2>
      <div class="flex justify-around gap-8">
        @foreach ([['Users', '10,000+', 'users'], ['Appointments Scheduled', '50,000+', 'calendar-check']] as $stat)
          <div class="bg-white rounded-lg shadow-lg p-8 max-w-xs">
            <i class="fas fa-{{ $stat[2] }} fa-3x text-blue-600 mb-4"></i>
            <h3 class="text-3xl font-semibold mb-2">{{ $stat[1] }}</h3>
            <p class="text-lg text-gray-500">{{ $stat[0] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section id="contact" class="bg-blue-100 border-b-2 border-gray-200 py-12 px-4 pt-20">
    <div class="container mx-auto text-center">
      <h2 class="text-4xl font-bold mb-4">Contact Us</h2>
      <p class="text-lg mb-8">Have questions or need support? We're here to help!</p>
      <form class="max-w-md mx-auto flex flex-col gap-4">
        <input type="text" placeholder="Your Name" required class="border border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-blue-500" />
        <input type="email" placeholder="Your Email" required class="border border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-blue-500" />
        <textarea placeholder="Your Message" rows="5" required class="border border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
        <button type="submit" class="bg-blue-600 text-white rounded-lg p-3 text-lg hover:bg-blue-700 transition-colors">Send Message</button>
      </form>
    </div>
  </section>
@endsection
