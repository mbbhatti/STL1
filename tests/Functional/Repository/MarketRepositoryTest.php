<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Market;
use  App\Tests\ApiEnv\TestEntityManager;
use App\Tests\ApiEnv\WebBaseTestCase;

/**
 * Class MediaRepositoryTest
 * @package App\Tests\Functional\Repository
 */
class MarketRepositoryTest extends TestEntityManager
{
    public function testMarketEtag()
    {
        $etag = $this->entityManager->getRepository(Market::class)->getMarketEtag();
        $this->assertArrayHasKey('etag', $etag[0]);
        $this->assertNotNull($etag[0]['etag']);
    }

    public function testMarkets()
    {
        $username = 'admin';
        $markets = $this->entityManager->getRepository(Market::class)->getMarkets($username);
        $this->assertEmpty($markets);

        $username = 'karmic';
        $markets = $this->entityManager->getRepository(Market::class)->getMarkets($username);
        $apiResult = new WebBaseTestCase();
        $apiResult->assertHasAttributes(['id', 'customer_id', 'name'], $markets[0]);
    }

    public function testMarketExist()
    {
        $marketId = 123456;
        $market = $this->entityManager->getRepository(Market::class)->isMarketExist($marketId);
        $this->assertNull($market);

        $marketId = 1;
        $market = $this->entityManager->getRepository(Market::class)->isMarketExist($marketId);
        $this->assertEquals($marketId, $market['id']);
    }
}

