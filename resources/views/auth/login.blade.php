@extends('layouts.master')

@section('title', 'Login - Sistema Checklist')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" href="{{ asset('images/promova.jpg') }}">
@endsection

@section('content')
    <section>
        <div class="box">
            <div class="form">
                <img src="{{ asset('images/promova.jpg') }}" class="user" alt="">

                <h2>Checklist</h2>

                <!-- Formulário adaptado para Laravel -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="inputBx">
                        <input type="email" name="email" placeholder="E-mail" id="email" value="{{ old('email') }}"
                            oninput="validation()" required autofocus>

                        <img src="{{ asset('images/user.png') }}" alt="">
                    </div>
                    

                    <div class="inputBx">
                        {{-- <img src="{{ asset('images/lock.png') }}" alt="Ícone de cadeado" class="icon-lock"> --}}

                        <input type="password" name="password" id="password" placeholder="Senha" oninput="validation()"
                            required>

                        <img src="{{ asset('images/view2.png') }}" alt="Mostrar senha" class="toggle-password"
                            id="togglePassword" data-show="{{ asset('images/view2.png') }}"
                            data-hide="{{ asset('images/view.png') }}">
                    </div>

                    <div class="inputBx">
                        <input type="submit" name="submit" value="Acessar" id="submit">
                    </div>

                         @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror

                </form>
            </div>
        </div>

        {{-- <div class="plugwin">
            <a href="https://site.plugwin.net/" target="_blank">
                <img src="{{ asset('images/logo_plugwin1.png') }}" alt="" srcset="">
            </a>
        </div> --}}
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
@endsection
