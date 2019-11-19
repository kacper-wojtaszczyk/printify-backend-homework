<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\Product;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Product;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductRepositoryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findOneById(ProductId $productId): ?Product
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', (string) $productId)
            ->getQuery()->getOneOrNullResult();
    }
}
