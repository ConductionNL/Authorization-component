<?php

namespace App\Entity;

use App\Repository\AuthorizationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AuthorizationRepository::class)
 */
class Authorization
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
     * @var string The component this authorisation relates to
     * @Assert\NotNull()
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private string $component;

    /**
     * @var array The scopes this authorisation grants on the component
     * @Assert\NotNull()
     * @Groups({"read", "write"})
     * @ORM\Column(type="array")
     */
    private array $scopes = [];

    /**
     * @var Application The application this authorisation is related to
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="authorizations")
     * @ORM\JoinColumn(nullable=false)
     */
    private Application $application;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getComponent(): ?string
    {
        return $this->component;
    }

    public function setComponent(string $component): self
    {
        $this->component = $component;

        return $this;
    }

    public function getScopes(): ?array
    {
        return $this->scopes;
    }

    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): self
    {
        $this->application = $application;

        return $this;
    }
}
