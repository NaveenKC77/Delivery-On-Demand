<?php

namespace App\Services;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Aws\Exception\AwsException;
use Aws\Result;
use Aws\Sdk;

class DynamoDbService
{
    public const TABLE_NAME = "Logs";

    private Marshaler $marshaler;
    public function __construct(private DynamoDbClient $client)
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

    public function getLogsByEntityType(string $entityType)
    {

        $queryParam = [
            'TableName' => self::TABLE_NAME,
            'IndexName' => 'EntityIndex',
            'KeyConditionExpression' => '
            #entity = :entityType',
            'ExpressionAttributeNames' => [
                '#entity' => 'Entity'
            ],
            'ExpressionAttributeValues' => [
                ':entityType' => ['S' => $entityType]
            ],
            'ScanIndexForward'          => true
        ];

        $logs = $this->client->query($queryParam);

        return $this->resultstoObjectArrays($logs);
    }

    public function getLogsByEntityAction(string $entityType, string $action)
    {

        $queryParam = [
            'TableName' => self::TABLE_NAME,
            'IndexName' => 'Entity-Action-Index',
            'KeyConditionExpression' => '
            #entity = :entityType AND
            #action = :action',
            'ExpressionAttributeNames' => [
                '#entity' => 'Entity',
                '#action' => 'Action'
            ],
            'ExpressionAttributeValues' => [
                ':entityType' => ['S' => $entityType],
                ':action' => ['S' => $action]
            ]
        ];

        $logs = $this->client->query($queryParam);

        return $this->resultstoObjectArrays($logs);
    }

    public function getLogsByAdminId(string $adminId)
    {
        $queryParam = [
            'TableName' => self::TABLE_NAME,
            'IndexName' => 'AdminIndex',
            'KeyConditionExpression' => '
            #adminId = :adminId',
            'ExpressionAttributeNames' => [
                '#adminId' => 'AdminId'
            ],
            'ExpressionAttributeValues' => [
                ':adminId' => ['S' => $adminId]
            ]
        ];

        $logs = $this->client->query($queryParam);

        return $this->resultstoObjectArrays($logs);
    }

    public function getLogsByTimeInterval(string $entityType, string $startDateString, string $endDateString)
    {
        $queryParam = [
            'TableName' => self::TABLE_NAME,
            'IndexName' => 'Entity-Date-Index',
            'KeyConditionExpression' => '
            #entity = :entityType AND
            #date BETWEEN :startDate AND :endDate',
            'ExpressionAttributeNames' => [
                '#entity' => 'Entity',
                '#date' => 'Date'
            ],
            'ExpressionAttributeValues' => [
                ':entityType' => ['S' => $entityType],
                ':startDate' => ['S' => $startDateString],
                ':endDate' => ['S' => $endDateString]
            ]
        ];

        $logs = $this->client->query($queryParam);

        return $this->resultstoObjectArrays($logs);
    }

    public function getLogsByentityItem($entityType, $entityId)
    {
        $queryParam = [
            'TableName' => self::TABLE_NAME,
            'IndexName' => 'Entity-Item-Index',
            'KeyConditionExpression' => '
            #entity = :entityType AND
            #entityId = :entityId',
            'ExpressionAttributeNames' => [
                '#entity' => 'Entity',
                '#entityId' => 'EntityId'
            ],
            'ExpressionAttributeValues' => [
                ':entityType' => ['S' => $entityType],
                ':entityId' => ['N' => $entityId]
            ]
        ];

        $logs = $this->client->query($queryParam);

        return $this->resultstoObjectArrays($logs);
    }


}
