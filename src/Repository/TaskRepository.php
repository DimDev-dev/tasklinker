<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }


    public function findTaskInDoing($id): array
    {
        return $this->createQueryBuilder('t')
        ->andWhere('t.projet = :id')
        ->andWhere('t.status = :status')
        ->setParameter('status', 'doing')
        ->setParameter('id', $id)
        ->orderBy('t.id', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function findTaskInTodo($id): array
    {
        return $this->createQueryBuilder('t')
        ->andWhere('t.projet = :id')
        ->andWhere('t.status = :status')
        ->setParameter('status', 'todo')
        ->setParameter('id', $id)
        ->orderBy('t.id', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function findTakInDone($id): array
    {
        return $this->createQueryBuilder('t')
        ->andWhere('t.projet = :id')
        ->andWhere('t.status = :status')
        ->setParameter('status', 'done')
        ->setParameter('id', $id)
        ->orderBy('t.id', 'ASC')
        ->getQuery()
        ->getResult();
    }

    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
