<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class LogFilterService implements LogFilterServiceInterface
{
    public function filterLogsByTimeInterval(string $timeInterval, array $logs): array
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
        $logs = array_filter($logs, function ($log) use ($startDateString, $endDateString): bool {
            $logDate = new \DateTime($log->Date);
            return $logDate >= new \DateTime($startDateString) && $logDate <= new \DateTime($endDateString);
        });

        return $logs;
    }

    public function resetFilters(Request $request): void
    {
        $request->getSession()->set('action', 'All');
        $request->getSession()->set('adminId', 0);
        $request->getSession()->set('timeInterval', 'All');
        $request->getSession()->set('itemId', 0);
    }
}
