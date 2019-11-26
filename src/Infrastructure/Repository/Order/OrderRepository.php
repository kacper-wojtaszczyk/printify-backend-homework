<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\Order;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Order;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderRepositoryInterface;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findOneById(OrderId $orderId): ?Order
    {
        return $this->createQueryBuilder('o')
            ->where('o.id = :id')
            ->setParameter('id', (string) $orderId)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    public function findAll(): ?ArrayCollection
    {
        return new ArrayCollection($this->createQueryBuilder('o')
            ->getQuery()->execute());
    }

    public function findByProductType(ProductType $type): ?ArrayCollection
    {
        return new ArrayCollection($this->createQueryBuilder('o')
            ->leftJoin('o.orderItem', 'i')
            ->leftJoin('i.product', 'p')
            ->where('p.productType = :type')
            ->setParameter('type', (string) $type)
            ->getQuery()->execute());
    }

    public function save(Order $order): void
    {
        $em = $this->getEntityManager();
        try{
            $em->persist($order);
            $em->flush();
        } catch (\Exception $e) {
            die($e->getMessage());
        }

    }
}
