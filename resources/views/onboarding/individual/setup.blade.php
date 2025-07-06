@extends("layouts.main")

@section("content")
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-cyan-100">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto">
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
                        <span>Step 3 of 4</span>
                        <span>75% Complete</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 75%"></div>
                    </div>
                </div>

                <!-- Individual Setup Card -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Set Up Your Individual Account</h1>
                        <p class="text-gray-600">
                            Let's configure your personal Skedulaa
                        </p>
                    </div>

                    <form action="{{ route('onboarding.individual.setup.store') }}" method="POST">
                        @csrf
                        
                        <!-- Business Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Business Information (Optional)</h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Business Name
                                    </label>
                                    <input type="text" 
                                           id="business_name" 
                                           name="business_name" 
                                           value="{{ old('business_name') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="e.g., John's Consulting">
                                    @error('business_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="business_type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Business Type
                                    </label>
                                    <input type="text" 
                                           id="business_type" 
                                           name="business_type" 
                                           value="{{ old('business_type') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="e.g., Consulting, Therapy, Coaching">
                                    @error('business_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Working Hours -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Working Hours</h3>
                            <div class="grid md:grid-cols-3 gap-6">
                                <div>
                                    <label for="working_hours_start" class="block text-sm font-medium text-gray-700 mb-2">
                                        Start Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" 
                                           id="working_hours_start" 
                                           name="working_hours_start" 
                                           value="{{ old('working_hours_start', '09:00') }}"
                                           required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('working_hours_start')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="working_hours_end" class="block text-sm font-medium text-gray-700 mb-2">
                                        End Time <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time" 
                                           id="working_hours_end" 
                                           name="working_hours_end" 
                                           value="{{ old('working_hours_end', '17:00') }}"
                                           required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('working_hours_end')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="appointment_duration" class="block text-sm font-medium text-gray-700 mb-2">
                                        Appointment Duration <span class="text-red-500">*</span>
                                    </label>
                                    <select id="appointment_duration" 
                                            name="appointment_duration" 
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="15" {{ old('appointment_duration', '30') == '15' ? 'selected' : '' }}>15 minutes</option>
                                        <option value="30" {{ old('appointment_duration', '30') == '30' ? 'selected' : '' }}>30 minutes</option>
                                        <option value="45" {{ old('appointment_duration', '30') == '45' ? 'selected' : '' }}>45 minutes</option>
                                        <option value="60" {{ old('appointment_duration', '30') == '60' ? 'selected' : '' }}>1 hour</option>
                                        <option value="90" {{ old('appointment_duration', '30') == '90' ? 'selected' : '' }}>1.5 hours</option>
                                        <option value="120" {{ old('appointment_duration', '30') == '120' ? 'selected' : '' }}>2 hours</option>
                                    </select>
                                    @error('appointment_duration')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Services -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Services You Offer (Optional)</h3>
                            <p class="text-sm text-gray-600 mb-4">Add the services you provide to clients. You can always modify these later.</p>
                            
                            <div id="services-container" class="space-y-3">
                                <div class="service-row flex gap-3">
                                    <input type="text" 
                                           name="services[]" 
                                           value="{{ old('services.0') }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="e.g., One-on-one consultation">
                                    <button type="button" onclick="removeService(this)" class="px-3 py-2 text-red-600 hover:text-red-800 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <button type="button" onclick="addService()" class="mt-3 inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Another Service
                            </button>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Complete Setup
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                            <a href="{{ route('onboarding.account-type') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                </svg>
                                Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addService() {
            const container = document.getElementById('services-container');
            const serviceRow = document.createElement('div');
            serviceRow.className = 'service-row flex gap-3';
            serviceRow.innerHTML = `
                <input type="text" 
                       name="services[]" 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="e.g., Group workshop">
                <button type="button" onclick="removeService(this)" class="px-3 py-2 text-red-600 hover:text-red-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            container.appendChild(serviceRow);
        }

        function removeService(button) {
            const container = document.getElementById('services-container');
            if (container.children.length > 1) {
                button.parentElement.remove();
            }
        }
    </script>
@endsection
