<?php

namespace App\Services;

class LoggerService extends DynamoDbService
{
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
