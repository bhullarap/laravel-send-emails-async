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
    * @var string
    */
    protected $elastic_search_index;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(){
        $this->elastic_search_index = config("services.elastic_search.indexes.emails");
    }

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
            $elastic_index_data = $elasticsearchHelper->storeEmail($this->elastic_search_index,$emailData['body'],$emailData['subject'],$emailData['recipient']);
            # Create implementation for storeRecentMessage in redis
            $redisHelper->storeRecentMessage($elastic_index_data["_id"], $emailData['subject'], $emailData['recipient']);
        }
        # return response
        return response()->json(['message' => 'Emails queued for sending'], Response::HTTP_ACCEPTED);
    }

    /**
     * list
     *
     * @param  ElasticsearchHelperInterface $elasticsearchHelper
     * @param  RedisHelperInterface $redisHelper
     * @return JsonResponse
     */
    public function list(ElasticsearchHelperInterface $elasticsearchHelper, RedisHelperInterface $redisHelper)
    {
        # Intialize Response Array
        $emails = [];
        # Retrieve Cached Data
        $cached_data = $redisHelper->retrieverecentMessages();
        # Loop Throught Cached Data
        foreach ($cached_data as $cache) {
            # Retrieve Data From Elastic Search and push to response array
            $response = $elasticsearchHelper->retrieveEmail($cache->elasticsearch_id, $this->elastic_search_index);
            if (isset($response['_source'])) {
                $emails[] = $response['_source'];
            }
        }
        # Return Json Response
        return response()->json(['emails' => $emails ], Response::HTTP_OK);
    }
}
