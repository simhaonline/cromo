<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurContratoModalidadRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurContratoModalidad
{
    public $infoLog = [
        "primaryKey" => "codigoContratoModalidadPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_modalidad_pk", type="string", length=10)
     */
    private $codigoContratoModalidadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="abreviatura", type="string", length=10, nullable=true)
     */
    private $abreviatura;

    /**
     * @ORM\Column(name="tipo", type="integer")
     */
    private $tipo = 0;

    /**
     * @ORM\Column(name="porcentaje", type="float")
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="porcentaje_especial", type="float", nullable=true)
     */
    private $porcentajeEspecial = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="contratoModalidadRel")
     */
    protected $pedidosDetallesContratoModalidadRel;

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
    public function getCodigoContratoModalidadPk()
    {
        return $this->codigoContratoModalidadPk;
    }

    /**
     * @param mixed $codigoContratoModalidadPk
     */
    public function setCodigoContratoModalidadPk($codigoContratoModalidadPk): void
    {
        $this->codigoContratoModalidadPk = $codigoContratoModalidadPk;
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
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * @param mixed $abreviatura
     */
    public function setAbreviatura($abreviatura): void
    {
        $this->abreviatura = $abreviatura;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param mixed $porcentaje
     */
    public function setPorcentaje($porcentaje): void
    {
        $this->porcentaje = $porcentaje;
    }

    /**
     * @return mixed
     */
    public function getPorcentajeEspecial()
    {
        return $this->porcentajeEspecial;
    }

    /**
     * @param mixed $porcentajeEspecial
     */
    public function setPorcentajeEspecial($porcentajeEspecial): void
    {
        $this->porcentajeEspecial = $porcentajeEspecial;
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
    public function getPedidosDetallesContratoModalidadRel()
    {
        return $this->pedidosDetallesContratoModalidadRel;
    }

    /**
     * @param mixed $pedidosDetallesContratoModalidadRel
     */
    public function setPedidosDetallesContratoModalidadRel($pedidosDetallesContratoModalidadRel): void
    {
        $this->pedidosDetallesContratoModalidadRel = $pedidosDetallesContratoModalidadRel;
    }



}

