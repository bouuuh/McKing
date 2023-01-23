<?php

namespace App\Repository;

use App\Entity\IllustrationHome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IllustrationHome>
 *
 * @method IllustrationHome|null find($id, $lockMode = null, $lockVersion = null)
 * @method IllustrationHome|null findOneBy(array $criteria, array $orderBy = null)
 * @method IllustrationHome[]    findAll()
 * @method IllustrationHome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IllustrationHomeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IllustrationHome::class);
    }

    public function save(IllustrationHome $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IllustrationHome $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return IllustrationHome[] Returns an array of IllustrationHome objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?IllustrationHome
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
