@extends('errors.layout')

@section('code', '403')
@section('title', 'Acceso Denegado')
@section('message', 'No tienes los permisos necesarios para acceder a esta sección. Si crees que esto es un error, contacta al administrador del sistema.')

@section('action')
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Regresar
    </a>
@endsection
