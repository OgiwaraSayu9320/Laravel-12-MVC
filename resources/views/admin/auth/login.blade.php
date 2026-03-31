<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('styles')

</head>

<body class="d-flex align-items-center py-5 vh-100 login">
    <main class="form-signin w-100 m-auto" style="max-width: 330px;">
        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <h1 class="h3 mb-3 fw-normal text-center">Đăng nhập Admin</h1>

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="form-floating mb-2">
                <input type="email" name="email" class="form-control" id="floatingInput"
                    placeholder="name@example.com" required>
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password"
                    required>
                <label for="floatingPassword">Password</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        </form>
    </main>
</body>

</html>
