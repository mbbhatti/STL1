<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $username
     * @return array|null
     * @throws NonUniqueResultException
     */
    public function getUserProfile(string $username): ?array
    {
        return $this->createQueryBuilder('u')
            ->select('u.id', 'u.email', 'u.username')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $username
     * @return array|null
     */
    public function getUserEtag(string $username): ?array
    {
        return $this->createQueryBuilder('u')
            ->select('max(u.updatedAt) AS etag')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $username
     * @return array|null
     */
    public function getUserMarketGroup(string $username): ?array
    {
        return $this->createQueryBuilder('u')
            ->select('group_concat(mg.id) as ids')
            ->leftJoin('u.groups', 'mg')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult();
    }
}

