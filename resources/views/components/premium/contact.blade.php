<section id="contact" class="section-premium contact-premium">
    <div class="container">
        @php
            $contact = $contactInfo ?? null;
            $phoneNumber = $contact->phone ?? '+1 (555) 123-4567';
            $primaryEmail = $contact->email ?? 'hello@example.com';
            $secondaryEmail = $contact->alternative_email ?? null;
            $websiteUrl = $contact->website ?? null;
            $addressLines = $contact && $contact->address
                ? preg_split("/\\r\\n|\\n|\\r/", $contact->address)
                : ['123 Design Street', 'Creative City, CC 12345'];
        @endphp

        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <h2 class="section-title display-font text-white">Get In Touch</h2>
                <p class="section-subtitle text-white-50">Have a project in mind? Let's create something amazing
                    together!</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Phone -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="0">
                <div class="contact-item-premium">
                    <div class="contact-icon-premium">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <h5 class="contact-title">Call Us</h5>
                    <p class="contact-info">{{ $phoneNumber }}</p>
                    @if ($websiteUrl)
                        <p class="contact-info mb-0">
                            <a href="{{ $websiteUrl }}" target="_blank" rel="noopener" class="text-decoration-none text-white-50">
                                {{ parse_url($websiteUrl, PHP_URL_HOST) ?? $websiteUrl }}
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Email -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-item-premium">
                    <div class="contact-icon-premium">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <h5 class="contact-title">Email Us</h5>
                    <p class="contact-info">
                        <a href="mailto:{{ $primaryEmail }}" class="text-decoration-none text-white">{{ $primaryEmail }}</a>
                    </p>
                    @if ($secondaryEmail)
                        <p class="contact-info mb-0">
                            <a href="mailto:{{ $secondaryEmail }}" class="text-decoration-none text-white-50">{{ $secondaryEmail }}</a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Address -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-item-premium">
                    <div class="contact-icon-premium">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h5 class="contact-title">Visit Us</h5>
                    @foreach ($addressLines as $index => $line)
                        <p class="contact-info {{ $loop->last ? 'mb-0' : '' }}">{{ $line }}</p>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Social Links -->
        @php
            $activeSocialLinks = isset($socialLinks) ? $socialLinks->filter(fn($link) => !empty($link->url)) : collect();
        @endphp
        @if ($activeSocialLinks->isNotEmpty())
            <div class="social-links-premium" data-aos="fade-up" data-aos-delay="300">
                @foreach ($activeSocialLinks as $social)
                    <a href="{{ $social->url }}" class="social-link-premium" target="_blank" rel="noopener"
                       aria-label="{{ ucfirst($social->platform ?? 'social link') }}">
                        <i class="bi {{ $social->icon ?? 'bi-share' }}"></i>
                    </a>
                @endforeach
            </div>
        @endif

        <!-- CTA Section -->
        <div class="row mt-5 pt-5">
            <div class="col-12 text-center" data-aos="fade-up">
                <h3 class="text-white fw-bold mb-4">Ready to Start Your Project?</h3>
                <p class="text-white-50 mb-4" style="font-size: 18px;">Let's discuss how we can help bring your vision
                    to life with our creative expertise.</p>
                <a href="mailto:{{ $primaryEmail }}" class="btn btn-premium btn-premium-primary btn-lg">
                    <i class="bi bi-envelope me-2"></i>
                    Send Us a Message
                </a>
            </div>
        </div>
    </div>
</section>
