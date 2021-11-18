<?php

namespace App\Tests\Unit\Service;

use App\Entity\Form;
use App\Entity\Market;
use App\Service\ApiValidator;
use App\Service\FormService;
use App\Tests\ApiEnv\TestEntityManager;
use App\Tests\ApiEnv\TestUtils;
use App\Tests\ApiEnv\WebBaseTestCase;

/**
 * Class CsvTest
 * @package App\Tests\Unit\Service
 */
class FormServiceTest extends TestEntityManager
{
    public function testAllUserFormData()
    {
        $entityManager = $this->entityManager;
        $formRespository = $entityManager->getRepository(Form::class);
        $formService = new FormService($entityManager, $formRespository);

        $userId = 10;
        $forms = $formService->getAllUserForms($userId);
        $this->assertNotEmpty($forms);

        $userId = 123456;
        $forms = $formService->getAllUserForms($userId);
        $this->assertEmpty($forms);
    }

    public function testInsertForm()
    {
        $valid = new ApiValidator();
        $postData = json_decode(TestUtils::getPostFormBodyMock(), true);
        $result = $valid->isFormValid($postData);

        $apiResult = new WebBaseTestCase();
        $apiResult->assertApiSuccess('valid form data', $result);

        $marketId = $postData['market_id'];
        $market = $this->entityManager->getRepository(Market::class)->isMarketExist($marketId);
        $this->assertNotEmpty($market);

        $formRespository = $this->entityManager->getRepository(Form::class);
        $formService = new FormService($this->entityManager, $formRespository);
        $response = $formService->insertForm(10, $postData, $market);

        $apiResult = new WebBaseTestCase();
        $apiResult->assertHasAttributes(['id', 'start_at', 'items_sold'], $response);
    }
}

