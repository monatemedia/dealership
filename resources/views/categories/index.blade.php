{{-- resource/views/categories/index.blade.php --}}

<x-app-layout title="Categories Page">
    <main class="no-padding">
        {{-- Debug: Check if variable is reaching the view --}}
        @dump($selectingForCreate)

        {{-- Category Section --}}
        <x-category.category-section
            :categories="$categories"
            selectingForCreate="{{ $selectingForCreate }}"
        />

    </main>

</x-app-layout>
