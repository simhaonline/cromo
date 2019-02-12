<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarCuentaCobrarTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarCuentaCobrarTipo
{
    public $infoLog = [
        "primaryKey" => "codigoCuentaCobrarTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, nullable=false, unique=true)
     */
    private $codigoCuentaCobrarTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="saldo_inicial", type="boolean", options={"default":false})
     */
    private $saldoInicial;

    /**
     * @ORM\Column(name="prefijo", type="string", length=5, nullable=true)
     */
    private $prefijo;

    /**
     * @ORM\Column(name="codigo_cuenta_cliente_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaClienteFk;

    /**
     * @ORM\Column(name="codigo_cuenta_aplicacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaAplicacionFk;

    /**
     * @ORM\Column(name="permite_recibo_masivo", type="boolean", nullable=true, options={"default" : false})
     */
    private $permiteReciboMasivo = false;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarCuentaCobrar", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarCuentaCobrarTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="CarReciboDetalle", mappedBy="cuentaCobrarTipoRel")
     */
    protected $recibosDetallesCuentaCobrarTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="CarReciboDetalle", mappedBy="cuentaCobrarAplicacionTipoRel")
     */
    protected $recibosDetallesCuentaCobrarAplicacionTipoRel;

    /**
     * @ORM\OneToMany(targetEntity="CarAnticipoTipo", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentaCobrarTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarTipoPk()
    {
        return $this->codigoCuentaCobrarTipoPk;
    }

    /**
     * @param mixed $codigoCuentaCobrarTipoPk
     */
    public function setCodigoCuentaCobrarTipoPk( $codigoCuentaCobrarTipoPk ): void
    {
        $this->codigoCuentaCobrarTipoPk = $codigoCuentaCobrarTipoPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre( $nombre ): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * @param mixed $operacion
     */
    public function setOperacion( $operacion ): void
    {
        $this->operacion = $operacion;
    }

    /**
     * @return mixed
     */
    public function getSaldoInicial()
    {
        return $this->saldoInicial;
    }

    /**
     * @param mixed $saldoInicial
     */
    public function setSaldoInicial( $saldoInicial ): void
    {
        $this->saldoInicial = $saldoInicial;
    }

    /**
     * @return mixed
     */
    public function getPrefijo()
    {
        return $this->prefijo;
    }

    /**
     * @param mixed $prefijo
     */
    public function setPrefijo( $prefijo ): void
    {
        $this->prefijo = $prefijo;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaClienteFk()
    {
        return $this->codigoCuentaClienteFk;
    }

    /**
     * @param mixed $codigoCuentaClienteFk
     */
    public function setCodigoCuentaClienteFk( $codigoCuentaClienteFk ): void
    {
        $this->codigoCuentaClienteFk = $codigoCuentaClienteFk;
    }

    /**
     * @return mixed
     */
    public function getPermiteReciboMasivo()
    {
        return $this->permiteReciboMasivo;
    }

    /**
     * @param mixed $permiteReciboMasivo
     */
    public function setPermiteReciboMasivo( $permiteReciboMasivo ): void
    {
        $this->permiteReciboMasivo = $permiteReciboMasivo;
    }

    /**
     * @return mixed
     */
    public function getCuentasCobrarCuentaCobrarTipoRel()
    {
        return $this->cuentasCobrarCuentaCobrarTipoRel;
    }

    /**
     * @param mixed $cuentasCobrarCuentaCobrarTipoRel
     */
    public function setCuentasCobrarCuentaCobrarTipoRel( $cuentasCobrarCuentaCobrarTipoRel ): void
    {
        $this->cuentasCobrarCuentaCobrarTipoRel = $cuentasCobrarCuentaCobrarTipoRel;
    }

    /**
     * @return mixed
     */
    public function getRecibosDetallesCuentaCobrarTipoRel()
    {
        return $this->recibosDetallesCuentaCobrarTipoRel;
    }

    /**
     * @param mixed $recibosDetallesCuentaCobrarTipoRel
     */
    public function setRecibosDetallesCuentaCobrarTipoRel( $recibosDetallesCuentaCobrarTipoRel ): void
    {
        $this->recibosDetallesCuentaCobrarTipoRel = $recibosDetallesCuentaCobrarTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarTipoRel()
    {
        return $this->cuentaCobrarTipoRel;
    }

    /**
     * @param mixed $cuentaCobrarTipoRel
     */
    public function setCuentaCobrarTipoRel( $cuentaCobrarTipoRel ): void
    {
        $this->cuentaCobrarTipoRel = $cuentaCobrarTipoRel;
    }

    /**
     * @return mixed
     */
    public function getRecibosDetallesCuentaCobrarAplicacionTipoRel()
    {
        return $this->recibosDetallesCuentaCobrarAplicacionTipoRel;
    }

    /**
     * @param mixed $recibosDetallesCuentaCobrarAplicacionTipoRel
     */
    public function setRecibosDetallesCuentaCobrarAplicacionTipoRel($recibosDetallesCuentaCobrarAplicacionTipoRel): void
    {
        $this->recibosDetallesCuentaCobrarAplicacionTipoRel = $recibosDetallesCuentaCobrarAplicacionTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaAplicacionFk()
    {
        return $this->codigoCuentaAplicacionFk;
    }

    /**
     * @param mixed $codigoCuentaAplicacionFk
     */
    public function setCodigoCuentaAplicacionFk($codigoCuentaAplicacionFk): void
    {
        $this->codigoCuentaAplicacionFk = $codigoCuentaAplicacionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * @param mixed $codigoComprobanteFk
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk): void
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;
    }



}
