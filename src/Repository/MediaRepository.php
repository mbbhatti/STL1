<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    /**
     * @var FormRepository
     */
    private $form;

    /**
     * MediaRepository constructor.
     * @param ManagerRegistry $registry
     * @param FormRepository $form
     */
    public function __construct(ManagerRegistry $registry, FormRepository $form)
    {
        parent::__construct($registry, Media::class);
        $this->form = $form;
    }

    /**
     * @param int $id
     * @return array|mixed
     * @throws NonUniqueResultException
     */
    public function getMediaEtag(int $id)
    {
        $formIds = $this->form->getUserFormIds($id);
        if ($formIds['ids'] === null) {
            return [];
        }
        $ids = explode(',', $formIds['ids']);

        return $this->createQueryBuilder('m')
            ->select('max(m.updatedAt) AS etag')
            ->where('m.deletedAt IS NULL')
            ->andWhere('m.updatedAt < UTC_TIMESTAMP()')
            ->andWhere('m.form IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }
}

