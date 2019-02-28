<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDespachoAuxiliarRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteDespachoAuxiliar
{
    public $infoLog = [
        "primaryKey" => "codigoDespachoAuxiliarPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_despacho_auxiliar_pk")
     */
    private $codigoDespachoAuxiliarPk;

    /**
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */
    private $codigoDespachoFk;

    /**
     * @ORM\Column(name="codigo_auxiliar_fk", type="integer", nullable=true)
     */
    private $codigoAuxiliarFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteDespacho", inversedBy="despachoAuxiliarRel")
     * @ORM\JoinColumn(name="codigo_despacho_fk", referencedColumnName="codigo_despacho_pk")
     */
    private $despachoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporte\TteAuxiliar", inversedBy="despachoAuxiliarRel")
     * @ORM\JoinColumn(name="codigo_auxiliar_fk", referencedColumnName="codigo_auxiliar_pk")
     */
    private $auxiliarRel;

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
    public function getCodigoDespachoAuxiliarPk()
    {
        return $this->codigoDespachoAuxiliarPk;
    }

    /**
     * @param mixed $codigoDespachoAuxiliarPk
     */
    public function setCodigoDespachoAuxiliarPk($codigoDespachoAuxiliarPk): void
    {
        $this->codigoDespachoAuxiliarPk = $codigoDespachoAuxiliarPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoFk()
    {
        return $this->codigoDespachoFk;
    }

    /**
     * @param mixed $codigoDespachoFk
     */
    public function setCodigoDespachoFk($codigoDespachoFk): void
    {
        $this->codigoDespachoFk = $codigoDespachoFk;
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
    public function getDespachoRel()
    {
        return $this->despachoRel;
    }

    /**
     * @param mixed $despachoRel
     */
    public function setDespachoRel($despachoRel): void
    {
        $this->despachoRel = $despachoRel;
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

