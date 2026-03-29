@extends('layouts.auth')
@section('title', 'Inscription — Sodium Vision')


@section('content')
    <div class="auth-shell">

        <div class="auth-left">
            <div class="auth-left-spacer"></div>

            <div class="auth-left-content">
                <h1 class="auth-left-title">
                    Rejoignez<br>
                    <span>Sodium Vision</span><br>
                </h1>
                <p class="auth-left-desc">
                    Créez votre compte dès maintenant, et utilisez vos appareils.
                </p>
            </div>

            <div class="auth-left-spacer"></div>
        </div>

        <div class="auth-right">
            <div class="auth-form-wrapper">

                <h2 class="auth-form-title">Créer un compte</h2>
                <p class="auth-form-sub">Rejoignez Sodium Vision gratuitement</p>

                @if($errors->any())
                    <div class="auth-error">
                        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="field-group">
                        <label class="field-label" for="name">Nom d'utilisateur</label>
                        <input class="field-input @error('name') is-danger @enderror"
                               id="name" type="text" name="name"
                               value="{{ old('name') }}"
                               placeholder="JeanDupont"
                               autocomplete="name" autofocus required>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="email">Email</label>
                        <input class="field-input @error('email') is-danger @enderror"
                               id="email" type="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="email@exemple.com"
                               autocomplete="email" required>
                    </div>

                    <div class="field-row">
                        <div class="field-group" style="margin-bottom:0;">
                            <label class="field-label" for="password">Mot de passe</label>
                            <div class="password-wrapper">
                                <input class="field-input @error('password') is-danger @enderror"
                                       id="password" type="password" name="password"
                                       placeholder="••••••••"
                                       autocomplete="new-password" required>
                                <button type="button" class="toggle-password" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="field-group" style="margin-bottom:0;">
                            <label class="field-label" for="password_confirmation">Confirmer</label>
                            <div class="password-wrapper">
                                <input class="field-input"
                                       id="password_confirmation" type="password"
                                       name="password_confirmation"
                                       placeholder="••••••••"
                                       autocomplete="new-password" required>
                                <button type="button" class="toggle-password" data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Créer mon compte</button>
                </form>

                <p class="auth-footer-link">
                    Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
                </p>
            </div>
        </div>

    </div>
@endsection
