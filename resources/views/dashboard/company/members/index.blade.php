@extends("dashboard.layout")

@section("title", "Company Members")

@section("content")
    <div class="container mx-auto px-6">
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Team Members
            </h1>
            <p class="mt-1 text-gray-600 dark:text-gray-300">
                Manage your company members and their roles. You can invite new members and manage existing ones efficiently.
            </p>
        </div>

        <div class="mb-6 flex justify-end">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                onclick="openInviteModal()"
            >
                <i class="fas fa-envelope"></i>
                Invite Member
            </button>
        </div>


        <!-- Members Table -->
        @if ($members->isEmpty())
            <div class="rounded-lg border border-gray-200 bg-white p-8 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                    <i class="fas fa-users text-xl text-gray-400"></i>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">
                    No team members yet
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Get started by inviting your first team member to collaborate.
                </p>
                <button
                    type="button"
                    class="mt-4 inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    onclick="openInviteModal()"
                >
                    <i class="fas fa-plus"></i>
                    Invite Member
                </button>
            </div>
        @else
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Member
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Role
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Joined
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($members as $member)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <!-- Member Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if ($member->user && $member->user->profile_photo_path)
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                         src="{{ Storage::url($member->user->profile_photo_path) }}" 
                                                         alt="{{ $member->user->name }}">
                                                @else
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                                                        <span class="text-sm font-medium">
                                                            @if ($member->user)
                                                                {{ strtoupper(substr($member->user->name, 0, 2)) }}
                                                            @else
                                                                {{ strtoupper(substr($member->user ? $member->user->email : 'Guest', 0, 2)) }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    @if ($member->user)
                                                        {{ $member->user->name }}
                                                    @else
                                                        <span class="text-gray-500 dark:text-gray-400">Pending Invitation</span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $member->user ? $member->user->email : ($member->email ?: 'N/A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Role -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $member->role === 'owner' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 
                                               ($member->role === 'admin' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                               'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200') }}">
                                            @if ($member->role === 'owner')
                                                <i class="fas fa-crown mr-1"></i>
                                            @elseif ($member->role === 'admin')
                                                <i class="fas fa-shield-alt mr-1"></i>
                                            @else
                                                <i class="fas fa-user mr-1"></i>
                                            @endif
                                            {{ ucfirst($member->role) }}
                                        </span>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($member->user)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <div class="mr-1 h-1.5 w-1.5 rounded-full bg-green-400"></div>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                <div class="mr-1 h-1.5 w-1.5 rounded-full bg-yellow-400"></div>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Joined Date -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        @if ($member->user)
                                            {{ $member->created_at->format('M d, Y') }}
                                        @else
                                            Invited {{ $member->created_at->format('M d, Y') }}
                                        @endif
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if ($member->role !== 'owner')
                                            <div class="flex items-center justify-end space-x-2">
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800"
                                                    onclick="openEditModal({{ $member->id }}, '{{ $member->role }}', '{{ $member->user ? $member->user->email : ($member->email ?: 'N/A') }}')"
                                                >
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Edit
                                                </button>
                                                <form method="POST" action="{{ route('dashboard.company.members.destroy', $member->id) }}" 
                                                      onsubmit="return confirm('Are you sure you want to remove this member?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800">
                                                        <i class="fas fa-trash mr-1"></i>
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                <i class="fas fa-crown mr-1"></i>
                                                Owner
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Invite Member Modal -->
    <div id="inviteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="inviteModalLabel">
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeInviteModal()"></div>
            
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Modal panel -->
            <div class="relative inline-block transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:w-full sm:max-w-lg sm:p-6 sm:align-middle">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-500 dark:hover:text-gray-300" 
                            onclick="closeInviteModal()">
                        <span class="sr-only">Close</span>
                        <i class="fas fa-times h-6 w-6"></i>
                    </button>
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-envelope text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="inviteModalLabel">
                            Invite Team Member
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Send an invitation to join your team
                            </p>
                        </div>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('dashboard.company.members.invite') }}" class="mt-5">
                    @csrf
                    <div class="space-y-4">
                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email Address
                            </label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" 
                                       placeholder="john@example.com" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role Selection -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Role
                            </label>
                            <div class="mt-1">
                                <select name="role" id="role" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" 
                                        required>
                                    <option value="">Choose a role...</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin - Full access</option>
                                    <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Member - Basic access</option>
                                </select>
                            </div>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="rounded-md bg-blue-50 p-4 dark:bg-blue-900/20">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                        Invitation Process
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                        <p>The invited user will receive an email with instructions to join your team.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <button type="submit" 
                                class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Invitation
                        </button>
                        <button type="button" 
                                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 sm:col-start-1 sm:mt-0 sm:text-sm"
                                onclick="closeInviteModal()">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div id="editRoleModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="editRoleModalLabel">
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>
            
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Modal panel -->
            <div class="relative inline-block transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:w-full sm:max-w-lg sm:p-6 sm:align-middle">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-500 dark:hover:text-gray-300" 
                            onclick="closeEditModal()">
                        <span class="sr-only">Close</span>
                        <i class="fas fa-times h-6 w-6"></i>
                    </button>
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-user-edit text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="editRoleModalLabel">
                            Edit Member Role
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Update the role for: <strong id="editMemberEmail" class="text-gray-700 dark:text-gray-300"></strong>
                            </p>
                        </div>
                    </div>
                </div>
                
                <form method="POST" id="editRoleForm" class="mt-5">
                    @csrf
                    @method("PATCH")
                    <div class="space-y-4">
                        <!-- Role Selection -->
                        <div>
                            <label for="editRole" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                New Role
                            </label>
                            <div class="mt-1">
                                <select id="editRole" name="role" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" 
                                        required>
                                    <option value="admin">Admin - Full access</option>
                                    <option value="member">Member - Basic access</option>
                                </select>
                            </div>
                        </div>

                        <!-- Warning Box -->
                        <div class="rounded-md bg-yellow-50 p-4 dark:bg-yellow-900/20">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                        Role Change
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                        <p>Changing a member's role will update their permissions immediately.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <button type="submit" 
                                class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm">
                            <i class="fas fa-save mr-2"></i>
                            Update Role
                        </button>
                        <button type="button" 
                                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 sm:col-start-1 sm:mt-0 sm:text-sm"
                                onclick="closeEditModal()">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openInviteModal() {
            const modal = document.getElementById('inviteModal');
            modal.classList.remove('hidden');
        }
        
        function closeInviteModal() {
            const modal = document.getElementById('inviteModal');
            modal.classList.add('hidden');
        }
        
        function openEditModal(memberId, memberRole, memberEmail) {
            const modal = document.getElementById('editRoleModal');
            const form = document.getElementById('editRoleForm');
            const emailSpan = document.getElementById('editMemberEmail');
            const roleSelect = document.getElementById('editRole');

            form.action = `{{ route('dashboard.company.members.update-role', ':member') }}`.replace(':member', memberId);
            emailSpan.textContent = memberEmail;
            roleSelect.value = memberRole;
            
            modal.classList.remove('hidden');
        }
        
        function closeEditModal() {
            const modal = document.getElementById('editRoleModal');
            modal.classList.add('hidden');
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const inviteModal = document.getElementById('inviteModal');
            const editModal = document.getElementById('editRoleModal');
            if (event.target === inviteModal) {
                closeInviteModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
        }
    </script>
@endsection
