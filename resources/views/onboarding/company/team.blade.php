@extends("layouts.main")

@section("content")
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-cyan-100">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto">
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
                        <span>Step 4 of 4</span>
                        <span>100% Complete</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 100%"></div>
                    </div>
                </div>

                <!-- Team Setup Card -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Invite Your Team</h1>
                        <p class="text-gray-600">
                            Add team members to your company. You can always do this later from your dashboard.
                        </p>
                    </div>

                    <form action="{{ route('onboarding.company.team.store') }}" method="POST">
                        @csrf
                        
                        <!-- Team Members Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Team Member Invitations</h3>
                            <p class="text-sm text-gray-600 mb-6">
                                Invite team members by entering their email addresses. They'll receive an invitation to join your company.
                            </p>
                            
                            <div id="team-members-container" class="space-y-4">
                                <!-- Initial team member input -->
                                <div class="team-member-row">
                                    <div class="grid md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Email Address
                                            </label>
                                            <input type="email" 
                                                   name="team_members[0][email]" 
                                                   value="{{ old('team_members.0.email') }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                   placeholder="john@example.com">
                                            @error('team_members.0.email')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Name
                                            </label>
                                            <input type="text" 
                                                   name="team_members[0][name]" 
                                                   value="{{ old('team_members.0.name') }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                   placeholder="John Doe">
                                            @error('team_members.0.name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Role
                                            </label>
                                            <div class="flex items-center">
                                                <select name="team_members[0][role]" 
                                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                    <option value="member" {{ old('team_members.0.role', 'member') == 'member' ? 'selected' : '' }}>Member</option>
                                                    <option value="admin" {{ old('team_members.0.role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                                <button type="button" onclick="removeTeamMember(this)" class="ml-2 px-3 py-2 text-red-600 hover:text-red-800 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            @error('team_members.0.role')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" onclick="addTeamMember()" class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Another Team Member
                            </button>
                        </div>

                        <!-- Company Settings -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Settings</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="allow_public_booking" 
                                           name="allow_public_booking" 
                                           value="1"
                                           {{ old('allow_public_booking', true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="allow_public_booking" class="ml-2 block text-sm text-gray-900">
                                        Allow public booking on your company page
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="require_approval" 
                                           name="require_approval" 
                                           value="1"
                                           {{ old('require_approval', false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="require_approval" class="ml-2 block text-sm text-gray-900">
                                        Require approval for new appointments
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Complete Setup
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                            <a href="{{ route('onboarding.company.setup') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                </svg>
                                Back
                            </a>
                        </div>

                        <!-- Skip Option -->
                        <div class="text-center mt-6">
                            <p class="text-sm text-gray-500 mb-2">
                                You can add team members later from your dashboard
                            </p>
                            <button type="submit" name="skip_team" value="1" class="text-sm text-indigo-600 hover:text-indigo-500 underline">
                                Skip team setup for now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let teamMemberIndex = 1;

        function addTeamMember() {
            const container = document.getElementById('team-members-container');
            const newRow = document.createElement('div');
            newRow.className = 'team-member-row';
            newRow.innerHTML = `
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input type="email" 
                               name="team_members[${teamMemberIndex}][email]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="john@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Name
                        </label>
                        <input type="text" 
                               name="team_members[${teamMemberIndex}][name]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Role
                        </label>
                        <div class="flex items-center">
                            <select name="team_members[${teamMemberIndex}][role]" 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="member">Member</option>
                                <option value="admin">Admin</option>
                            </select>
                            <button type="button" onclick="removeTeamMember(this)" class="ml-2 px-3 py-2 text-red-600 hover:text-red-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(newRow);
            teamMemberIndex++;
        }

        function removeTeamMember(button) {
            const container = document.getElementById('team-members-container');
            if (container.children.length > 1) {
                button.closest('.team-member-row').remove();
            }
        }
    </script>
@endsection
