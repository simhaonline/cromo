<?php


namespace App\Entity\Contabilidad;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Contabilidad\CtbCuentaRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoCuentaPk"},message="Ya existe el codigo de cuenta")
 */
class CtbCuenta
{
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
     * @ORM\Column(name="permite_movimiento", type="boolean", nullable=true, options={"default" : 0})
     */
    private $permiteMovimiento = false;

    /**
     * @ORM\Column(name="exige_tercero", type="boolean", nullable=true, options={"default" : 0})
     */
    private $exigeTercero = false;

    /**
     * @ORM\Column(name="exige_centro_costo", type="boolean", nullable=true, options={"default" : 0})
     */
    private $exigeCentroCosto = false;

    /**
     * @ORM\Column(name="exige_base", type="boolean", nullable=true, options={"default" : 0})
     */
    private $exigeBase = false;

    /**
     * @ORM\Column(name="porcentaje_base_retencion", type="float", nullable=true)
     */
    private $porcentajeBaseRetencion = 0;

    /**
     * @ORM\Column(name="nivel", type="integer", nullable=true, options={"default" : 1})
     */
    private $nivel = 1;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contabilidad\CtbRegistro", mappedBy="cuentaRel")
     */
    protected $ctbRegistrosCuentaRel;

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
    public function getCtbRegistrosCuentaRel()
    {
        return $this->ctbRegistrosCuentaRel;
    }

    /**
     * @param mixed $ctbRegistrosCuentaRel
     */
    public function setCtbRegistrosCuentaRel($ctbRegistrosCuentaRel): void
    {
        $this->ctbRegistrosCuentaRel = $ctbRegistrosCuentaRel;
    }


}

