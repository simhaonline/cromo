<?php


namespace App\Entity\Inventario;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Inventario\InvCostoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class InvCosto
{
    public $infoLog = [
        "primaryKey" => "codigoCostoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoCostoPk;

    /**
     * @ORM\Column(name="codigo_costo_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoCostoTipoFk;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes;

    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="comentario", type="string", length=500, nullable=true)
     * @Assert\Length(max=500, maxMessage="El campo no puede contener mas de 500 caracteres")
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inventario\InvCostoTipo", inversedBy="costosCostoTipoRel")
     * @ORM\JoinColumn(name="codigo_costo_tipo_fk", referencedColumnName="codigo_costo_tipo_pk")
     */
    protected $costoTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="InvTercero", inversedBy="costosTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @return mixed
     */
    public function getCodigoCostoPk()
    {
        return $this->codigoCostoPk;
    }

    /**
     * @param mixed $codigoCostoPk
     */
    public function setCodigoCostoPk( $codigoCostoPk ): void
    {
        $this->codigoCostoPk = $codigoCostoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCostoTipoFk()
    {
        return $this->codigoCostoTipoFk;
    }

    /**
     * @param mixed $codigoCostoTipoFk
     */
    public function setCodigoCostoTipoFk( $codigoCostoTipoFk ): void
    {
        $this->codigoCostoTipoFk = $codigoCostoTipoFk;
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
    public function setCodigoTerceroFk( $codigoTerceroFk ): void
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
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
    public function setNumero( $numero ): void
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * @param mixed $anio
     */
    public function setAnio( $anio ): void
    {
        $this->anio = $anio;
    }

    /**
     * @return mixed
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param mixed $mes
     */
    public function setMes( $mes ): void
    {
        $this->mes = $mes;
    }

    /**
     * @return mixed
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * @param mixed $vrCosto
     */
    public function setVrCosto( $vrCosto ): void
    {
        $this->vrCosto = $vrCosto;
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
    public function setEstadoAutorizado( $estadoAutorizado ): void
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
    public function setEstadoAprobado( $estadoAprobado ): void
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
    public function setEstadoAnulado( $estadoAnulado ): void
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
    public function setEstadoContabilizado( $estadoContabilizado ): void
    {
        $this->estadoContabilizado = $estadoContabilizado;
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
    public function setUsuario( $usuario ): void
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
    public function setComentario( $comentario ): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return mixed
     */
    public function getCostoTipoRel()
    {
        return $this->costoTipoRel;
    }

    /**
     * @param mixed $costoTipoRel
     */
    public function setCostoTipoRel( $costoTipoRel ): void
    {
        $this->costoTipoRel = $costoTipoRel;
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
    public function setTerceroRel( $terceroRel ): void
    {
        $this->terceroRel = $terceroRel;
    }



}

