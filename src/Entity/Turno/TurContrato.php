<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurContratoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurContrato
{
    public $infoLog = [
        "primaryKey" => "codigoContratoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoPk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_contrato_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoContratoTipoFk;

    /**
     * @ORM\Column(name="soporte", type="string", length=100, nullable=true)
     */
    private $soporte;

    /**
     * @ORM\Column(name="fecha_generacion", type="date", nullable=true)
     */
    private $fechaGeneracion;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", options={"default":false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default":false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", options={"default":false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_terminado", type="boolean", options={"default":false})
     */
    private $estadoTerminado = false;

    /**
     * @ORM\Column(name="fecha_cierre", type="date", nullable=true)
     */
    private $fechaCierre;

    /**
     * @ORM\Column(name="codigo_usuario_cierre", type="string", length=50, nullable=true)
     */
    private $codigoUsuarioCierre;

    /**
     * @ORM\Column(name="codigo_sector_fk", type="string", length=10, nullable=true)
     */
    private $codigoSectorFk;

    /**
     * @ORM\Column(name="cantidad", type="integer", options={"default":0})
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="estrato", type="integer", nullable=true)
     */
    private $estrato = 0;

    /**
     * @ORM\Column(name="horas", type="integer", options={"default":0})
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="horas_diurnas", type="integer", options={"default":0})
     */
    private $horasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_nocturnas", type="integer", options={"default":0})
     */
    private $horasNocturnas = 0;

    /**
     * @ORM\Column(name="vr_total_costo", type="float", options={"default":0})
     */
    private $vrTotalCosto = 0;

    /**
     * @ORM\Column(name="vr_total_contrato", type="float", options={"default":0})
     */
    private $vrTotalContrato = 0;

    /**
     * @ORM\Column(name="vr_total_precio_ajustado", type="float", options={"default":0})
     */
    private $vrTotalPrecioAjustado = 0;

    /**
     * @ORM\Column(name="vr_total_precio_minimo", type="float", options={"default":0})
     */
    private $vrTotalPrecioMinimo = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float", options={"default":0})
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float", options={"default":0})
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_base_aiu", type="float", options={"default":0})
     */
    private $vrBaseAiu = 0;

    /**
     * @ORM\Column(name="vr_salario_base", type="float", options={"default":0}))
     */
    private $vrSalarioBase = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", options={"default":0})
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="contratosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurContratoTipo", inversedBy="contratosContratoTipoRel")
     * @ORM\JoinColumn(name="codigo_contrato_tipo_fk", referencedColumnName="codigo_contrato_tipo_pk")
     */
    protected $contratoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurSector", inversedBy="contratosSectorRel")
     * @ORM\JoinColumn(name="codigo_sector_fk", referencedColumnName="codigo_sector_pk")
     */
    protected $sectorRel;
    
    /**
     * @ORM\OneToMany(targetEntity="TurContratoDetalle", mappedBy="contratoRel")
     */
    protected $contratosDetallesContratoRel;

    /**
     * @return mixed
     */
    public function getCodigoContratoPk()
    {
        return $this->codigoContratoPk;
    }

    /**
     * @param mixed $codigoContratoPk
     */
    public function setCodigoContratoPk($codigoContratoPk): void
    {
        $this->codigoContratoPk = $codigoContratoPk;
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
    public function getCodigoContratoTipoFk()
    {
        return $this->codigoContratoTipoFk;
    }

    /**
     * @param mixed $codigoContratoTipoFk
     */
    public function setCodigoContratoTipoFk($codigoContratoTipoFk): void
    {
        $this->codigoContratoTipoFk = $codigoContratoTipoFk;
    }

    /**
     * @return mixed
     */
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * @param mixed $soporte
     */
    public function setSoporte($soporte): void
    {
        $this->soporte = $soporte;
    }

    /**
     * @return mixed
     */
    public function getFechaGeneracion()
    {
        return $this->fechaGeneracion;
    }

    /**
     * @param mixed $fechaGeneracion
     */
    public function setFechaGeneracion($fechaGeneracion): void
    {
        $this->fechaGeneracion = $fechaGeneracion;
    }

    /**
     * @return mixed
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * @param mixed $estadoAutorizado
     */
    public function setEstadoAutorizado($estadoAutorizado): void
    {
        $this->estadoAutorizado = $estadoAutorizado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * @param mixed $estadoAnulado
     */
    public function setEstadoAnulado($estadoAnulado): void
    {
        $this->estadoAnulado = $estadoAnulado;
    }

    /**
     * @return mixed
     */
    public function getEstadoTerminado()
    {
        return $this->estadoTerminado;
    }

    /**
     * @param mixed $estadoTerminado
     */
    public function setEstadoTerminado($estadoTerminado): void
    {
        $this->estadoTerminado = $estadoTerminado;
    }

    /**
     * @return mixed
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * @param mixed $fechaCierre
     */
    public function setFechaCierre($fechaCierre): void
    {
        $this->fechaCierre = $fechaCierre;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuarioCierre()
    {
        return $this->codigoUsuarioCierre;
    }

    /**
     * @param mixed $codigoUsuarioCierre
     */
    public function setCodigoUsuarioCierre($codigoUsuarioCierre): void
    {
        $this->codigoUsuarioCierre = $codigoUsuarioCierre;
    }

    /**
     * @return mixed
     */
    public function getCodigoSectorFk()
    {
        return $this->codigoSectorFk;
    }

    /**
     * @param mixed $codigoSectorFk
     */
    public function setCodigoSectorFk($codigoSectorFk): void
    {
        $this->codigoSectorFk = $codigoSectorFk;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad): void
    {
        $this->cantidad = $cantidad;
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
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * @param mixed $horas
     */
    public function setHoras($horas): void
    {
        $this->horas = $horas;
    }

    /**
     * @return mixed
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * @param mixed $horasDiurnas
     */
    public function setHorasDiurnas($horasDiurnas): void
    {
        $this->horasDiurnas = $horasDiurnas;
    }

    /**
     * @return mixed
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * @param mixed $horasNocturnas
     */
    public function setHorasNocturnas($horasNocturnas): void
    {
        $this->horasNocturnas = $horasNocturnas;
    }

    /**
     * @return mixed
     */
    public function getVrTotalCosto()
    {
        return $this->vrTotalCosto;
    }

    /**
     * @param mixed $vrTotalCosto
     */
    public function setVrTotalCosto($vrTotalCosto): void
    {
        $this->vrTotalCosto = $vrTotalCosto;
    }

    /**
     * @return mixed
     */
    public function getVrTotalContrato()
    {
        return $this->vrTotalContrato;
    }

    /**
     * @param mixed $vrTotalContrato
     */
    public function setVrTotalContrato($vrTotalContrato): void
    {
        $this->vrTotalContrato = $vrTotalContrato;
    }

    /**
     * @return mixed
     */
    public function getVrTotalPrecioAjustado()
    {
        return $this->vrTotalPrecioAjustado;
    }

    /**
     * @param mixed $vrTotalPrecioAjustado
     */
    public function setVrTotalPrecioAjustado($vrTotalPrecioAjustado): void
    {
        $this->vrTotalPrecioAjustado = $vrTotalPrecioAjustado;
    }

    /**
     * @return mixed
     */
    public function getVrTotalPrecioMinimo()
    {
        return $this->vrTotalPrecioMinimo;
    }

    /**
     * @param mixed $vrTotalPrecioMinimo
     */
    public function setVrTotalPrecioMinimo($vrTotalPrecioMinimo): void
    {
        $this->vrTotalPrecioMinimo = $vrTotalPrecioMinimo;
    }

    /**
     * @return mixed
     */
    public function getVrSubtotal()
    {
        return $this->vrSubtotal;
    }

    /**
     * @param mixed $vrSubtotal
     */
    public function setVrSubtotal($vrSubtotal): void
    {
        $this->vrSubtotal = $vrSubtotal;
    }

    /**
     * @return mixed
     */
    public function getVrIva()
    {
        return $this->vrIva;
    }

    /**
     * @param mixed $vrIva
     */
    public function setVrIva($vrIva): void
    {
        $this->vrIva = $vrIva;
    }

    /**
     * @return mixed
     */
    public function getVrBaseAiu()
    {
        return $this->vrBaseAiu;
    }

    /**
     * @param mixed $vrBaseAiu
     */
    public function setVrBaseAiu($vrBaseAiu): void
    {
        $this->vrBaseAiu = $vrBaseAiu;
    }

    /**
     * @return mixed
     */
    public function getVrSalarioBase()
    {
        return $this->vrSalarioBase;
    }

    /**
     * @param mixed $vrSalarioBase
     */
    public function setVrSalarioBase($vrSalarioBase): void
    {
        $this->vrSalarioBase = $vrSalarioBase;
    }

    /**
     * @return mixed
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * @param mixed $vrTotal
     */
    public function setVrTotal($vrTotal): void
    {
        $this->vrTotal = $vrTotal;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
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
    public function getContratoTipoRel()
    {
        return $this->contratoTipoRel;
    }

    /**
     * @param mixed $contratoTipoRel
     */
    public function setContratoTipoRel($contratoTipoRel): void
    {
        $this->contratoTipoRel = $contratoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getSectorRel()
    {
        return $this->sectorRel;
    }

    /**
     * @param mixed $sectorRel
     */
    public function setSectorRel($sectorRel): void
    {
        $this->sectorRel = $sectorRel;
    }

    /**
     * @return mixed
     */
    public function getContratosDetallesContratoRel()
    {
        return $this->contratosDetallesContratoRel;
    }

    /**
     * @param mixed $contratosDetallesContratoRel
     */
    public function setContratosDetallesContratoRel($contratosDetallesContratoRel): void
    {
        $this->contratosDetallesContratoRel = $contratosDetallesContratoRel;
    }


}

