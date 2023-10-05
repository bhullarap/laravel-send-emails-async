<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailControllerTest extends TestCase
{
    
    /**
     * testSendEmailsEndpoint
     *
     * @return void
     */
    public function testSendEmailsEndpoint()
    {
        # Create sample email data
        $emailData = [
            [
                'subject' => 'Test Subject 1',
                'body' => 'Test Body 1',
                'recipient' => 'recipient1@example.com',
            ],
            [
                'subject' => 'Test Subject 2',
                'body' => 'Test Body 2',
                'recipient' => 'recipient2@example.com',
            ],
        ];

        # Send a POST request to the sendEmails endpoint
        $response = $this->json('POST', '/api/1/send', ['emails' => $emailData]);
       /*  $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/api/1/send', ['emails' => $emailData]); */

        $response->assertStatus(202); // Expect a 202 Accepted response
    }
    
    /**
     * testSendEmailsEndpointValidationFailure
     *
     * @return void
     */
    public function testSendEmailsEndpointValidationFailure()
    {
        # Invalid email data with missing 'recipient' field
        $emailData = [
            [
                'subject' => 'Test Subject 1',
                'body' => 'Test Body 1',
            ],
        ];

       # Send a POST request to the sendEmails endpoint
       $response = $this->json('POST', '/api/1/send', ['emails' => $emailData]);
       /*  $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/api/1/send', ['emails' => $emailData]); */

        $response->assertStatus(422); // Expect a 422 Unprocessable Entity response
        $response->assertJsonValidationErrors(['emails.0.recipient']);
    }
}
