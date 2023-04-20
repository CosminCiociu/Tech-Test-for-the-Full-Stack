<?php
 
namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;

class OrderController extends AbstractController
{

    #[Route('/orders', name: 'order.index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $page = $request->query->get('page') ?? "1";
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $ordersRepository = $entityManager->getRepository(Order::class);

        $ordersQuery = $ordersRepository->createQueryBuilder('o')
        ->setFirstResult($offset)
        ->setMaxResults($limit)
        ;

        $paginator = new Paginator($ordersQuery, $fetchJoinCollection = true);
        
        return $this->inertia->render('Order/Index', [
            'orders' => $paginator,
            'page' => (int)$page,
            'totalPages' => ceil(count($paginator) / $limit),
            'statusStates' => Order::getCancelOrder()
        ]);
    }
 
    /**
     * @Route("/api/cancel-order/{id}", name="order_cancel_order", methods={"PUT"})
     */
    public function edit(EntityManagerInterface $entityManager,int $id): Response
    {
        $order = $entityManager->getRepository(Order::class)->find($id);
 
        if (!$order && !$order->getStatus == "pending") {
            return $this->json('No order found for id / Status not pending' . $id, 404);
        }
 
        $order->setStatus('cancelled');
        $entityManager->flush();
         
        return $this->json('Success');
    }
}