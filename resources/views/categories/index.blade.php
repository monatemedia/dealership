{{-- resource/views/categories/index.blade.php --}}

<x-app-layout title="Categories Page">
    <main class="no-padding">

        {{-- Category Section --}}
        {{-- Category Section --}}
        <x-category.section
            :categories="$categories"
            type="Main Category"
            pluralType="Main Categories"
            indexRouteName="main-categories.index"
            showRouteName="main-categories.show"
            :selectingForCreate="$selectingForCreate"
        />

    </main>

</x-app-layout>
