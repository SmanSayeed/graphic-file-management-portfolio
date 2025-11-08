@extends('layouts.premium')

@section('title', 'Graphic Portfolio - Premium Design Showcase')
@section('description',
    'Professional graphic design portfolio with premium quality designs, creative works, and
    downloadable resources.')

@section('content')
    <!-- Hero Slider -->
    @include('components.premium.hero-slider-new')

    <!-- Portfolio Section -->
    @include('components.premium.portfolio')

    <!-- About Section -->
    @include('components.premium.about')

    <!-- Contact Section -->
    @include('components.premium.contact')
@endsection
