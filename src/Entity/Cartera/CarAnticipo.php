<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarAnticipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarAnticipo
{
    public $infoLog = [
        "primaryKey" => "codigoAnticipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_anticipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoAnticipoPk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=10, nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */
    private $codigoAsesorFk;

    /**
     * @ORM\Column(name="codigo_anticipo_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoAnticipoTipoFk;

    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="soporte", type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max=30,
     *     maxMessage="El campo no puede tener mas de 20 caracteres"
     * )
     */
    private $soporte;

    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */
    private $fechaPago;

    /**
     * @ORM\Column(name="vr_pago", type="float")
     */
    private $vrPago = 0;

    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */
    private $estadoImpreso = 0;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */
    private $estadoAnulado = 0;

    /**
     * @ORM\Column(name="estado_exportado", type="boolean")
     */
    private $estadoExportado = 0;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */
    private $estadoAprobado = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */
    private $estadoContabilizado = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="CarCliente", inversedBy="anticiposClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarAnticipoTipo", inversedBy="anticiposAnticipoTipoRel")
     * @ORM\JoinColumn(name="codigo_anticipo_tipo_fk", referencedColumnName="codigo_anticipo_tipo_pk")
     */
    protected $anticipoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCuenta", inversedBy="anticiposCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenAsesor", inversedBy="anticipoAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;

    /**
     * @ORM\OneToMany(targetEntity="CarAnticipoDetalle", mappedBy="anticipoRel")
     */
    protected $anticiposDetallesRel;

    /**
     * @return mixed
     */
    public function getCodigoAnticipoPk()
    {
        return $this->codigoAnticipoPk;
    }

    /**
     * @param mixed $codigoAnticipoPk
     */
    public function setCodigoAnticipoPk($codigoAnticipoPk): void
    {
        $this->codigoAnticipoPk = $codigoAnticipoPk;
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
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * @param mixed $codigoCuentaFk
     */
    public function setCodigoCuentaFk($codigoCuentaFk): void
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
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
    public function getCodigoAnticipoTipoFk()
    {
        return $this->codigoAnticipoTipoFk;
    }

    /**
     * @param mixed $codigoAnticipoTipoFk
     */
    public function setCodigoAnticipoTipoFk($codigoAnticipoTipoFk): void
    {
        $this->codigoAnticipoTipoFk = $codigoAnticipoTipoFk;
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
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * @param mixed $vrPago
     */
    public function setVrPago($vrPago): void
    {
        $this->vrPago = $vrPago;
    }

    /**
     * @return mixed
     */
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * @param mixed $estadoImpreso
     */
    public function setEstadoImpreso($estadoImpreso): void
    {
        $this->estadoImpreso = $estadoImpreso;
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
    public function getEstadoExportado()
    {
        return $this->estadoExportado;
    }

    /**
     * @param mixed $estadoExportado
     */
    public function setEstadoExportado($estadoExportado): void
    {
        $this->estadoExportado = $estadoExportado;
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
    public function getAnticipoTipoRel()
    {
        return $this->anticipoTipoRel;
    }

    /**
     * @param mixed $anticipoTipoRel
     */
    public function setAnticipoTipoRel($anticipoTipoRel): void
    {
        $this->anticipoTipoRel = $anticipoTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * @param mixed $cuentaRel
     */
    public function setCuentaRel($cuentaRel): void
    {
        $this->cuentaRel = $cuentaRel;
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
    public function getAnticiposDetallesRel()
    {
        return $this->anticiposDetallesRel;
    }

    /**
     * @param mixed $anticiposDetallesRel
     */
    public function setAnticiposDetallesRel($anticiposDetallesRel): void
    {
        $this->anticiposDetallesRel = $anticiposDetallesRel;
    }



}
