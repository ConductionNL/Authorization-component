<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ApplicationRepository::class)
 */
class Application
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $clientIds = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasAllAuthorizations;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=Authorization::class, mappedBy="application", orphanRemoval=true)
     */
    private $authorizations;

    /**
     * @ORM\Column(type="text")
     */
    private $publicKey;

    public function __construct()
    {
        $this->authorizations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientIds(): ?array
    {
        return $this->clientIds;
    }

    public function setClientIds(array $clientIds): self
    {
        $this->clientIds = $clientIds;

        return $this;
    }

    public function getHasAllAuthorizations(): ?bool
    {
        return $this->hasAllAuthorizations;
    }

    public function setHasAllAuthorizations(bool $hasAllAuthorizations): self
    {
        $this->hasAllAuthorizations = $hasAllAuthorizations;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|Authorization[]
     */
    public function getAuthorizations(): Collection
    {
        return $this->authorizations;
    }

    public function addAuthorization(Authorization $authorization): self
    {
        if (!$this->authorizations->contains($authorization)) {
            $this->authorizations[] = $authorization;
            $authorization->setApplication($this);
        }

        return $this;
    }

    public function removeAuthorization(Authorization $authorization): self
    {
        if ($this->authorizations->contains($authorization)) {
            $this->authorizations->removeElement($authorization);
            // set the owning side to null (unless already changed)
            if ($authorization->getApplication() === $this) {
                $authorization->setApplication(null);
            }
        }

        return $this;
    }

    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    public function setPublicKey(string $publicKey): self
    {
        $this->publicKey = $publicKey;

        return $this;
    }
}
