<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteFacturaConceptoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteFacturaConcepto
{
    public $infoLog = [
        "primaryKey" => "codigoFacturaConceptoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoFacturaConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TteFactura", mappedBy="facturaConceptoRel")
     */
    protected $facturasFacturaConceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoFacturaConceptoPk()
    {
        return $this->codigoFacturaConceptoPk;
    }

    /**
     * @param mixed $codigoFacturaConceptoPk
     */
    public function setCodigoFacturaConceptoPk( $codigoFacturaConceptoPk ): void
    {
        $this->codigoFacturaConceptoPk = $codigoFacturaConceptoPk;
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
    public function getFacturasFacturaConceptoRel()
    {
        return $this->facturasFacturaConceptoRel;
    }

    /**
     * @param mixed $facturasFacturaConceptoRel
     */
    public function setFacturasFacturaConceptoRel( $facturasFacturaConceptoRel ): void
    {
        $this->facturasFacturaConceptoRel = $facturasFacturaConceptoRel;
    }





}

