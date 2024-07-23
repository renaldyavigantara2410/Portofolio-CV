@extends('layouts.app')

@section('content')
<div class="container sm:px-10">
    <div class="block xl:grid grid-cols-2 gap-4">
        <!-- BEGIN: Login Info -->
        <div class="hidden xl:flex flex-col min-h-screen">
            <a href="" class="-intro-x flex items-center pt-5">
                <img alt="Siskupala" class="w-6" src="{{ asset('/images/nila.svg')}}">
                <span class="text-white text-lg ml-3"> SISKUPALA </span>
            </a>
            <div class="my-auto">
                <img alt="Siskupala" class="-intro-x w-1/2 -mt-16" src="{{ asset('/images/nila.svg')}}">
                <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                    Sistem Kontrol Kualitas Air
                    <br>
                    & Pemberian Pakan Ikan Nila
                </div>
            </div>
        </div>
        <!-- END: Login Info -->
        <!-- BEGIN: Login Form -->
        <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
            <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                        Sign In
                    </h2>
                    <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place</div>
                    <div class="intro-x mt-8">
                        <input type="email" id="email" name="email" class="intro-x login__input form-control py-3 px-4 block @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <input type="password" id="password" name="password" class="intro-x login__input form-control py-3 px-4 block mt-4 @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                        <div class="flex items-center mr-auto">
                    </div>
                    <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                        <button type="submit" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Login</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- END: Login Form -->
    </div>
</div>
@endsection
