<?php

namespace App\Repository;

use App\Entity\MusicGender;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MusicGender|null find($id, $lockMode = null, $lockVersion = null)
 * @method MusicGender|null findOneBy(array $criteria, array $orderBy = null)
 * @method MusicGender[]    findAll()
 * @method MusicGender[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicGenderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MusicGender::class);
    }

    // /**
    //  * @return MusicGender[] Returns an array of MusicGender objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MusicGender
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
