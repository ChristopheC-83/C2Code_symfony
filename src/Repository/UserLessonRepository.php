<?php

namespace App\Repository;

use App\Entity\Lessons;
use App\Entity\User;
use App\Entity\UserLesson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserLesson>
 */
class UserLessonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLesson::class);
    }

    public function hasPurchasedLesson(User $user, Lessons $lesson): bool
    {
        return (bool) $this->createQueryBuilder('ul')
            ->select('COUNT(ul.id)')
            ->where('ul.user = :user')
            ->andWhere('ul.lesson = :lesson')
            ->setParameter('user', $user)
            ->setParameter('lesson', $lesson)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countValidatedPremiumLessonsForUser(User $user): int
{
    return $this->createQueryBuilder('ul')
        ->select('COUNT(ul.id)')
        ->join('ul.lesson', 'l')
        ->where('ul.user = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getSingleScalarResult();
}
    
}
