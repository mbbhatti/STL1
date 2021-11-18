<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role extends Entity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="integer")
     */
    private $version;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="roles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return Role
     */
    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * @param int $version
     * @return Role
     */
    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getUsers(): ?array
    {
        return $this->users->toArray();
    }

    /**
     * @param Collection $users
     * @return Role
     */
    public function setUsers(Collection $users):self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @param User $user
     * @return Role
     */
    public function addUser(User $user):self
    {
        $this->users->add($user);

        return $this;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function removeUser(User $user): bool
    {
        return $this->users->removeElement($user);
    }
}

