<?php


namespace App\Entity\Crm;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Crm\CrmNegocioRepository")
 */

class CrmNegocio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="codigo_negocio_pk",type="integer")
     */
    private $codigoNegocioPk;

    /**
     * @ORM\Column(name="nombre" , type="string" , length=50)
     */
    private  $nombre;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_cierre", type="date", nullable=true )
     */
    private $fechaCierre;

    /**
     * @ORM\Column(name="fecha_negocio", type="date", nullable=true )
     */
    private $fechaNegocio;

    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=100, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="codigo_contacto_fk", type="integer", nullable=true)
     */
    private $codigoContactoFk;

    /**
     * @ORM\Column(name="codigo_fase_fk" , type="string" , length=10, nullable=true)
     */
    private  $codigoFaseFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Crm\CrmCliente", inversedBy="negociosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Crm\CrmContacto", inversedBy="negociosContactoRel")
     * @ORM\JoinColumn(name="codigo_contacto_fk", referencedColumnName="codigo_contacto_pk")
     */
    protected $contactoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Crm\CrmFase", inversedBy="negociosFaseRel")
     * @ORM\JoinColumn(name="codigo_fase_fk", referencedColumnName="codigo_fase_pk")
     */
    protected $faseRel;

    /**
     * @return mixed
     */
    public function getCodigoNegocioPk()
    {
        return $this->codigoNegocioPk;
    }

    /**
     * @param mixed $codigoNegocioPk
     */
    public function setCodigoNegocioPk($codigoNegocioPk): void
    {
        $this->codigoNegocioPk = $codigoNegocioPk;
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
    public function getFechaNegocio()
    {
        return $this->fechaNegocio;
    }

    /**
     * @param mixed $fechaNegocio
     */
    public function setFechaNegocio($fechaNegocio): void
    {
        $this->fechaNegocio = $fechaNegocio;
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
    public function getCodigoContactoFk()
    {
        return $this->codigoContactoFk;
    }

    /**
     * @param mixed $codigoContactoFk
     */
    public function setCodigoContactoFk($codigoContactoFk): void
    {
        $this->codigoContactoFk = $codigoContactoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFaseFk()
    {
        return $this->codigoFaseFk;
    }

    /**
     * @param mixed $codigoFaseFk
     */
    public function setCodigoFaseFk($codigoFaseFk): void
    {
        $this->codigoFaseFk = $codigoFaseFk;
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
    public function getContactoRel()
    {
        return $this->contactoRel;
    }

    /**
     * @param mixed $contactoRel
     */
    public function setContactoRel($contactoRel): void
    {
        $this->contactoRel = $contactoRel;
    }

    /**
     * @return mixed
     */
    public function getFaseRel()
    {
        return $this->faseRel;
    }

    /**
     * @param mixed $faseRel
     */
    public function setFaseRel($faseRel): void
    {
        $this->faseRel = $faseRel;
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
}