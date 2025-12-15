<?php

namespace App\Repository;

use App\Entity\Dto\Search;
use App\Entity\User;
use App\Service\UserService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator, private UserService $userService)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Get data filtered
     * @return PaginationInterface
     */
    public function search(Search $search): PaginationInterface
    {
        $limit = 20;

        $query = $this->createQueryBuilder('u')
            ->andWhere('u.id != :userID')
            ->setParameter('userID', $this->userService->getLogedInUser()->getId())
            ->orderBy('u.updated', 'DESC');

        /*if ($this->userService->isRole('ROLE_ADMIN')) {
            $query = $this->createQueryBuilder('p')
                ->orderBy('u.updated', 'DESC');
        }*/

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('u.name LIKE :query')
                ->orWhere('u.email LIKE :query')
                ->setParameter('query', "%{$search->getQuery()}%");
        }

        if (!empty($search->getOrdre())) {
            $query = $query
                ->orderBy('u.updated', $search->getOrdre());
        } else {
            $query = $query
                ->orderBy('u.name', 'ASC');
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

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
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

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
