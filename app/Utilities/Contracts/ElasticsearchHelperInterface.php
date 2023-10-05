<?php

namespace App\Utilities\Contracts;

interface ElasticsearchHelperInterface {
    /**
     * Store the email's message body, subject and to address inside elasticsearch.
     *
     * @param  string  $index
     * @param  string  $messageBody
     * @param  string  $messageSubject
     * @param  string  $toEmailAddress
     * @return mixed - Return the id of the record inserted into Elasticsearch
     */
    public function storeEmail(string $index, string $messageBody, string $messageSubject, string $toEmailAddress): mixed;
    
    /**
     * retrieveEmail
     * @param string $elastic_search_id
     * @param string $elastic_search_index
     * @return mixed
     */
    public function retrieveEmail(string $elastic_search_id, string $elastic_search_index):mixed;
}
