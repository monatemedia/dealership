@props(['title' => '', 'bodyClass' => null, 'footerLinks' => ''])

<x-base-layout :$title :$bodyClass>
    <x-layouts.header />

    {{-- Flash Messages --}}
    <x-flash-messages />

    {{ $slot }}
</x-base-layout>
