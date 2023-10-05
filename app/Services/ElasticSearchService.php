<?php

namespace App\Services;

use Illuminate\Support\Str;
use Elasticsearch\ClientBuilder;
use App\Utilities\Contracts\ElasticsearchHelperInterface;

Class ElasticSearchService implements ElasticsearchHelperInterface{
    
    /**
     * storeEmail
     *
     * @param  string $index
     * @param  string $messageBody
     * @param  string $messageSubject
     * @param  string $toEmailAddress
     * @return mixed
     */
    public function storeEmail(string $index, string $messageBody, string $messageSubject, string $toEmailAddress): mixed{
        # Create Data Object
        $data = [
            'body' => [
                'messageBody'       => $messageBody,
                'messageSubject'    => $messageSubject,
                'toEmailAddress'    => $toEmailAddress
            ],
            'index' => $index
        ];
        $client = ClientBuilder::create()->build();
        return $client->index($data);
    }
}