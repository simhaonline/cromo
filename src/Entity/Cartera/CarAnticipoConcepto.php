<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarAnticipoConceptoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarAnticipoConcepto
{
    public $infoLog = [
        "primaryKey" => "codigoAnticipoConceptoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\Column(name="codigo_anticipo_concepto_pk" , type="string", length=10)
     */
    private $codigoAnticipoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\OneToMany(targetEntity="CarAnticipoDetalle", mappedBy="anticipoConceptoRel")
     */
    protected $anticiposDetallesConceptosRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoAnticipoConceptoPk()
    {
        return $this->codigoAnticipoConceptoPk;
    }

    /**
     * @param mixed $codigoAnticipoConceptoPk
     */
    public function setCodigoAnticipoConceptoPk($codigoAnticipoConceptoPk): void
    {
        $this->codigoAnticipoConceptoPk = $codigoAnticipoConceptoPk;
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
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getAnticiposDetallesConceptosRel()
    {
        return $this->anticiposDetallesConceptosRel;
    }

    /**
     * @param mixed $anticiposDetallesConceptosRel
     */
    public function setAnticiposDetallesConceptosRel($anticiposDetallesConceptosRel): void
    {
        $this->anticiposDetallesConceptosRel = $anticiposDetallesConceptosRel;
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
    public function setCodigoCuentaFk($codigoCuentaFk): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
    }



}