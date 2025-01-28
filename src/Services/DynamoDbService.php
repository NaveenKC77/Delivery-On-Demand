<?php

namespace App\Services;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Aws\Result;

class DynamoDbService
{
    public const TABLE_NAME = "Logs";

    protected Marshaler $marshaler;
    public function __construct(protected DynamoDbClient $client)
    {

        $this->marshaler = new Marshaler();

    }

    /**
     * Summary of resultstoObjectArrays.
     *
     * @return array
     */
    public function resultstoObjectArrays(Result $result)
    {
        $items = [];
        foreach ($result['Items'] as $item) {
            $items[] = (object) $this->marshaler->unmarshalItem($item);
        }

        return $items;
    }

    /**
     * Summary of itemToObject.
     *
     * @return object
     *                Single Item to object from AWS Result
     */
    public function itemToObject(Result $result)
    {
        $item = (object) $this->marshaler->unmarshalItem($result['Item']);

        return $item;
    }
    /**
     * @param array $queryParam
     * @return array
     *               perform query on dynamodb
     */
    public function query(array $queryParam): Result
    {
        $result = $this->client->query($queryParam);
        return $result;
    }
    /**
     * Summary of getItem
     * @param array $key
     * @return object
     *                get single item for dynamodb table and cast result to object
     */
    protected function getItem(array $key)
    {
        $result = $this->client->getItem([
            'Key' => $key,
            'TableName' => self::TABLE_NAME,
        ]);

        $resultObject = $this->itemToObject($result);

        return $resultObject;
    }
    /**
     * Summary of putItem
     * @param array $item
     * @return void
     *              add single item to dynamodb table
     */
    public function putItem(array $item)
    {
        $this->client->putItem([
            'Item' => $item,
            'TableName' => self::TABLE_NAME,
        ]);
    }

    /**
     * Summary of deleteItem
     * @param array $key
     * @return void
     *              delete item from dynamo db table
     */
    protected function deleteItem(array $key)
    {
        $this->client->deleteItem([
            'Key' => $key,
            'TableName' => self::TABLE_NAME,
        ]);
    }

    /**
     * Summary of updateItem
     * @param array $args
     * @return void
     *              perform update on dynamodb item
     */
    public function updateItem(array $args)
    {
        $this->client->updateItem($args);
    }


}
