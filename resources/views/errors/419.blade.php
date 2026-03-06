@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesión Expirada')
@section('message', 'Tu sesión ha expirado por inactividad. Por favor, vuelve a iniciar sesión para continuar.')

@section('action')
    <a href="{{ url('/') }}" class="btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        Iniciar Sesión
    </a>
@endsection
