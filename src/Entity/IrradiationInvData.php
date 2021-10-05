<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AV2Irradiationinvdatas.
 *
 * @ORM\Table(name="A_V2_irradiationInvDatas")
 * @ORM\Entity
 */
class IrradiationInvData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="id_projet", type="integer", nullable=false)
     */
    private int $idProjet;

    /**
     * @var \DateTime
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="datetime", type="datetime_immutable", nullable=false)
     */
    private \DateTimeInterface $datetime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="G572", type="integer", nullable=true, options={"unsigned"=true})
     */
    private int $g572;

    /**
     * @ORM\Column(name="ghi", type="float", precision=10, scale=0, nullable=false)
     */
    private float $ghi;

    /**
     * @ORM\Column(name="gti", type="float", precision=10, scale=0, nullable=false)
     */
    private float $gti;

    /**
     * @ORM\Column(name="temp", type="float", precision=10, scale=0, nullable=false)
     */
    private float $temp;

    /**
     * @ORM\Column(name="pvout", type="float", precision=10, scale=0, nullable=false)
     */
    private float $pvout;

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

    public function getG572(): ?int
    {
        return $this->g572;
    }

    public function getGhi(): float
    {
        return $this->ghi;
    }

    public function getGti(): float
    {
        return $this->gti;
    }

    public function getTemp(): float
    {
        return $this->temp;
    }

    public function getPvout(): float
    {
        return $this->pvout;
    }
}
