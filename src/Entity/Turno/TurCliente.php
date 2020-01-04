<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurClienteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurCliente
{
    public $infoLog = [
        "primaryKey" => "codigoClientePk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoClientePk;

    /**
     * @ORM\Column(name="codigo_identificacion_fk", type="string", length=3, nullable=true)
     */
    private $codigoIdentificacionFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */
    private $codigoAsesorFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=150, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="nombre_extendido", type="string", length=500, nullable=true)
     */
    private $nombreExtendido;

    /**
     * @ORM\Column(name="nombre1", type="string", length=50, nullable=true)
     */
    private $nombre1;

    /**
     * @ORM\Column(name="nombre2", type="string", length=50, nullable=true)
     */
    private $nombre2;

    /**
     * @ORM\Column(name="apellido1", type="string", length=50, nullable=true)
     */
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=50, nullable=true)
     */
    private $apellido2;

    /**
     * @ORM\Column(name="direccion", type="string", length=200, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="movil", type="string", length=30, nullable=true)
     */
    private $movil;

    /**
     * @ORM\Column(name="plazo_pago", type="integer", nullable=true, options={"default":0})
     */
    private $plazoPago = 0;

    /**
     * @ORM\Column(name="correo", type="string", length=1000, nullable=true)
     */
    private $correo;

    /**
     * @ORM\Column(name="estado_inactivo", type="boolean", nullable=true,options={"default":false})
     */
    private $estadoInactivo = false;

    /**
     * @ORM\Column(name="retencion_fuente", type="boolean", options={"default":false})
     */
    private $retencionFuente = false;

    /**
     * @ORM\Column(name="retencion_fuente_sin_base", type="boolean", options={"default":false})
     */
    private $retencionFuenteSinBase = false;

    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="string", length=10, nullable=true)
     */
    private $codigoFormaPagoFk;

    /**
     * @ORM\Column(name="codigo_sector_comercial_fk",type="integer", nullable=true)
     */
    private $codigoSectorComercialFk;

    /**
     * @ORM\Column(name="codigo_cobertura_fk", type="integer", nullable=true)
     */
    private $codigoCoberturaFk;

    /**
     * @ORM\Column(name="codigo_dimension_fk",type="integer", nullable=true)
     */
    private $codigoDimensionFk;

    /**
     * @ORM\Column(name="codigo_origen_capital_fk",type="integer", nullable=true)
     */
    private $codigoOrigenCapitalFk;

    /**
     * @ORM\Column(name="codigo_sector_economico_fk",type="integer", nullable=true)
     */
    private $codigoSectorEconomicoFk;

    /**
      * @ORM\Column(name="codigo_tipo_persona_fk", type="string", length=10, nullable=true)
     */
    private $codigoTipoPersonaFk;

    /**
        * @ORM\Column(name="codigo_regimen_fk", type="string", length=10, nullable=true)
     */
    private $codigoRegimenFk;

    /**
     * @ORM\Column(name="estrato", type="string", length=5, nullable=true)
     */
    private $estrato;

    /**
     * @ORM\Column(name="codigo_ciuu", type="string", length=20, nullable=true)
     */
    private $codigoCIUU;

    /**
     * @ORM\Column(name="codigo_segmento_fk", type="string", length=10, nullable=true)
     */
    private $codigoSegmentoFk;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenIdentificacion", inversedBy="turClientesIdentificacionRel")
     * @ORM\JoinColumn(name="codigo_identificacion_fk", referencedColumnName="codigo_identificacion_pk")
     */
    private $identificacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenFormaPago", inversedBy="turClientesFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    private $formaPagoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad", inversedBy="turClientesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenAsesor", inversedBy="turClienteAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenSegmento", inversedBy="turClientesSegmentoRel")
     * @ORM\JoinColumn(name="codigo_segmento_fk", referencedColumnName="codigo_segmento_pk")
     */
    protected $segmentoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenSectorComercial", inversedBy="turClientesSectorComercialRel")
     * @ORM\JoinColumn(name="codigo_sector_comercial_fk", referencedColumnName="codigo_sector_comercial_pk")
     */
    protected $sectorComercialRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCobertura", inversedBy="turClientesCoberturaRel")
     * @ORM\JoinColumn(name="codigo_cobertura_fk", referencedColumnName="codigo_cobertura_pk")
     */
    protected $coberturaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenDimension", inversedBy="turClientesDimensionRel")
     * @ORM\JoinColumn(name="codigo_dimension_fk", referencedColumnName="codigo_dimension_pk")
     */
    protected $dimensionRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenOrigenCapital", inversedBy="turClientesOrigenCapitalRel")
     * @ORM\JoinColumn(name="codigo_origen_capital_fk", referencedColumnName="codigo_origen_capital_pk")
     */
    protected $origenCapitalRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenSectorEconomico", inversedBy="turClientesSectorEconomicoRel")
     * @ORM\JoinColumn(name="codigo_sector_economico_fk", referencedColumnName="codigo_sector_economico_pk")
     */
    protected $sectorEconomicoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenRegimen", inversedBy="turClientesRegimenRel")
     * @ORM\JoinColumn(name="codigo_regimen_fk", referencedColumnName="codigo_regimen_pk")
     */
    private $regimenRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenTipoPersona", inversedBy="turClientesTipoPersonaRel")
     * @ORM\JoinColumn(name="codigo_tipo_persona_fk", referencedColumnName="codigo_tipo_persona_pk")
     */
    private $tipoPersonaRel;

    /**
     * @ORM\OneToMany(targetEntity="TurFactura", mappedBy="clienteRel")
     */
    protected $facturasClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurContrato", mappedBy="clienteRel")
     */
    protected $contratosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="clienteRel")
     */
    protected $PuestosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoEmpleadoServicio", mappedBy="clienteRel")
     */
    protected $costosEmpleadosServiciosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoServicio", mappedBy="clienteRel")
     */
    protected $costosServiciosClienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurGrupo", mappedBy="clienteRel")
     */
    protected $gruposClienteRel;


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
    public function getCodigoClientePk()
    {
        return $this->codigoClientePk;
    }

    /**
     * @param mixed $codigoClientePk
     */
    public function setCodigoClientePk($codigoClientePk): void
    {
        $this->codigoClientePk = $codigoClientePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoIdentificacionFk()
    {
        return $this->codigoIdentificacionFk;
    }

    /**
     * @param mixed $codigoIdentificacionFk
     */
    public function setCodigoIdentificacionFk($codigoIdentificacionFk): void
    {
        $this->codigoIdentificacionFk = $codigoIdentificacionFk;
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
    public function getCodigoAsesorFk()
    {
        return $this->codigoAsesorFk;
    }

    /**
     * @param mixed $codigoAsesorFk
     */
    public function setCodigoAsesorFk($codigoAsesorFk): void
    {
        $this->codigoAsesorFk = $codigoAsesorFk;
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
    public function getDigitoVerificacion()
    {
        return $this->digitoVerificacion;
    }

    /**
     * @param mixed $digitoVerificacion
     */
    public function setDigitoVerificacion($digitoVerificacion): void
    {
        $this->digitoVerificacion = $digitoVerificacion;
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
    public function getNombreExtendido()
    {
        return $this->nombreExtendido;
    }

    /**
     * @param mixed $nombreExtendido
     */
    public function setNombreExtendido($nombreExtendido): void
    {
        $this->nombreExtendido = $nombreExtendido;
    }

    /**
     * @return mixed
     */
    public function getNombre1()
    {
        return $this->nombre1;
    }

    /**
     * @param mixed $nombre1
     */
    public function setNombre1($nombre1): void
    {
        $this->nombre1 = $nombre1;
    }

    /**
     * @return mixed
     */
    public function getNombre2()
    {
        return $this->nombre2;
    }

    /**
     * @param mixed $nombre2
     */
    public function setNombre2($nombre2): void
    {
        $this->nombre2 = $nombre2;
    }

    /**
     * @return mixed
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * @param mixed $apellido1
     */
    public function setApellido1($apellido1): void
    {
        $this->apellido1 = $apellido1;
    }

    /**
     * @return mixed
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * @param mixed $apellido2
     */
    public function setApellido2($apellido2): void
    {
        $this->apellido2 = $apellido2;
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
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * @param mixed $movil
     */
    public function setMovil($movil): void
    {
        $this->movil = $movil;
    }

    /**
     * @return int
     */
    public function getPlazoPago(): int
    {
        return $this->plazoPago;
    }

    /**
     * @param int $plazoPago
     */
    public function setPlazoPago(int $plazoPago): void
    {
        $this->plazoPago = $plazoPago;
    }

    /**
     * @return mixed
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param mixed $correo
     */
    public function setCorreo($correo): void
    {
        $this->correo = $correo;
    }

    /**
     * @return bool
     */
    public function getEstadoInactivo()
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

    /**
     * @return bool
     */
    public function getRetencionFuente()
    {
        return $this->retencionFuente;
    }

    /**
     * @param bool $retencionFuente
     */
    public function setRetencionFuente(bool $retencionFuente): void
    {
        $this->retencionFuente = $retencionFuente;
    }

    /**
     * @return bool
     */
    public function getRetencionFuenteSinBase()
    {
        return $this->retencionFuenteSinBase;
    }

    /**
     * @param bool $retencionFuenteSinBase
     */
    public function setRetencionFuenteSinBase(bool $retencionFuenteSinBase): void
    {
        $this->retencionFuenteSinBase = $retencionFuenteSinBase;
    }

    /**
     * @return mixed
     */
    public function getCodigoFormaPagoFk()
    {
        return $this->codigoFormaPagoFk;
    }

    /**
     * @param mixed $codigoFormaPagoFk
     */
    public function setCodigoFormaPagoFk($codigoFormaPagoFk): void
    {
        $this->codigoFormaPagoFk = $codigoFormaPagoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSectorComercialFk()
    {
        return $this->codigoSectorComercialFk;
    }

    /**
     * @param mixed $codigoSectorComercialFk
     */
    public function setCodigoSectorComercialFk($codigoSectorComercialFk): void
    {
        $this->codigoSectorComercialFk = $codigoSectorComercialFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCoberturaFk()
    {
        return $this->codigoCoberturaFk;
    }

    /**
     * @param mixed $codigoCoberturaFk
     */
    public function setCodigoCoberturaFk($codigoCoberturaFk): void
    {
        $this->codigoCoberturaFk = $codigoCoberturaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDimensionFk()
    {
        return $this->codigoDimensionFk;
    }

    /**
     * @param mixed $codigoDimensionFk
     */
    public function setCodigoDimensionFk($codigoDimensionFk): void
    {
        $this->codigoDimensionFk = $codigoDimensionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOrigenCapitalFk()
    {
        return $this->codigoOrigenCapitalFk;
    }

    /**
     * @param mixed $codigoOrigenCapitalFk
     */
    public function setCodigoOrigenCapitalFk($codigoOrigenCapitalFk): void
    {
        $this->codigoOrigenCapitalFk = $codigoOrigenCapitalFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSectorEconomicoFk()
    {
        return $this->codigoSectorEconomicoFk;
    }

    /**
     * @param mixed $codigoSectorEconomicoFk
     */
    public function setCodigoSectorEconomicoFk($codigoSectorEconomicoFk): void
    {
        $this->codigoSectorEconomicoFk = $codigoSectorEconomicoFk;
    }

    /**
     * @return mixed
     */
    public function getEstrato()
    {
        return $this->estrato;
    }

    /**
     * @param mixed $estrato
     */
    public function setEstrato($estrato): void
    {
        $this->estrato = $estrato;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return mixed
     */
    public function getIdentificacionRel()
    {
        return $this->identificacionRel;
    }

    /**
     * @param mixed $identificacionRel
     */
    public function setIdentificacionRel($identificacionRel): void
    {
        $this->identificacionRel = $identificacionRel;
    }

    /**
     * @return mixed
     */
    public function getFormaPagoRel()
    {
        return $this->formaPagoRel;
    }

    /**
     * @param mixed $formaPagoRel
     */
    public function setFormaPagoRel($formaPagoRel): void
    {
        $this->formaPagoRel = $formaPagoRel;
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
    public function getAsesorRel()
    {
        return $this->asesorRel;
    }

    /**
     * @param mixed $asesorRel
     */
    public function setAsesorRel($asesorRel): void
    {
        $this->asesorRel = $asesorRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasClienteRel()
    {
        return $this->facturasClienteRel;
    }

    /**
     * @param mixed $facturasClienteRel
     */
    public function setFacturasClienteRel($facturasClienteRel): void
    {
        $this->facturasClienteRel = $facturasClienteRel;
    }

    /**
     * @return mixed
     */
    public function getContratosClienteRel()
    {
        return $this->contratosClienteRel;
    }

    /**
     * @param mixed $contratosClienteRel
     */
    public function setContratosClienteRel($contratosClienteRel): void
    {
        $this->contratosClienteRel = $contratosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getPuestosClienteRel()
    {
        return $this->PuestosClienteRel;
    }

    /**
     * @param mixed $PuestosClienteRel
     */
    public function setPuestosClienteRel($PuestosClienteRel): void
    {
        $this->PuestosClienteRel = $PuestosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getCostosEmpleadosServiciosClienteRel()
    {
        return $this->costosEmpleadosServiciosClienteRel;
    }

    /**
     * @param mixed $costosEmpleadosServiciosClienteRel
     */
    public function setCostosEmpleadosServiciosClienteRel($costosEmpleadosServiciosClienteRel): void
    {
        $this->costosEmpleadosServiciosClienteRel = $costosEmpleadosServiciosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getCostosServiciosClienteRel()
    {
        return $this->costosServiciosClienteRel;
    }

    /**
     * @param mixed $costosServiciosClienteRel
     */
    public function setCostosServiciosClienteRel($costosServiciosClienteRel): void
    {
        $this->costosServiciosClienteRel = $costosServiciosClienteRel;
    }

    /**
     * @return mixed
     */
    public function getSectorComercialRel()
    {
        return $this->sectorComercialRel;
    }

    /**
     * @param mixed $sectorComercialRel
     */
    public function setSectorComercialRel($sectorComercialRel): void
    {
        $this->sectorComercialRel = $sectorComercialRel;
    }

    /**
     * @return mixed
     */
    public function getCoberturaRel()
    {
        return $this->coberturaRel;
    }

    /**
     * @param mixed $coberturaRel
     */
    public function setCoberturaRel($coberturaRel): void
    {
        $this->coberturaRel = $coberturaRel;
    }

    /**
     * @return mixed
     */
    public function getDimensionRel()
    {
        return $this->dimensionRel;
    }

    /**
     * @param mixed $dimensionRel
     */
    public function setDimensionRel($dimensionRel): void
    {
        $this->dimensionRel = $dimensionRel;
    }

    /**
     * @return mixed
     */
    public function getOrigenCapitalRel()
    {
        return $this->origenCapitalRel;
    }

    /**
     * @param mixed $origenCapitalRel
     */
    public function setOrigenCapitalRel($origenCapitalRel): void
    {
        $this->origenCapitalRel = $origenCapitalRel;
    }

    /**
     * @return mixed
     */
    public function getSectorEconomicoRel()
    {
        return $this->sectorEconomicoRel;
    }

    /**
     * @param mixed $sectorEconomicoRel
     */
    public function setSectorEconomicoRel($sectorEconomicoRel): void
    {
        $this->sectorEconomicoRel = $sectorEconomicoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoCIUU()
    {
        return $this->codigoCIUU;
    }

    /**
     * @param mixed $codigoCIUU
     */
    public function setCodigoCIUU($codigoCIUU): void
    {
        $this->codigoCIUU = $codigoCIUU;
    }

    /**
     * @return mixed
     */
    public function getRegimenRel()
    {
        return $this->regimenRel;
    }

    /**
     * @param mixed $regimenRel
     */
    public function setRegimenRel($regimenRel): void
    {
        $this->regimenRel = $regimenRel;
    }

    /**
     * @return mixed
     */
    public function getTipoPersonaRel()
    {
        return $this->tipoPersonaRel;
    }

    /**
     * @param mixed $tipoPersonaRel
     */
    public function setTipoPersonaRel($tipoPersonaRel): void
    {
        $this->tipoPersonaRel = $tipoPersonaRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoTipoPersonaFk()
    {
        return $this->codigoTipoPersonaFk;
    }

    /**
     * @param mixed $codigoTipoPersonaFk
     */
    public function setCodigoTipoPersonaFk($codigoTipoPersonaFk): void
    {
        $this->codigoTipoPersonaFk = $codigoTipoPersonaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoRegimenFk()
    {
        return $this->codigoRegimenFk;
    }

    /**
     * @param mixed $codigoRegimenFk
     */
    public function setCodigoRegimenFk($codigoRegimenFk): void
    {
        $this->codigoRegimenFk = $codigoRegimenFk;
    }

    /**
     * @return mixed
     */
    public function getGruposClienteRel()
    {
        return $this->gruposClienteRel;
    }

    /**
     * @param mixed $gruposClienteRel
     */
    public function setGruposClienteRel($gruposClienteRel): void
    {
        $this->gruposClienteRel = $gruposClienteRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoSegmentoFk()
    {
        return $this->codigoSegmentoFk;
    }

    /**
     * @param mixed $codigoSegmentoFk
     */
    public function setCodigoSegmentoFk($codigoSegmentoFk): void
    {
        $this->codigoSegmentoFk = $codigoSegmentoFk;
    }

    /**
     * @return mixed
     */
    public function getSegmentoRel()
    {
        return $this->segmentoRel;
    }

    /**
     * @param mixed $segmentoRel
     */
    public function setSegmentoRel($segmentoRel): void
    {
        $this->segmentoRel = $segmentoRel;
    }


}

