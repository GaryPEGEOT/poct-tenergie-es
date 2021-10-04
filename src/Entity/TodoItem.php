<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TodoItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TodoItemRepository::class)
 */
class TodoItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Assert\Length(max=80, min=3)
     */
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Length(min=3)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="boolean", name="is_done")
     */
    private bool $done = false;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeInterface $createdAt;

    public function __construct(string $title, ?string $description = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isDone(): bool
    {
        return $this->done;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
