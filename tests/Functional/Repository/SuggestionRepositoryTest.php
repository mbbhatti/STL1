<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Suggestion;
use App\Repository\SuggestionRepository;
use App\Service\CSV;
use App\Tests\ApiEnv\TestEntityManager;
use App\Tests\ApiEnv\TestUtils;
use App\Tests\ApiEnv\WebBaseTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class SuggestionRepositoryTest
 * @package App\Tests\Functional\Repository
 */
class SuggestionRepositoryTest extends TestEntityManager
{
    public function testConstrctor()
    {
        $kernel = self::bootKernel();
        $registry = $kernel->getContainer()->get('doctrine');
        $response = new SuggestionRepository($registry);

        $this->assertNotEmpty($response);
    }

    public function testSuggestionCsvData()
    {
        $csv = $this->entityManager->getRepository(Suggestion::class)->getSuggestionCsvData();
        $this->assertNotEmpty($csv);
        $this->assertGreaterThanOrEqual(0, $csv);
    }

    public function testInsertSuggestion()
    {
        $csv = new CSV();
        $file = new UploadedFile(
            'tests/assets/suggestions.csv',
            'suggestions.csv',
            'text/csv',
            66
        );
        $data = $csv->readCsv($file);

        // Random text with type
        $string =  substr(
            str_shuffle(
                str_repeat(
                    'abcdefghijklmnopqrstuvwxyz0123456789',
                    10
                )
            ),
            0,
            10
        );
        array_push($data, ['action', $string]);

        $this->entityManager->getRepository(Suggestion::class)->insertSuggestion($data, true);
        $suggestions = $this->entityManager->getRepository(Suggestion::class)->getSuggestions();
        $this->assertGreaterThanOrEqual(0, $suggestions);

        $testUtil = new TestUtils();
        $testUtil->rollbackSuggestion($this->entityManager);
    }

    public function testSuggestions()
    {
        $suggestions = $this->entityManager->getRepository(Suggestion::class)->getSuggestions();
        if(empty($suggestions)) {
            $this->assertEmpty($suggestions);
        } else {
            $apiResult = new WebBaseTestCase();
            $apiResult->assertHasAttributes(['artist', 'action', 'department', 'type'], $suggestions);
        }
    }

    public function testSuggestionEtag()
    {
        $etag = $this->entityManager->getRepository(Suggestion::class)->getSuggestionEtag();
        $this->assertArrayHasKey('etag', $etag[0]);
        $this->assertNotNull($etag[0]['etag']);
    }
}

