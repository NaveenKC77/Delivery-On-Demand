<?php

namespace App\Controller;

use App\Enum\OrderStatus;
use App\Event\Events\OrderCancelledEvent;
use App\Event\Events\OrderCompletedEvent;
use App\Event\Events\OrderConfirmedEvent;
use App\Services\OrderService;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Order;
use App\Enum\ActiveSidenav;
use App\Services\AppContextInterface;
use App\Services\OrderServiceInterface;
use App\Services\PaginatorServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OrderController extends AbstractReadController
{
    public function __construct(
        private OrderServiceInterface $orderService,
        private EventDispatcherInterface $eventDispatcher,
        private AppContextInterface $appContext,
        private PaginatorServiceInterface $paginatorService
    ) {
        parent::__construct($paginatorService);
    }


    #[Route('admin/order/{page</d+>}', 'admin_order')]
    public function index(int $page = 1)
    {

        $qb = $this->orderService->getAllQueryBuilder();

        $pagination = $this->getPagination($qb, $page, 10);

        $this->setTemplateName('admin/order/index.html.twig');
        $this->setTemplateData(['pager' => $pagination,'orderStatuses' => OrderStatus::cases()]);


        return $this->read();


    }

    #[Route('/admin/order/update-status/{id}', name: 'admin_order_update_status', methods: ['POST'])]
    public function updateStatus(Order $order, Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $statusValue = $request->request->get('status');

        if (!in_array($statusValue, array_column(OrderStatus::cases(), 'value'))) {
            $this->addFlash('error', 'Invalid status selected.');
            return $this->redirectToRoute('admin_order');
        }

        $order->setStatus(OrderStatus::from($statusValue));
        $entityManager->flush();

        switch ($statusValue) {
            case OrderStatus::CONFIRMED->value:
                $event = new OrderConfirmedEvent($order);
                $this->eventDispatcher->dispatch($event, OrderConfirmedEvent::class);
                break;
            case OrderStatus::COMPLETED->value:
                $event = new OrderCompletedEvent($order);
                $this->eventDispatcher->dispatch($event, OrderCompletedEvent::class);
                break;

            case OrderStatus::CANCELLED->value:
                $event = new OrderCancelledEvent($order);
                $this->eventDispatcher->dispatch($event, OrderCancelledEvent::class);
                break;

            default:
                $this->addFlash('error', 'No action available');
                break;
        }
        $this->addFlash('success', 'Order status updated successfully.');

        return $this->redirectToRoute('admin_order');
    }

    #[Route('/admin/order/view/{id}', 'admin_order_view')]
    public function viewOrder(int $id)
    {
        $order = $this->orderService->getOneById($id);


        $this->setTemplateName('/admin/order/show.html.twig');
        $this->setTemplateData(['order' => $order,'orderStatuses' => OrderStatus::cases()]);


        return $this->read();
    }

    #[Route('/user/order/{page<\d+>}', name:'app_order')]
    public function userOrdersPage(int $page = 1)
    {

        $user = $this->appContext->getCurrentUser();
        $customerId = $this->appContext->getCurrentCustomer()?->getId();


        $qb = $this->orderService->getAllByCustomerIdQueryBuilder($customerId);

        $pagination = $this->getPagination($qb, $page, 5);

        $this->setTemplateName('/user/order/index.html.twig');

        $this->setTemplateData(['user' => $user,'pager' => $pagination,'active' => ActiveSidenav::ORDERS]);

        return $this->read();

    }

    #[Route('/user/order/view/{id}', 'app_order_view')]
    public function viewOrderUser(int $id)
    {
        $order = $this->orderService->getOneById($id);


        $this->setTemplateName('/user/order/show.html.twig');
        $this->setTemplateData(['order' => $order,'orderStatuses' => OrderStatus::cases()]);


        return $this->read();
    }
}
