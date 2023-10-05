<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailController extends Controller
{
    /**
     * send
     *
     * @param  ElasticsearchHelperInterface $elasticsearchHelper
     * @param  RedisHelperInterface $redisHelper
     * @param  Request $request
     * @return JsonResponse
     */
    public function send(ElasticsearchHelperInterface $elasticsearchHelper, RedisHelperInterface $redisHelper, Request $request):JsonResponse
    {
        # TODO: Validate Input
        $emails = $request->validate([
            'emails' => 'required|array',
            'emails.*.subject' => 'required|string',
            'emails.*.body' => 'required|string',
            'emails.*.recipient' => 'required|email',
        ]);

        foreach ($emails['emails'] as $emailData) {
            # Dispatch Email Job
            SendEmailJob::dispatch($emailData)->onQueue('emails'); # Dispatch a job for each email
            # Create implementation for storeEmail in elastic search
            $elastic_index_data = $elasticsearchHelper->storeEmail('email_data',$emailData['body'],$emailData['subject'],$emailData['recipient']);
            # Create implementation for storeRecentMessage in redis
            $redisHelper->storeRecentMessage($elastic_index_data["_id"], $emailData['subject'], $emailData['recipient']);
        }
        # return response
        return response()->json(['message' => 'Emails queued for sending'], Response::HTTP_ACCEPTED);
    }

    //  TODO - BONUS: implement list method
    public function list()
    {

    }
}
