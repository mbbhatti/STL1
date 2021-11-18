<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormRepository")
 */
class Form extends Entity
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
     * @ORM\Column(name="items_sold", type="integer")
     */
    private $itemsSold;

    /**
     * @ORM\Column(name="items_amount", type="integer")
     */
    private $itemsAmount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $placement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $action;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $artist;

    /**
     * @ORM\Column(name="start_at", type="datetime")
     */
    private $startAt;

    /**
     * @ORM\Column(name="end_at", type="datetime")
     */
    private $endAt;

    /**
     * @ORM\Column(name="market_name", type="string", length=255, nullable=true, options={"default" : ""})
     */
    private $marketName;

    /**
     * @ORM\Column(name="customer_id", type="string", length=255, nullable=true)
     */
    private $customerId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Media", mappedBy="form", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $medias;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forms", cascade={"persist"})
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Market", inversedBy="forms", cascade={"persist"})
     * @ORM\JoinColumn(name="market", referencedColumnName="id", nullable=false)
     */
    private $market;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MarketGroup", inversedBy="forms", cascade={"persist"})
     * @ORM\JoinColumn(name="market_group", referencedColumnName="id", nullable=false)
     */
    private $group;

    /**
     * Form constructor.
     */
    public function __construct()
    {
        $this->medias = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return Form
     */
    public function setVersion(?int $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getItemsSold(): ?int
    {
        return $this->itemsSold;
    }

    /**
     * @param int $itemsSold
     * @return Form
     */
    public function setItemsSold(int $itemsSold): self
    {
        $this->itemsSold = $itemsSold;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getItemsAmount(): ?int
    {
        return $this->itemsAmount;
    }

    /**
     * @param int $itemsAmount
     * @return Form
     */
    public function setItemsAmount(int $itemsAmount): self
    {
        $this->itemsAmount = $itemsAmount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Form
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlacement(): ?string
    {
        return $this->placement;
    }

    /**
     * @param string $placement
     * @return Form
     */
    public function setPlacement(string $placement): self
    {
        $this->placement = $placement;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Form
     */
    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getArtist(): ?string
    {
        return $this->artist;
    }

    /**
     * @param string $artist
     * @return Form
     */
    public function setArtist(string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStartAt(): ?DateTimeInterface
    {
        return $this->startAt;
    }

    /**
     * @param DateTimeInterface $startAt
     * @return Form
     */
    public function setStartAt(DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEndAt(): ?DateTimeInterface
    {
        return $this->endAt;
    }

    /**
     * @param DateTimeInterface $endAt
     * @return Form
     */
    public function setEndAt(DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMarketName(): ?string
    {
        return $this->marketName;
    }

    /**
     * @param string|null $marketName
     * @return Form
     */
    public function setMarketName(?string $marketName): self
    {
        $this->marketName = $marketName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    /**
     * @param string|null $customerId
     * @return Form
     */
    public function setCustomerId(?string $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @param Media $media
     * @return Form
     */
    public function addMedia(Media $media): self
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * @param Media $media
     * @return bool
     */
    public function removeMedia(Media $media): bool
    {
        return $this->medias->removeElement($media);
    }

    /**
     * @return Collection
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    /**
     * @param User $user
     * @return Form
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param Market $market
     * @return Form
     */
    public function setMarket(Market $market): self
    {
        $this->market = $market;

        return $this;
    }

    /**
     * @return Market
     */
    public function getMarket(): Market
    {
        return $this->market;
    }

    /**
     * @param MarketGroup $group
     * @return Form
     */
    public function setGroup(MarketGroup $group): self
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return MarketGroup
     */
    public function getGroup(): MarketGroup
    {
        return $this->group;
    }
}

