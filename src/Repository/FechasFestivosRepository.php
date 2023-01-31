<?php

namespace App\Repository;

use App\Entity\FechasFestivos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FechasFestivos>
 *
 * @method FechasFestivos|null find($id, $lockMode = null, $lockVersion = null)
 * @method FechasFestivos|null findOneBy(array $criteria, array $orderBy = null)
 * @method FechasFestivos[]    findAll()
 * @method FechasFestivos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FechasFestivosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FechasFestivos::class);
    }

    public function save(FechasFestivos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FechasFestivos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FechasFestivos[] Returns an array of FechasFestivos objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FechasFestivos
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
