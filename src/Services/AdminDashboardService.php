<?php

namespace App\Services;

class AdminDashboardService
{
    private LoggerService $loggerService;
    private LogFilterService $logFilterService;

    public function __construct(LoggerService $loggerService, LogFilterService $logFilterService)
    {
        $this->loggerService = $loggerService;
        $this->logFilterService = $logFilterService;
    }

    /**
     * Fetch data for the admin dashboard
     */
    public function getDashboardData(): array
    {
        // Get logs for products and categories
        $productsAdded = $this->loggerService->getLogsByEntityAction('Product', 'Create');
        $categoriesAdded = $this->loggerService->getLogsByEntityAction('Category', 'Create');

        // Prepare chart data for products
        $productsAddedData = $this->getChartData($productsAdded);

        // Prepare chart data for categories
        $categoriesAddedData = $this->getChartData($categoriesAdded);

        // Example data for users (this could be fetched dynamically if needed)
        $usersData = [5, 20, 6];

        return [
            'products' => $productsAddedData,
            'categories' => $categoriesAddedData,
            'users' => $usersData
        ];
    }

    /**
     * Generate chart data for a given logs set
     */
    private function getChartData(array $logs): array
    {
        return [
            count($this->logFilterService->filterLogsByTimeInterval('day', $logs)),
            count($this->logFilterService->filterLogsByTimeInterval('week', $logs)),
            count($this->logFilterService->filterLogsByTimeInterval('month', $logs)),
        ];
    }
}
