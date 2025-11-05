<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ContactInfo;
use App\Models\FooterContent;
use App\Models\PersonalInfo;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Root Admin
        User::create([
            'name' => 'Root Admin',
            'email' => 'root@admin.com',
            'password' => Hash::make('root_admin_2024_secret'),
            'role' => 'root_admin',
            'is_active' => true,
        ]);

        // Create Admin with specified credentials
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Web Design', 'slug' => 'web-design', 'icon' => 'bi-layout-text-window-reverse', 'color' => '#667EEA', 'description' => 'Modern web design projects'],
            ['name' => 'UI/UX', 'slug' => 'ui-ux', 'icon' => 'bi-palette', 'color' => '#00B894', 'description' => 'User interface and experience designs'],
            ['name' => 'Graphics', 'slug' => 'graphics', 'icon' => 'bi-image', 'color' => '#FD79A8', 'description' => 'Graphic design and illustrations'],
            ['name' => 'Branding', 'slug' => 'branding', 'icon' => 'bi-tag', 'color' => '#FDCB6E', 'description' => 'Brand identity and logo design'],
            ['name' => 'Print', 'slug' => 'print', 'icon' => 'bi-printer', 'color' => '#6C5CE7', 'description' => 'Print design materials'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Personal Info
        PersonalInfo::create([
            'full_name' => 'John Doe',
            'title' => 'Creative Graphic Designer',
            'short_bio' => 'Passionate about creating beautiful designs',
            'full_bio' => 'I am a creative graphic designer with over 10 years of experience in creating stunning visual designs. I specialize in web design, UI/UX, branding, and print media.',
            'phone' => '+1 234 567 890',
            'email' => 'contact@example.com',
            'address' => '123 Design Street, Creative City, CC 12345',
            'website' => 'https://example.com',
        ]);

        // Create Skills
        $skills = [
            ['name' => 'Photoshop', 'icon' => 'bi-paint-bucket', 'percentage' => 95, 'description' => 'Advanced Photoshop skills'],
            ['name' => 'Illustrator', 'icon' => 'bi-pencil', 'percentage' => 90, 'description' => 'Expert in vector graphics'],
            ['name' => 'Figma', 'icon' => 'bi-layers', 'percentage' => 85, 'description' => 'UI/UX design tool'],
            ['name' => 'InDesign', 'icon' => 'bi-file-earmark-text', 'percentage' => 80, 'description' => 'Print design expert'],
            ['name' => 'HTML/CSS', 'icon' => 'bi-code-slash', 'percentage' => 75, 'description' => 'Web development skills'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // Create Contact Info
        ContactInfo::create([
            'phone' => '+1 234 567 890',
            'email' => 'contact@example.com',
            'address' => '123 Design Street, Creative City, CC 12345',
            'alternative_email' => 'info@example.com',
            'website' => 'https://example.com',
        ]);

        // Create Social Links
        $socialLinks = [
            ['platform' => 'twitter', 'url' => 'https://twitter.com', 'icon' => 'bi-twitter', 'is_active' => true],
            ['platform' => 'instagram', 'url' => 'https://instagram.com', 'icon' => 'bi-instagram', 'is_active' => true],
            ['platform' => 'linkedin', 'url' => 'https://linkedin.com', 'icon' => 'bi-linkedin', 'is_active' => true],
            ['platform' => 'dribbble', 'url' => 'https://dribbble.com', 'icon' => 'bi-dribbble', 'is_active' => true],
            ['platform' => 'behance', 'url' => 'https://behance.net', 'icon' => 'bi-behance', 'is_active' => true],
            ['platform' => 'github', 'url' => 'https://github.com', 'icon' => 'bi-github', 'is_active' => true],
        ];

        foreach ($socialLinks as $link) {
            SocialLink::create($link);
        }

        // Create Footer Content
        FooterContent::create([
            'about_text' => 'We are a creative team passionate about delivering high-quality design solutions for businesses and individuals worldwide.',
            'services' => "Web Design\nUI/UX Design\nBranding\nPrint Design\nLogo Design",
            'copyright_text' => '© 2024 Graphic Portfolio. All rights reserved.',
            'privacy_policy_url' => '/privacy-policy',
            'terms_of_service_url' => '/terms-of-service',
        ]);
    }
}

