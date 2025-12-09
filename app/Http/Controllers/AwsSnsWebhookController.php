<?php // app/Http/Controllers/AwsSnsWebhookController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AwsSnsWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Get the payload and decode the JSON
        $payload = json_decode($request->getContent(), true);

        // 2. Handle Subscription/Unsubscribe Confirmation
        if (isset($payload['Type']) && ($payload['Type'] === 'SubscriptionConfirmation' || $payload['Type'] === 'UnsubscribeConfirmation')) {
            return $this->handleSubscriptionConfirmation($payload);
        }

        // 3. Handle Notification (Bounce/Complaint)
        if (isset($payload['Type']) && $payload['Type'] === 'Notification') {
            return $this->handleNotification($payload);
        }

        // Default response for unhandled requests
        return response()->json(['status' => 'received'], 200);
    }

    protected function handleSubscriptionConfirmation(array $payload)
    {
        // This confirms the SNS subscription link to activate the webhook
        $url = $payload['SubscribeURL'] ?? null;
        if ($url) {
            // In a production environment, you would use Guzzle or curl to call this URL.
            // For now, log it so you can confirm it manually.
            Log::info("AWS SNS Subscription Confirmation URL: " . $url);

            // To confirm manually: Copy the URL from your logs and paste it into your browser.
            // AWS will send one test request, then your webhook will be active.

            return response()->json(['status' => 'confirmation_pending'], 200);
        }
        return response()->json(['status' => 'error'], 400);
    }

    protected function handleNotification(array $payload)
    {
        // The actual SES event data is inside the 'Message' field as a JSON string
        $messageData = json_decode($payload['Message'] ?? '{}', true);

        // The type of event (Bounce, Complaint, Delivery, etc.)
        $eventType = $messageData['notificationType'] ?? null;

        if ($eventType === 'Bounce' || $eventType === 'Complaint') {
            // Dispatch a job/event to handle the email suppression asynchronously
            // We will define this event in Phase 2
            $this->processFeedback($messageData);
        }

        return response()->json(['status' => 'processed'], 200);
    }

    protected function processFeedback(array $messageData)
    {
        $eventType = $messageData['notificationType'];
        $feedback = $messageData['bounce'] ?? $messageData['complaint'] ?? [];

        // Check for specific bounced recipients
        if (isset($feedback['bouncedRecipients'])) {
            $recipients = $feedback['bouncedRecipients'];
            foreach ($recipients as $recipient) {
                $email = $recipient['emailAddress'] ?? null;
                $bounceType = $feedback['bounceType'] ?? 'Unknown';

                if ($email && $bounceType === 'Permanent') {
                    // Log and suppress the user
                    Log::critical("Permanent Bounce Detected: {$email}");
                    // Call the function to update your database (Phase 2)
                    $this->suppressUser($email, 'bounced');
                }
            }
        }

        // Check for complaint recipients
        if (isset($feedback['complainedRecipients'])) {
            $recipients = $feedback['complainedRecipients'];
            foreach ($recipients as $recipient) {
                $email = $recipient['emailAddress'] ?? null;
                if ($email) {
                    // Log and suppress the user
                    Log::critical("Complaint Detected: {$email}");
                    // Call the function to update your database (Phase 2)
                    $this->suppressUser($email, 'complaint');
                }
            }
        }
    }

    protected function suppressUser(string $email, string $reason): void
    {
        // Mark the user as suppressed in the database
        User::where('email', $email)->update(['email_status' => 'suppressed']);
        Log::info("User with email {$email} flagged as 'suppressed' due to {$reason}.");
    }
}
