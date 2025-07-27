@extends('layouts.app')

@section('title')
    New User
@endsection

@section('content')
    <div class="page-heading">
        <h3>New User</h3>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('user.index') }}" class="btn btn-primary mb-4">
                    Back
                </a>
            </div>
            <div class="col-lg-6">
                <form action="{{ route('user.store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="basicInput">Username</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                    name="username" placeholder="username" value="{{ old('username') }}">
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" id="passwordInput"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    value="{{ old('password') }}" placeholder="Password">
                                <span class="password-toggle" toggle="#passwordInput">
                                    <i class="bi bi-eye"></i>
                                </span>
                                @error('password')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Password Confirmation</label>
                                <input type="password" id="passwordConfirmationInput"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    name="password_confirmation" value="{{ old('password_confirmation') }}"
                                    placeholder="Confirm Password">
                                <span class="password-toggle" toggle="#passwordConfirmationInput">
                                    <i class="bi bi-eye"></i>
                                </span>
                                @error('password_confirmation')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="basicInput">Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="basicSelect"
                                    name="role">
                                    <option value="">-- Select --</option>
                                    <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                                    <option value="manager" @selected(old('role') == 'manager')>Manager</option>
                                    <option value="receptionist" @selected(old('role') == 'receptionist')>Receptionist</option>
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group float-end">
                                <button type="submit" class="btn btn-outline-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .form-group {
            position: relative;
        }

        .form-control {
            padding-right: 2.5rem;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
            color: #657485;
            font-size: 1.2em;
            height: 100%;
            display: flex;
            align-items: center;
        }
    </style>
@endpush

@push('script')
    <script>
        document.querySelectorAll('.password-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                let input = document.querySelector(this.getAttribute('toggle'));
                let icon = this.querySelector('i');
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    input.type = "password";
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        });
    </script>
@endpush
