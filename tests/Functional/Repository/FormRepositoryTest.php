<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Form;
use App\Repository\FormRepository;
use App\Tests\ApiEnv\TestEntityManager;
use App\Tests\ApiEnv\WebBaseTestCase;
use App\Util\PosEntity;

/**
 * Class FormRepositoryTest
 * @package App\Tests\Functional\Repository
 */
class FormRepositoryTest extends TestEntityManager
{
    public function testConstrctor()
    {
        $kernel = self::bootKernel();
        $registry = $kernel->getContainer()->get('doctrine');
        $pos = $this->getMockBuilder(PosEntity::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response = new FormRepository($registry, $pos);

        $this->assertNotEmpty($response);
    }

    public function testFormCsvHedaer()
    {
        $csv = $this->entityManager->getRepository(Form::class)->getFormsCsvHeader();
        if (empty($csv)) {
            $this->assertEmpty($csv);
        } else {
            $this->assertEquals('id', $csv[0]);
            $this->assertEquals('customer_id', $csv[3]);
            $this->assertEquals('items_sold', $csv[12]);
            $this->assertEquals('email', $csv[2]);
        }
    }

    public function testFormCsvData()
    {
        $csv = $this->entityManager->getRepository(Form::class)->getFormsCsvData();
        $this->assertNotEmpty($csv);
    }

    public function testSearchForm()
    {
        $search = $this->entityManager->getRepository(Form::class)->searchForms('');
        $this->assertEquals([], $search);

        $search = $this->entityManager->getRepository(Form::class)->searchForms('Pausini');
        $this->assertNotEmpty($search);
    }

    public function testMediaUrls()
    {
        $urls = $this->entityManager->getRepository(Form::class)->getMediaUrls();
        $apiResult = new WebBaseTestCase();
        $apiResult->assertHasAttributes(['100', '102'], $urls);
    }

    public function testFormEtag()
    {
        $userId = 1;
        $etag = $this->entityManager->getRepository(Form::class)->getFormEtag($userId);
        $this->assertArrayHasKey('etag', $etag[0]);
        $this->assertNotNull($etag[0]['etag']);

        $userId = 123456;
        $etag = $this->entityManager->getRepository(Form::class)->getFormEtag($userId);
        $this->assertEmpty($etag);
    }

    public function testFormByUser()
    {
        $userId = 1;
        $form = $this->entityManager->getRepository(Form::class)->getUserFormIds($userId);
        $list = $form['ids'];
        if ($list === null) {
            $this->assertNull($form['ids']);
        } else {
            $this->assertGreaterThan(0, explode(',', $list));
        }
    }

    public function testFormById()
    {
        $formId = 108;
        $form = $this->entityManager->getRepository(Form::class)->getFormById($formId);
        $this->assertEquals($formId, $form['id']);

        $formId = 1007;
        $form = $this->entityManager->getRepository(Form::class)->getFormById($formId);
        $this->assertEquals($formId, $form['id']);

        $formId = 123456;
        $form = $this->entityManager->getRepository(Form::class)->getFormById($formId);
        $this->assertEmpty($form);
    }

    public function testFormExist()
    {
        $username = 'admin';
        $formId = 1;
        $form = $this->entityManager->getRepository(Form::class)->isFormExist($formId, $username);
        $this->assertEquals(0, $form);
    }
}

