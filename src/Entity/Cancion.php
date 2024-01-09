<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cancion
 *
 * @ORM\Table(name="cancion", indexes={@ORM\Index(name="titulo_idx", columns={"titulo"}), @ORM\Index(name="fk_cancion_album1_idx", columns={"album_id"})})
 * @ORM\Entity
 *
 * @Groups({"cancion"})
 */
class Cancion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Groups({"cancion"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=false)
     *
     * @Groups({"cancion"})
     */
    private $titulo;

    /**
     * @var int
     *
     * @ORM\Column(name="duracion", type="integer", nullable=false)
     *
     * @Groups({"cancion"})
     */
    private $duracion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ruta", type="string", length=255, nullable=true)
     *
     * @Groups({"cancion"})
     */
    private $ruta;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_reproducciones", type="integer", nullable=false)
     *
     * @Groups({"cancion"})
     */
    private $numeroReproducciones;

    /**
     * @var Album
     *
     * @ORM\ManyToOne(targetEntity="Album")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     * })
     *
     * @Groups({"cancion"})
     */
    private $album;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="cancion")
     *
     * @Groups({"cancion"})
     */
    private $usuario = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Premium", inversedBy="cancion")
     * @ORM\JoinTable(name="usuario_descarga_cancion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="cancion_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="premium_usuario_id", referencedColumnName="usuario_id")
     *   }
     * )
     *
     * @Groups({"cancion"})
     */
    private $premiumUsuario = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->premiumUsuario = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getDuracion(): int
    {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): void
    {
        $this->duracion = $duracion;
    }

    public function getRuta(): ?string
    {
        return $this->ruta;
    }

    public function setRuta(?string $ruta): void
    {
        $this->ruta = $ruta;
    }

    public function getNumeroReproducciones(): int
    {
        return $this->numeroReproducciones;
    }

    public function setNumeroReproducciones(int $numeroReproducciones): void
    {
        $this->numeroReproducciones = $numeroReproducciones;
    }

    public function getAlbum(): Album
    {
        return $this->album;
    }

    public function setAlbum(Album $album): void
    {
        $this->album = $album;
    }

    public function getUsuario(): \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
    {
        return $this->usuario;
    }

    public function setUsuario(\Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection $usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getPremiumUsuario(): \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
    {
        return $this->premiumUsuario;
    }

    public function setPremiumUsuario(\Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection $premiumUsuario): void
    {
        $this->premiumUsuario = $premiumUsuario;
    }


}
