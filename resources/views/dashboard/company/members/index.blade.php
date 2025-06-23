@extends('layouts.dashboard')

@section('title', 'Company Members')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Company Members</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inviteModal">
                        <i class="fas fa-plus"></i> Invite Member
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <x-shared.alert type="success">
                            {{ session('success') }}
                        </x-shared.alert>
                    @endif

                    @if(session('error'))
                        <x-shared.alert type="danger">
                            {{ session('error') }}
                        </x-shared.alert>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                    <tr>
                                        <td>
                                            @if($member->user)
                                                {{ $member->user->name }}
                                            @else
                                                <span class="text-muted">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $member->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $member->role === 'owner' ? 'primary' : ($member->role === 'admin' ? 'success' : 'secondary') }}">
                                                {{ ucfirst($member->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($member->user)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($member->user)
                                                {{ $member->created_at->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($member->role !== 'owner')
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editRoleModal"
                                                            data-member-id="{{ $member->id }}"
                                                            data-member-role="{{ $member->role }}"
                                                            data-member-email="{{ $member->email }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <form method="POST" action="{{ route('dashboard.company.members.destroy', $member->id) }}" 
                                                          class="d-inline" 
                                                          onsubmit="return confirm('Are you sure you want to remove this member?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted">Owner</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No members found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invite Member Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('dashboard.company.members.invite') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Invite Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role *</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Member</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Invitation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editRoleForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Member Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Editing role for: <strong id="editMemberEmail"></strong></p>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Role *</label>
                        <select class="form-select" id="editRole" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="member">Member</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editRoleModal = document.getElementById('editRoleModal');
    editRoleModal.addEventListener('show.bs.modal', function(event) {
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
