<?php

namespace App\Services;

use Elasticsearch\ClientBuilder;
use App\Utilities\Contracts\ElasticsearchHelperInterface;

Class ElasticSearchService implements ElasticsearchHelperInterface{

    /**
    * @var Elasticsearch\ClientBuilder
    */
    protected $client;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()->build();
    }

    
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
        return $this->client->index($data);
    }
    
    /**
     * retrieveEmail
     * @param string $elastic_search_id
     * @param string $elastic_search_index
     * @return mixed
     */
    public function retrieveEmail(string $elastic_search_id, string $elastic_search_index): mixed{
        return $this->client->get([
                    'index' => $elastic_search_index,
                    'id' => $elastic_search_id,
               ]);;
    }
}