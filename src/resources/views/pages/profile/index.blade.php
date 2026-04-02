@extends('layouts.app')



@section('content')
    <div class="container is-fluid" style="padding: 1.5rem 1rem;">

        <h1 class="title is-3">
            <span class="icon"><i class="fa-solid fa-user"></i></span>
            &nbsp;Mon Profil
        </h1>

        <div class="columns">

            <!-- Informations utilisateur -->
            <div class="column is-6">
                <div class="box">
                    <h2 class="title is-5">
                        <span class="icon"><i class="fa-solid fa-id-card"></i></span>
                        &nbsp;Informations personnelles
                    </h2>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="field">
                            <label class="label">Nom</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" name="name"
                                       value="{{ old('name', auth()->user()->name) }}" required>
                                <span class="icon is-left"><i class="fa-solid fa-user"></i></span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Adresse email</label>
                            <div class="control has-icons-left">
                                <input class="input" type="email" name="email"
                                       value="{{ old('email', auth()->user()->email) }}" required>
                                <span class="icon is-left"><i class="fa-solid fa-envelope"></i></span>
                            </div>
                        </div>

                        <div class="field mt-4">
                            <button class="button is-primary is-fullwidth">
                                <span class="icon"><i class="fa-solid fa-floppy-disk"></i></span>
                                <span>Enregistrer</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mot de passe -->
            <div class="column is-6">
                <div class="box">
                    <h2 class="title is-5">
                        <span class="icon"><i class="fa-solid fa-lock"></i></span>
                        &nbsp;Changer le mot de passe
                    </h2>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="field-group">
                            <label class="field-label">Mot de passe actuel</label>

                            <div class="password-wrapper">
                                <input class="field-input @error('current_password') is-danger @enderror"
                                       id="current_password"
                                       type="password"
                                       name="current_password"
                                       placeholder="••••••••"
                                       required>

                                <button type="button" class="toggle-password" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>

                            @error('current_password')
                            <div class="auth-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label class="field-label">Nouveau mot de passe</label>

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

                            @error('password')
                            <div class="auth-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label class="field-label">Confirmer le mot de passe</label>

                            <div class="password-wrapper">
                                <input class="field-input"
                                       id="password_confirmation"
                                       type="password"
                                       name="password_confirmation"
                                       placeholder="••••••••"
                                       required>

                                <button type="button" class="toggle-password" data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button class="button is-link is-fullwidth mt-4">
                            <span class="icon"><i class="fa-solid fa-rotate"></i></span>
                            <span>Mettre à jour</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Suppression du compte -->
        <div class="box has-background-danger-light">
            <h2 class="title is-5 has-text-danger">
                <span class="icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                &nbsp;Supprimer mon compte
            </h2>

            <p class="mb-3">Cette action est irréversible. Toutes vos caméras et données associées seront supprimées.</p>

            <button type="button" class="button is-danger" id="openDeleteModal">
                <span class="icon"><i class="fa-solid fa-trash"></i></span>
                <span>Supprimer mon compte</span>
            </button>
        </div>

    </div>

    <!-- Modal de confirmation -->
    <div id="deleteModal" class="modal">
        <div class="modal-background"></div>

        <div class="modal-card">
            <header class="modal-card-head has-background-danger">
                <p class="modal-card-title has-text-white">
                    <span class="icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                    &nbsp;Confirmer la suppression
                </p>
                <button class="delete" aria-label="close" id="closeDeleteModal"></button>
            </header>

            <section class="modal-card-body">
                <p>
                    Cette action est <strong>définitive</strong>.<br>
                    Toutes vos caméras, événements et données associées seront supprimées.
                </p>

                <p class="mt-3">
                    Pour confirmer, tapez : <strong>SUPPRIMER</strong>
                </p>

                <input id="deleteConfirmInput"
                       class="input mt-2"
                       type="text"
                       placeholder="SUPPRIMER">
            </section>

            <footer class="modal-card-foot">
                <button class="button" id="cancelDelete">Annuler</button>

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <button id="confirmDeleteBtn"
                            class="button is-danger"
                            disabled>
                        <span class="icon"><i class="fa-solid fa-trash"></i></span>
                        <span>Supprimer définitivement</span>
                    </button>
                </form>
            </footer>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- Show/Hide Password ---
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = document.getElementById(btn.dataset.target);
                    const icon = btn.querySelector('i');

                    if (target.type === 'password') {
                        target.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        target.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            });

            // --- Modal Suppression ---
            const modal = document.getElementById('deleteModal');
            const openBtn = document.getElementById('openDeleteModal');
            const closeBtn = document.getElementById('closeDeleteModal');
            const cancelBtn = document.getElementById('cancelDelete');
            const input = document.getElementById('deleteConfirmInput');
            const confirmBtn = document.getElementById('confirmDeleteBtn');

            const openModal = () => modal.classList.add('is-active');
            const closeModal = () => modal.classList.remove('is-active');

            openBtn.addEventListener('click', openModal);
            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);

            input.addEventListener('input', () => {
                confirmBtn.disabled = (input.value !== 'SUPPRIMER');
            });
        });
    </script>
@endpush
