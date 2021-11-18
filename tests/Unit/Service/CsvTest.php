<?php

namespace App\Tests\Unit\Service;

use App\Service\CSV;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class CsvTest
 * @package App\Tests\Unit\Service
 */
class CsvTest extends TestCase
{
    public function testReadValidCsv()
    {
        $csv = new CSV();
        $file = new UploadedFile(
            'tests/assets/suggestions.csv',
            'suggestions.csv',
            'text/csv',
            66
        );
        $this->assertCount(3, $csv->readCsv($file));
    }

    public function testReadInvalidCsv()
    {
        $csv = new CSV();
        $file = new UploadedFile(
            'tests/assets/suggestionsError.csv',
            'suggestions.csv',
            'text/csv',
            66
        );
        $this->assertNull($csv->readCsv($file));
    }

    public function testReadCsv()
    {
        $csv = new CSV();
        $headers = ['type', 'text'];
        $data = [
            [
                "type" => "action",
                "text" => "bfkkff "
            ]
        ];

        $response = $csv->createCsv($headers, $data);
        $this->assertEquals($response, null);
    }
}

