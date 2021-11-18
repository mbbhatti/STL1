<?php

namespace App\Repository;

use App\Entity\Market;
use App\Util\PosEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Market|null find($id, $lockMode = null, $lockVersion = null)
 * @method Market|null findOneBy(array $criteria, array $orderBy = null)
 * @method Market[]    findAll()
 * @method Market[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketRepository extends ServiceEntityRepository
{
    /**
     * @var UserRepository
     */
    private $user;

    /**
     * @var PosEntity
     */
    private $posEntity;

    /**
     * MarketRepository constructor.
     * @param ManagerRegistry $registry
     * @param UserRepository $user
     * @param PosEntity $posEntity
     */
    public function __construct(ManagerRegistry $registry, UserRepository $user, PosEntity $posEntity)
    {
        parent::__construct($registry, Market::class);
        $this->user = $user;
        $this->posEntity = $posEntity;
    }

    /**
     * @return array|null
     */
    public function getMarketEtag(): ?array
    {
        return $this->createQueryBuilder('m')
            ->select('max(m.updatedAt) AS etag')
            ->where('m.deletedAt IS NULL')
            ->andWhere('m.updatedAt < UTC_TIMESTAMP()')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $username
     * @return array|null
     */
    public function getMarkets(string $username): ?array
    {
        $groups = $this->user->getUserMarketGroup($username);
        $ids = explode(',', $groups[0]['ids']);

        $rows = $this->createQueryBuilder('m')
            ->addSelect('market_group')
            ->innerJoin('m.group', 'market_group')
            ->select(
                'm.id',
                'm.customerId AS customer_id',
                'm.name',
                'market_group.sr',
                'm.ecrId As ecr_id',
                'm.zipcode',
                'm.city',
                'm.street',
                'm.ceo',
                'm.director',
                'm.dispatcher',
                'm.phone',
                'm.image',
                'm.id AS marketMedia'
            )
            ->where('m.deletedAt IS NULL')
            ->andWhere('m.group IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();

        $mapper = $this->posEntity->mysql2ApiMapper();
        $results = array_map($mapper, $rows);
        foreach ($results as &$result) {
            $this->posEntity->urlMapper($result);
        }

        return $results;
    }

    /**
     * @param int $marketId
     * @return array|null
     * @throws NonUniqueResultException
     */
    public function isMarketExist(int $marketId): ?array
    {
        return $this->createQueryBuilder('m')
            ->addSelect('market_group')
            ->innerJoin('m.group', 'market_group')
            ->select(
                'm.id',
                'm.name',
                'm.customerId AS customerId',
                'market_group.id AS mgId'
            )
            ->where('m.deletedAt IS NULL')
            ->andWhere('m.id = :id')
            ->setParameter('id', $marketId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

