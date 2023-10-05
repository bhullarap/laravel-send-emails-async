<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
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
        $response = $this->withHeaders([
                        'Accept'    => 'application/json'
                    ])->post('/api/1/send?api_token=123246', ['emails' => $emailData]);

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
        $response = $this->withHeaders([
            'Accept'    => 'application/json'
        ])->post('/api/1/send?api_token=123246', ['emails' => $emailData]);

        $response->assertStatus(422); // Expect a 422 Unprocessable Entity response
        $response->assertJsonValidationErrors(['emails.0.recipient']);
    }
    
    /**
     * testListEmailsEndpoint
     *
     * @return void
     */
    public function testListEmailsEndpoint()
    {
        # Make a GET request to API endpoint
        $response = $this->withHeaders([
                        'Accept'    => 'application/json'
                    ])->get('/api/list?api_token=321321');

        # Assert that the response has a 200 status code
        $response->assertStatus(200);
        # Decode the JSON response
        $responseData = $response->json();
        Log::info($responseData);
        # Check if the JSON response contains specific data
        $this->assertArrayHasKey('emails', $responseData);
        $nestedData = $responseData['emails'];
        foreach($nestedData as $data){
            $this->assertArrayHasKey('messageSubject', $data);
            $this->assertArrayHasKey('messageBody', $data);
        }
    }
}
