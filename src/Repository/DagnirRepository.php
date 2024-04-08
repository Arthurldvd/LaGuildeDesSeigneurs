<?php

namespace App\Repository;

use App\Entity\Dagnir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dagnir>
 *
 * @method Dagnir|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dagnir|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dagnir[]    findAll()
 * @method Dagnir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DagnirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dagnir::class);
    }

//    /**
//     * @return Dagnir[] Returns an array of Dagnir objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Dagnir
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
