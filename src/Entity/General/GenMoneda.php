<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenMonedaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenMoneda
{
    public $infoLog = [
        "primaryKey" => "codigoMonedaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_moneda_pk", type="string", length=10, nullable=false)
     */
    private $codigoMonedaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvImportacion", mappedBy="monedaRel")
     */
    protected $invImportacionesMonedaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvOrden", mappedBy="monedaRel")
     */
    protected $invOrdenesMonedaRel;

    /**
     * @return mixed
     */
    public function getCodigoMonedaPk()
    {
        return $this->codigoMonedaPk;
    }

    /**
     * @param mixed $codigoMonedaPk
     */
    public function setCodigoMonedaPk( $codigoMonedaPk ): void
    {
        $this->codigoMonedaPk = $codigoMonedaPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre( $nombre ): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getInvImportacionesMonedaRel()
    {
        return $this->invImportacionesMonedaRel;
    }

    /**
     * @param mixed $invImportacionesMonedaRel
     */
    public function setInvImportacionesMonedaRel( $invImportacionesMonedaRel ): void
    {
        $this->invImportacionesMonedaRel = $invImportacionesMonedaRel;
    }

    /**
     * @return mixed
     */
    public function getInvOrdenesMonedaRel()
    {
        return $this->invOrdenesMonedaRel;
    }

    /**
     * @param mixed $invOrdenesMonedaRel
     */
    public function setInvOrdenesMonedaRel( $invOrdenesMonedaRel ): void
    {
        $this->invOrdenesMonedaRel = $invOrdenesMonedaRel;
    }



}

