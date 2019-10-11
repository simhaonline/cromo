<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenResolucionFacturaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenResolucionFactura
{
    public $infoLog = [
        "primaryKey" => "codigoResolucionFacturaPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_resolucion_factura_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoResolucionFacturaPk;

    /**
     * @ORM\Column(name="numero", type="float", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="prefijo", type="string",length=5, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\Column(name="numero_desde", type="float", nullable=true)
     */
    private $numeroDesde;

    /**
     * @ORM\Column(name="numero_hasta", type="float", nullable=true)
     */
    private $numeroHasta;

    /**
     * @return mixed
     */
    public function getCodigoResolucionFacturaPk()
    {
        return $this->codigoResolucionFacturaPk;
    }

    /**
     * @param mixed $codigoResolucionFacturaPk
     */
    public function setCodigoResolucionFacturaPk($codigoResolucionFacturaPk): void
    {
        $this->codigoResolucionFacturaPk = $codigoResolucionFacturaPk;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * @param mixed $fechaDesde
     */
    public function setFechaDesde($fechaDesde): void
    {
        $this->fechaDesde = $fechaDesde;
    }

    /**
     * @return mixed
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * @param mixed $fechaHasta
     */
    public function setFechaHasta($fechaHasta): void
    {
        $this->fechaHasta = $fechaHasta;
    }

    /**
     * @return mixed
     */
    public function getPrefijo()
    {
        return $this->prefijo;
    }

    /**
     * @param mixed $prefijo
     */
    public function setPrefijo($prefijo): void
    {
        $this->prefijo = $prefijo;
    }

    /**
     * @return mixed
     */
    public function getNumeroDesde()
    {
        return $this->numeroDesde;
    }

    /**
     * @param mixed $numeroDesde
     */
    public function setNumeroDesde($numeroDesde): void
    {
        $this->numeroDesde = $numeroDesde;
    }

    /**
     * @return mixed
     */
    public function getNumeroHasta()
    {
        return $this->numeroHasta;
    }

    /**
     * @param mixed $numeroHasta
     */
    public function setNumeroHasta($numeroHasta): void
    {
        $this->numeroHasta = $numeroHasta;
    }



}

