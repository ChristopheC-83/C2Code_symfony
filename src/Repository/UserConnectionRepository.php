<?php

namespace App\Repository;

use App\Entity\UserConnection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserConnection>
 */
class UserConnectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserConnection::class);
    }

    //    /**
    //     * @return UserConnection[] Returns an array of UserConnection objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserConnection
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function countDistinctIpsByUser(): array
    {
        return $this->createQueryBuilder('uc')
            ->select('u.id as userId, u.pseudo as pseudo, COUNT(DISTINCT uc.ipAddress) as ipCount')
            ->join('uc.user', 'u')
            ->groupBy('u.id, u.pseudo')
            ->orderBy('ipCount', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findConnectionsByUserOrderedByDate($id = null)
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->orderBy('u.connectionAt', 'DESC');

        if ($id) {
            $queryBuilder->where('u.user = :user')
                ->setParameter('user', $id);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
