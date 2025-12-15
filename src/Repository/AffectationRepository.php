<?php

namespace App\Repository;

use App\Entity\Affectation;
use App\Entity\Dto\Search;
use App\Service\AnneeService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Affectation>
 */
class AffectationRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private AnneeService $anneeService,
        private PaginatorInterface $paginator
    ) {
        parent::__construct($registry, Affectation::class);
    }

    /**
     * Get data filtered
     * @return PaginationInterface
     */
    public function search(Search $search): PaginationInterface
    {
        $limit = 20;

        $query = $this->createQueryBuilder('a')
            ->select('u', 'a')
            ->leftjoin('a.user', 'u')
            ->andWhere('a.annee = :annee')
            ->setParameter('annee', $this->anneeService->getCurrenteAnnee())
            ->orderBy('a.updated', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('u.name LIKE :query')
                ->setParameter('query', "%{$search->getQuery()}%");
        }

        if (!empty($search->getOrdre())) {
            $query = $query
                ->orderBy('a.updated', $search->getOrdre());
        } else {
            $query = $query
                ->orderBy('a.created', 'ASC');
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
//     * @return Affectation[] Returns an array of Affectation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Affectation
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
