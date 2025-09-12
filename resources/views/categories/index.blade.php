{{-- resource/views/home/index.blade.php --}}

<x-app-layout title="Home Page">
    <main>

        <!-- Category Boxes -->
        <style>

            .services-section {
                margin-bottom: 1rem;
                padding: 5rem 0;
                background: #fff;

                /* width: 100vw;
                position: relative;
                left: 50%;
                right: 50%;
                margin-left: -50vw;
                margin-right: -50vw; */
            }

            .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            }

            .service-card {
            position: relative;
            height: 24rem;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            cursor: pointer;
            }

            .service-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
            display: block;
            }

            .service-card:hover img {
            transform: scale(1.1);
            }

            .service-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0.3), transparent);
            pointer-events: none;
            z-index: 1;
            }

            .service-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.5rem;
            color: #fff;
            }

            .service-content h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
            }

            .service-content p {
            margin: 0 0 1.5rem;
            color: #ddd;
            }

            .service-link {
            display: inline-flex;
            align-items: center;
            font-weight: 500;
            color: #fbbf24; /* amber-400 */
            text-decoration: none;
            z-index: 3;
            transition: color 0.2s;
            }

            .service-link:hover {
            color: #f59e0b; /* amber-300 */
            }

            .service-link svg {
            margin-left: 0.5rem;
            width: 1rem;
            height: 1rem;
            fill: currentColor;
            }
        </style>
        <section class="services-section">
            <div>
                <h2 class="hero-slider-title text-center">Popular <strong>Categories</strong></h2>
                <p class="hero-slider-content text-center">Experience the pinnacle of quality
                    <br>with our carefully curated vehicle categories.</p>
            </div>

            <div class="services-grid container">
                <!-- Passenger Cars -->
                <div class="service-card">
                    <a href="/cars">
                        <img src="https://images.unsplash.com/photo-1702141583381-68d8b34a2898?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Passenger Cars">
                        <div class="service-overlay"></div>
                        <div class="service-content">
                            <h3>Passenger Cars</h3>
                            <p>Comfortable and efficient options for everyday travel.</p>
                            <div class="service-link">
                                Learn more
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Bakkies -->
                <div class="service-card">
                    <a href="/bakkies">
                        <img src="https://images.pexels.com/photos/8438569/pexels-photo-8438569.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Bakkies">
                        <div class="service-overlay"></div>
                        <div class="service-content">
                            <h3>Bakkies</h3>
                            <p>Reliable pickups built for work, travel, and adventure.</p>
                            <div class="service-link">
                                Learn more
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Motorcycles -->
                <div class="service-card">
                    <a href="/motorcycles">
                        <img src="https://images.unsplash.com/photo-1609630875171-b1321377ee65?q=80&w=680&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Motorcycles">
                        <div class="service-overlay"></div>
                        <div class="service-content">
                            <h3>Motorcycles</h3>
                            <p>Two-wheel freedom for speed, style, and exploration.</p>
                            <div class="service-link">
                                Learn more
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>


            </div>
        </section>
        <!-- /Category Boxes -->
    </main>

</x-app-layout>
