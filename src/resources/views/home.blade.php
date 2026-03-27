@extends('layouts.app')
@section('title', 'Accueil')

@section('content')
    <div class="page-wrapper">

        {{-- Hero --}}
        <section class="hero is-white" style="padding: 4rem 1rem;">
            <div class="container">
                <div class="columns is-vcentered">

                    {{-- Texte --}}
                    <div class="column is-6">
                        <h1 class="title" style="font-size: 2.2rem; font-weight: 700;">
                            Surveillance intelligente, simple et rapide
                        </h1>
                        <p class="subtitle" style="font-size: 1.1rem; color: var(--sodium-muted);">
                            Sodium Vision vous permet de connecter, visualiser et contrôler vos caméras Raspberry Pi en temps réel.
                            Une interface moderne, un contrôle précis, et une fiabilité pensée pour vos projets.
                        </p>

                        @auth
                            <a href="{{ route('dashboard') }}" class="button is-primary is-medium">
                                <span class="icon"><i class="fas fa-gauge"></i></span>
                                <span>Accéder au Dashboard</span>
                            </a>
                        @else
                            <div class="buttons">
                                <a href="/register" class="button is-primary is-medium">Créer un compte</a>
                                <a href="/login" class="button is-light is-medium">Connexion</a>
                            </div>
                        @endauth
                    </div>

                    {{-- Illustration --}}
                    <div class="column is-6 has-text-centered">
                        <img src="https://cdn-icons-png.flaticon.com/512/1048/1048953.png"
                             alt="Camera illustration"
                             style="max-width: 260px; opacity: 0.9;">
                    </div>
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section style="padding: 3rem 1rem;">
            <div class="container">
                <h2 class="title is-4 has-text-centered" style="margin-bottom: 2rem;">
                    Pourquoi choisir Sodium Vision ?
                </h2>

                <div class="columns is-multiline">

                    <div class="column is-4">
                        <div class="box" style="text-align: center;">
                        <span class="icon is-large" style="color: var(--sodium-blue);">
                            <i class="fas fa-video fa-2x"></i>
                        </span>
                            <h3 class="title is-6 mt-3">Flux en direct</h3>
                            <p class="subtitle is-7" style="color: var(--sodium-muted);">
                                Visualisez vos caméras en temps réel via MediaMTX, sans latence perceptible.
                            </p>
                        </div>
                    </div>

                    <div class="column is-4">
                        <div class="box" style="text-align: center;">
                        <span class="icon is-large" style="color: var(--sodium-blue);">
                            <i class="fas fa-sliders-h fa-2x"></i>
                        </span>
                            <h3 class="title is-6 mt-3">Contrôle à distance</h3>
                            <p class="subtitle is-7" style="color: var(--sodium-muted);">
                                Déplacez la caméra, activez la LED, redémarrez le module… tout depuis votre navigateur.
                            </p>
                        </div>
                    </div>

                    <div class="column is-4">
                        <div class="box" style="text-align: center;">
                        <span class="icon is-large" style="color: var(--sodium-blue);">
                            <i class="fas fa-shield-alt fa-2x"></i>
                        </span>
                            <h3 class="title is-6 mt-3">Sécurisé & fiable</h3>
                            <p class="subtitle is-7" style="color: var(--sodium-muted);">
                                Authentification des caméras, heartbeat automatique, gestion des flux stable.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="has-text-centered" style="padding: 3rem 1rem;">
            <h2 class="title is-4">Prêt à connecter vos caméras ?</h2>
            <p class="subtitle" style="color: var(--sodium-muted); margin-bottom: 1.5rem;">
                Lancez-vous en quelques minutes avec votre Raspberry Pi.
            </p>

            @auth
                <a href="{{ route('cameras.create') }}" class="button is-primary is-medium">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>Ajouter une caméra</span>
                </a>
            @else
                <a href="/register" class="button is-primary is-medium">Créer un compte</a>
            @endauth
        </section>

    </div>
@endsection
