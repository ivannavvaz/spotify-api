<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Suscripcion
 *
 * @ORM\Table(name="suscripcion", indexes={@ORM\Index(name="fk_suscripcion_premium1_idx", columns={"premium_usuario_id"}), @ORM\Index(name="fecha_fin_idx", columns={"fecha_fin"}), @ORM\Index(name="fecha_inicio_idx", columns={"fecha_inicio"})})
 * @ORM\Entity
 *
 * @Groups({"suscripcion"})
 */
class Suscripcion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Groups({"suscripcion"})
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=false)
     *
     * @Groups({"suscripcion"})
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=false)
     *
     * @Groups({"suscripcion"})
     */
    private $fechaFin;

    /**
     * @var Premium
     *
     * @ORM\ManyToOne(targetEntity="Premium")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="premium_usuario_id", referencedColumnName="usuario_id")
     * })
     *
     * @Groups({"suscripcion"})
     */
    private $premiumUsuario;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFechaInicio(): \DateTime
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTime $fechaInicio): void
    {
        $this->fechaInicio = $fechaInicio;
    }

    public function getFechaFin(): \DateTime
    {
        return $this->fechaFin;
    }

    public function setFechaFin(\DateTime $fechaFin): void
    {
        $this->fechaFin = $fechaFin;
    }

    public function getPremiumUsuario(): Premium
    {
        return $this->premiumUsuario;
    }

    public function setPremiumUsuario(Premium $premiumUsuario): void
    {
        $this->premiumUsuario = $premiumUsuario;
    }




}
