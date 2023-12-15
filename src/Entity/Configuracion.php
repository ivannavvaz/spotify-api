<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Configuracion
 *
 * @ORM\Table(name="configuracion", uniqueConstraints={@ORM\UniqueConstraint(name="usuario_id_UNIQUE", columns={"usuario_id"})}, indexes={@ORM\Index(name="fk_configuracion_calidad1_idx", columns={"calidad_id"}), @ORM\Index(name="fk_configuracion_idioma1_idx", columns={"idioma_id"}), @ORM\Index(name="fk_configuracion_tipo_descarga1_idx", columns={"tipo_descarga_id"})})
 * @ORM\Entity
 */
class Configuracion
{
    /**
     * @var bool
     *
     * @ORM\Column(name="autoplay", type="boolean", nullable=false)
     */
    private $autoplay;

    /**
     * @var bool
     *
     * @ORM\Column(name="ajuste", type="boolean", nullable=false)
     */
    private $ajuste;

    /**
     * @var bool
     *
     * @ORM\Column(name="normalizacion", type="boolean", nullable=false)
     */
    private $normalizacion;

    /**
     * @var Usuario
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var Calidad
     *
     * @ORM\ManyToOne(targetEntity="Calidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="calidad_id", referencedColumnName="id")
     * })
     */
    private $calidad;

    /**
     * @var Idioma
     *
     * @ORM\ManyToOne(targetEntity="Idioma")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idioma_id", referencedColumnName="id")
     * })
     */
    private $idioma;

    /**
     * @var TipoDescarga
     *
     * @ORM\ManyToOne(targetEntity="TipoDescarga")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_descarga_id", referencedColumnName="id")
     * })
     */
    private $tipoDescarga;

    public function isAutoplay(): bool
    {
        return $this->autoplay;
    }

    public function setAutoplay(bool $autoplay): void
    {
        $this->autoplay = $autoplay;
    }

    public function isAjuste(): bool
    {
        return $this->ajuste;
    }

    public function setAjuste(bool $ajuste): void
    {
        $this->ajuste = $ajuste;
    }

    public function isNormalizacion(): bool
    {
        return $this->normalizacion;
    }

    public function setNormalizacion(bool $normalizacion): void
    {
        $this->normalizacion = $normalizacion;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getCalidad(): Calidad
    {
        return $this->calidad;
    }

    public function setCalidad(Calidad $calidad): void
    {
        $this->calidad = $calidad;
    }

    public function getIdioma(): Idioma
    {
        return $this->idioma;
    }

    public function setIdioma(Idioma $idioma): void
    {
        $this->idioma = $idioma;
    }

    public function getTipoDescarga(): TipoDescarga
    {
        return $this->tipoDescarga;
    }

    public function setTipoDescarga(TipoDescarga $tipoDescarga): void
    {
        $this->tipoDescarga = $tipoDescarga;
    }



}
