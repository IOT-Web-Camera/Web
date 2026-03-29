@extends('layouts.app')
@section('title', 'Mes Caméras')

@section('content')
    <div class="page-wrapper">

        {{-- Header --}}
        <div class="page-header">
            <div class="is-flex is-justify-content-space-between is-align-items-flex-start">
                <div>
                    <h1 class="title">Caméras</h1>
                    <p class="subtitle">Gérez vos caméras connectées</p>
                </div>
                <a href="{{ route('cameras.create') }}" class="button is-primary">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>Ajouter</span>
                </a>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="notification is-success mb-4" style="background: #D1FAE5; border-color: #A7F3D0; color: #065F46;">
                <span class="icon"><i class="fas fa-check-circle"></i></span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        <div class="box p-0" style="overflow: hidden;">
            @if($cameras->isEmpty())
                <div class="has-text-centered" style="padding: 4rem 2rem;">
                <span class="icon is-large mb-4" style="color: var(--sodium-muted);">
                    <i class="fas fa-camera fa-2x"></i>
                </span>
                    <p style="font-weight: 500; margin-bottom: 0.5rem;">Aucune caméra</p>
                    <p style="font-size: 0.875rem; color: var(--sodium-muted); margin-bottom: 1.5rem;">
                        Ajoutez votre première caméra pour commencer.
                    </p>
                    <a href="{{ route('cameras.create') }}" class="button is-primary is-small">
                        Ajouter une caméra
                    </a>
                </div>
            @else
                <table class="table is-fullwidth is-hoverable" style="margin: 0;">
                    <thead>
                    <tr>
                        <th>Caméra</th>
                        <th>Path</th>
                        <th>Utilisateur flux</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cameras as $cam)
                        <tr>
                            <td>
                                <strong style="font-size: 0.875rem;">{{ $cam->label }}</strong>
                            </td>
                            <td>
                                <code style="font-size: 0.8rem; background: var(--sodium-bg); padding: 2px 6px; border-radius: 4px; border: 1px solid var(--sodium-border);">
                                    {{ $cam->name }}
                                </code>
                            </td>
                            <td style="font-size: 0.875rem; color: var(--sodium-muted);">{{ $cam->stream_user }}</td>
                            <td>
                            <span class="tag {{ $cam->is_active ? 'is-success' : 'is-danger' }}">
                                {{ $cam->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                            </td>
                            <td>
                                <div class="buttons is-right" style="margin: 0;">
                                    <a href="{{ route('cameras.show', $cam->name) }}" class="button is-light is-small">
                                        <span class="icon"><i class="fas fa-eye"></i></span>
                                    </a>
                                    <form action="{{ route('cameras.destroy', $cam->id) }}" method="POST"
                                          onsubmit="return confirm('Supprimer définitivement cette caméra ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button is-light is-small" style="color: #DC2626;">
                                            <span class="icon"><i class="fas fa-trash"></i></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
@endsection
