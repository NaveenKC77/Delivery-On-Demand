<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\OrderStatus;
use App\Services\OrderService;
use App\Services\PaginatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\OrderDetailsFormType;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\RedirectResponse;

class OrderController extends AbstractController
{

    use AppControllerTrait;

    public function __construct(
        private OrderService $orderService,
        private PaginatorService $paginatorService,
    ){

    }

    #Needs Updating
    public function getFormType(): string{
        return OrderDetailsFormType::class;
    }

    public function getService(){
        return $this->orderService;
    }
    #[Route('admin/order/{page</d+>}', 'admin_order')]
    public function index(int $page=1)
    {
        $qb = $this->orderService->getAllQueryBuilder();

        $pagination = $this->getPagination($qb, $page,10);

        $this->setTemplateName('admin/order/index.html.twig');
        $this->setTemplateData(['pager'=>$pagination,'orderStatuses'=>OrderStatus::cases()]);

        
        return $this->read();
       

    }

    #[Route('/admin/order/update-status/{id}', name: 'admin_order_update_status', methods: ['POST'])]
    public function updateStatus(Order $order,Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $statusValue = $request->request->get('status');
        
        if (!in_array($statusValue, array_column(OrderStatus::cases(), 'value'))) {
            $this->addFlash('error', 'Invalid status selected.');
            return $this->redirectToRoute('admin_order');
        }

        $order->setStatus(OrderStatus::from($statusValue));
        $entityManager->flush();

        $this->addFlash('success', 'Order status updated successfully.');

        return $this->redirectToRoute('admin_order');
    }

    #[Route('/admin/order/single/{id}','admin_view_order')]
    public function viewOrder(int $id){
        $order = $this->orderService->getOneById($id);


        $this->setTemplateName('/admin/order/single.html.twig');
        $this->setTemplateData(['order'=>$order,'orderStatuses'=>OrderStatus::cases()]);

  
        return $this->read();
    }

#[Route('/profile/order/{page<\d+>}',name:'app_order')]
    public function userOrdersPage(int $page=1){
        
        // get logged in customer Id 
        $user = $this->getUser();
        
        if($user instanceof User && $user->getCustomer()){
            $customerId = $user->getCustomer()->getId();
        }
        
        $qb= $this->orderService->getAllByCustomerIdQueryBuilder($customerId);

        $pagination = $this->getPagination($qb,$page,5 );

        $this->setTemplateName('/user/order/index.html.twig');

        $this->setTemplateData(['user'=>$user,'pager'=>$pagination]);

        return $this->read();
        
    }

    #[Route('/profile/order/single/{id}','app_view_order')]
    public function viewOrderUser(int $id){
        $order = $this->orderService->getOneById($id);


        $this->setTemplateName('/user/order/single.html.twig');
        $this->setTemplateData(['order'=>$order,'orderStatuses'=>OrderStatus::cases()]);

  
        return $this->read();
    }
}