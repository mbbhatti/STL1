<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarketGroupRepository")
 */
class MarketGroup extends Entity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $sr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default" : ""})
     */
    private $display;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="groups", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Market", mappedBy="group", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $markets;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Form", mappedBy="group", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $forms;

    /**
     * MarketGroup constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->markets = new ArrayCollection();
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
     * @param int|null $id
     * @return MarketGroup
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

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
     * @return MarketGroup
     */
    public function setVersion(?int $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSr(): ?string
    {
        return $this->sr;
    }

    /**
     * @param string $sr
     * @return MarketGroup
     */
    public function setSr(string $sr): self
    {
        $this->sr = $sr;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDisplay(): ?string
    {
        return $this->display;
    }

    /**
     * @param string|null $display
     * @return MarketGroup
     */
    public function setDisplay(?string $display): self
    {
        $this->display = $display;

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
     * @return MarketGroup
     */
    public function setUsers(Collection $users):self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @param User $user
     * @return MarketGroup
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

    /**
     * @param Market $market
     * @return MarketGroup
     */
    public function addMarket(Market $market): self
    {
        $this->markets[] = $market;

        return $this;
    }

    /**
     * @param Market $market
     * @return bool
     */
    public function removeMarket(Market $market): bool
    {
        return $this->markets->removeElement($market);
    }

    /**
     * @return Collection
     */
    public function getMarkets(): Collection
    {
        return $this->markets;
    }

    /**
     * @param Form $form
     * @return MarketGroup
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

