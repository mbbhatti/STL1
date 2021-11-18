<?php

namespace App\Repository;

use App\Entity\Suggestion;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;

/**
 * @method Suggestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Suggestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Suggestion[]    findAll()
 * @method Suggestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuggestionRepository extends ServiceEntityRepository
{
    /**
     * SuggestionRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Suggestion::class);
    }

    /**
     * @return array|null
     */
    public function getSuggestionCsvData(): ?array
    {
        return $this->createQueryBuilder('s')
            ->select('s.type','s.text')
            ->andWhere('s.deletedAt IS NULL')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $suggestions
     * @param bool $reset
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function insertSuggestion(array $suggestions, bool $reset)
    {
        if ($reset === true) {
            $this->deleteSuggestions();
        }

        $manager = $this->getEntityManager();
        foreach ($suggestions as $data) {
            $type = trim($data[0]);
            $text = trim($data[1]);
            if ($type == 'artist') {
                continue;
            } else {
                $exist = $this->findOneBy(['text' => $text, 'type' => $type]);
                if ($exist !== null) {
                    continue;
                }
            }

            $suggestion = new Suggestion();
            $suggestion->setType($type);
            $suggestion->setText($text);
            $suggestion->setCreatedAt(new DateTime());
            $suggestion->setUpdatedAt(new DateTime());
            $suggestion->setVersion(0);
            $manager->persist($suggestion);
        }

        $manager->flush();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function deleteSuggestions()
    {
        return $this->createQueryBuilder('s')
            ->update()
            ->set('s.deletedAt', ':delete')
            ->setParameter('delete', new DateTime())
            ->where('s.deletedAt IS NULL')
            ->getQuery()
            ->execute();
    }

    /**
     * @return array|null
     */
    public function getSuggestions(): ?array
    {
        $data = $this->createQueryBuilder('s')
            ->select('s.text', 's.type')
            ->where('s.deletedAt IS NULL')
            ->andWhere('s.updatedAt < UTC_TIMESTAMP()')
            ->orderBy('s.text', 'ASC')
            ->getQuery()
            ->getResult();

        $response = [];
        if (!empty($data)) {
            $suggestions = [];
            foreach ($data as $key => $val) {
                $suggestions[$val['type']][]= $val['text'];
            }
            $response['artist'] = $suggestions['artist'];
            $response['action'] = $suggestions['action'];
            $response['department'] = $suggestions['department'];
            $response['type'] = $suggestions['type'];
        }

        return $response;
    }

    /**
     * @return array|null
     */
    public function getSuggestionEtag(): ?array
    {
        return $this->createQueryBuilder('s')
            ->select('max(s.updatedAt) AS etag')
            ->where('s.deletedAt IS NULL')
            ->andWhere('s.updatedAt < UTC_TIMESTAMP()')
            ->getQuery()
            ->getResult();
    }
}

