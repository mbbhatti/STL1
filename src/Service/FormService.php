<?php

namespace App\Service;

use App\Entity\Form;
use App\Entity\Market;
use App\Entity\MarketGroup;
use App\Entity\User;
use App\Repository\FormRepository;
use App\Util\DBEntity;
use DateTime;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class FormService
 * @package App\Util
 */
class FormService
{
    /**
     * @var FormRepository
     */
    private $form;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * FormService constructor.
     * @param EntityManagerInterface $em
     * @param FormRepository $form
     */
    public function __construct(EntityManagerInterface $em, FormRepository $form)
    {
        $this->em = $em;
        $this->form = $form;
    }

    /**
     * @param int $userId
     * @return array|null
     * @throws NonUniqueResultException
     */
    public function getAllUserForms(int $userId): ?array
    {
        $rows = $this->form->getUserFormIds($userId);
        if ($rows['ids'] === null) {
            return [];
        }

        $ids = explode(',',$rows['ids']);
        $mapper = function ($row) {
            return $this->form->getFormById($row);
        };

        return array_map($mapper, $ids);
    }

    /**
     * @param int $userId
     * @param array $request
     * @param array $market
     * @return array|null
     * @throws Exception
     */
    public function insertForm(int $userId, array $request, array $market): ?array
    {
        // Get DBEntity object
        $dbEntity = new DBEntity();

        // Merge required form fields
        $params = array_merge(
            $request,
            $dbEntity->standardDbParams(),
            [
                'startAt' => $request['start_at'],
                'endAt' => $request['end_at'],
                'user' => $userId,
                'market' => $market['id'],
                'marketName' => $market['name'],
                'customerId' => $market['customerId'],
                'marketGroup' => $market['mgId'],
            ]);

        // Save a form and remove market id from params list
        DBEntity::unsetAttributes($params, ['market_id']);

        // Entity Manage for relationship
        $user = $this->em->getRepository(User::class)->findOneById($params['user']);
        $market = $this->em->getRepository(Market::class)->findOneById($params['market']);
        $group = $this->em->getRepository(MarketGroup::class)->findOneById($params['marketGroup']);

        $form = new Form();
        $form->setStartAt(new DateTime('@'.strtotime($params['startAt'])));
        $form->setEndAt(new DateTime('@'.strtotime($params['endAt'])));
        $form->setArtist($params['artist']);
        $form->setAction($params['action']);
        $form->setPlacement($params['placement']);
        $form->setType($params['type']);
        $form->setItemsAmount($params['items_amount']);
        $form->setItemsSold($params['items_sold']);
        $form->setVersion($params['version']);
        $form->setCreatedAt($params['createdAt']);
        $form->setUpdatedAt($params['updatedAt']);
        $form->setUser($user);
        $form->setMarket($market);
        $form->setMarketName($params['marketName']);
        $form->setCustomerId($params['customerId']);
        $form->setGroup($group);
        $deletedAt = ($params['deletedAt'] !== null) ? new DateTime('@'.strtotime($params['deletedAt'])) : null;
        $form->setDeletedAt($deletedAt);
        $this->em->persist($form);
        $this->em->flush();

        // Get inserted id
        $formId = $form->getId();

        // Get last form data
        return $this->form->getFormById($formId);
    }
}

