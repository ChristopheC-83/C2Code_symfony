<?php

namespace App\Repository;

use App\Entity\Courses;
use App\Entity\Lessons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lessons>
 */
class LessonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lessons::class);
    }

    //    /**
    //     * @return Lessons[] Returns an array of Lessons objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Lessons
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function countLessonsByCourseId(int $courseId): int
    {
        return (int) $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->where('l.course = :courseId')
            ->andWhere('l.is_visible = true')
            ->setParameter('courseId', $courseId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findFirstLessonByCourse(Courses $course): ?Lessons
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.course = :course')
            ->andWhere('l.is_visible = true')
            ->setParameter('course', $course)
            ->orderBy('l.position', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getTotalDurationByCourse($courseId): array
    {
        return $this->createQueryBuilder('l')
            ->select('l.time')
            ->where('l.course = :courseId')
            ->setParameter('courseId', $courseId)
            ->getQuery()
            ->getResult();
    }
    public function countPremiumLessons(): int
{
    return $this->createQueryBuilder('l')
        ->select('COUNT(l.id)')
        ->where('l.is_premium = true')
        ->getQuery()
        ->getSingleScalarResult();
}
}
