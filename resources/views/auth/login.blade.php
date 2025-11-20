{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout title="Login" bodyClass="page-login">
    <h1 class="auth-page-title">Login</h1>
    {{ session('error') }}
    <form action="{{ route('login.store') }}" method="post">
        @csrf
        <div class="form-group @error('email') has-error @enderror">
            <input
                type="email"
                placeholder="Your Email"
                name="email"
                value="{{ old('email') }}"/>
            <div class="error-message">
                {{ $errors->first('email') }}
            </div>
        </div>
        <div class="form-group @error('password') has-error @enderror">
            <input
                type="password"
                placeholder="Your Password"
                name="password"/>
        </div>
        <div class="error-message">
            {{ $errors->first('password') }}
        </div>
        <div class="text-right mb-medium">
            <a href="{{ route('password.request') }}"
                class="auth-page-password-reset">
                Forgot Password?
            </a>
        </div>
        <button class="btn btn-primary btn-login w-full">
            Login
        </button>
    </form>

    <x-slot:footerLink>
        Don't have an account? -
        <a href="{{ route('signup') }}">Click here to create one</a>
    </x-slot:footerLink>


</x-guest-layout>
