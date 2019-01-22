<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarIngresoConceptoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarIngresoConcepto
{
    public $infoLog = [
        "primaryKey" => "codigoIngresoConceptoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, nullable=false, unique=true)
     */
    private $codigoIngresoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\OneToMany(targetEntity="CarReciboDetalle", mappedBy="ingresoConceptoRel")
     */
    protected $recibosDetallesIngresoConceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoIngresoConceptoPk()
    {
        return $this->codigoIngresoConceptoPk;
    }

    /**
     * @param mixed $codigoIngresoConceptoPk
     */
    public function setCodigoIngresoConceptoPk( $codigoIngresoConceptoPk ): void
    {
        $this->codigoIngresoConceptoPk = $codigoIngresoConceptoPk;
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

    /**
     * @return mixed
     */
    public function getRecibosDetallesIngresoConceptoRel()
    {
        return $this->recibosDetallesIngresoConceptoRel;
    }

    /**
     * @param mixed $recibosDetallesIngresoConceptoRel
     */
    public function setRecibosDetallesIngresoConceptoRel( $recibosDetallesIngresoConceptoRel ): void
    {
        $this->recibosDetallesIngresoConceptoRel = $recibosDetallesIngresoConceptoRel;
    }




}
