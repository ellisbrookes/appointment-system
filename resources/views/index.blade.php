@extends("layouts.layout")

@section("content")
    <div class="py-24 text-center">
        <h1 class="text-4xl font-bold">Welcome to Appointment System</h1>
        <p class="mt-6 text-lg">
            Your reliable tool for managing appointments effectively.
        </p>
    </div>

    <section
        id="features"
        class="border-b-2 border-gray-200 bg-blue-100 px-4 py-12 pt-20"
    >
        <div class="container mx-auto text-center">
            <h2 class="mb-8 text-4xl font-bold">Features</h2>
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (["Feature 1", "Feature 2", "Feature 3", "Feature 4"] as $feature)
                    <div class="rounded-lg bg-white p-6 shadow-lg">
                        <h3 class="mb-4 text-xl font-semibold">
                            {{ $feature }}
                        </h3>
                        <p>Description of {{ strtolower($feature) }}.</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section
        id="statistics"
        class="border-b-2 border-gray-200 bg-gray-50 px-4 py-12 pt-20"
    >
        <div class="container mx-auto text-center">
            <h2 class="mb-8 text-4xl font-bold">Our Achievements</h2>
            <div class="flex justify-around gap-8">
                @foreach ([["Users", "10,000+", "users"], ["Appointments Scheduled", "50,000+", "calendar-check"]] as $stat)
                    <div class="max-w-xs rounded-lg bg-white p-8 shadow-lg">
                        <i
                            class="fas fa-{{ $stat[2] }} fa-3x mb-4 text-blue-600"
                        ></i>
                        <h3 class="mb-2 text-3xl font-semibold">
                            {{ $stat[1] }}
                        </h3>
                        <p class="text-lg text-gray-500">{{ $stat[0] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section
        id="contact"
        class="border-b-2 border-gray-200 bg-blue-100 px-4 py-12 pt-20"
    >
        <div class="container mx-auto text-center">
            <h2 class="mb-4 text-4xl font-bold">Contact Us</h2>
            <p class="mb-8 text-lg">
                Have questions or need support? We're here to help!
            </p>
            <form class="mx-auto flex max-w-md flex-col gap-4">
                <input
                    type="text"
                    placeholder="Your Name"
                    required
                    class="rounded-lg border border-gray-300 p-4 text-lg focus:ring-2 focus:ring-blue-500"
                />
                <input
                    type="email"
                    placeholder="Your Email"
                    required
                    class="rounded-lg border border-gray-300 p-4 text-lg focus:ring-2 focus:ring-blue-500"
                />
                <textarea
                    placeholder="Your Message"
                    rows="5"
                    required
                    class="resize-none rounded-lg border border-gray-300 p-4 text-lg focus:ring-2 focus:ring-blue-500"
                ></textarea>
                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 p-3 text-lg text-white transition-colors hover:bg-blue-700"
                >
                    Send Message
                </button>
            </form>
        </div>
    </section>
@endsection
