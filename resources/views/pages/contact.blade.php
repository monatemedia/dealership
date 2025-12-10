<x-app-layout title="Contact Us">
    {{-- Removed <style> block as per previous step --}}

    <div class="container-small my-large">
        <h1 class="text-center">Get in Touch</h1>
        <p class="text-center text-muted subtitle">
            We're here to help! Reach out to us with any questions, feedback, or support inquiries.
        </p>
        <hr>

        {{-- Contact Cards Section --}}
        <section class="section">
            <h2 class="text-center">Customer Support</h2>
            <div class="contact-cards">
                <div class="contact-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email Address</h3>
                    <a href="mailto:info@actuallyfind.com" class="contact-link">
                        info@actuallyfind.com
                    </a>
                    <p class="contact-note">We typically respond within 24 hours</p>
                </div>
                <div class="contact-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Phone Number</h3>
                    <a href="tel:+27783245326" class="contact-link">
                        +27 78 324 5326
                    </a>
                    <p class="contact-note">Standard call rates apply</p>
                </div>
            </div>
        </section>

        {{-- Operating Hours and Live Clock Section --}}
        <section class="section">
            <h2 class="text-center">Operating Hours</h2>

            <div class="hours-card">
                <ul class="hours-list">
                    <li class="hours-item">
                        <span class="hours-day">
                            <i class="far fa-calendar"></i> Monday - Friday
                        </span>
                        <span class="hours-time">9:00 AM - 5:00 PM</span>
                    </li>
                    <li class="hours-item">
                        <span class="hours-day">
                            <i class="far fa-calendar"></i> Weekends & Public Holidays
                        </span>
                        <span class="hours-time closed">Closed</span>
                    </li>
                </ul>
            </div>

            {{-- Alpine.js Live Clock --}}
            <div class="current-time-card" x-data="southAfricaTime()">
                <h3 class="text-center">
                    <i class="fas fa-clock"></i> Current Time in South Africa
                </h3>
                <div class="time-display text-center" x-text="currentTime">--:--:--</div>
                <div class="date-display text-center" x-text="currentDate">-</div>
            </div>
        </section>
    </div>

    {{-- Alpine.js Script Block (Updated for 12-hour format) --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('southAfricaTime', () => ({
                currentTime: 'Loading...',
                currentDate: '-',
                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                },
                updateTime() {
                    const now = new Date();

                    // --- CHANGED: Set hour12 to true to enable AM/PM ---
                    const timeOptions = {
                        timeZone: 'Africa/Johannesburg',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true // Set to true for 12-hour format (AM/PM)
                    };
                    // --------------------------------------------------

                    const dateOptions = {
                        timeZone: 'Africa/Johannesburg',
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };

                    this.currentTime = now.toLocaleTimeString('en-US', timeOptions); // Changed locale to 'en-US' for guaranteed AM/PM format
                    this.currentDate = now.toLocaleDateString('en-ZA', dateOptions);
                }
            }));
        });
    </script>
</x-app-layout>
