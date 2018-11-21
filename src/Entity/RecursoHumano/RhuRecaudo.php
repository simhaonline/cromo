<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuRecaudoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuRecaudo
{
    public $infoLog = [
        "primaryKey" => "codigoRecaudoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recaudo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRecaudoPk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_entidad_fk", type="integer", nullable=true)
     */
    private $codigoEntidadFk;

    /**
     * @ORM\Column(name="numero", options={"default":0}, type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="vr_total", options={"default":0}, type="float", nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default":false}, type="boolean", nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */
    private $fechaPago;

    /**
     * @ORM\Column(name="estado_aprobado", options={"default":false}, type="boolean", nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", options={"default":false}, type="boolean", nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="recibo_caja", type="string", length=20 , nullable=true)
     */
    private  $reciboCaja;

    /**
     * @ORM\Column(name="valor_recibo_caja", type="float", nullable=true)
     */
    private  $ValorReciboCaja;

    /**
     * @ORM\Column(name="estado_cerrado", options={"default":false}, type="boolean", nullable=true)
     */
    private $estadoCerrado = false;

    /**
     * @ORM\Column(name="vr_total_entidad", options={"default":0}, type="float", nullable=true)
     */
    private $vrTotalEntidad = 0;

    /**
     * @return mixed
     */
    public function getCodigoRecaudoPk()
    {
        return $this->codigoRecaudoPk;
    }

    /**
     * @param mixed $codigoRecaudoPk
     */
    public function setCodigoRecaudoPk($codigoRecaudoPk): void
    {
        $this->codigoRecaudoPk = $codigoRecaudoPk;
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
    public function getCodigoEntidadFk()
    {
        return $this->codigoEntidadFk;
    }

    /**
     * @param mixed $codigoEntidadFk
     */
    public function setCodigoEntidadFk($codigoEntidadFk): void
    {
        $this->codigoEntidadFk = $codigoEntidadFk;
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
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * @param mixed $fechaPago
     */
    public function setFechaPago($fechaPago): void
    {
        $this->fechaPago = $fechaPago;
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
    public function getReciboCaja()
    {
        return $this->reciboCaja;
    }

    /**
     * @param mixed $reciboCaja
     */
    public function setReciboCaja($reciboCaja): void
    {
        $this->reciboCaja = $reciboCaja;
    }

    /**
     * @return mixed
     */
    public function getValorReciboCaja()
    {
        return $this->ValorReciboCaja;
    }

    /**
     * @param mixed $ValorReciboCaja
     */
    public function setValorReciboCaja($ValorReciboCaja): void
    {
        $this->ValorReciboCaja = $ValorReciboCaja;
    }

    /**
     * @return mixed
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * @param mixed $estadoCerrado
     */
    public function setEstadoCerrado($estadoCerrado): void
    {
        $this->estadoCerrado = $estadoCerrado;
    }

    /**
     * @return mixed
     */
    public function getVrTotalEntidad()
    {
        return $this->vrTotalEntidad;
    }

    /**
     * @param mixed $vrTotalEntidad
     */
    public function setVrTotalEntidad($vrTotalEntidad): void
    {
        $this->vrTotalEntidad = $vrTotalEntidad;
    }
}
