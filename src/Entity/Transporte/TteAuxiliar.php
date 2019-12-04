<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteAuxiliarRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteAuxiliar
{

    public $infoLog = [
        "primaryKey" => "codigoAuxiliarPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoAuxiliarPk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=150, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="estado_inactivo", type="boolean", nullable=true, options={"default":false})
     */
    private $estadoInactivo = false;


    /**
     * @ORM\OneToMany(targetEntity="TteDespachoRecogidaAuxiliar", mappedBy="auxiliarRel")
     */
    protected $despachosRecogidasAuxiliaresAuxiliarRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteDespachoAuxiliar", mappedBy="auxiliarRel")
     */
    protected $despachoAuxiliarRel;

    /**
     * @return mixed
     */
    public function getCodigoAuxiliarPk()
    {
        return $this->codigoAuxiliarPk;
    }

    /**
     * @param mixed $codigoAuxiliarPk
     */
    public function setCodigoAuxiliarPk($codigoAuxiliarPk): void
    {
        $this->codigoAuxiliarPk = $codigoAuxiliarPk;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param mixed $numeroIdentificacion
     */
    public function setNumeroIdentificacion($numeroIdentificacion): void
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
    }

    /**
     * @return mixed
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * @param mixed $nombreCorto
     */
    public function setNombreCorto($nombreCorto): void
    {
        $this->nombreCorto = $nombreCorto;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasAuxiliaresAuxiliarRel()
    {
        return $this->despachosRecogidasAuxiliaresAuxiliarRel;
    }

    /**
     * @param mixed $despachosRecogidasAuxiliaresAuxiliarRel
     */
    public function setDespachosRecogidasAuxiliaresAuxiliarRel($despachosRecogidasAuxiliaresAuxiliarRel): void
    {
        $this->despachosRecogidasAuxiliaresAuxiliarRel = $despachosRecogidasAuxiliaresAuxiliarRel;
    }

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
    public function getDespachoAuxiliarRel()
    {
        return $this->despachoAuxiliarRel;
    }

    /**
     * @param mixed $despachoAuxiliarRel
     */
    public function setDespachoAuxiliarRel($despachoAuxiliarRel): void
    {
        $this->despachoAuxiliarRel = $despachoAuxiliarRel;
    }

    /**
     * @return bool
     */
    public function getEstadoInactivo(): bool
    {
        return $this->estadoInactivo;
    }

    /**
     * @param bool $estadoInactivo
     */
    public function setEstadoInactivo(bool $estadoInactivo): void
    {
        $this->estadoInactivo = $estadoInactivo;
    }



}

