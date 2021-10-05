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
     * @ORM\Column(name="id_projet", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private int $idProjet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime_immutable", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
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

    public function getIdProjet(): int
    {
        return $this->idProjet;
    }

    /**
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
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
