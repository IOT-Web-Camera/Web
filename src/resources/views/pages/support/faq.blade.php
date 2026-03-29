@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">Foire aux questions</h1>

        <div class="space-y-6">

            <div>
                <h2 class="text-xl font-semibold">Comment ajouter une caméra ?</h2>
                <p class="text-gray-600">
                    Depuis votre tableau de bord, cliquez sur “Ajouter une caméra”, puis suivez les instructions.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-semibold">Pourquoi ma caméra apparaît hors ligne ?</h2>
                <p class="text-gray-600">
                    Vérifiez la connexion réseau, l’alimentation et que le flux RTSP est bien actif.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-semibold">Comment contacter le support ?</h2>
                <p class="text-gray-600">
                    Vous pouvez utiliser le formulaire disponible sur la page Contact.
                </p>
            </div>

        </div>
    </div>
@endsection
