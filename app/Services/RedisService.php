<?php

namespace App\Services;

use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

Class RedisService implements RedisHelperInterface{
    
    /**
    * @var Illuminate\Support\Facades\Redis
    */
    protected $redis;

    /**
    * @var string
    */
    protected $callable_key;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->redis = Redis::connection();
        #$this->redis = new RedisClient(config('database.redis.default'));
        $this->callable_key = config("services.emails_cache.callable_key");
    }

    /**
     * Store the id of a message along with a message subject in Redis.
     *
     * @param  mixed  $id
     * @param  string  $messageSubject
     * @param  string  $toEmailAddress
     * @return void
     */
    public function storeRecentMessage(mixed $id, string $messageSubject, string $toEmailAddress): void{
        # Get Already Existing Data 
        $cached_data =  json_decode($this->redis->get($this->callable_key), true)?:[];
        # Merge the New Data with Existing Data
        $new_data = [
            'elasticsearch_id'  => $id,
            'subject'           => $messageSubject, 
            'toEmail'           => $toEmailAddress
        ];
        $updated_cache = Arr::prepend($cached_data,$new_data);
        # Set the Updated Cache to Redis
        $this->redis->set($this->callable_key,json_encode($updated_cache));
    }
    
    /**
     * retrieverecentMessages
     *
     * @return array
     */
    public function retrieverecentMessages():array{
        return  json_decode($this->redis->get($this->callable_key));
    }
}