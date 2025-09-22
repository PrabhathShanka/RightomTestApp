@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4 sm:p-6">
        <a href="{{ route('planets.index') }}" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Back to list</a>

        <div class="bg-white shadow-lg rounded-lg p-6 sm:p-8 max-w-xl mx-auto">

            <h1 class="text-3xl sm:text-4xl font-bold mb-4 text-center text-gray-800">{{ $planet['name'] }}</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
                <p><strong>Diameter:</strong> {{ $planet['diameter'] }}</p>
                <p><strong>Terrain:</strong> {{ $planet['terrain'] }}</p>
                <p><strong>Climate:</strong> {{ $planet['climate'] }}</p>
                <p><strong>Population:</strong> {{ $planet['population'] }}</p>
                <p><strong>Gravity:</strong> {{ $planet['gravity'] }}</p>
                <p><strong>Rotation Speed:</strong> {{ number_format($planet['rotation_speed'], 2) }} km/h</p>
            </div>

            <div class="flex justify-center mt-6">
                <div class="bg-blue-500 rounded-full transition-transform duration-300"
                    style="width: {{ max(20, $planet['rotation_speed'] / 1000) }}px;
                        height: {{ max(20, $planet['rotation_speed'] / 1000) }}px;">
                </div>
            </div>

            @if (isset($planet['description']))
                <p class="mt-6 text-gray-600">{{ $planet['description'] }}</p>
            @endif
        </div>
    </div>
@endsection
