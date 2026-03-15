@extends('layouts/layoutMaster')

@section('title', 'User List - Pages')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleave-zen/cleave-zen.js'])
@endsection

@section('page-script')
    @vite('resources/assets/js/app-user-list.js')
@endsection

@section('content')
    <div class="row g-6 mb-6">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="me-1">
                            <p class="text-heading mb-1">Session</p>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-1 me-2">{{ $totalUsers }}</h4>
                                <p class="text-success mb-1">(+{{ $totalUsersLastMonthPercentage }}%)</p>
                            </div>
                            <small class="mb-0">Total Users</small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded-3">
                                <div class="icon-base ri ri-group-line icon-26px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="me-1">
                            <p class="text-heading mb-1">Admin Users</p>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-1 me-1">{{ $adminUsers }}</h4>
                            </div>
                            <small class="mb-0">Last week analytics</small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-danger rounded">
                                <div class="icon-base ri ri-user-add-line icon-26px scaleX-n1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="me-1">
                            <p class="text-heading mb-1">Active Users</p>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-1 me-1">{{ $activeUsers }}</h4>
                            </div>
                            <small class="mb-0">Last week analytics</small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded-3">
                                <div class="icon-base ri ri-user-follow-line icon-26px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="me-1">
                            <p class="text-heading mb-1">Inactive Users</p>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-1 me-1">{{ $inactiveUsers }}</h4>
                            </div>
                            <small class="mb-0">Last week analytics</small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-warning rounded-3">
                                <div class="icon-base ri ri-user-search-line icon-26px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Users List Table -->
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Filters</h5>
            <div class="d-flex justify-content-between align-items-center row gx-5 pt-4 gap-5 gap-md-0">
                <div class="col-md-6 user_role"></div>
                <div class="col-md-6 user_status"></div>
            </div>
        </div>
        <div class="card-datatable">
            <table class="datatables-users table">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td></td>
                            <td>
                                <div class="avatar">
                                    <img src="{{ $user->photo }}" alt="{{ $user->name }}"
                                        class="rounded-circle" />
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $user->name }}</span>
                                    <small class="text-muted">{{ $user->username }}</small>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>
                                @if ($user->is_active)
                                    <span class="badge bg-label-success">Active</span>
                                @else
                                    <span class="badge bg-label-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-body"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <i class="ri ri-edit-2-line"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                        class="d-inline delete-user-form" data-user-name="{{ $user->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-0 border-0 text-danger"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                            <i class="ri ri-delete-bin-7-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <!-- Offcanvas to add new user -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
            <div class="offcanvas-header border-bottom">
                <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 grow-0 h-100">
                <form class="add-new-user pt-0" id="addNewUserForm" method="POST"
                    action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="form-floating form-floating-outline mb-5 form-control-validation">
                        <input type="text" class="form-control" id="add-user-username" placeholder="fajrichan"
                            name="username" aria-label="fajrichan" value="{{ old('username') }}" />
                        <label for="add-user-username">Username</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-5 form-control-validation">
                        <input type="text" class="form-control" id="add-user-name" placeholder="Fajri Chan"
                            name="name" aria-label="Fajri Chan" value="{{ old('name') }}" />
                        <label for="add-user-name">Full Name</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-5 form-control-validation">
                        <input type="text" id="add-user-email" class="form-control" placeholder="your.mail@example.com"
                            aria-label="your.mail@example.com" name="email" value="{{ old('email') }}" />
                        <label for="add-user-email">Email</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-5 form-control-validation">
                        <input type="text" id="add-user-phone" class="form-control phone-mask"
                            placeholder="0812 3456 7890" aria-label="0812 3456 7890" name="phone"
                            value="{{ old('phone') }}" />
                        <label for="add-user-phone">Phone</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-5 form-control-validation">
                        <input type="password" id="add-user-password" class="form-control" placeholder="********"
                            aria-label="********" name="password" />
                        <label for="add-user-password">Password</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-5 form-control-validation">
                        <select id="user-role" class="form-select" name="role">
                            <option value="user" @selected(old('role') === 'user')>User</option>
                            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        </select>
                        <label for="user-role">User Role</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-5 form-control-validation">
                        <select id="user-status" class="form-select" name="is_active">
                            <option value="1" @selected(old('is_active', '1') == '1')>Active</option>
                            <option value="0" @selected(old('is_active') == '0')>Inactive</option>
                        </select>
                        <label for="user-status">Status</label>
                    </div>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="offcanvas">Cancel</button>
                </form>
            </div>
        </div>
    </div>

@endsection
