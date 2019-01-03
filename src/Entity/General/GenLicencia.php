<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenLicenciaRepository")
 */
class GenLicencia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", name="codigo_licencia_pk", unique=true, length=100)
     */
    private $codigoLicenciaPk;

    /**
     * @ORM\Column(name="fecha_activacion", type="datetime")
     */
    private $fechaActivacion;

    /**
     * @ORM\Column(name="fecha_valida_hasta", type="datetime")
     */
    private $fechaValidaHasta;

    /**
     * @ORM\Column(name="numero_usuarios", type="integer", nullable=false)
     */
    private $numeroUsuarios;

    /**
     * @ORM\Column(name="cartera",type="boolean", options={"default":false}, nullable=true)
     */
    private $cartera=false;

    /**
     * @ORM\Column(name="compra",type="boolean", options={"default":false}, nullable=true)
     */
    private $compra=false;

    /**
     * @ORM\Column(name="documental",type="boolean", options={"default":false}, nullable=true)
     */
    private $documental=false;

    /**
     * @ORM\Column(name="financiero",type="boolean", options={"default":false}, nullable=true)
     */
    private $financiero=false;

    /**
     * @ORM\Column(name="general",type="boolean", options={"default":false}, nullable=true)
     */
    private $general=false;

    /**
     * @ORM\Column(name="inventario",type="boolean", options={"default":false}, nullable=true)
     */
    private $inventario=false;

    /**
     * @ORM\Column(name="recurso_humano",type="boolean", options={"default":false}, nullable=true)
     */
    private $recursoHumano=false;

    /**
     * @ORM\Column(name="seguridad",type="boolean", options={"default":false}, nullable=true)
     */
    private $seguridad=false;

    /**
     * @ORM\Column(name="transporte",type="boolean", options={"default":false}, nullable=true)
     */
    private $transporte=false;

    /**
     * @ORM\Column(name="turno",type="boolean", options={"default":false}, nullable=true)
     */
    private $turno=false;

    /**
     * @ORM\Column(name="crm",type="boolean", options={"default":false}, nullable=true)
     */
    private $crm=false;

    /**
     * @return mixed
     */
    public function getCodigoLicenciaPk()
    {
        return $this->codigoLicenciaPk;
    }

    /**
     * @param mixed $codigoLicenciaPk
     */
    public function setCodigoLicenciaPk($codigoLicenciaPk)
    {
        $this->codigoLicenciaPk = $codigoLicenciaPk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaActivacion()
    {
        return $this->fechaActivacion;
    }

    /**
     * @param mixed $fechaActivacion
     */
    public function setFechaActivacion($fechaActivacion)
    {
        $this->fechaActivacion = $fechaActivacion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaValidaHasta()
    {
        return $this->fechaValidaHasta;
    }

    /**
     * @param mixed $fechaValidaHasta
     */
    public function setFechaValidaHasta($fechaValidaHasta)
    {
        $this->fechaValidaHasta = $fechaValidaHasta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCartera()
    {
        return $this->cartera;
    }

    /**
     * @param mixed $cartera
     */
    public function setCartera($cartera)
    {
        $this->cartera = $cartera;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompra()
    {
        return $this->compra;
    }

    /**
     * @param mixed $compra
     */
    public function setCompra($compra)
    {
        $this->compra = $compra;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDocumental()
    {
        return $this->documental;
    }

    /**
     * @param mixed $documental
     */
    public function setDocumental($documental)
    {
        $this->documental = $documental;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFinanciero()
    {
        return $this->financiero;
    }

    /**
     * @param mixed $financiero
     */
    public function setFinanciero($financiero)
    {
        $this->financiero = $financiero;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGeneral()
    {
        return $this->general;
    }

    /**
     * @param mixed $general
     */
    public function setGeneral($general)
    {
        $this->general = $general;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInventario()
    {
        return $this->inventario;
    }

    /**
     * @param mixed $inventario
     */
    public function setInventario($inventario)
    {
        $this->inventario = $inventario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecursoHumano()
    {
        return $this->recursoHumano;
    }

    /**
     * @param mixed $recursoHumano
     */
    public function setRecursoHumano($recursoHumano)
    {
        $this->recursoHumano = $recursoHumano;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeguridad()
    {
        return $this->seguridad;
    }

    /**
     * @param mixed $seguridad
     */
    public function setSeguridad($seguridad)
    {
        $this->seguridad = $seguridad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransporte()
    {
        return $this->transporte;
    }

    /**
     * @param mixed $transporte
     */
    public function setTransporte($transporte)
    {
        $this->transporte = $transporte;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * @param mixed $turno
     */
    public function setTurno($turno)
    {
        $this->turno = $turno;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCrm()
    {
        return $this->crm;
    }

    /**
     * @param mixed $crm
     */
    public function setCrm($crm)
    {
        $this->crm = $crm;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getNumeroUsuarios()
    {
        return $this->numeroUsuarios;
    }

    /**
     * @param mixed $numeroUsuarios
     */
    public function setNumeroUsuarios($numeroUsuarios)
    {
        $this->numeroUsuarios = $numeroUsuarios;
        return $this;
    }

}


