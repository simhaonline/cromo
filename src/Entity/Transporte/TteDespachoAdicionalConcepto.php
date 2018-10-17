<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoAdicionalConceptoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteDespachoAdicionalConcepto
{
    public $infoLog = [
        "primaryKey" => "codigoDespachoAdicionalConceptoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoDespachoAdicionalConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespachoAdicional", mappedBy="despachoAdicionalConceptoRel")
     */
    protected $despachosAdicionalesDespachoAdicionalConceptoRel;

    /**
     * @return mixed
     */
    public function getCodigoDespachoAdicionalConceptoPk()
    {
        return $this->codigoDespachoAdicionalConceptoPk;
    }

    /**
     * @param mixed $codigoDespachoAdicionalConceptoPk
     */
    public function setCodigoDespachoAdicionalConceptoPk($codigoDespachoAdicionalConceptoPk): void
    {
        $this->codigoDespachoAdicionalConceptoPk = $codigoDespachoAdicionalConceptoPk;
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
    public function getDespachosAdicionalesDespachoAdicionalConceptoRel()
    {
        return $this->despachosAdicionalesDespachoAdicionalConceptoRel;
    }

    /**
     * @param mixed $despachosAdicionalesDespachoAdicionalConceptoRel
     */
    public function setDespachosAdicionalesDespachoAdicionalConceptoRel($despachosAdicionalesDespachoAdicionalConceptoRel): void
    {
        $this->despachosAdicionalesDespachoAdicionalConceptoRel = $despachosAdicionalesDespachoAdicionalConceptoRel;
    }



}

