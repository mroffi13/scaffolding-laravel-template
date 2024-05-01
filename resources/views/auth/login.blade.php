<x-guest-layout>
    <!-- Session Status -->
    <div class="m-3 p-4">
        <img src="{{ asset('img/stisla-fill.svg') }}" alt="logo" width="80"
            class="shadow-light rounded-circle mb-5 mt-2">
        <h4 class="text-dark font-weight-normal">Welcome to <span class="font-weight-bold">Fuji Academy</span>
        </h4>
        <p class="text-muted">Before you get started, <br>you must login.</p>
        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}"
                    name="email" tabindex="1" required autofocus>
                <div class="invalid-feedback">
                    {{ $errors->get('email')[0] ?? 'Please fill in your email' }}
                </div>
            </div>

            <div class="form-group">
                <div class="d-block">
                    <label for="password" class="control-label">Password</label>
                </div>
                <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                <div class="invalid-feedback">
                    {{ 'Please fill in your password' }}
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                    <label class="custom-control-label" for="remember-me">Remember Me</label>
                </div>
            </div>

            <div class="form-group text-right">
                <a href="{{ route('password.request') }}" class="float-left mt-3">
                    Forgot Password?
                </a>
                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                    Login
                </button>
            </div>
        </form>

        <div class="text-small mt-5 mb-5 text-center">
            Copyright &copy; {{ config('app.name') }}. Made with 💙 by Stisla
            <div class="mt-2">
                <a href="#">Privacy Policy</a>
                <div class="bullet"></div>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</x-guest-layout>
