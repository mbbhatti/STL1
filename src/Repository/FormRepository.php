<?php

namespace App\Repository;

use App\Entity\Form;
use App\Util\PosEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method Form|null find($id, $lockMode = null, $lockVersion = null)
 * @method Form|null findOneBy(array $criteria, array $orderBy = null)
 * @method Form[]    findAll()
 * @method Form[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormRepository extends ServiceEntityRepository
{
    /**
     * @var PosEntity
     */
    private $posEntity;

    /**
     * FormRepository constructor.
     * @param ManagerRegistry $registry
     * @param PosEntity $posEntity
     */
    public function __construct(ManagerRegistry $registry, PosEntity $posEntity)
    {
        parent::__construct($registry, Form::class);
        $this->posEntity = $posEntity;
    }

    /**
     * @return array
     */
    public function getFormsCsvHeader(): array
    {
        return [
            'id',
            'username',
            'email',
            'customer_id',
            'market_name',
            'start_at',
            'end_at',
            'artist',
            'action',
            'placement',
            'type',
            'items_amount',
            'items_sold',
        ];
    }

    /**
     * @return array|null
     */
    public function getFormsCsvData(): ?array
    {
        return $this->createQueryBuilder('f')
            ->addSelect('user', 'market')
            ->innerJoin('f.user', 'user')
            ->innerJoin('f.market', 'market')
            ->select(
                'f.id',
                'user.username',
                'user.email',
                'market.customerId AS customer_id',
                'market.name AS market_name',
                'DATE_FORMAT(f.startAt, \'%Y-%m-%d\') AS start_at',
                'DATE_FORMAT(f.endAt, \'%Y-%m-%d\') AS end_at',
                'f.artist',
                'f.action',
                'f.placement',
                'f.type',
                'f.itemsAmount AS items_amount',
                'f.itemsSold AS items_sold'
            )
            ->where('f.deletedAt IS NULL')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $search
     * @return array|null
     */
    public function searchForms(string $search): ?array
    {
        if (empty($search)) {
            return [];
        }

        $rows = $this->createQueryBuilder('f')
            ->select('f.id')
            ->where('f.deletedAt IS NULL')
            ->andWhere(
                'CONCAT(LOWER(f.action), \' ; \', 
                LOWER(f.artist), \' ; \', 
                LOWER(f.placement), \' ; \', 
                LOWER(f.type), \' ; \', 
                LOWER(f.customerId), \' ; \', 
                LOWER(f.marketName)) 
                LIKE :searchterm'
            )
            ->setParameter('searchterm', '%' . trim(strtolower($search)) . '%')
            ->orderBy('f.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map($this->posEntity->firstColumn(), $rows);
    }

    /**
     * @return array|null
     */
    public function getMediaUrls(): ?array
    {
        $rows = $this->createQueryBuilder('f')
            ->addSelect('media')
            ->leftJoin(
                'f.medias',
                'media',
                Expr\Join::WITH,
                'media.type LIKE \'image/%\' AND media.deletedAt IS NULL')
            ->select('f.id', 'media.id AS url', 'media.thumbnail AS filename')
            ->where('f.deletedAt IS NULL')
            ->getQuery()
            ->getResult();

        $hierarchy = $this->posEntity->toHierarchy(
            $rows,
            1,
            'children',
            'id'
        );

        $results = [];
        foreach ($hierarchy as $form) {
            $urls = array_map(function (&$row) {
                $this->posEntity->urlMapper($row);
                return $row['url'];
            }, $form['children']);
            $results[$form['id']] = $urls;
        }

        return $results;
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getFormEtag(int $id): ?array
    {
        $etag = $this->createQueryBuilder('f')
            ->select('max(f.updatedAt) AS etag')
            ->where('f.deletedAt IS NULL')
            ->andWhere('f.updatedAt < UTC_TIMESTAMP()')
            ->andWhere('f.user = :user')
            ->setParameter('user', $id)
            ->getQuery()
            ->getResult();

        return ($etag[0]['etag'] !== null) ? $etag : [];
    }

    /**
     * @param int $userId
     * @return array|null
     * @throws NonUniqueResultException
     */
    public function getUserFormIds(int $userId): ?array
    {
        return $this->createQueryBuilder('f')
            ->select('group_concat(f.id) as ids')
            ->where('f.user = :id')
            ->setParameter('id', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $formId
     * @return array|null
     */
    public function getFormById(int $formId): ?array
    {
        $rows = $this->createQueryBuilder('f')
            ->addSelect('media', 'market')
            ->leftJoin(
                'f.medias',
                'media',
                Expr\Join::WITH,
                'media.deletedAt IS NULL'
            )
            ->innerJoin('f.market', 'market')
            ->select(
                'f.id',
                'f.deletedAt AS deleted',
                'f.createdAt',
                'f.startAt AS start_at',
                'f.endAt AS end_at',
                'f.artist',
                'f.action',
                'f.placement',
                'f.type',
                'f.itemsAmount AS items_amount',
                'f.itemsSold AS items_sold',
                'market.id AS market_id',
                'market.customerId AS customer_id',
                'market.name AS market_name',
                'media.id AS media_id',
                'media.type AS media_type',
                'media.createdAt AS created_at',
                'media.id AS url',
                'media.filename'
            )
            ->where('f.id = :id')
            ->setParameter('id', $formId)
            ->getQuery()
            ->getResult();

        if (empty($rows)) {
            return [];
        }

        $rows = array_map($this->posEntity->mysql2ApiMapper(), $rows);
        $forms = $this->posEntity->toHierarchy(
            $rows,
            PosEntity::NUMBER_FORM_ATTRIBUTES,
            'children',
            'id'
        );

        $form = $forms[0];
        $form['deleted'] = ($form['deleted'] !== null);
        $form['market'] = [
            'id' => $form['market_id'],
            'customer_id' => $form['customer_id'],
            'name' => $form['market_name']
        ];
        $form['video'] = null;
        $form['pictures'] = [];

        foreach ($form['children'] as $child) {
            $this->posEntity->urlMapper($child);
            $media = array_combine(['id', 'type', 'createdAt', 'url'], $child);
            $type = $child['media_type'];
            if (strpos($type, 'image/') === 0) {
                array_push($form['pictures'], $media);
            }
            if (strpos($type, 'video/') === 0) {
                $form['video'] = $media;
            }
        }

        $unsets = ['market_id', 'customer_id', 'market_name', 'children'];
        foreach ($unsets as $key) {
            unset($form[$key]);
        }

        return $form;
    }

    /**
     * @param int $formId
     * @param string $username
     * @return string|null
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function isFormExist(int $formId, string $username): ?string
    {
        return $this->createQueryBuilder('f')
            ->addSelect('user')
            ->innerJoin('f.user', 'user')
            ->select('count(f.id) AS count')
            ->where('f.deletedAt IS NULL')
            ->andWhere('f.id = :id')
            ->andWhere('user.username = :username')
            ->setParameter('id', $formId)
            ->setParameter('username', $username)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

