<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\Builder\Identity;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function add(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findDetail($id):array
    {
        $db = $this->createQueryBuilder('o')
            ->select('o.id, d.quantity, p.Name, p.Picture, p.Price, p.id as idproduct')
            ->leftJoin('App\Entity\OrderDetail','d', 'WITH', 'o.id = d.orderRef')
            ->leftJoin('App\Entity\Product','p', 'WITH', 'd.product = p.id')
            ->where('o.id = :id')
            ->setParameter('id', $id);

        $query = $db->getQuery();
        return $query->execute();
    }

    public function findCustomer($id):array{
        $db = $this->createQueryBuilder('o')
            ->select('o.id,u.name,u.location,u.email,u.phone,u.last_name')
            ->leftJoin('App\Entity\User','u', 'WITH', 'u.id = o.user')
            ->where('o.id = :id')
            ->setParameter('id', $id);
        $query = $db->getQuery();
        return $query->execute();
    }

//    /**
//     * @return Order[] Returns an array of Order objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Order
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
