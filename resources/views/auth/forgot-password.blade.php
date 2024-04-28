<x-guest-layout>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="m-3 p-4">
        <img src="{{ asset('img/stisla-fill.svg') }}" alt="logo" width="80"
            class="shadow-light rounded-circle mb-5 mt-2">
        <h4 class="text-dark font-weight-normal">Welcome to <span class="font-weight-bold">Stisla</span>
        </h4>
        <p class="text-muted">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>
        <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate="">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control" name="email" tabindex="1" required
                    autofocus>
                <div class="invalid-feedback">
                    Please fill in your email
                </div>
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    Forgot Password
                </button>
            </div>
        </form>

        <div class="text-small mt-5 text-center">
            Copyright &copy; {{ config('app.name') }}. Made with 💙 by Stisla
            <div class="mt-2">
                <a href="#">Privacy Policy</a>
                <div class="bullet"></div>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</x-guest-layout>
