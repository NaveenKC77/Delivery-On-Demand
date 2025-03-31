<?php

namespace App\Services;

/**
 * Helper Functions For Dashboard Page
 * Get Dashboard Data, get ChartData
 */
class AdminDashboardService implements AdminDashboardServiceInterface
{
    public function __construct(private LoggerServiceInterface $loggerService, private LogFilterServiceInterface $logFilterService)
    {
    }

    /**
      * get Data for Admin Dashboard , mainly logs data
       * @return array of logs data
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
     * Modify Data for Chart to access it
     * @return array
     */
    public function getChartData(array $logs): array
    {
        return [
            count($this->logFilterService->filterLogsByTimeInterval('day', $logs)),
            count($this->logFilterService->filterLogsByTimeInterval('week', $logs)),
            count($this->logFilterService->filterLogsByTimeInterval('month', $logs)),
        ];
    }
}
