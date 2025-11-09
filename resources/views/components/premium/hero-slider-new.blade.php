<!-- Premium Bootstrap Hero Slider -->
@if (isset($sliders) && $sliders->count() > 0)
    <section id="home" class="hero-slider-premium">
        <div id="heroCarousel" class="carousel carousel-hero slide" data-bs-ride="carousel">
            <!-- Indicators -->
            @if ($sliders->count() > 1)
                <div class="carousel-indicators">
                    @foreach ($sliders as $index => $slider)
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}"
                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                    @endforeach
                </div>
            @endif

            <!-- Slides -->
            <div class="carousel-inner">
                @foreach ($sliders as $index => $slider)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="hero-slide-item">
                            <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}"
                                class="hero-slide-image d-block w-100" loading="lazy">
                            <div class="hero-slide-overlay">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-8 mx-auto text-center">
                                            {{-- @if ($slider->title)
                                                <h1 class="hero-slide-title display-font mb-3">
                                                    {{ $slider->title }}
                                                </h1>
                                            @endif

                                            @if ($slider->subtitle)
                                                <h3 class="hero-slide-subtitle mb-3">
                                                    {{ $slider->subtitle }}
                                                </h3>
                                            @endif

                                            @if ($slider->description)
                                                <p class="hero-slide-description mb-4">
                                                    {{ $slider->description }}
                                                </p>
                                            @endif --}}

                                            @if ($slider->button_text && $slider->button_link)
                                                <a href="{{ $slider->button_link }}" class="btn-view-projects">
                                                    {{ $slider->button_text }}
                                                    <i class="bi bi-arrow-right"></i>
                                                </a>
                                            @elseif($slider->button_text)
                                                <a href="#portfolio" class="btn-view-projects">
                                                    {{ $slider->button_text }}
                                                    <i class="bi bi-arrow-right"></i>
                                                </a>
                                            @else
                                                <a href="#portfolio" class="btn-view-projects">
                                                    View Projects
                                                    <i class="bi bi-arrow-right"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Controls -->
            @if ($sliders->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    </section>
@endif
