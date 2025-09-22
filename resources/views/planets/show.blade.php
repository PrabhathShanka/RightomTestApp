@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <a href="{{ route('planets.index') }}" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Back to list</a>

    <div class="bg-white shadow rounded p-6">
        <h1 class="text-3xl font-bold mb-4">{{ $planet['name'] }}</h1>
        <p class="mb-2"><strong>Diameter:</strong> {{ $planet['diameter'] }}</p>
        <p class="mb-2"><strong>Terrain:</strong> {{ $planet['terrain'] }}</p>
        <p class="mb-2"><strong>Climate:</strong> {{ $planet['climate'] }}</p>
        <p class="mb-2"><strong>Population:</strong> {{ $planet['population'] }}</p>
        <p class="mb-2"><strong>Gravity:</strong> {{ $planet['gravity'] }}</p>
        <p class="mb-4"><strong>Rotation Speed:</strong> {{ number_format($planet['rotation_speed'], 2) }} km/h</p>

        <div class="flex justify-center mt-6">
            <div class="bg-blue-500 rounded-full" style="width: {{ $planet['rotation_speed'] / 1000 }}px; height: {{ $planet['rotation_speed'] / 1000 }}px;"></div>
        </div>
    </div>
</div>
@endsection
