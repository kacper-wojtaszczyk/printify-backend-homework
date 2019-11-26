<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\Order;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Country;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderRequest;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderRequestRepositoryInterface;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;

class OrderRequestRepository extends ServiceEntityRepository implements OrderRequestRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderRequest::class);
    }

    public function findByDateCountry(\DateTime $date, Country $country): ?ArrayCollection
    {
        $start = (clone $date)->sub(new \DateInterval("PT1S"));
        $stop = $date;
        return new ArrayCollection($this->createQueryBuilder('r')
            ->where('r.country = :country')
            ->andWhere('r.date BETWEEN :start AND :stop')
            ->setParameters([
                'country' => (string) $country,
                'start' => $start,
                'stop' => $stop
            ])
            ->getQuery()->execute(null));
    }

    public function save(OrderRequest $OrderRequest): void
    {
        $em = $this->getEntityManager();
        $em->persist($OrderRequest);
        $em->flush();
    }
}
