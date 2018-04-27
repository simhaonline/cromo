<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarNotaCreditoRepository")
 */
class CarNotaCredito
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_nota_credito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaCreditoPk;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_nota_credito_concepto_fk", type="integer", nullable=true)
     */
    private $codigoNotaCreditoConceptoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="integer", nullable=true)
     */
    private $codigoCuentaFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0;

    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */
    private $fechaPago;

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
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */
    private $estadoAutorizado = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="CarCliente", inversedBy="notasCreditosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\OneToMany(targetEntity="CarNotaCreditoDetalle", mappedBy="notaCreditoRel")
     */
    protected $notasCreditosDetallesNotaCreditoRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarNotaCredito", inversedBy="notasCreditosDetallesNotaCreditoRel")
     * @ORM\JoinColumn(name="codigo_nota_credito_fk", referencedColumnName="codigo_nota_credito_pk")
     */
    protected $notaCreditoRel;

    /**
     * @return mixed
     */
    public function getCodigoNotaCreditoPk()
    {
        return $this->codigoNotaCreditoPk;
    }

    /**
     * @param mixed $codigoNotaCreditoPk
     */
    public function setCodigoNotaCreditoPk($codigoNotaCreditoPk): void
    {
        $this->codigoNotaCreditoPk = $codigoNotaCreditoPk;
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
    public function getCodigoNotaCreditoConceptoFk()
    {
        return $this->codigoNotaCreditoConceptoFk;
    }

    /**
     * @param mixed $codigoNotaCreditoConceptoFk
     */
    public function setCodigoNotaCreditoConceptoFk($codigoNotaCreditoConceptoFk): void
    {
        $this->codigoNotaCreditoConceptoFk = $codigoNotaCreditoConceptoFk;
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
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor): void
    {
        $this->valor = $valor;
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
    public function getNotasCreditosDetallesNotaCreditoRel()
    {
        return $this->notasCreditosDetallesNotaCreditoRel;
    }

    /**
     * @param mixed $notasCreditosDetallesNotaCreditoRel
     */
    public function setNotasCreditosDetallesNotaCreditoRel($notasCreditosDetallesNotaCreditoRel): void
    {
        $this->notasCreditosDetallesNotaCreditoRel = $notasCreditosDetallesNotaCreditoRel;
    }

    /**
     * @return mixed
     */
    public function getNotaCreditoRel()
    {
        return $this->notaCreditoRel;
    }

    /**
     * @param mixed $notaCreditoRel
     */
    public function setNotaCreditoRel($notaCreditoRel): void
    {
        $this->notaCreditoRel = $notaCreditoRel;
    }

}
