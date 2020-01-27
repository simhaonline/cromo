<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSeleccionReferenciaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSeleccionReferencia
{
    public $infoLog = [
        "primaryKey" => "codigoSeleccionRefernciaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_referencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSeleccionReferenciaPk;

    /**
     * @ORM\Column(name="codigo_seleccion_fk", type="integer")
     */
    private $codigoSeleccionFk;

    /**
     * @ORM\Column(name="codigo_seleccion_referencia_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoSeleccionReferenciaTipoFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="empresa", type="string", length=80, nullable=true)
     */
    private $empresa;

    /**
     * @ORM\Column(name="suministra_informacion", type="string", length=80, nullable=true)
     */
    private $suministraInformacion;

    /**
     * @ORM\Column(name="cargo", type="string", length=80, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\Column(name="motivo_retiro", type="string", length=2000, nullable=true)
     */
    private $motivoRetiro;

    /**
     * @ORM\Column(name="tiempo_laborado", type="string", length=80, nullable=true)
     */
    private $tiempoLaborado;

    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */
    private $celular;

    /**
     * @ORM\Column(name="direccion", type="string", length=30, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="estado_verificada", type="boolean")
     */
    private $estadoVerificada = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccion", inversedBy="seleccionesReferenciasSeleccionRel")
     * @ORM\JoinColumn(name="codigo_seleccion_fk", referencedColumnName="codigo_seleccion_pk")
     */
    protected $seleccionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionReferenciaTipo", inversedBy="seleccionesReferenciasSelecionReferenciaTipoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_referencia_tipo_fk", referencedColumnName="codigo_seleccion_referencia_tipo_pk")
     */
    protected $seleccionReferenciaTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="rhuSeleccionesReferenciasCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

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
    public function getCodigoSeleccionReferenciaPk()
    {
        return $this->codigoSeleccionReferenciaPk;
    }

    /**
     * @param mixed $codigoSeleccionReferenciaPk
     */
    public function setCodigoSeleccionReferenciaPk($codigoSeleccionReferenciaPk): void
    {
        $this->codigoSeleccionReferenciaPk = $codigoSeleccionReferenciaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSeleccionFk()
    {
        return $this->codigoSeleccionFk;
    }

    /**
     * @param mixed $codigoSeleccionFk
     */
    public function setCodigoSeleccionFk($codigoSeleccionFk): void
    {
        $this->codigoSeleccionFk = $codigoSeleccionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSeleccionReferenciaTipoFk()
    {
        return $this->codigoSeleccionReferenciaTipoFk;
    }

    /**
     * @param mixed $codigoSeleccionReferenciaTipoFk
     */
    public function setCodigoSeleccionReferenciaTipoFk($codigoSeleccionReferenciaTipoFk): void
    {
        $this->codigoSeleccionReferenciaTipoFk = $codigoSeleccionReferenciaTipoFk;
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
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param mixed $empresa
     */
    public function setEmpresa($empresa): void
    {
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    public function getSuministraInformacion()
    {
        return $this->suministraInformacion;
    }

    /**
     * @param mixed $suministraInformacion
     */
    public function setSuministraInformacion($suministraInformacion): void
    {
        $this->suministraInformacion = $suministraInformacion;
    }

    /**
     * @return mixed
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * @param mixed $cargo
     */
    public function setCargo($cargo): void
    {
        $this->cargo = $cargo;
    }

    /**
     * @return mixed
     */
    public function getMotivoRetiro()
    {
        return $this->motivoRetiro;
    }

    /**
     * @param mixed $motivoRetiro
     */
    public function setMotivoRetiro($motivoRetiro): void
    {
        $this->motivoRetiro = $motivoRetiro;
    }

    /**
     * @return mixed
     */
    public function getTiempoLaborado()
    {
        return $this->tiempoLaborado;
    }

    /**
     * @param mixed $tiempoLaborado
     */
    public function setTiempoLaborado($tiempoLaborado): void
    {
        $this->tiempoLaborado = $tiempoLaborado;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * @param mixed $codigoCiudadFk
     */
    public function setCodigoCiudadFk($codigoCiudadFk): void
    {
        $this->codigoCiudadFk = $codigoCiudadFk;
    }

    /**
     * @return mixed
     */
    public function getEstadoVerificada()
    {
        return $this->estadoVerificada;
    }

    /**
     * @param mixed $estadoVerificada
     */
    public function setEstadoVerificada($estadoVerificada): void
    {
        $this->estadoVerificada = $estadoVerificada;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
    }

    /**
     * @return mixed
     */
    public function getSeleccionRel()
    {
        return $this->seleccionRel;
    }

    /**
     * @param mixed $seleccionRel
     */
    public function setSeleccionRel($seleccionRel): void
    {
        $this->seleccionRel = $seleccionRel;
    }

    /**
     * @return mixed
     */
    public function getSeleccionReferenciaTipoRel()
    {
        return $this->seleccionReferenciaTipoRel;
    }

    /**
     * @param mixed $seleccionReferenciaTipoRel
     */
    public function setSeleccionReferenciaTipoRel($seleccionReferenciaTipoRel): void
    {
        $this->seleccionReferenciaTipoRel = $seleccionReferenciaTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * @param mixed $ciudadRel
     */
    public function setCiudadRel($ciudadRel): void
    {
        $this->ciudadRel = $ciudadRel;
    }

    /**
     * @return mixed
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param mixed $celular
     */
    public function setCelular($celular): void
    {
        $this->celular = $celular;
    }



}
