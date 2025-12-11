{{-- resources/views/components/layouts/footer.blade.php --}}
<div class="footer-basic">
    <footer>
        {{-- <div class="social">
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-snapchat"></i></a>
            <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
        </div> --}}
        <ul class="list-inline">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
            <li><a href="{{ route('terms') }}">Terms</a></li>
            <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
        </ul>
        <p class="copyright">
            {{ config('app.name', 'Dealership') }} © {{ date('Y') }}
        </p>
    </footer>
</div>
