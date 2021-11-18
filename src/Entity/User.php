<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends Entity
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $version;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="users", cascade={"persist", "remove"})
     * @ORM\JoinTable(
     *     name="user_role",
     *     joinColumns={
     *          @ORM\JoinColumn(name="user", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="role", referencedColumnName="id")
     *     }
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MarketGroup", inversedBy="users", cascade={"persist", "remove"})
     * @ORM\JoinTable(
     *     name="user_market",
     *     joinColumns={
     *          @ORM\JoinColumn(name="user", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="market_group", referencedColumnName="id")
     *     }
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Form", mappedBy="users", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $forms;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->forms = new ArrayCollection();
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     * @return User
     */
    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

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
     * @param int|null $version
     * @return User
     */
    public function setVersion(?int $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getRoles(): ?array
    {
        return $this->roles->toArray();
    }

    /**
     * @param Collection $roles
     * @return User
     */
    public function setRoles(Collection $roles):self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param Role $role
     * @return User
     */
    public function addRole(Role $role):self
    {
        $this->roles->add($role);

        return $this;
    }

    /**
     * @param Role $role
     * @return bool
     */
    public function removeRole(Role $role): bool
    {
        return $this->roles->removeElement($role);
    }

    /**
     * @return array|null
     */
    public function getGroups(): ?array
    {
        return $this->groups->toArray();
    }

    /**
     * @param Collection $groups
     * @return User
     */
    public function setGroups(Collection $groups):self
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param MarketGroup $group
     * @return User
     */
    public function addGroup(MarketGroup $group):self
    {
        $this->groups->add($group);

        return $this;
    }

    /**
     * @param MarketGroup $group
     * @return bool
     */
    public function removeGroup(MarketGroup $group): bool
    {
        return $this->groups->removeElement($group);
    }

    /**
     * @param Form $form
     * @return User
     */
    public function addForm(Form $form): self
    {
        $this->forms[] = $form;

        return $this;
    }

    /**
     * @param Form $form
     * @return bool
     */
    public function removeForm(Form $form): bool
    {
        return $this->forms->removeElement($form);
    }

    /**
     * @return Collection
     */
    public function getForms(): Collection
    {
        return $this->forms;
    }
}

