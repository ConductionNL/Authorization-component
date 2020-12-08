<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     	normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     	denormalizationContext={"groups"={"write"}, "enable_max_depth"=true},
 *     itemOperations={
 * 	   "get",
 * 	   "put",
 * 	   "delete",
 *     "get_change_logs"={
 *              "path"="/applications/{id}/change_log",
 *              "method"="get",
 *              "swagger_context" = {
 *                  "summary"="Changelogs",
 *                  "description"="Gets al the change logs for this resource"
 *              }
 *          },
 *     "get_audit_trail"={
 *              "path"="/applications/{id}/audit_trail",
 *              "method"="get",
 *              "swagger_context" = {
 *                  "summary"="Audittrail",
 *                  "description"="Gets the audit trail for this resource"
 *              }
 *          },
 *
 *     "generate_jwt_token"={
 *          "path"="/applications/{id}/jwt_token",
 *          "method"="get",
 *          "swagger_context" = {
 *              "summary"="JWT token generator",
 *              "description"="Generates a JWT token for this application _FOR TESTING_PURPOSES ONLY_"
 *              }
 *          }
 *     },
 * )
 * @ORM\Entity(repositoryClass=ApplicationRepository::class)
 */
class Application
{
    /**
     * @var UuidInterface The UUID identifier of this resource
     *
     * @example e2984465-190a-4562-829e-a8cca81aa35d
     *
     * @Assert\Uuid
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private UuidInterface $id;

    /**
     * @var array The client id's for this application
     *
     * @Groups({"read", "write"})
     * @ORM\Column(type="array")
     *
     *
     */
    private array $clientIds = [];

    /**
     * @var bool Wether the application has all authorizations on the ecosystem or not
     *
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="boolean")
     */
    private bool $hasAllAuthorizations;

    /**
     * @var string The name of the application
     * @example VRC
     *
     * @Assert\NotNull()
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private string $label;

    /**
     * @var Collection The authorisations for the application
     * @Groups({"read", "write"})
     *
     * @ORM\OneToMany(targetEntity=Authorization::class, mappedBy="application", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private ?Collection $authorizations;

    /**
     * @var array The public key of the application
     * @Assert\NotNull()
     * @Groups({"read", "write"})
     * @ORM\Column(type="json")
     */
    private array $publicKey;

    public function __construct()
    {
        $this->authorizations = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
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

    public function getPublicKey(): ?array
    {
        return $this->publicKey;
    }

    public function setPublicKey(array $publicKey): self
    {
        $this->publicKey = $publicKey;

        return $this;
    }
}
