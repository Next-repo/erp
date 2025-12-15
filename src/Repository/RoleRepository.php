<?php

namespace App\Repository;

use App\Entity\Dto\Search;
use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Role>
 */
class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Role::class);
    }

    /**
     * Get data filtered
     * @return PaginationInterface
     */
    public function search(Search $search): PaginationInterface
    {
        $limit = 20;

        $query = $this->createQueryBuilder('r')
            ->orderBy('r.updated', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('r.name LIKE :query')
                ->setParameter('query', "%{$search->getQuery()}%");
        }

        if (!empty($search->getOrdre())) {
            $query = $query
                ->orderBy('r.updated', $search->getOrdre());
        } else {
            $query = $query
                ->orderBy('r.name', 'ASC');
        }

        if (!empty($search->getLimit())) {
            $limit = $search->getLimit();
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            $limit
        );
    }

//    /**
//     * @return Role[] Returns an array of Role objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Role
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
