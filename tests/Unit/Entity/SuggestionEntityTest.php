<?php

namespace App\Unit\Entity\Tests;

use App\Entity\Suggestion;
use PHPUnit\Framework\TestCase;

/**
 * Class SuggestionEntityTest
 * @package App\Entity\Tests
 */
class SuggestionEntityTest extends TestCase
{
    public function testId()
    {
        $suggestion = new Suggestion();

        $suggestion->setId('101');
        $this->assertEquals('101', $suggestion->getId());
    }

    public function testVersion()
    {
        $suggestion = new Suggestion();

        $suggestion->setVersion('786');
        $this->assertEquals('786', $suggestion->getVersion());

        $suggestion->setVersion('1090');
        $this->assertEquals('1090', $suggestion->getVersion());

        $this->assertNotEquals('1040', $suggestion->getVersion());
    }

    public function testType()
    {
        $suggestion = new Suggestion();

        $suggestion->setType('action');
        $this->assertEquals('action', $suggestion->getType());

        $suggestion->setType('department');
        $this->assertEquals('department', $suggestion->getType());

        $suggestion->setType('type');
        $this->assertEquals('type', $suggestion->getType());

        $suggestion->setType('artist');
        $this->assertEquals('artist', $suggestion->getType());

        $this->assertNotEquals('wrong type', $suggestion->getType());
    }

    public function testText()
    {
        $suggestion = new Suggestion();

        $suggestion->setText('ABC');
        $this->assertEquals('ABC', $suggestion->getText());

        $this->assertNotEquals('XYZ', $suggestion->getText());
    }
}

