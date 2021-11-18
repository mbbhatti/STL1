<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Form;
use App\Tests\ApiEnv\TestEntityManager;
use App\Service\TwigExtension;

/**
 * Class TwigExtensionTest
 * @package App\Tests\Functional\Repository
 */
class TwigExtensionTest extends TestEntityManager
{
    public function testFormExist()
    {
        $form = $this->entityManager->getRepository(Form::class);
        $twig = new TwigExtension($form);
        $functionResponse = $twig->getFunctions();
        $this->assertEquals('mediaUrls', $functionResponse[0]->getName());

        $urls = $twig->findMediaUrls();
        $this->assertGreaterThanOrEqual(0, $urls);
    }
}

