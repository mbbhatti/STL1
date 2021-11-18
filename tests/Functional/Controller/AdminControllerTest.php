<?php

namespace App\Tests\Controller;

use App\Entity\Suggestion;
use App\Tests\ApiEnv\TestUtils;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testBatchDelete()
    {
        $id = 1290;
        $client = static::createClient();
        $client->request(
            'DELETE',
            '/adminapi/v1/batchDelete/suggestion?ids=['.$id.']',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $suggestion = $entityManager->getRepository(Suggestion::class)->find($id);
        $suggestion->setDeletedAt(NULL);
        $entityManager->persist($suggestion);
        $entityManager->flush();
    }

    public function testCsvForm()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/adminapi/v1/csv/form',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth()
        );

        $code = $client->getResponse()->getStatusCode();
        $this->assertEquals(200, $code);
    }

    public function testHomePage()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testSearchForm()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/adminapi/search?search=test',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCsvSuggestion()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/adminapi/v1/csv/sugestion',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth()
        );

        $code = $client->getResponse()->getStatusCode();
        $this->assertEquals(200, $code);
    }

    public function testGetImportSuggestion()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/adminapi/v1/sugestion',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNull($content);
    }

    public function testInvalidImportSuggestion()
    {
        $file = new UploadedFile(
            'tests/assets/suggestionsError.csv',
            'suggestions.csv',
            'text/csv',
            66
        );

        $client = static::createClient();
        $client->request(
            'POST',
            '/adminapi/v1/sugestion',
            TestUtils::getHeader(),
            ['file' => $file],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertNull($content);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testImportSuggestion()
    {
        $file = new UploadedFile(
            'tests/assets/suggestions.csv',
            'suggestions.csv',
            'text/csv',
            66
        );

        $client = static::createClient();
        $client->request(
            'POST',
            '/adminapi/v1/sugestion',
            TestUtils::getHeader(),
            ['file' => $file],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
        if (empty($data)) {
            $this->assertEmpty($data);
        } else {
            $this->assertNotEmpty($data);
        }
    }
}

