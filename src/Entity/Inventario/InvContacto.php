<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvContactoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvContacto
{
    public $infoLog = [
        "primaryKey" => "codigoContactoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="codigo_contacto_pk",type="integer")
     */
    private $codigoContactoPk;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer" ,nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=80)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=150, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="cargo", type="string", length=150, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */
    private $celular;

    /**
     * @ORM\Column(name="area", type="string", length=100, nullable=true)
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="InvTercero", inversedBy="contactosTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento",mappedBy="contactoRel")
     */
    protected $movimientosContactoRel;

    /**
     * @ORM\OneToMany(targetEntity="InvCotizacion",mappedBy="contactoRel")
     */
    protected $cotizacionesContactoRel;

    /**
     * @return mixed
     */
    public function getCodigoContactoPk()
    {
        return $this->codigoContactoPk;
    }

    /**
     * @param mixed $codigoContactoPk
     */
    public function setCodigoContactoPk($codigoContactoPk): void
    {
        $this->codigoContactoPk = $codigoContactoPk;
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
    public function getMovimientosContactoRel()
    {
        return $this->movimientosContactoRel;
    }

    /**
     * @param mixed $movimientosContactoRel
     */
    public function setMovimientosContactoRel($movimientosContactoRel): void
    {
        $this->movimientosContactoRel = $movimientosContactoRel;
    }

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
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * @param mixed $cargo
     */
    public function setCargo($cargo): void
    {
        $this->cargo = $cargo;
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
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area): void
    {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * @param mixed $codigoTerceroFk
     */
    public function setCodigoTerceroFk($codigoTerceroFk): void
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
    }

    /**
     * @return mixed
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * @param mixed $terceroRel
     */
    public function setTerceroRel($terceroRel): void
    {
        $this->terceroRel = $terceroRel;
    }

    /**
     * @return mixed
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param mixed $celular
     */
    public function setCelular($celular): void
    {
        $this->celular = $celular;
    }

    /**
     * @return mixed
     */
    public function getCotizacionesContactoRel()
    {
        return $this->cotizacionesContactoRel;
    }

    /**
     * @param mixed $cotizacionesContactoRel
     */
    public function setCotizacionesContactoRel($cotizacionesContactoRel): void
    {
        $this->cotizacionesContactoRel = $cotizacionesContactoRel;
    }



}

