<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarDescuentoConceptoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarDescuentoConcepto
{
    public $infoLog = [
        "primaryKey" => "codigoDescuentoConceptoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, nullable=false, unique=true)
     */
    private $codigoDescuentoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\OneToMany(targetEntity="CarReciboDetalle", mappedBy="descuentoConceptoRel")
     */
    protected $recibosDetallesDescuentoConceptoRel;

    /**
     * @return mixed
     */
    public function getRecibosDetallesDescuentoConceptoRel()
    {
        return $this->recibosDetallesDescuentoConceptoRel;
    }

    /**
     * @param mixed $recibosDetallesDescuentoConceptoRel
     */
    public function setRecibosDetallesDescuentoConceptoRel( $recibosDetallesDescuentoConceptoRel ): void
    {
        $this->recibosDetallesDescuentoConceptoRel = $recibosDetallesDescuentoConceptoRel;
    }



    /**
     * @return mixed
     */
    public function getCodigoDescuentoConceptoPk()
    {
        return $this->codigoDescuentoConceptoPk;
    }

    /**
     * @param mixed $codigoDescuentoConceptoPk
     */
    public function setCodigoDescuentoConceptoPk( $codigoDescuentoConceptoPk ): void
    {
        $this->codigoDescuentoConceptoPk = $codigoDescuentoConceptoPk;
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
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * @param mixed $codigoCuentaFk
     */
    public function setCodigoCuentaFk( $codigoCuentaFk ): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
    }



}
