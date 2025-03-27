<?php

namespace App\Services;

interface AdminDashboardServiceInterface
{
    /**
     * Gets all dashboard data including products, categories, and users statistics
     *
     * @return array{
     *     products: array,
     *     categories: array,
     *     users: array
     * }
     */
    public function getDashboardData(): array;

    /**
     * Prepares log data for chart visualization by counting logs in different time intervals
     *
     * @param array $logs Array of log entries to process
     * @return array<int> Counts of logs for [day, week, month] intervals
     */
    public function getChartData(array $logs): array;
}
