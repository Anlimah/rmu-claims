<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Register</div>
            <div class="card-body">
                <form id="registerForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" required>
                            <option value="lecturer">Lecturer</option>
                            <option value="hod">Head of Department</option>
                            <option value="dean">Dean</option>
                            <option value="provost">Provost</option>
                            <option value="auditor">Auditor</option>
                            <option value="vc">Vice Chancellor</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const full_name = document.getElementById('full_name').value;
        const role = document.getElementById('role').value;

        axios.post('/register', { username, email, password, full_name, role })
            .then(response => {
                alert('Registration successful. Please log in.');
                window.location.href = '/login';
            })
            .catch(error => {
                alert('Registration failed. Please try again.');
                console.error('Registration error:', error);
            });
    });
</script>
@endpush