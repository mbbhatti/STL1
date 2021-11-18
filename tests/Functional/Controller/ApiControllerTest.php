<?php

namespace App\Tests\Controller;

use App\Entity\Form;
use App\Entity\Market;
use App\Entity\Media;
use App\Entity\Suggestion;
use App\Entity\User;
use App\Tests\ApiEnv\TestUtils;
use App\Tests\ApiEnv\WebBaseTestCase;
use App\Util\EtagEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testUser()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/v1/profile',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('admin', $content['username']);
        $this->assertEquals('1', $content['id']);
    }

    public function testSuggestion()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/v1/suggestion',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());

        $apiResult = new WebBaseTestCase();
        $apiResult->assertHasAttributes(['artist', 'action', 'department', 'type'], $content);
    }

    public function testMatchSuggestion()
    {
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $etags = $entityManager->getRepository(Suggestion::class)->getSuggestionEtag();
        $etagEntity = new EtagEntity();
        $timestamp = $etagEntity->headersWithEtag($etags);

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/v1/suggestion',
            TestUtils::getHeader(),
            [],
            [
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'admin',
                'HTTP_IF_NONE_MATCH' => $timestamp
            ]
        );

        $response = $client->getResponse();
        $this->assertEquals(304, $response->getStatusCode());
    }

    public function testMarket()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/v1/market',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
        if (empty($content)) {
            $this->assertEmpty($content);
        } else {
            $apiResult = new WebBaseTestCase();
            $apiResult->assertHasAttributes(['id', 'customer_id', 'name'], $content[0]);
        }
    }

    public function testMatchMarket()
    {
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $market = $entityManager->getRepository(Market::class)->getMarketEtag();
        $user = $entityManager->getRepository(User::class)->getUserEtag('admin');
        $etags = array_merge($market, $user);
        $etagEntity = new EtagEntity();
        $timestamp = $etagEntity->headersWithEtag($etags);

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/v1/market',
            TestUtils::getHeader(),
            [],
            [
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'admin',
                'HTTP_IF_NONE_MATCH' => $timestamp
            ]
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(304, $response->getStatusCode());
        $this->assertNull($content);
    }

    public function testForm()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/v1/form',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
        if (empty($content)) {
            $this->assertEmpty($content);
        } else {
            $apiResult = new WebBaseTestCase();
            $apiResult->assertHasAttributes(['id', 'placement', 'type'], $content[0]);
        }
    }

    public function testMatchForm()
    {
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $userId = 1;
        $form = $entityManager->getRepository(Form::class)->getFormEtag($userId);
        $media = $entityManager->getRepository(Media::class)->getMediaEtag($userId);
        $etags = array_merge($form,$media);
        $etagEntity = new EtagEntity();
        $timestamp = $etagEntity->headersWithEtag($etags);

        $entityManager->close();
        $entityManager = null;

        $client = static::createClient();
        $client->request(
            'GET',
            '/api/v1/form',
            TestUtils::getHeader(),
            [],
            [
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'admin',
                'HTTP_IF_NONE_MATCH' => $timestamp
            ]
        );

        $response = $client->getResponse();
        $this->assertEquals(304, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }

    public function testUploadForm()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/v1/form',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth(),
            TestUtils::getPostFormBodyMock()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($content);
    }

    public function testRequiredUploadForm()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/v1/form',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth(),
            TestUtils::getPostErrorFormBodyMock()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Invalid parameters', $content['message']);
    }

    public function testInvaildUploadForm()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/v1/form',
            TestUtils::getHeader(),
            [],
            TestUtils::getAuth(),
            TestUtils::getPostInvalidIdFormBodyMock()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(120, $content['code']);
    }

    public function testInvalidIdUploadMedia()
    {
        $file = new UploadedFile(
            'tests/assets/photo.jpeg',
            'photo.jpeg',
            'image/jpeg'
        );

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/v1/form/1234567890/media',
            ['headers' => ['Content-Type' => 'image/jpeg'], ['Accept' => 'application/json']],
            ['file' => $file],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(10, $content['code']);
    }

    public function testNoFileUploadMedia()
    {
        $testUtil = new TestUtils();
        $formId = $testUtil->getMaxFormId(self::bootKernel());

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/v1/form/'.$formId.'/media',
            ['headers' => ['Content-Type' => 'image/jpeg'], ['Accept' => 'application/json']],
            [],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(20, $content['code']);
    }

    public function testInvalidFileUploadMedia()
    {
        $testUtil = new TestUtils();
        $formId = $testUtil->getMaxFormId(self::bootKernel());

        $file = new UploadedFile(
            'tests/assets/suggestions.csv',
            'suggestions.csv',
            'type/csv'
        );

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/v1/form/'.$formId.'/media',
            ['headers' => ['Content-Type' => 'image/jpeg'], ['Accept' => 'application/json']],
            ['file' => $file],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(21, $content['code']);
    }

    public function testUploadMedia()
    {
        $testUtil = new TestUtils();
        $formId = $testUtil->getMaxFormId(self::bootKernel());

        $file = new UploadedFile(
            'tests/assets/photo.jpeg',
            'photo.jpeg',
            'image/jpeg'
        );

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/v1/form/'.$formId.'/media',
            ['headers' => ['Content-Type' => 'image/jpeg'], ['Accept' => 'application/json']],
            ['file' => $file],
            TestUtils::getAuth()
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($content);
    }
}

