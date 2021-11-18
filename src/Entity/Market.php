<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarketRepository")
 */
class Market extends Entity
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
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $version;

    /**
     * @ORM\Column(name="customer_id", type="string", length=255)
     */
    private $customerId;

    /**
     * @ORM\Column(name="ecr_id", type="string", length=255)
     */
    private $ecrId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ceo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $director;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dispatcher;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(name="field_worker", type="string", length=255, nullable=true)
     */
    private $fieldWorker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MarketGroup", inversedBy="markets", cascade={"persist"})
     * @ORM\JoinColumn(name="market_group", referencedColumnName="id", nullable=false)
     */
    private $group;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Form", mappedBy="markets", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $forms;

    /**
     * Market constructor.
     */
    public function __construct()
    {
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
     * @return Market
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Market
     */
    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Market
     */
    public function setVersion(?int $version): self
    {
        $this->version = $version;

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
     * @param string $customerId
     * @return Market
     */
    public function setCustomerId(string $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEcrId(): ?string
    {
        return $this->ecrId;
    }

    /**
     * @param string $ecrId
     * @return Market
     */
    public function setEcrId(string $ecrId): self
    {
        $this->ecrId = $ecrId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Market
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return Market
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    /**
     * @param string $zipcode
     * @return Market
     */
    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCeo(): ?string
    {
        return $this->ceo;
    }

    /**
     * @param string $ceo
     * @return Market
     */
    public function setCeo(string $ceo): self
    {
        $this->ceo = $ceo;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDirector(): ?string
    {
        return $this->director;
    }

    /**
     * @param string $director
     * @return Market
     */
    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDispatcher(): ?string
    {
        return $this->dispatcher;
    }

    /**
     * @param string $dispatcher
     * @return Market
     */
    public function setDispatcher(string $dispatcher): self
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Market
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return Market
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFieldWorker(): ?string
    {
        return $this->fieldWorker;
    }

    /**
     * @param string|null $fieldWorker
     * @return Market
     */
    public function setFieldWorker(?string $fieldWorker): self
    {
        $this->fieldWorker = $fieldWorker;

        return $this;
    }

    /**
     * @param MarketGroup $group
     * @return Market
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

    /**
     * @param Form $form
     * @return Market
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

