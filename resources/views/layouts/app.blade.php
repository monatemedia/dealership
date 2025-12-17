{{-- resources/views/layouts/app.blade.php --}}
@props(['title' => '', 'bodyClass' => null, 'footerLinks' => '', 'managedFooter' => false])

<x-base-layout :$title :$bodyClass>
    <x-layouts.header />
    <x-flash-messages />

        {{ $slot }}

    {{-- The footer container now uses a simple window event to listen for updates --}}
    <div class="footer-wrapper"
        x-data="{ show: {{ $managedFooter ? 'false' : 'true' }} }"
        x-show="show"
        x-on:toggle-footer.window="show = $event.detail"
        style="display: none;" {{-- Replaces x-cloak to ensure it works even without specific CSS --}}
        :style="show ? 'display: block' : 'display: none'">
        <x-layouts.footer :$footerLinks />
    </div>
</x-base-layout>
