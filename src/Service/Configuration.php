<?php

namespace App\Service;

use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;

/**
 * Class Configuration
 * @package App\Service
 */
class Configuration
{
    /**
     * @var Local
     */
    private $localAdapter;

    /**
     * @var AwsS3Adapter
     */
    private $awsAdapter;

    /**
     * @var string
     */
    private $awsStorage;

    /**
     * Configuration constructor.
     * @param AwsS3Adapter $awsAdapter
     * @param Local $localAdapter
     * @param string $awsStorage
     */
    public function __construct(AwsS3Adapter $awsAdapter, Local $localAdapter, string $awsStorage)
    {
        $this->localAdapter = $localAdapter;
        $this->awsAdapter = $awsAdapter;
        $this->awsStorage = $awsStorage;
    }

    /**
     * @return Filesystem
     */
    public function getFileSystem()
    {
        if ($this->awsStorage === 'true') {
            $adapter = $this->awsAdapter;
        } else {
            $adapter = $this->localAdapter;
        }

        return new Filesystem($adapter);
    }
}

