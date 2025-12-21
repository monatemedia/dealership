{{-- resources/views/legal/privacy.blade.php --}}
<x-app-layout title="Privacy Policy">
    <main>
        <div class="container-small my-large">
            <h1>POPIA Privacy Policy</h1>
            <p class="text-muted">Last Updated: {{ date('F j, Y') }}</p>
            <hr>
            <section class="mb-medium">
                <h2>Customer Privacy Notice</h2>
                <p>This Notice explains how we obtain, use and disclose your personal information, in accordance with the requirements of the <strong>Protection of Personal Information Act (“POPIA”)</strong>.</p>
                <p>At <strong>{{ config('app.name') }}</strong>, we are committed to protecting your privacy and to ensure that your personal information is collected and used properly, lawfully and transparently.</p>
            </section>

            <section class="mb-medium">
                <h2>The Information We Collect</h2>
                <p>We collect and process your personal information mainly to contact you for the purposes of understanding your requirements, and delivering services accordingly.</p>
                <p><strong>Website usage:</strong> Information may be collected using “cookies” (including Google Analytics) which allows us to collect standard internet visitor usage information.</p>
                <p><strong>Social Media Logins:</strong> If you choose to register or log in using Google or Facebook (Meta), we receive limited information from your social profile, such as your name, email address, and profile picture. This is used solely to create and manage your user account.</p>
            </section>

            <section class="mb-medium">
                <h2>How We Use Your Information</h2>
                <p>We will use your personal information only for the purposes for which it was collected. For example:</p>
                <ul>
                    <li>To confirm and verify your identity via <strong>email confirmation</strong> (using Amazon SES).</li>
                    <li>To verify that you are an authorized user for security purposes.</li>
                    <li>For audit and record keeping purposes.</li>
                    <li>In connection with legal proceedings.</li>
                </ul>
            </section>

            <section class="mb-medium">
                <h2>Data Deletion & Your Rights</h2>
                <p>You have the right to request a copy of the personal information we hold about you or to ask us to update, correct or delete your personal information.</p>
                <p><strong>Data Deletion Instructions:</strong> You may request the deletion of your account and all associated data at any time. To do so, please email our support team at <strong>webmaster@actuallyfind.com</strong> or submit a request via our <a href="{{ route('contact') }}">Contact Page</a>. Upon verification, we will permanently remove your data from our systems within 30 days, except where legal retention is required.</p>
                <p>If you use Facebook Login, you may also remove <strong>ActuallyFind</strong>'s access to your data through your Facebook Profile under <em>Settings & Privacy > Apps and Websites</em>.</p>
            </section>

            <section class="mb-medium">
                <h2>Information Security</h2>
                <p>We are legally obliged to provide adequate protection for the personal information we hold. Our security policies cover:</p>
                <ul>
                    <li>Computer and network security.</li>
                    <li>Secure communications.</li>
                    <li>Monitoring access and usage of private information.</li>
                </ul>
            </section>

            <section class="mb-medium">
                <h2>How to Contact Us</h2>
                <p>If you have any queries about this notice, please contact us at <strong>webmaster@actuallyfind.com</strong>, via our <a href="{{ route('contact') }}">Contact Page</a>, or using the details in the website footer.</p>
            </section>
        </div>
    </main>
</x-app-layout>
