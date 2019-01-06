<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenLicenciaRepository")
 */
class GenLicencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_licencia_pk", type="string", length=20, nullable=false)
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
    public function setCodigoLicenciaPk( $codigoLicenciaPk ): void
    {
        $this->codigoLicenciaPk = $codigoLicenciaPk;
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
    public function setFechaActivacion( $fechaActivacion ): void
    {
        $this->fechaActivacion = $fechaActivacion;
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
    public function setFechaValidaHasta( $fechaValidaHasta ): void
    {
        $this->fechaValidaHasta = $fechaValidaHasta;
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
    public function setNumeroUsuarios( $numeroUsuarios ): void
    {
        $this->numeroUsuarios = $numeroUsuarios;
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
    public function setCartera( $cartera ): void
    {
        $this->cartera = $cartera;
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
    public function setCompra( $compra ): void
    {
        $this->compra = $compra;
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
    public function setDocumental( $documental ): void
    {
        $this->documental = $documental;
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
    public function setFinanciero( $financiero ): void
    {
        $this->financiero = $financiero;
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
    public function setGeneral( $general ): void
    {
        $this->general = $general;
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
    public function setInventario( $inventario ): void
    {
        $this->inventario = $inventario;
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
    public function setRecursoHumano( $recursoHumano ): void
    {
        $this->recursoHumano = $recursoHumano;
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
    public function setSeguridad( $seguridad ): void
    {
        $this->seguridad = $seguridad;
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
    public function setTransporte( $transporte ): void
    {
        $this->transporte = $transporte;
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
    public function setTurno( $turno ): void
    {
        $this->turno = $turno;
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
    public function setCrm( $crm ): void
    {
        $this->crm = $crm;
    }



}


