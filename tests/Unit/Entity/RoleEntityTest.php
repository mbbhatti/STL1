<?php

namespace App\Unit\Entity\Tests;

use App\Entity\User;
use App\Entity\Role;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class RoleEntityTest
 * @package App\Entity\Tests
 */
class RoleEntityTest extends TestCase
{
    public function testId()
    {
        $role = new Role();
        $this->assertEquals(null, $role->getId());
    }

    public function testRole()
    {
        $role = new Role();

        $role->setRole('ADMIN');
        $this->assertEquals('ADMIN', $role->getRole());

        $role->setRole('USER');
        $this->assertEquals('USER', $role->getRole());

        $this->assertNotEquals('SUPERVISIOR', $role->getRole());
    }

    public function testVersion()
    {
        $role = new Role();

        $role->setVersion('100');
        $this->assertEquals('100', $role->getVersion());

        $role->setVersion('1111');
        $this->assertEquals('1111', $role->getVersion());

        $this->assertNotEquals('202', $role->getVersion());
    }

    public function testUsers()
    {
        $role = new Role();

        $users = ['ABC','XYZ'];
        $role->setUsers(new ArrayCollection($users));
        $this->assertEquals($users, $role->getUsers());

        $this->assertNotEquals(['TEST'], $role->getUsers());

        $user = new User(1);
        $role->addUser($user);
        $this->assertEquals(true, $role->removeUser($user));
    }
}

