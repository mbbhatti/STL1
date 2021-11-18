<?php

namespace App\Tests\Util;

use App\Util\EtagEntity;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

/**
 * Class EtagEntityTest
 * @package App\Tests\Util
 */
class EtagEntityTest extends TestCase
{
    public function testTodayEtag()
    {
        $etagEntity = new EtagEntity();
        $today = $etagEntity->todayEtag();

        $this->assertEquals(date('Ymd', time()), $today);
    }

    public function testHeadersWithEtag()
    {
        $row = [
            ['etag' => '2019-04-17 06:01:34']
        ];

        $etagEntity = new EtagEntity();
        $timestamp = $etagEntity->headersWithEtag($row);

        $this->assertEquals(1555480894, $timestamp['ETag']);
    }

    public function testNotModifiedSince()
    {
        $etagEntity = new EtagEntity();
        $timestamp = [ 'ETag' => 1555480894];
        $modified = $etagEntity->notModifiedSince(new Request(), $timestamp);
        $this->assertEquals(false, $modified);

        $timestamp = [];
        $modified = $etagEntity->notModifiedSince(new Request(), $timestamp);
        $this->assertEquals(false, $modified);
    }
}

