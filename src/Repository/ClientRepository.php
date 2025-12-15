<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Dto\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * Get data filtered
     * @return PaginationInterface
     */
    public function search(Search $search): PaginationInterface
    {
        $limit = 20;

        $query = $this->createQueryBuilder('c')
            ->orderBy('c.updated', 'DESC');

        /*if ($this->userService->isRole('ROLE_ADMIN')) {
            $query = $this->createQueryBuilder('c')
                ->orderBy('c.updated', 'DESC');
        }*/

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('c.nom LIKE :query')
                ->setParameter('query', "%{$search->getQuery()}%");
        }

        if (!empty($search->getTypeClient())) {
            $query = $query
                ->andWhere('c.type = :type')
                ->setParameter('type', $search->getTypeClient());
        }

        if (!empty($search->getTo())) {
            $query = $query
                ->andWhere('c.created <= :to')
                ->setParameter('to', $search->getTo());
        }

        if (!empty($search->getOrdre())) {
            $query = $query
                ->orderBy('c.updated', $search->getOrdre());
        } else {
            $query = $query
                ->orderBy('c.nom', 'ASC');
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
    //     * @return Client[] Returns an array of Client objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
