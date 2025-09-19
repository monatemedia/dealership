{{-- resource/views/categories/index.blade.php --}}

<x-app-layout title="Categories Page">
    <main class="no-padding">

        <!-- Category Boxes -->
        <section class="category-section">
            <x-title
                tag="h1"
                title="All <strong>Categories</strong>"
                paragraph="Experience the pinnacle of quality <br> with our carefully curated vehicle categories."
            />

            <x-category-grid :categories="$categories" />

        </section>
        <!-- /Category Boxes -->
    </main>

</x-app-layout>
