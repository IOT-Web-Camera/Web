<footer class="footer">
    <div class="container" style="padding: 4rem 1rem;">
        <div class="columns is-multiline">

            <!-- ------------------------
                 SODIUM VISION INFO
                 ------------------------ -->
            <div class="column is-3">
                <h3 class="title is-5" style="color: var(--sv-accent);">Sodium Vision</h3>
                <p style="color: var(--sv-text-muted); font-size: 0.9rem;">
                    Surveillance intelligente et contrôle de vos caméras Raspberry Pi.
                    Interface moderne, flux en direct, et sécurité optimale.
                </p>
                <p style="color: var(--sv-text-muted); font-size: 0.8rem; margin-top: 1rem;">
                    &copy; {{ date('Y') }} Sodium Vision. Tous droits réservés.
                </p>
            </div>

            <!-- ------------------------
                 LIENS RAPIDES
                 ------------------------ -->
            <div class="column is-3">
                <h3 class="title is-6" style="color: var(--sv-accent);">Liens rapides</h3>
                <ul style="list-style: none; padding-left: 0;">
                    <li><a href="/" class="footer-link">Accueil</a></li>
                    <li><a href="{{route('dashboard')}}" class="footer-link">Dashboard</a></li>
                    <li><a href="{{route('profile.edit')}}" class="footer-link">Profil</a></li>
                    <li><a href="{{route('cameras.index')}}" class="footer-link">Mes caméras</a></li>
                </ul>
            </div>

            <!-- ------------------------
                 SUPPORT
                 ------------------------ -->
            <div class="column is-3">
                <h3 class="title is-6" style="color: var(--sv-accent);">Support</h3>
                <ul style="list-style: none; padding-left: 0;">
                    <li><a href="{{route('support.faq')}}" class="footer-link">FAQ</a></li>
                    <li><a href="{{route('support.contact')}}" class="footer-link">Contact</a></li>
                </ul>
            </div>

            <!-- ------------------------
                 RÉSEAUX SOCIAUX
                 ------------------------ -->
            <div class="column is-3">
                <h3 class="title is-6" style="color: var(--sv-accent);">Suivez-nous</h3>
                <div class="social-icons" style="margin-top: 1rem;">
                    <a href="https://www.youtube.com/@gauthierdefrance6143" class="icon is-large social-link"><i class="fab fa-youtube"></i></a>
                    <a href="https://github.com/IOT-Web-Camera" class="icon is-large social-link"><i class="fab fa-github"></i></a>
                </div>
            </div>

        </div>
    </div>
</footer>
