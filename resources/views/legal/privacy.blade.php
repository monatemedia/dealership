<x-app-layout title="Privacy Policy">
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
            <h2>Disclosure of Information</h2>
            <p>We may disclose your personal information to our service providers who are involved in the delivery of products or services to you. For instance, we use <strong>Amazon SES (Simple Email Service)</strong> to deliver account security and verification emails.</p>
            <p>We have agreements in place to ensure that these providers comply with the privacy requirements as required by POPIA.</p>
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
            <h2>Your Rights</h2>
            <p>You have the right to request a copy of the personal information we hold about you or to ask us to update, correct or delete your personal information.</p>
        </section>

        <section class="mb-medium">
            <h2>How to Contact Us</h2>
            <p>If you have any queries about this notice, please contact us at the details listed in our website footer or Contact Us page.</p>
        </section>
    </div>
</x-app-layout>
