<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

interface LogFilterServiceInterface
{
    /**
     * Filters logs based on a specified time interval
     *
     * @param string $timeInterval The time interval to filter by ('day', 'week', 'month')
     * @param array $logs The array of log entries to filter
     * @return array Filtered array of logs
     */
    public function filterLogsByTimeInterval(string $timeInterval, array $logs): array;

    /**
     * Resets all filter values in the session
     *
     * @param Request $request The Symfony request object containing the session
     */
    public function resetFilters(Request $request): void;
}
