@extends('layouts.app')
@section('title', 'Ajouter une caméra')

@section('content')
    <div class="page-wrapper">

        <div class="columns is-centered">
            <div class="column is-5">

                <div class="page-header">
                    <h1 class="title">Nouvelle caméra</h1>
                    <p class="subtitle">Connectez une caméra à votre compte</p>
                </div>

                <div class="box">
                    {{-- Erreurs --}}
                    @if($errors->any())
                        <div class="notification mb-4" style="background: #FEE2E2; border-color: #FECACA; color: #991B1B;">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li style="font-size: 0.875rem;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('cameras.store') }}" method="POST">
                        @csrf

                        <div class="field mb-4">
                            <label class="label">Nom technique (Path)</label>
                            <div class="control has-icons-left">
                                <input class="input @error('name') is-danger @enderror"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="ex: salon_5f3a"
                                       required>
                                <span class="icon is-small is-left">
                                <i class="fas fa-terminal" style="color: var(--sodium-muted);"></i>
                            </span>
                            </div>
                            <p class="help">Utilisé dans l'URL de streaming MediaMTX.</p>
                        </div>

                        <div class="field mb-4">
                            <label class="label">Label d'affichage</label>
                            <div class="control has-icons-left">
                                <input class="input @error('label') is-danger @enderror"
                                       type="text"
                                       name="label"
                                       value="{{ old('label') }}"
                                       placeholder="ex: Caméra Entrée"
                                       required>
                                <span class="icon is-small is-left">
                                <i class="fas fa-tag" style="color: var(--sodium-muted);"></i>
                            </span>
                            </div>
                        </div>

                        <div class="field mb-5">
                            <label class="label">Mot de passe du flux</label>
                            <div class="control has-icons-left">
                                <input class="input @error('stream_pass') is-danger @enderror"
                                       type="password"
                                       name="stream_pass"
                                       placeholder="Mot de passe MediaMTX"
                                       required>
                                <span class="icon is-small is-left">
                                <i class="fas fa-key" style="color: var(--sodium-muted);"></i>
                            </span>
                            </div>
                        </div>

                        <div class="is-flex" style="gap: 0.75rem;">
                            <button type="submit" class="button is-primary">
                                Enregistrer
                            </button>
                            <a href="{{ route('cameras.index') }}" class="button is-light">Annuler</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
