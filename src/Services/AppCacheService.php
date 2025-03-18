<?php


namespace App\Services;

use Psr\Cache\CacheItemPoolInterface;



class AppCacheService {
    public function __construct(private CacheItemPoolInterface $cache) {
    }

   
    public function check(){
       $cacheItem = $this->cache->getItem("item_key");
        if(!$cacheItem->isHit()) {
            $apiKey = md5('foo');
    
            $cacheItem->set($apiKey);
            $this->cache->save($cacheItem);
        }
        else{
            echo 'Hit <br>';

            $apiKey = $cacheItem->get();
            dd($apiKey);
            return $apiKey;
        }
    }

    



}