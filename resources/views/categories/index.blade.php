{{-- resource/views/categories/index.blade.php --}}

<x-app-layout title="Categories Page">
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
                <h2 class="hero-slider-title text-center">All <strong>Categories</strong></h2>
                <p class="hero-slider-content text-center">Experience the pinnacle of quality
                    <br>with our carefully curated vehicle categories.</p>
            </div>

            <div class="services-grid container">
                @foreach ($categories as $category)
                    <x-service-card
                        :href="route('category.show', $category->slug)"
                        :image="$category->image_path"
                        :title="$category->long_name ?? $category->name"
                        :description="$category->description"
                    />
                @endforeach
            </div>
        </section>
        <!-- /Category Boxes -->
    </main>

</x-app-layout>
