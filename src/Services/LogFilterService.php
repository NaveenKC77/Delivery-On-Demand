<?php

namespace App\Services;

class LogFilterService
{
    public function __construct()
    {
    }

    /**
     * Summary of filterLogsByTimeInterval
     * @param string $timeInterval
     * @param array $logs
     * @return array
     *               filter arrays using timeInterval String
     */
    public function filterLogsByTimeInterval(string $timeInterval, array $logs)
    {
        $currentDateTime = new \DateTime('now', new \DateTimeZone('UTC')); // Current time in UTC
        $endDateString = $currentDateTime->format('Y-m-d\TH:i:s\Z'); // ISO 8601 format

        switch ($timeInterval) {
            case 'day':
                $startDateString = (clone $currentDateTime)->modify('-24 hours')->format('Y-m-d\TH:i:s\Z');
                break;
            case 'week':
                $startDateString = (clone $currentDateTime)->modify('-7 days')->format('Y-m-d\TH:i:s\Z');
                break;
            case 'month':
                $startDateString = (clone $currentDateTime)->modify('-30 days')->format('Y-m-d\TH:i:s\Z');
                break;
            default:
                $startDateString = $endDateString; // No filtering
        }

        // Apply time interval filter
        $logs = array_filter($logs, function ($log) use ($startDateString, $endDateString) {
            $logDate = new \DateTime($log->Date);
            return $logDate >= new \DateTime($startDateString) && $logDate <= new \DateTime($endDateString);
        });

        return $logs;
    }
}
