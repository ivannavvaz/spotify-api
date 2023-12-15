<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TarjetaCredito
 *
 * @ORM\Table(name="tarjeta_credito", uniqueConstraints={@ORM\UniqueConstraint(name="numero_tarjeta_UNIQUE", columns={"numero_tarjeta"})}, indexes={@ORM\Index(name="fk_tarjeta_credito_forma_pago1_idx", columns={"forma_pago_id"})})
 * @ORM\Entity
 *
 * @Groups({"tarjeta_credito"})
 */
class TarjetaCredito
{
    /**
     * @var string
     *
     * @ORM\Column(name="numero_tarjeta", type="string", length=20, nullable=false)
     *
     * @Groups({"tarjeta_credito"})
     */
    private $numeroTarjeta;

    /**
     * @var bool
     *
     * @ORM\Column(name="mes_caducidad", type="boolean", nullable=false)
     *
     * @Groups({"tarjeta_credito"})
     */
    private $mesCaducidad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="anyo_caducidad", type="date", nullable=false)
     *
     * @Groups({"tarjeta_credito"})
     */
    private $anyoCaducidad;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_seguridad", type="smallint", nullable=false, options={"unsigned"=true})
     *
     * @Groups({"tarjeta_credito"})
     */
    private $codigoSeguridad;

    /**
     * @var FormaPago
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="FormaPago")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="forma_pago_id", referencedColumnName="id")
     * })
     *
     * @Groups({"tarjeta_credito"})
     */
    private $formaPago;

    public function getNumeroTarjeta(): string
    {
        return $this->numeroTarjeta;
    }

    public function setNumeroTarjeta(string $numeroTarjeta): void
    {
        $this->numeroTarjeta = $numeroTarjeta;
    }

    public function isMesCaducidad(): bool
    {
        return $this->mesCaducidad;
    }

    public function setMesCaducidad(bool $mesCaducidad): void
    {
        $this->mesCaducidad = $mesCaducidad;
    }

    public function getAnyoCaducidad(): \DateTime
    {
        return $this->anyoCaducidad;
    }

    public function setAnyoCaducidad(\DateTime $anyoCaducidad): void
    {
        $this->anyoCaducidad = $anyoCaducidad;
    }

    public function getCodigoSeguridad(): int
    {
        return $this->codigoSeguridad;
    }

    public function setCodigoSeguridad(int $codigoSeguridad): void
    {
        $this->codigoSeguridad = $codigoSeguridad;
    }

    public function getFormaPago(): FormaPago
    {
        return $this->formaPago;
    }

    public function setFormaPago(FormaPago $formaPago): void
    {
        $this->formaPago = $formaPago;
    }



}
