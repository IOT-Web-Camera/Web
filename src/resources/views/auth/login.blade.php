@extends('layouts.auth')
@section('title', 'Connexion')

@push('styles')
    <style>
        .auth-wrapper {
            min-height: calc(100vh - 64px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .auth-box { width: 100%; max-width: 400px; }
        .auth-box .box { padding: 2rem; }
        .auth-title { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem; }
        .auth-subtitle { font-size: 0.875rem; color: var(--sodium-muted); margin-bottom: 1.75rem; }
    </style>
@endpush

@section('content')
    <div class="auth-wrapper">
        <div class="auth-box">
            <div class="box">
                <p class="auth-title">Connexion</p>
                <p class="auth-subtitle">Accédez à votre tableau de bord</p>

                @if(session('status'))
                    <div class="notification is-info is-light mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="notification is-danger is-light mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="field mb-4">
                        <label class="label">Email</label>
                        <div class="control has-icons-left">
                            <input class="input @error('email') is-danger @enderror"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="email@exemple.com"
                                   required
                                   autofocus
                                   autocomplete="username">
                            <span class="icon is-small is-left">
                            <i class="fas fa-envelope" style="color: var(--sodium-muted);"></i>
                        </span>
                        </div>
                        @error('email')<p class="help is-danger">{{ $message }}</p>@enderror
                    </div>

                    <div class="field mb-4">
                        <label class="label">Mot de passe</label>
                        <div class="control has-icons-left">
                            <input class="input @error('password') is-danger @enderror"
                                   type="password"
                                   name="password"
                                   placeholder="••••••••"
                                   required
                                   autocomplete="current-password">
                            <span class="icon is-small is-left">
                            <i class="fas fa-lock" style="color: var(--sodium-muted);"></i>
                        </span>
                        </div>
                        @error('password')<p class="help is-danger">{{ $message }}</p>@enderror
                    </div>

                    <div class="field mb-4">
                        <label class="checkbox">
                            <input type="checkbox" name="remember">
                            Se souvenir de moi
                        </label>
                    </div>

                    <div class="field is-grouped is-justify-content-space-between is-align-items-center">
                        @if (Route::has('password.request'))
                            <a class="is-size-7" href="{{ route('password.request') }}">
                                Mot de passe oublié ?
                            </a>
                        @endif

                        <button type="submit" class="button is-primary">
                            Se connecter
                        </button>
                    </div>
                </form>

                <p class="is-size-7 has-text-grey has-text-centered mt-4">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" style="color: var(--sodium-blue); font-weight: 500;">S'inscrire</a>
                </p>
            </div>
        </div>
    </div>
@endsection
