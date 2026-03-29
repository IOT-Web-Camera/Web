@extends('layouts.auth')
@section('title', 'Connexion — Sodium Vision')

@push('styles')

@endpush

@section('content')
    <div class="auth-shell">

        <div class="auth-left">
            <div class="auth-left-spacer"></div>

            <div class="auth-left-content">
                <h1 class="auth-left-title">
                    Bon retour à vous<br>
                    <span>Content de vous revoir</span><br>
                </h1>
                <p class="auth-left-desc">
                    Connectez-vous pour accéder à votre tableau de bord et gérer vos caméras.
                </p>
            </div>

            <div class="auth-left-spacer"></div>
        </div>

        <div class="auth-right">
            <div class="auth-form-wrapper">

                <h2 class="auth-form-title">Se connecter</h2>
                <p class="auth-form-sub">Accédez à votre tableau de bord</p>

                @if(session('error'))
                    <div class="auth-error">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="field-group">
                        <label class="field-label" for="email">Email</label>
                        <input class="field-input @error('email') is-danger @enderror"
                               id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="email@exemple.com"
                               required autofocus>
                        @error('email')<div class="auth-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="password">Mot de passe</label>

                        <div class="password-wrapper">
                            <input class="field-input @error('password') is-danger @enderror"
                                   id="password"
                                   type="password"
                                   name="password"
                                   placeholder="••••••••"
                                   required>

                            <button type="button" class="toggle-password" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        @error('password')<div class="auth-error">{{ $message }}</div>@enderror
                    </div>




                    <button type="submit" class="btn-submit">Se connecter</button>
                </form>

                <p class="auth-footer-link">
                    Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a>
                </p>

            </div>
        </div>

    </div>
@endsection



