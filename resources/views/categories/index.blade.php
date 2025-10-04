{{-- resource/views/categories/index.blade.php --}}

<x-app-layout title="Categories Page">
    <main class="no-padding">

        {{-- Category Section --}}
        <x-category.category-section
            :categories="$categories"
            selectingForCreate="{{ $selectingForCreate }}"
        />

    </main>

</x-app-layout>
