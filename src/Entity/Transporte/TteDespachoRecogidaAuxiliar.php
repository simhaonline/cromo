<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoRecogidaAuxiliarRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteDespachoRecogidaAuxiliar
{
    public $infoLog = [
        "primaryKey" => "codigoDespachoRecogidaAuxiliarPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoDespachoRecogidaAuxiliarPk;

    /**
     * @ORM\Column(name="codigo_despacho_recogida_fk", type="integer", nullable=true)
     */
    private $codigoDespachoRecogidaFk;

    /**
     * @ORM\Column(name="codigo_auxiliar_fk", type="integer", nullable=true)
     */
    private $codigoAuxiliarFk;

    /**
     * @ORM\ManyToOne(targetEntity="TteDespachoRecogida", inversedBy="despachosRecogidasAuxiliaresDespachoRecogidaRel")
     * @ORM\JoinColumn(name="codigo_despacho_recogida_fk", referencedColumnName="codigo_despacho_recogida_pk")
     */
    private $despachoRecogidaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteAuxiliar", inversedBy="despachosRecogidasAuxiliaresAuxiliarRel")
     * @ORM\JoinColumn(name="codigo_auxiliar_fk", referencedColumnName="codigo_auxiliar_pk")
     */
    private $auxiliarRel;

    /**
     * @return mixed
     */
    public function getCodigoDespachoRecogidaAuxiliarPk()
    {
        return $this->codigoDespachoRecogidaAuxiliarPk;
    }

    /**
     * @param mixed $codigoDespachoRecogidaAuxiliarPk
     */
    public function setCodigoDespachoRecogidaAuxiliarPk($codigoDespachoRecogidaAuxiliarPk): void
    {
        $this->codigoDespachoRecogidaAuxiliarPk = $codigoDespachoRecogidaAuxiliarPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoRecogidaFk()
    {
        return $this->codigoDespachoRecogidaFk;
    }

    /**
     * @param mixed $codigoDespachoRecogidaFk
     */
    public function setCodigoDespachoRecogidaFk($codigoDespachoRecogidaFk): void
    {
        $this->codigoDespachoRecogidaFk = $codigoDespachoRecogidaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAuxiliarFk()
    {
        return $this->codigoAuxiliarFk;
    }

    /**
     * @param mixed $codigoAuxiliarFk
     */
    public function setCodigoAuxiliarFk($codigoAuxiliarFk): void
    {
        $this->codigoAuxiliarFk = $codigoAuxiliarFk;
    }

    /**
     * @return mixed
     */
    public function getDespachoRecogidaRel()
    {
        return $this->despachoRecogidaRel;
    }

    /**
     * @param mixed $despachoRecogidaRel
     */
    public function setDespachoRecogidaRel($despachoRecogidaRel): void
    {
        $this->despachoRecogidaRel = $despachoRecogidaRel;
    }

    /**
     * @return mixed
     */
    public function getAuxiliarRel()
    {
        return $this->auxiliarRel;
    }

    /**
     * @param mixed $auxiliarRel
     */
    public function setAuxiliarRel($auxiliarRel): void
    {
        $this->auxiliarRel = $auxiliarRel;
    }



}

