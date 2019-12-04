<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurCotizacionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */

class TurCotizacion
{
    public $infoLog = [
        "primaryKey" => "codigoCotizacionPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cotizacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCotizacionPk;

    /**
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_cotizacion_tipo_fk", type="string", length=20)
     */
    private $codigoCotizacionTipoFk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_sector_fk", type="string", length=10, nullable=true)
     */
    private $codigoSectorFk;

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
     * @ORM\Column(name="estado_contabilizado", type="boolean", options={"default":false})
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="fecha_generacion", type="date", nullable=true)
     */
    private $fechaGeneracion;

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */
    private $fechaVence;

    /**
     * @ORM\Column(name="horas", type="integer")
     */
    private $horas = 0;

    /**
     * @ORM\Column(name="horas_diurnas", type="integer")
     */
    private $horasDiurnas = 0;

    /**
     * @ORM\Column(name="horas_nocturnas", type="integer")
     */
    private $horasNocturnas = 0;

    /**
     * @ORM\Column(name="vr_total_precio_ajustado", type="float")
     */
    private $vrTotalPrecioAjustado = 0;

    /**
     * @ORM\Column(name="vr_total_precio_minimo", type="float")
     */
    private $vrTotalPrecioMinimo = 0;

    /**
     * @ORM\Column(name="vr_total_servicio", type="float")
     */
    private $vrTotalServicio = 0;

    /**
     * @ORM\Column(name="vr_subtotal", type="float")
     */
    private $vrSubtotal = 0;

    /**
     * @ORM\Column(name="vr_iva", type="float", nullable=true)estado
     */
    private $vrIva = 0;

    /**
     * @ORM\Column(name="vr_base_aiu", type="float")
     */
    private $vrBaseAiu = 0;

    /**
     * @ORM\Column(name="vr_total", type="float", nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="vr_total_costo", type="float", options={"default":0})
     */
    private $vrTotalCosto = 0;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="comentario", type="string", length=500, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="vr_salario_base", type="float")
     */
    private $vrSalarioBase = 0;

    /**
     * @ORM\Column(name="estrato", type="integer", nullable=true)
     */
    private $estrato = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TurCotizacionTipo", inversedBy="cotizacionesCotizacionTipoRel")
     * @ORM\JoinColumn(name="codigo_cotizacion_tipo_fk", referencedColumnName="codigo_cotizacion_tipo_pk")
     */
    protected $cotizacionTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="cotizacionesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurSector", inversedBy="cotizacionesSectorRel")
     * @ORM\JoinColumn(name="codigo_sector_fk", referencedColumnName="codigo_sector_pk")
     */
    protected $sectorRel;

    /**
     * @ORM\OneToMany(targetEntity="TurCotizacionDetalle", mappedBy="cotizacionRel")
     */
    protected $cotizacionesDetallesCotizacionRel;

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
    public function getCodigoCotizacionPk()
    {
        return $this->codigoCotizacionPk;
    }

    /**
     * @param mixed $codigoCotizacionPk
     */
    public function setCodigoCotizacionPk($codigoCotizacionPk): void
    {
        $this->codigoCotizacionPk = $codigoCotizacionPk;
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
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getCodigoCotizacionTipoFk()
    {
        return $this->codigoCotizacionTipoFk;
    }

    /**
     * @param mixed $codigoCotizacionTipoFk
     */
    public function setCodigoCotizacionTipoFk($codigoCotizacionTipoFk): void
    {
        $this->codigoCotizacionTipoFk = $codigoCotizacionTipoFk;
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
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
    }

    /**
     * @param mixed $estadoContabilizado
     */
    public function setEstadoContabilizado($estadoContabilizado): void
    {
        $this->estadoContabilizado = $estadoContabilizado;
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
    public function getVrTotalServicio()
    {
        return $this->vrTotalServicio;
    }

    /**
     * @param mixed $vrTotalServicio
     */
    public function setVrTotalServicio($vrTotalServicio): void
    {
        $this->vrTotalServicio = $vrTotalServicio;
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
    public function getCotizacionTipoRel()
    {
        return $this->cotizacionTipoRel;
    }

    /**
     * @param mixed $cotizacionTipoRel
     */
    public function setCotizacionTipoRel($cotizacionTipoRel): void
    {
        $this->cotizacionTipoRel = $cotizacionTipoRel;
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
    public function getCotizacionesDetallesCotizacionRel()
    {
        return $this->cotizacionesDetallesCotizacionRel;
    }

    /**
     * @param mixed $cotizacionesDetallesCotizacionRel
     */
    public function setCotizacionesDetallesCotizacionRel($cotizacionesDetallesCotizacionRel): void
    {
        $this->cotizacionesDetallesCotizacionRel = $cotizacionesDetallesCotizacionRel;
    }

    /**
     * @return mixed
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * @param mixed $fechaVence
     */
    public function setFechaVence($fechaVence): void
    {
        $this->fechaVence = $fechaVence;
    }



}

