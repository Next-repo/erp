<?php

namespace App\Repository;

use App\Entity\Dto\Search;
use App\Entity\Projet;
use App\Service\AnneeService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Projet>
 */
class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator,
        private AnneeService $anneeService
    ) {
        parent::__construct($registry, Projet::class);
    }

    /**
     * Get data filtered
     * @return PaginationInterface
     */
    public function search(Search $search): PaginationInterface
    {
        $limit = 20;

        $query = $this->createQueryBuilder('p')
            ->andWhere('p.annee = :annee')
            ->setParameter('annee', $this->anneeService->getCurrenteAnnee())
            ->orderBy('p.updated', 'DESC');

        /*if ($this->userService->isRole('ROLE_ADMIN')) {
            $query = $this->createQueryBuilder('p')
                ->orderBy('p.updated', 'DESC');
        }*/

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('p.name LIKE :query')
                ->setParameter('query', "%{$search->getQuery()}%");
        }

        if (!empty($search->getTypePrestation())) {
            $query = $query
                ->andWhere('p.typePrestation = :typePrestation')
                ->setParameter('typePrestation', $search->getTypePrestation());
        }

        if (!empty($search->getClient())) {
            $query = $query
                ->andWhere('p.client = :client')
                ->setParameter('client', $search->getClient());
        }

        if (!empty($search->getAffectedTo())) {
            $query = $query
                ->andWhere('p.affectedTo = :affectedTo')
                ->setParameter('affectedTo', $search->getAffectedTo());
        }

        if (!empty($search->getTo())) {
            $query = $query
                ->andWhere('p.created <= :to')
                ->setParameter('to', $search->getTo());
        }

        if (!empty($search->getOrdre())) {
            $query = $query
                ->orderBy('p.updated', $search->getOrdre());
        } else {
            $query = $query
                ->orderBy('p.name', 'ASC');
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
    //     * @return Projet[] Returns an array of Projet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Projet
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
