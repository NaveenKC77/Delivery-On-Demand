<?php

namespace App\Services;

/**
 * Get Logs and Add Logs from and to DynamoDb
 */
interface LoggerServiceInterface extends DynamoDbServiceInterface
{
    /**
     * Return Logs For Certain Entity Type
     * @param string $entityTyoe
     * @return array
     */
    public function getLogsByEntityType(string $entityTyoe): array;

    /**
     * Return Logs By ENtity Type and ACtion Performed
     * @param string $entityTyoe
     * @param string $action
     * @return array
     */
    public function getLogsByEntityAction(string $entityTyoe, string $action): array;

    /**
     * Get Logs For a specific Admin
     * @param string $adminId
     * @return array
     */
    public function getLogsByAdminId(string $adminId): array;

    /**
     * Get Logs For A SSpecific Item
     * @param string $entityTyoe
     * @param string $itemId
     * @return array
     */
    public function getLogsByEntityItem(string $entityTyoe, string $entityId): array;


    /**
     * Get Logs For Certain ENtity Type for given time
     * @param string $entityType
     * @param string $startDateString
     * @param string $endDateString
     * @return array
     */
    public function getLogsByTimeInterval(string $entityType, string $startDateString, string $endDateString): array;
}
