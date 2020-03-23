@extends('layouts.livewire')

@section('title', __(':Squad squad in :Club', ['Squad' => $squad, 'Club' => $club->name]) . ' - ')

@section('header')
    @include('clubs.partials.header')
@endsection

@section('content')

    <livewire:squad :locale="app()->getLocale()" :club="$club" :squad="$squad"/>

@endsection
