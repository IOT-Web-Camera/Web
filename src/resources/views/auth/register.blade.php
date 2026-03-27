@extends('layouts.auth')
@section('title', 'Inscription')

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
                <p class="auth-title">Créer un compte</p>
                <p class="auth-subtitle">Rejoignez Sodium Vision</p>

                {{-- Affichage des erreurs --}}
                @if($errors->any())
                    <div class="notification is-danger is-light mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Nom --}}
                    <div class="field mb-4">
                        <label class="label">Nom</label>
                        <div class="control has-icons-left">
                            <input class="input @error('name') is-danger @enderror"
                                   type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Jean Dupont"
                                   required
                                   autofocus>
                            <span class="icon is-small is-left">
                                <i class="fas fa-user" style="color: var(--sodium-muted);"></i>
                            </span>
                        </div>
                        @error('name')<p class="help is-danger">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="field mb-4">
                        <label class="label">Email</label>
                        <div class="control has-icons-left">
                            <input class="input @error('email') is-danger @enderror"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="email@exemple.com"
                                   required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope" style="color: var(--sodium-muted);"></i>
                            </span>
                        </div>
                        @error('email')<p class="help is-danger">{{ $message }}</p>@enderror
                    </div>

                    {{-- Mot de passe --}}
                    <div class="field mb-4">
                        <label class="label">Mot de passe</label>
                        <div class="control has-icons-left">
                            <input class="input @error('password') is-danger @enderror"
                                   type="password"
                                   name="password"
                                   placeholder="••••••••"
                                   required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock" style="color: var(--sodium-muted);"></i>
                            </span>
                        </div>
                        @error('password')<p class="help is-danger">{{ $message }}</p>@enderror
                    </div>

                    {{-- Confirmer le mot de passe --}}
                    <div class="field mb-5">
                        <label class="label">Confirmer le mot de passe</label>
                        <div class="control has-icons-left">
                            <input class="input"
                                   type="password"
                                   name="password_confirmation"
                                   placeholder="••••••••"
                                   required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock" style="color: var(--sodium-muted);"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="button is-primary is-fullwidth">
                        Créer mon compte
                    </button>
                </form>

                <p class="is-size-7 has-text-grey has-text-centered mt-4">
                    Déjà inscrit ?
                    <a href="{{ route('login') }}" style="color: var(--sodium-blue); font-weight: 500;">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
@endsection
