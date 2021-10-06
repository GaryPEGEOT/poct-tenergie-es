<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AV2Invertersdatas.
 *
 * @ORM\Table(name="A_V2_invertersDatas")
 * @ORM\Entity
 */
class InvertersData
{
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="id_projet", type="integer", nullable=false)
     */
    private int $projectId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime_immutable", nullable=false)
     */
    private \DateTimeInterface $datetime;

    /**
     * @ORM\Column(name="G572", type="integer", nullable=true, options={"unsigned"=true})
     */
    private ?int $inverterId;

    /**
     * @ORM\Column(name="pac", type="integer", nullable=true)
     */
    private ?int $pac;

    /**
     * @ORM\Column(name="pac_consolidate", type="integer", nullable=true)
     */
    private ?int $pacConsolidate;

    public function __construct(int $projectId, \DateTimeInterface $datetime, ?int $inverterId = null, ?int $pac = null, ?int $pacConsolidate = null)
    {
        $this->projectId = $projectId;
        $this->datetime = $datetime;
        $this->inverterId = $inverterId;
        $this->pac = $pac;
        $this->pacConsolidate = $pacConsolidate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getIdentifier()
    {
        return "{$this->projectId}_{$this->datetime->format(\DATE_ISO8601)}";
    }

    public function getDatetime(): string
    {
        return $this->datetime->format(\DATE_ISO8601);
    }

    public function getInverterId(): ?int
    {
        return $this->inverterId;
    }

    public function getPac(): ?int
    {
        return $this->pac;
    }

    public function getPacConsolidate(): ?int
    {
        return $this->pacConsolidate;
    }
}
