<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Premium
 *
 * @ORM\Table(name="premium", indexes={@ORM\Index(name="fecha_renovacion_idx", columns={"fecha_renovacion"}), @ORM\Index(name="fk_premium_usuario1_idx", columns={"usuario_id"})})
 * @ORM\Entity
 *
 */
class Premium
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_renovacion", type="date", nullable=false)
     *
     * @Groups({"premium"})
     */
    private $fechaRenovacion;

    /**
     * @var Usuario
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     *
     * @Groups({"premium"})
     */
    private $usuario;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Cancion", mappedBy="premiumUsuario")
     *
     * @Groups({"premium"})
     */
    private $cancion = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cancion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getFechaRenovacion(): \DateTime
    {
        return $this->fechaRenovacion;
    }

    public function setFechaRenovacion(\DateTime $fechaRenovacion): void
    {
        $this->fechaRenovacion = $fechaRenovacion;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getCancion(): \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
    {
        return $this->cancion;
    }

    public function setCancion(\Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection $cancion): void
    {
        $this->cancion = $cancion;
    }


}
