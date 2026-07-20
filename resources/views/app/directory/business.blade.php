@extends('layouts.app')
@section('title', 'Directory Bisnis')
@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold">Bisnis Alumni</h1>
        <a href="{{ route('business.index') }}" class="text-xs font-semibold text-brand">Kelola bisnis saya</a>
    </div>
    @livewire('business-directory')
@endsection
