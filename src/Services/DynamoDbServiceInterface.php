<?php

namespace App\Services;

use Aws\Result;

/**
 * All Dynamo Db Functions
 */
interface DynamoDbServiceInterface
{
    /**
     * Convert DYnamoDB Results To Consumable Arrays
     * @param \Aws\Result $result
     * @return array
     */
    public function resultstoObjectArrays(Result $result): array ;

    /**
     * Convert AWS result for single row to an PHP Object
     * @param \Aws\Result $result
     * @return object
     */
    public function itemToObject(Result $result): object;

    /**
     * Query DynamoDB
     * @param array $queryParam
     * @return Result
     */
    public function query(array $queryParam): Result;


    /**
     * Get a single rpw from DynaamoDb
     * @param array $key
     * @return object
     */
    public function getItem(array $key): object;

    /**
     * Enter Data to DynamoDb
     * @param array $item
     * @return void
     */
    public function putItem(array $item): void;

    /**
     * Delete Data from DYnamoDb
     * @param array $key
     * @return void
     */
    public function deleteItem(array $key): void;

    /**
     * Update Row in DynamoDB
     * @param array $args
     * @return void
     */
    public function updateItem(array $args): void;



}
