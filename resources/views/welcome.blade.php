@extends('layouts.premium')

@section('title', optional($siteSettings)->site_name)
@section('description', optional($personalInfo)->short_bio ?? optional($personalInfo)->full_bio)

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
