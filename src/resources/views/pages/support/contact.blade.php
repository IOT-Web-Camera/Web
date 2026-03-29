@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <div class="max-w-3xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">Contactez-nous</h1>

        <form action="#" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block font-semibold mb-1">Votre nom</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Votre nom">
            </div>

            <div>
                <label class="block font-semibold mb-1">Votre email</label>
                <input type="email" class="w-full border rounded px-3 py-2" placeholder="email@example.com">
            </div>

            <div>
                <label class="block font-semibold mb-1">Message</label>
                <textarea class="w-full border rounded px-3 py-2 h-32" placeholder="Votre message..."></textarea>
            </div>

            <button class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                Envoyer
            </button>
        </form>
    </div>
@endsection
