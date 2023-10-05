<?php

namespace Tests\Feature;

use App\Jobs\SendEmailJob;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendEmailJobTest extends TestCase
{    
    /**
     * testSendEmailJob
     *
     * @return void
     */
    public function testSendEmailJob()
    {
        # Arrange
        Mail::fake(); // Fake the mail sending

        $emailData = [
            'subject' => 'Test Subject',
            'body' => 'Test Body',
            'recipient' => 'test@example.com',
        ];

        $job = new SendEmailJob($emailData);

        # Act
        $job->handle();

        # Assert
        Mail::assertSent(SendEmail::class, function ($mail) use ($emailData) {
            return $mail->hasTo($emailData['recipient']) && $mail->subject === $emailData['subject'];
        });
    }
}
