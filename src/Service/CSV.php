<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class CSV
 * @package App\Service
 */
class CSV
{
    const DELIMITER = ';';
    const OUTPUT = 'php://output';
    private $outputHandle;

    /**
     * @param array $headers
     * @param array $data
     */
    public function createCsv(array $headers, array $data)
    {
        $this->outputHandle = fopen(static::OUTPUT, 'w');
        fputcsv($this->outputHandle, $headers, static::DELIMITER);
        foreach ($data as $row) {
            fputcsv($this->outputHandle, $row, static::DELIMITER);
        }
        fclose($this->outputHandle);
    }

    /**
     * @param UploadedFile $file
     * @return array|null
     */
    public function readCsv(UploadedFile $file): ?array
    {
        $content = [];
        $source = $file->openFile();
        $header = $source->fgetcsv(static::DELIMITER);
        if (count($header) !== 2 || $header[0] != 'type') {
            return null;
        }

        while (!$source->eof()) {
            $a = $source->fgetcsv(static::DELIMITER);
            if ($a[0] !== null) {
                $content[] = $a;
            }
        }

        return $content;
    }
}

