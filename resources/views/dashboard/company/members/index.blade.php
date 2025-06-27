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

        <div class="mb-4 flex justify-end">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                data-bs-toggle="modal"
                data-bs-target="#inviteModal"
            >
                <i class="fas fa-envelope"></i>
                Invite Member
            </button>
        </div>

        <!-- Alerts -->
        @if (session("success"))
            <x-shared.alert type="success">
                {{ session("success") }}
            </x-shared.alert>
        @endif

        @if (session("error"))
            <x-shared.alert type="danger">
                {{ session("error") }}
            </x-shared.alert>
        @endif

        <!-- Members Grid -->
        @if ($members->isEmpty())
            <div class="rounded-md border border-gray-300 bg-gray-50 p-8 text-center dark:border-gray-600 dark:bg-gray-800">
                <i class="fas fa-users text-3xl text-gray-400 dark:text-gray-500"></i>
                <h3 class="mt-3 text-lg font-medium text-gray-900 dark:text-white">
                    No team members yet
                </h3>
                <p class="mt-1 text-gray-500 dark:text-gray-400">
                    Get started by inviting your first team member.
                </p>
                <button
                    type="button"
                    class="mt-3 inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm text-white transition hover:bg-blue-700"
                    data-bs-toggle="modal"
                    data-bs-target="#inviteModal"
                >
                    <i class="fas fa-plus"></i>
                    Invite Member
                </button>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($members as $member)
                    <div class="rounded-md border border-gray-200 bg-white p-4 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-750">
                        <div>
                            <!-- Profile Section -->
                            <div class="flex items-center space-x-3">
                                <!-- Profile Picture -->
                                <div class="flex-shrink-0">
                                    @if ($member->user && $member->user->profile_photo_path)
                                        <img
                                            class="h-10 w-10 rounded-full object-cover"
                                            src="{{ Storage::url($member->user->profile_photo_path) }}"
                                            alt="{{ $member->user->name }}"
                                        />
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                                            <span class="text-sm font-medium">
                                                @if ($member->user)
                                                    {{ strtoupper(substr($member->user->name, 0, 2)) }}
                                                @else
                                                    {{ strtoupper(substr($member->email, 0, 2)) }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Member Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-medium text-gray-900 dark:text-white">
                                        @if ($member->user)
                                            {{ $member->user->name }}
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">
                                                Pending Invitation
                                            </span>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                        {{ $member->email }}
                                    </p>
                                </div>
                            </div>

                            <!-- Role and Status -->
                            <div class="mt-3 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <!-- Role Badge -->
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
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
                                    
                                    <!-- Status Badge -->
                                    @if ($member->user)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <div class="mr-1 h-1.5 w-1.5 rounded-full bg-green-400"></div>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            <div class="mr-1 h-1.5 w-1.5 rounded-full bg-yellow-400"></div>
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Member Details -->
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-1 w-3"></i>
                                    @if ($member->user)
                                        Joined {{ $member->created_at->format("M d, Y") }}
                                    @else
                                        Invited {{ $member->created_at->format("M d, Y") }}
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            @if ($member->role !== "owner")
                                <div class="mt-3 flex space-x-2">
                                    <button
                                        type="button"
                                        class="flex-1 rounded-md bg-gray-100 px-2 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editRoleModal"
                                        data-member-id="{{ $member->id }}"
                                        data-member-role="{{ $member->role }}"
                                        data-member-email="{{ $member->email }}"
                                    >
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </button>
                                    <form
                                        method="POST"
                                        action="{{ route("dashboard.company.members.destroy", $member->id) }}"
                                        onsubmit="return confirm('Are you sure you want to remove this member?')"
                                        class="flex-1"
                                    >
                                        @csrf
                                        @method("DELETE")
                                        <button
                                            type="submit"
                                            class="w-full rounded-md bg-red-50 px-2 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30"
                                        >
                                            <i class="fas fa-trash mr-1"></i>
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="mt-3">
                                    <div class="rounded-md bg-purple-50 px-2 py-1.5 text-center text-xs font-medium text-purple-700 dark:bg-purple-900/20 dark:text-purple-400">
                                        <i class="fas fa-crown mr-1"></i>
                                        Company Owner
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Invite Member Modal -->
    <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-md border border-gray-200 dark:border-gray-700">
                <div class="modal-header border-b border-gray-200 dark:border-gray-700 pb-3">
                    <div>
                        <h5 class="modal-title text-lg font-semibold text-gray-900 dark:text-white" id="inviteModalLabel">
                            <i class="fas fa-envelope text-blue-600 mr-2"></i>
                            Invite Team Member
                        </h5>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Send an invitation to join your team
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form method="POST" action="{{ route('dashboard.company.members.invite') }}" id="inviteForm">
                    @csrf
                    <div class="modal-body pt-3">
                        <div class="space-y-3">
                            <!-- Email Input -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    Email Address
                                </label>
                                <div class="relative">
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="block w-full px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                                        placeholder="john@example.com"
                                        value="{{ old('email') }}"
                                        required
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i class="fas fa-at text-gray-400"></i>
                                    </div>
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Role Selection -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-user-tag text-gray-400 mr-1"></i>
                                    Role
                                </label>
                                <div class="relative">
                                    <select
                                        name="role"
                                        id="role"
                                        class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        required
                                    >
                                        <option value="">Choose a role...</option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                            <i class="fas fa-shield-alt"></i> Admin - Full access
                                        </option>
                                        <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>
                                            <i class="fas fa-user"></i> Member - Basic access
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Info Box -->
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-2 dark:bg-blue-900/20 dark:border-blue-800">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                                    <div class="text-sm text-blue-700 dark:text-blue-300">
                                        <p class="font-medium">Invitation Process</p>
                                        <p class="mt-0.5">The invited user will receive an email with instructions to join your team.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-t border-gray-200 dark:border-gray-700 pt-3">
                        <div class="flex items-center justify-end space-x-2 w-full">
                            <button
                                type="button"
                                class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none transition-colors dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600"
                                data-bs-dismiss="modal"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none transition-colors"
                                id="inviteButton"
                            >
                                <i class="fas fa-paper-plane mr-1"></i>
                                <span>Send Invitation</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-md border border-gray-200 dark:border-gray-700">
                <div class="modal-header border-b border-gray-200 dark:border-gray-700 pb-3">
                    <div>
                        <h5 class="modal-title text-lg font-semibold text-gray-900 dark:text-white" id="editRoleModalLabel">
                            <i class="fas fa-user-edit text-blue-600 mr-2"></i>
                            Edit Member Role
                        </h5>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Update the role for: <strong id="editMemberEmail" class="text-gray-700 dark:text-gray-300"></strong>
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form method="POST" id="editRoleForm">
                    @csrf
                    @method("PUT")
                    <div class="modal-body pt-3">
                        <div class="space-y-3">
                            <!-- Role Selection -->
                            <div>
                                <label for="editRole" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-user-tag text-gray-400 mr-1"></i>
                                    New Role
                                </label>
                                <div class="relative">
                                    <select
                                        id="editRole"
                                        name="role"
                                        class="block w-full rounded-md border-gray-300 px-3 py-2 text-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        required
                                    >
                                        <option value="admin">
                                            Admin - Full access
                                        </option>
                                        <option value="member">
                                            Member - Basic access
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Warning Box -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-2 dark:bg-yellow-900/20 dark:border-yellow-800">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-2"></i>
                                    <div class="text-sm text-yellow-700 dark:text-yellow-300">
                                        <p class="font-medium">Role Change</p>
                                        <p class="mt-0.5">Changing a member's role will update their permissions immediately.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-t border-gray-200 dark:border-gray-700 pt-3">
                        <div class="flex items-center justify-end space-x-2 w-full">
                            <button
                                type="button"
                                class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none transition-colors dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600"
                                data-bs-dismiss="modal"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none transition-colors"
                            >
                                <i class="fas fa-save mr-1"></i>
                                <span>Update Role</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editRoleModal = document.getElementById('editRoleModal');
            editRoleModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const memberId = button.getAttribute('data-member-id');
                const memberRole = button.getAttribute('data-member-role');
                const memberEmail = button.getAttribute('data-member-email');

                const form = document.getElementById('editRoleForm');
                const emailSpan = document.getElementById('editMemberEmail');
                const roleSelect = document.getElementById('editRole');

                form.action = `/dashboard/company/members/${memberId}`;
                emailSpan.textContent = memberEmail;
                roleSelect.value = memberRole;
            });
        });
    </script>
@endsection
