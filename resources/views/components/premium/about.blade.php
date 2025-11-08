<section id="about" class="section-premium bg-light">
    <div class="container">
        @php
            $studio = $creativeStudio ?? null;
            $sectionTitle = $studio->section_title ?? ('About ' . ($siteSettings->site_name ?? 'Creative Studio'));
            $sectionSubtitle = $studio->section_subtitle ?? 'Crafting exceptional designs that elevate brands and captivate audiences worldwide';
            $profileName = $studio->profile_name ?? "Hi, I'm Jane Doe";
            $profileRole = $studio->profile_role ?? 'Creative Graphic Designer & Brand Strategist';
            $ctaText = $studio->cta_text ?? 'Hire Me';
            $ctaLink = $studio->cta_link ?? '#contact';
            $highlights = collect([
                $studio->highlight_one ?? null,
                $studio->highlight_two ?? null,
                $studio->highlight_three ?? null,
                $studio->highlight_four ?? null,
            ])->filter()->values();
            $description = $studio->description ?? '<p style="font-size: 17px; line-height: 1.8;">With over <strong>5 years of experience</strong> in the creative industry, I specialize in crafting visually stunning and strategically sound designs that help brands stand out in today\'s competitive marketplace.</p><p style="font-size: 17px; line-height: 1.8;">My passion lies in transforming complex ideas into simple, elegant visual solutions. From brand identity to digital design, I approach every project with creativity, precision, and a deep understanding of design principles.</p>';
            $image = $studio?->image_path ? asset('storage/' . $studio->image_path) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
        @endphp

        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <h2 class="section-title display-font">{{ $sectionTitle }}</h2>
                <p class="section-subtitle">{{ $sectionSubtitle }}</p>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <div class="about-image-wrapper">
                    <img src="{{ $image }}"
                        alt="Creative Studio Profile" class="about-image img-fluid rounded-4">
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <h3 class="fw-bold mb-4" style="font-size: 32px;">{{ $profileName }}</h3>
                <h5 class="text-primary mb-4">{{ $profileRole }}</h5>
                <div class="mb-4 about-description">
                    {!! $description !!}
                </div>

                @if ($highlights->isNotEmpty())
                    <div class="row g-3 mb-4">
                        @foreach ($highlights as $highlight)
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                    <span><strong>{{ $highlight }}</strong></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <a href="{{ $ctaLink }}" class="btn btn-premium btn-premium-primary">
                    {{ $ctaText }}
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@push('styles')
    <style>
        .about-description p {
            font-size: 17px;
            line-height: 1.8;
            margin-bottom: 1.25rem;
        }
    </style>
@endpush
