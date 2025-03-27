<?php

namespace App\Services;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Aws\Result;

class DynamoDbService implements DynamoDbServiceInterface
{
    public const TABLE_NAME = "Logs";

    protected Marshaler $marshaler;
    public function __construct(protected DynamoDbClient $client)
    {

        $this->marshaler = new Marshaler();

    }

    public function resultstoObjectArrays(Result $result): array
    {
        $items = [];
        foreach ($result['Items'] as $item) {
            $items[] = (object) $this->marshaler->unmarshalItem($item);
        }

        return $items;
    }

    public function itemToObject(Result $result): object
    {
        $item = (object) $this->marshaler->unmarshalItem($result['Item']);

        return $item;
    }

    public function query(array $queryParam): Result
    {
        $result = $this->client->query($queryParam);
        return $result;
    }

    public function getItem(array $key): object
    {
        $result = $this->client->getItem([
            'Key' => $key,
            'TableName' => self::TABLE_NAME,
        ]);

        $resultObject = $this->itemToObject($result);

        return $resultObject;
    }

    public function putItem(array $item): void
    {
        $this->client->putItem([
            'Item' => $item,
            'TableName' => self::TABLE_NAME,
        ]);
    }


    public function deleteItem(array $key): void
    {
        $this->client->deleteItem([
            'Key' => $key,
            'TableName' => self::TABLE_NAME,
        ]);
    }

    public function updateItem(array $args): void
    {
        $this->client->updateItem($args);
    }


}
