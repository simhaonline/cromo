<?php


namespace App\Entity\Financiero;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Financiero\FinCuentaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @DoctrineAssert\UniqueEntity(fields={"codigoCuentaPk"},message="Ya existe el codigo de cuenta")
 */
class FinCuenta
{
    public $infoLog = [
        "primaryKey" => "codigoCuentaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_pk",type="string", length=20)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     min = "1",
     *     max = 20,
     *     minMessage="El campo no puede contener menos de {{ limit }} caracteres",
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     *
     */
    private $codigoCuentaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=false)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     min = "1",
     *     max = 120,
     *     minMessage="El campo no puede contener menos de {{ limit }} caracteres",
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_cuenta_padre_fk", type="string", length=20, nullable=true)
     */
    private $codigo_cuenta_padre_fk;

    /**
     * @ORM\Column(name="permite_movimiento", type="boolean", nullable=true, options={"default":false})
     */
    private $permiteMovimiento = false;

    /**
     * @ORM\Column(name="exige_tercero", type="boolean", nullable=true, options={"default":false})
     */
    private $exigeTercero = false;

    /**
     * @ORM\Column(name="exige_centro_costo", type="boolean", nullable=true, options={"default":false})
     */
    private $exigeCentroCosto = false;

    /**
     * @ORM\Column(name="exige_base", type="boolean", nullable=true, options={"default":false})
     */
    private $exigeBase = false;

    /**
     * @ORM\Column(name="exige_documento_referencia", type="boolean", nullable=true, options={"default":false})
     */
    private $exigeDocumentoReferencia = false;

    /**
     * @ORM\Column(name="porcentaje_base_retencion", type="float", nullable=true)
     */
    private $porcentajeBaseRetencion = 0;

    /**
     * @ORM\Column(name="nivel", type="integer", nullable=true, options={"default" : 1})
     */
    private $nivel = 1;

    /**
     * @ORM\Column(name="clase", type="string", length=1, nullable=true)
     */
    private $clase;

    /**
     * @ORM\Column(name="grupo", type="string", length=2, nullable=true)
     */
    private $grupo;

    /**
     * @ORM\Column(name="cuenta", type="string", length=4, nullable=true)
     */
    private $cuenta;

    /**
     * @ORM\Column(name="subcuenta", type="string", length=6, nullable=true)
     */
    private $subcuenta;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Financiero\FinRegistro", mappedBy="cuentaRel")
     */
    protected $registrosCuentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Financiero\FinSaldo", mappedBy="cuentaRel")
     */
    protected $saldosCuentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Financiero\FinAsientoDetalle", mappedBy="cuentaRel")
     */
    protected $asientosDetallesCuentaRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tesoreria\TesEgresoDetalle", mappedBy="cuentaRel")
     */
    protected $egresosDetallesCuentaRel;

    /**
     * @return mixed
     */
    public function getCodigoCuentaPk()
    {
        return $this->codigoCuentaPk;
    }

    /**
     * @param mixed $codigoCuentaPk
     */
    public function setCodigoCuentaPk($codigoCuentaPk): void
    {
        $this->codigoCuentaPk = $codigoCuentaPk;
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
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaPadreFk()
    {
        return $this->codigo_cuenta_padre_fk;
    }

    /**
     * @param mixed $codigo_cuenta_padre_fk
     */
    public function setCodigoCuentaPadreFk($codigo_cuenta_padre_fk): void
    {
        $this->codigo_cuenta_padre_fk = $codigo_cuenta_padre_fk;
    }

    /**
     * @return mixed
     */
    public function getPermiteMovimiento()
    {
        return $this->permiteMovimiento;
    }

    /**
     * @param mixed $permiteMovimiento
     */
    public function setPermiteMovimiento($permiteMovimiento): void
    {
        $this->permiteMovimiento = $permiteMovimiento;
    }

    /**
     * @return mixed
     */
    public function getExigeTercero()
    {
        return $this->exigeTercero;
    }

    /**
     * @param mixed $exigeTercero
     */
    public function setExigeTercero($exigeTercero): void
    {
        $this->exigeTercero = $exigeTercero;
    }

    /**
     * @return mixed
     */
    public function getExigeCentroCosto()
    {
        return $this->exigeCentroCosto;
    }

    /**
     * @param mixed $exigeCentroCosto
     */
    public function setExigeCentroCosto($exigeCentroCosto): void
    {
        $this->exigeCentroCosto = $exigeCentroCosto;
    }

    /**
     * @return mixed
     */
    public function getExigeBase()
    {
        return $this->exigeBase;
    }

    /**
     * @param mixed $exigeBase
     */
    public function setExigeBase($exigeBase): void
    {
        $this->exigeBase = $exigeBase;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeBaseRetencion()
    {
        return $this->porcentajeBaseRetencion;
    }

    /**
     * @param mixed $porcentajeBaseRetencion
     */
    public function setPorcentajeBaseRetencion($porcentajeBaseRetencion): void
    {
        $this->porcentajeBaseRetencion = $porcentajeBaseRetencion;
    }

    /**
     * @return mixed
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * @param mixed $nivel
     */
    public function setNivel($nivel): void
    {
        $this->nivel = $nivel;
    }

    /**
     * @return mixed
     */
    public function getRegistrosCuentaRel()
    {
        return $this->registrosCuentaRel;
    }

    /**
     * @param mixed $registrosCuentaRel
     */
    public function setRegistrosCuentaRel($registrosCuentaRel): void
    {
        $this->registrosCuentaRel = $registrosCuentaRel;
    }

    /**
     * @return mixed
     */
    public function getAsientosDetallesCuentaRel()
    {
        return $this->asientosDetallesCuentaRel;
    }

    /**
     * @param mixed $asientosDetallesCuentaRel
     */
    public function setAsientosDetallesCuentaRel($asientosDetallesCuentaRel): void
    {
        $this->asientosDetallesCuentaRel = $asientosDetallesCuentaRel;
    }

    /**
     * @return mixed
     */
    public function getClase()
    {
        return $this->clase;
    }

    /**
     * @param mixed $clase
     */
    public function setClase($clase): void
    {
        $this->clase = $clase;
    }

    /**
     * @return mixed
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * @param mixed $grupo
     */
    public function setGrupo($grupo): void
    {
        $this->grupo = $grupo;
    }

    /**
     * @return mixed
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * @param mixed $cuenta
     */
    public function setCuenta($cuenta): void
    {
        $this->cuenta = $cuenta;
    }

    /**
     * @return mixed
     */
    public function getSubcuenta()
    {
        return $this->subcuenta;
    }

    /**
     * @param mixed $subcuenta
     */
    public function setSubcuenta($subcuenta): void
    {
        $this->subcuenta = $subcuenta;
    }

    /**
     * @return mixed
     */
    public function getSaldosCuentaRel()
    {
        return $this->saldosCuentaRel;
    }

    /**
     * @param mixed $saldosCuentaRel
     */
    public function setSaldosCuentaRel( $saldosCuentaRel ): void
    {
        $this->saldosCuentaRel = $saldosCuentaRel;
    }

    /**
     * @return mixed
     */
    public function getExigeDocumentoReferencia()
    {
        return $this->exigeDocumentoReferencia;
    }

    /**
     * @param mixed $exigeDocumentoReferencia
     */
    public function setExigeDocumentoReferencia($exigeDocumentoReferencia): void
    {
        $this->exigeDocumentoReferencia = $exigeDocumentoReferencia;
    }

    /**
     * @return mixed
     */
    public function getEgresosDetallesCuentaRel()
    {
        return $this->egresosDetallesCuentaRel;
    }

    /**
     * @param mixed $egresosDetallesCuentaRel
     */
    public function setEgresosDetallesCuentaRel($egresosDetallesCuentaRel): void
    {
        $this->egresosDetallesCuentaRel = $egresosDetallesCuentaRel;
    }



}

