<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\User;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();

        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'excerpt' => 'Learn more about our photography services and our passion for capturing beautiful moments.',
                'content' => '<h2>Welcome to Our Gallery</h2>
                <p>We are passionate photographers dedicated to capturing life\'s most precious moments. With years of experience in various photography styles, we specialize in creating stunning visual stories that last a lifetime.</p>
                
                <h3>Our Mission</h3>
                <p>Our mission is to provide exceptional photography services that exceed our clients\' expectations. We believe that every moment is unique and deserves to be captured with creativity, professionalism, and attention to detail.</p>
                
                <h3>Services We Offer</h3>
                <ul>
                    <li>Wedding Photography</li>
                    <li>Portrait Sessions</li>
                    <li>Event Photography</li>
                    <li>Commercial Photography</li>
                    <li>Landscape Photography</li>
                </ul>
                
                <h3>Why Choose Us</h3>
                <p>With our professional equipment, creative vision, and commitment to excellence, we ensure that your special moments are captured beautifully. Our team works closely with clients to understand their vision and deliver results that tell their unique story.</p>',
                'meta_title' => 'About Us - Professional Photography Services',
                'meta_description' => 'Learn about our professional photography services, our mission, and why we are passionate about capturing your special moments.',
                'is_published' => true,
                'show_in_menu' => true,
                'menu_order' => 1,
            ],
            [
                'title' => 'Services',
                'slug' => 'services',
                'excerpt' => 'Discover our comprehensive photography services tailored to meet your specific needs.',
                'content' => '<h2>Our Photography Services</h2>
                <p>We offer a wide range of professional photography services to capture your most important moments with style and creativity.</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <h3>Wedding Photography</h3>
                        <p>Your wedding day is one of the most important days of your life. We capture every precious moment, from the intimate getting-ready shots to the grand celebration.</p>
                        
                        <h3>Portrait Photography</h3>
                        <p>Professional portraits for individuals, families, and corporate needs. We create stunning portraits that reflect your personality and style.</p>
                    </div>
                    <div class="col-md-6">
                        <h3>Event Photography</h3>
                        <p>From corporate events to birthday parties, we document your special occasions with professionalism and creativity.</p>
                        
                        <h3>Commercial Photography</h3>
                        <p>High-quality commercial photography for businesses, including product photography, corporate headshots, and marketing materials.</p>
                    </div>
                </div>
                
                <h3>Pricing</h3>
                <p>We offer competitive pricing packages tailored to your specific needs. Contact us for a personalized quote based on your requirements.</p>
                
                <p><strong>Ready to book a session?</strong> Contact us today to discuss your photography needs and schedule your session.</p>',
                'meta_title' => 'Photography Services - Wedding, Portrait, Event & Commercial',
                'meta_description' => 'Professional photography services including wedding, portrait, event, and commercial photography. Contact us for competitive pricing.',
                'is_published' => true,
                'show_in_menu' => true,
                'menu_order' => 2,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'excerpt' => 'Get in touch with us to discuss your photography needs or to book a session.',
                'content' => '<h2>Get In Touch</h2>
                <p>We would love to hear from you! Whether you have questions about our services, want to book a session, or just want to say hello, don\'t hesitate to reach out.</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <h3>Contact Information</h3>
                        <p><strong>Phone:</strong> +62 123 456 7890</p>
                        <p><strong>Email:</strong> info@gagaleri.com</p>
                        <p><strong>Address:</strong> Jl. Photography No. 123, Jakarta, Indonesia</p>
                        
                        <h3>Business Hours</h3>
                        <p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM</p>
                        <p><strong>Saturday:</strong> 10:00 AM - 4:00 PM</p>
                        <p><strong>Sunday:</strong> By appointment only</p>
                    </div>
                    <div class="col-md-6">
                        <h3>Follow Us</h3>
                        <p>Stay connected with us on social media for the latest updates and behind-the-scenes content:</p>
                        <ul>
                            <li>Instagram: @gagaleri</li>
                            <li>Facebook: GaGaleri Photography</li>
                            <li>Twitter: @gagaleri</li>
                        </ul>
                        
                        <h3>Quick Response</h3>
                        <p>We typically respond to inquiries within 24 hours. For urgent requests, please call us directly.</p>
                    </div>
                </div>
                
                <h3>Book a Consultation</h3>
                <p>Ready to discuss your photography needs? We offer free consultations to understand your vision and provide personalized recommendations. Contact us today to schedule your consultation!</p>',
                'meta_title' => 'Contact Us - Professional Photography Services',
                'meta_description' => 'Contact us for professional photography services. Phone: +62 123 456 7890, Email: info@gagaleri.com. Free consultations available.',
                'is_published' => true,
                'show_in_menu' => true,
                'menu_order' => 3,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'excerpt' => 'Our commitment to protecting your privacy and personal information.',
                'content' => '<h2>Privacy Policy</h2>
                <p><em>Last updated: ' . now()->format('F j, Y') . '</em></p>
                
                <h3>Information We Collect</h3>
                <p>We collect information you provide directly to us, such as when you:</p>
                <ul>
                    <li>Contact us for photography services</li>
                    <li>Book a photography session</li>
                    <li>Subscribe to our newsletter</li>
                    <li>Leave testimonials or comments</li>
                </ul>
                
                <h3>How We Use Your Information</h3>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Provide and improve our photography services</li>
                    <li>Communicate with you about your bookings</li>
                    <li>Send you updates about our services (with your consent)</li>
                    <li>Respond to your inquiries and provide customer support</li>
                </ul>
                
                <h3>Information Sharing</h3>
                <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>
                
                <h3>Photo Usage Rights</h3>
                <p>By booking our photography services, you grant us permission to use selected photos for portfolio and marketing purposes, unless otherwise specified in your contract.</p>
                
                <h3>Contact Us</h3>
                <p>If you have any questions about this Privacy Policy, please contact us at info@gagaleri.com.</p>',
                'meta_title' => 'Privacy Policy - GaGaleri Photography',
                'meta_description' => 'Our privacy policy explains how we collect, use, and protect your personal information when using our photography services.',
                'is_published' => true,
                'show_in_menu' => false,
                'menu_order' => 99,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate(
                ['slug' => $pageData['slug']], // Check by slug
                array_merge($pageData, [
                    'created_by' => $admin->id ?? 1,
                ])
            );
        }

        $this->command->info('Sample pages created successfully!');
    }
}
