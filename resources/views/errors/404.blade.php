@extends('errors.layout')

@section('code', '404')
@section('title', 'Página No Encontrada')
@section('message', 'La página que buscas no existe o ha sido movida. Verifica la dirección e intenta nuevamente.')


@section('action')
    <a href="{{ url('/') }}" class="btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/>
        </svg>
        Ir al Inicio
    </a>
@endsection
