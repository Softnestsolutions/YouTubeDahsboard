@extends('layouts.app')
@section('content')
    <div class="mb-0 w-screen lg:mx-auto lg:w-[500px] card shadow-lg border-none shadow-slate-100 relative">
        <div class="!px-10 !py-12 card-body">
            <a href="#!">
                <img src="assets/images/logo-light.png" alt="" class="hidden h-6 mx-auto dark:block">
                <img src="assets/images/logo-dark.png" alt="" class="block h-6 mx-auto dark:hidden">
            </a>

            <div class="mt-8 text-center">
                <h4 class="mb-1 text-custom-500 dark:text-custom-500">Welcome Back !</h4>
                <p class="text-slate-500 dark:text-zink-200">Sign in to continue to Starcode.</p>
            </div>

            <form action="{{ route('login') }}" class="mt-10" id="" method="POST">
                @csrf
                <div class="hidden px-4 py-3 mb-3 text-sm text-green-500 border border-green-200 rounded-md bg-green-50 dark:bg-green-400/20 dark:border-green-500/50"
                    id="successAlert">
                    You have <b>successfully</b> signed in.
                </div>
                <div class="mb-3">
                    <label for="username" class="inline-block mb-2 text-base font-medium">UserName/ Email ID</label>
                    <input type="text" id="email" name="email"
                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                        placeholder="Enter username or email">
                    <div id="username-error" class="hidden mt-1 text-sm text-red-500">Please enter a valid email address.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="inline-block mb-2 text-base font-medium">Password</label>
                    <input type="password" id="password" name="password"
                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                        placeholder="Enter password">
                    <div id="password-error" class="hidden mt-1 text-sm text-red-500">Password must be at least 8 characters
                        long and contain both letters and numbers.</div>
                </div>
                <div class="container mt-5">
                    <div class="row justify-content-center">
                        <!-- Regular Sign In Button -->
                        <div class="col-md-4 text-center mb-3">
                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                                Sign In
                            </button>
                        </div>

                        <!-- Google Sign In Button -->
                        <div class="col-md-8 text-center">
                            <a href="{{ route('/auth/google') }}"
                                class="btn w-100 py-2 fw-bold d-flex align-items-center justify-content-center"
                                style="background-color: #DB4437; color: white; border-radius: 6px; transition: 0.3s;">
                                <i class="fab fa-google me-2"></i> Sign in with Google
                            </a>
                        </div>
                    </div>
                </div>               
                <div class="mt-10 text-center">
                    <p class="mb-0 text-slate-500 dark:text-zink-200">Don't have an account ?
                        <a href="{{ route('register') }}"
                            class="font-semibold underline transition-all duration-150 ease-linear text-slate-500 dark:text-zink-200 hover:text-custom-500 dark:hover:text-custom-500">
                            SignUp</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

@section('script')
@endsection
@endsection
