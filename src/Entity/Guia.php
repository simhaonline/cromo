<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GuiaRepository")
 */
class Guia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoGuiaPk;

    /**
     * @ORM\Column(name="numero", type="float", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="codigo_sede_ingreso_fk", type="string", length=20, nullable=true)
     */
    private $codigoSedeIngresoFk;

    /**
     * @ORM\Column(name="codigo_sede_cargo_fk", type="string", length=20, nullable=true)
     */
    private $codigoSedeCargoFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadOrigenFk;

    /**
     * @ORM\Column(name="codigo_ciudad_destino_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadDestinoFk;

    /**
     * @ORM\Column(name="documentoCliente", type="string", length=80, nullable=true)
     */
    private $documentoCliente;

    /**
     * @ORM\Column(name="remitente", type="string", length=80, nullable=true)
     */
    private $remitente;

    /**
     * @ORM\Column(name="nombre_destinatario", type="string", length=80, nullable=true)
     */
    private $nombreDestinatario;

    /**
     * @ORM\Column(name="direccion_destinatario", type="string", length=80, nullable=true)
     */
    private $direccionDestinatario;

    /**
     * @ORM\Column(name="telefono_destinatario", type="string", length=80, nullable=true)
     */
    private $telefonoDestinatario;

    /**
     * @ORM\Column(name="fecha_ingreso", type="datetime", nullable=true)
     */
    private $fechaIngreso;

    /**
     * @ORM\Column(name="fecha_despacho", type="datetime", nullable=true)
     */
    private $fechaDespacho;

    /**
     * @ORM\Column(name="fecha_entrega", type="datetime", nullable=true)
     */
    private $fechaEntrega;

    /**
     * @ORM\Column(name="fecha_cumplido", type="datetime", nullable=true)
     */
    private $fechaCumplido;

    /**
     * @ORM\Column(name="fecha_soporte", type="datetime", nullable=true)
     */
    private $fechaSoporte;

    /**
     * @ORM\Column(name="unidades", type="float")
     */
    private $unidades = 0;

    /**
     * @ORM\Column(name="peso_real", type="float")
     */
    private $pesoReal = 0;

    /**
     * @ORM\Column(name="peso_volumen", type="float")
     */
    private $pesoVolumen = 0;

    /**
     * @ORM\Column(name="peso_facturado", type="float")
     */
    private $pesoFacturado = 0;

    /**
     * @ORM\Column(name="vr_declara", type="float")
     */
    private $vrDeclara = 0;

    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;

    /**
     * @ORM\Column(name="vr_manejo", type="float")
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="vr_recaudo", type="float")
     */
    private $vrRecaudo = 0;

    /**
     * @ORM\Column(name="estado_despachado", type="boolean", nullable=true)
     */
    private $estadoDespachado = false;

    /**
     * @ORM\Column(name="estado_entregado", type="boolean", nullable=true)
     */
    private $estadoEntregado = false;

    /**
     * @ORM\Column(name="estado_soporte", type="boolean", nullable=true)
     */
    private $estadoSoporte = false;

    /**
     * @ORM\Column(name="estado_cumplido", type="boolean", nullable=true)
     */
    private $estadoCumplido = false;

    /**
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */
    private $codigoDespachoFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sede", inversedBy="guiasSedeIngresoRel")
     * @ORM\JoinColumn(name="codigo_sede_ingreso_fk", referencedColumnName="codigo_sede_pk")
     */
    private $sedeIngresoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sede", inversedBy="guiasSedeCargoRel")
     * @ORM\JoinColumn(name="codigo_sede_cargo_fk", referencedColumnName="codigo_sede_pk")
     */
    private $sedeCargoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="guiasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ciudad", inversedBy="guiasCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadOrigenRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ciudad", inversedBy="guiasCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadDestinoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Despacho", inversedBy="guiasDespachoRel")
     * @ORM\JoinColumn(name="codigo_despacho_fk", referencedColumnName="codigo_despacho_pk")
     */
    private $despachoRel;

    /**
     * @return mixed
     */
    public function getCodigoCiudadDestinoFk()
    {
        return $this->codigoCiudadDestinoFk;
    }

    /**
     * @param mixed $codigoCiudadDestinoFk
     */
    public function setCodigoCiudadDestinoFk($codigoCiudadDestinoFk): void
    {
        $this->codigoCiudadDestinoFk = $codigoCiudadDestinoFk;
    }

    /**
     * @return mixed
     */
    public function getCiudadDestinoRel()
    {
        return $this->ciudadDestinoRel;
    }

    /**
     * @param mixed $ciudadDestinoRel
     */
    public function setCiudadDestinoRel($ciudadDestinoRel): void
    {
        $this->ciudadDestinoRel = $ciudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadOrigenRel()
    {
        return $this->ciudadOrigenRel;
    }

    /**
     * @param mixed $ciudadOrigenRel
     */
    public function setCiudadOrigenRel($ciudadOrigenRel): void
    {
        $this->ciudadOrigenRel = $ciudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoGuiaPk()
    {
        return $this->codigoGuiaPk;
    }

    /**
     * @param mixed $codigoGuiaPk
     */
    public function setCodigoGuiaPk($codigoGuiaPk): void
    {
        $this->codigoGuiaPk = $codigoGuiaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadOrigenFk()
    {
        return $this->codigoCiudadOrigenFk;
    }

    /**
     * @param mixed $codigoCiudadOrigenFk
     */
    public function setCodigoCiudadOrigenFk($codigoCiudadOrigenFk): void
    {
        $this->codigoCiudadOrigenFk = $codigoCiudadOrigenFk;
    }


    /**
     * @return mixed
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * @param mixed $codigoClienteFk
     */
    public function setCodigoClienteFk($codigoClienteFk): void
    {
        $this->codigoClienteFk = $codigoClienteFk;
    }

    /**
     * @return mixed
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * @param mixed $clienteRel
     */
    public function setClienteRel($clienteRel): void
    {
        $this->clienteRel = $clienteRel;
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
    public function getNombreDestinatario()
    {
        return $this->nombreDestinatario;
    }

    /**
     * @param mixed $nombreDestinatario
     */
    public function setNombreDestinatario($nombreDestinatario): void
    {
        $this->nombreDestinatario = $nombreDestinatario;
    }

    /**
     * @return mixed
     */
    public function getCodigoSedeIngresoFk()
    {
        return $this->codigoSedeIngresoFk;
    }

    /**
     * @param mixed $codigoSedeIngresoFk
     */
    public function setCodigoSedeIngresoFk($codigoSedeIngresoFk): void
    {
        $this->codigoSedeIngresoFk = $codigoSedeIngresoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSedeCargoFk()
    {
        return $this->codigoSedeCargoFk;
    }

    /**
     * @param mixed $codigoSedeCargoFk
     */
    public function setCodigoSedeCargoFk($codigoSedeCargoFk): void
    {
        $this->codigoSedeCargoFk = $codigoSedeCargoFk;
    }

    /**
     * @return mixed
     */
    public function getDocumentoCliente()
    {
        return $this->documentoCliente;
    }

    /**
     * @param mixed $documentoCliente
     */
    public function setDocumentoCliente($documentoCliente): void
    {
        $this->documentoCliente = $documentoCliente;
    }

    /**
     * @return mixed
     */
    public function getRemitente()
    {
        return $this->remitente;
    }

    /**
     * @param mixed $remitente
     */
    public function setRemitente($remitente): void
    {
        $this->remitente = $remitente;
    }

    /**
     * @return mixed
     */
    public function getDireccionDestinatario()
    {
        return $this->direccionDestinatario;
    }

    /**
     * @param mixed $direccionDestinatario
     */
    public function setDireccionDestinatario($direccionDestinatario): void
    {
        $this->direccionDestinatario = $direccionDestinatario;
    }

    /**
     * @return mixed
     */
    public function getTelefonoDestinatario()
    {
        return $this->telefonoDestinatario;
    }

    /**
     * @param mixed $telefonoDestinatario
     */
    public function setTelefonoDestinatario($telefonoDestinatario): void
    {
        $this->telefonoDestinatario = $telefonoDestinatario;
    }

    /**
     * @return mixed
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * @param mixed $fechaIngreso
     */
    public function setFechaIngreso($fechaIngreso): void
    {
        $this->fechaIngreso = $fechaIngreso;
    }

    /**
     * @return mixed
     */
    public function getFechaDespacho()
    {
        return $this->fechaDespacho;
    }

    /**
     * @param mixed $fechaDespacho
     */
    public function setFechaDespacho($fechaDespacho): void
    {
        $this->fechaDespacho = $fechaDespacho;
    }

    /**
     * @return mixed
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * @param mixed $fechaEntrega
     */
    public function setFechaEntrega($fechaEntrega): void
    {
        $this->fechaEntrega = $fechaEntrega;
    }

    /**
     * @return mixed
     */
    public function getFechaCumplido()
    {
        return $this->fechaCumplido;
    }

    /**
     * @param mixed $fechaCumplido
     */
    public function setFechaCumplido($fechaCumplido): void
    {
        $this->fechaCumplido = $fechaCumplido;
    }

    /**
     * @return mixed
     */
    public function getFechaSoporte()
    {
        return $this->fechaSoporte;
    }

    /**
     * @param mixed $fechaSoporte
     */
    public function setFechaSoporte($fechaSoporte): void
    {
        $this->fechaSoporte = $fechaSoporte;
    }

    /**
     * @return mixed
     */
    public function getUnidades()
    {
        return $this->unidades;
    }

    /**
     * @param mixed $unidades
     */
    public function setUnidades($unidades): void
    {
        $this->unidades = $unidades;
    }

    /**
     * @return mixed
     */
    public function getPesoReal()
    {
        return $this->pesoReal;
    }

    /**
     * @param mixed $pesoReal
     */
    public function setPesoReal($pesoReal): void
    {
        $this->pesoReal = $pesoReal;
    }

    /**
     * @return mixed
     */
    public function getPesoVolumen()
    {
        return $this->pesoVolumen;
    }

    /**
     * @param mixed $pesoVolumen
     */
    public function setPesoVolumen($pesoVolumen): void
    {
        $this->pesoVolumen = $pesoVolumen;
    }

    /**
     * @return mixed
     */
    public function getPesoFacturado()
    {
        return $this->pesoFacturado;
    }

    /**
     * @param mixed $pesoFacturado
     */
    public function setPesoFacturado($pesoFacturado): void
    {
        $this->pesoFacturado = $pesoFacturado;
    }

    /**
     * @return mixed
     */
    public function getVrDeclara()
    {
        return $this->vrDeclara;
    }

    /**
     * @param mixed $vrDeclara
     */
    public function setVrDeclara($vrDeclara): void
    {
        $this->vrDeclara = $vrDeclara;
    }

    /**
     * @return mixed
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * @param mixed $vrFlete
     */
    public function setVrFlete($vrFlete): void
    {
        $this->vrFlete = $vrFlete;
    }

    /**
     * @return mixed
     */
    public function getVrManejo()
    {
        return $this->vrManejo;
    }

    /**
     * @param mixed $vrManejo
     */
    public function setVrManejo($vrManejo): void
    {
        $this->vrManejo = $vrManejo;
    }

    /**
     * @return mixed
     */
    public function getVrRecaudo()
    {
        return $this->vrRecaudo;
    }

    /**
     * @param mixed $vrRecaudo
     */
    public function setVrRecaudo($vrRecaudo): void
    {
        $this->vrRecaudo = $vrRecaudo;
    }

    /**
     * @return mixed
     */
    public function getEstadoDespachado()
    {
        return $this->estadoDespachado;
    }

    /**
     * @param mixed $estadoDespachado
     */
    public function setEstadoDespachado($estadoDespachado): void
    {
        $this->estadoDespachado = $estadoDespachado;
    }

    /**
     * @return mixed
     */
    public function getEstadoEntregado()
    {
        return $this->estadoEntregado;
    }

    /**
     * @param mixed $estadoEntregado
     */
    public function setEstadoEntregado($estadoEntregado): void
    {
        $this->estadoEntregado = $estadoEntregado;
    }

    /**
     * @return mixed
     */
    public function getEstadoSoporte()
    {
        return $this->estadoSoporte;
    }

    /**
     * @param mixed $estadoSoporte
     */
    public function setEstadoSoporte($estadoSoporte): void
    {
        $this->estadoSoporte = $estadoSoporte;
    }

    /**
     * @return mixed
     */
    public function getEstadoCumplido()
    {
        return $this->estadoCumplido;
    }

    /**
     * @param mixed $estadoCumplido
     */
    public function setEstadoCumplido($estadoCumplido): void
    {
        $this->estadoCumplido = $estadoCumplido;
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
    public function getSedeIngresoRel()
    {
        return $this->sedeIngresoRel;
    }

    /**
     * @param mixed $sedeIngresoRel
     */
    public function setSedeIngresoRel($sedeIngresoRel): void
    {
        $this->sedeIngresoRel = $sedeIngresoRel;
    }

    /**
     * @return mixed
     */
    public function getSedeCargoRel()
    {
        return $this->sedeCargoRel;
    }

    /**
     * @param mixed $sedeCargoRel
     */
    public function setSedeCargoRel($sedeCargoRel): void
    {
        $this->sedeCargoRel = $sedeCargoRel;
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


}

