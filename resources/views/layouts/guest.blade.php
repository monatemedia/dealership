@props(['title' => '', 'bodyClass' => '', 'socialAuth' => true])

<x-base-layout :$title :$bodyClass>
    <main>
        <div class="container-small page-login">
            <div class="flex" style="gap: 5rem">
                <div class="auth-page-form">
                    <div class="text-center">
                        <a href="/">
                            @if(app()->environment('production'))
                                <img src="{{ asset('img/actuallyfind-logo.png') }}" alt="ActuallyFind Logo" class="navbar-logo"/>
                            @else
                                <img src="{{ asset('img/logoipsum-265.svg') }}" alt="Development Logo" />
                            @endif
                        </a>
                    </div>

                    {{-- Flash Messages --}}
                    <x-flash-messages />

                    {{ $slot }}

                    {{-- @if ($socialAuth)
                    <div class="grid grid-cols-2 gap-1 social-auth-buttons">
                        <!-- Google Button -->
                        <x-google-button />
                        <!-- FaceBook Button -->
                        <x-fb-button />
                    </div>
                    @endif --}}

                    @isset($footerLink)
                    <div class="login-text-dont-have-account">
                        {{ $footerLink }}
                    </div>
                    @endisset


                </div>
                <div class="auth-page-image">
                    <img src="/img/car-png-39071.png" alt="" class="img-responsive" />
                </div>
            </div>
        </div>
    </main>
</x-base-layout>
