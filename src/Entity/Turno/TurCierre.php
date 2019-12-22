<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurCierreRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurCierre
{
    public $infoLog = [
        "primaryKey" => "codigoCierrePk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierrePk;

    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;

    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean", options={"default":false})
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default":false})
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean", options={"default":false})
     */
    private $estadoAnulado = false;

    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean", options={"default":false})
     */
    private $estadoContabilizado = false;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(name="comentario", type="string", length=500, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoEmpleado", mappedBy="cierreRel")
     */
    protected $costosEmpleadosCierreRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoEmpleadoServicio", mappedBy="cierreRel")
     */
    protected $costosEmpleadosServiciosCierreRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCostoServicio", mappedBy="cierreRel")
     */
    protected $costosServiciosCierreRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurDistribucion", mappedBy="cierreRel")
     */
    protected $distribucionesCierreRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurDistribucionEmpleado", mappedBy="cierreRel")
     */
    protected $distribucionesEmpleadosCierreRel;

    /**
     * @return mixed
     */
    public function getCodigoCierrePk()
    {
        return $this->codigoCierrePk;
    }

    /**
     * @param mixed $codigoCierrePk
     */
    public function setCodigoCierrePk($codigoCierrePk): void
    {
        $this->codigoCierrePk = $codigoCierrePk;
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
    public function setAnio($anio): void
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
    public function setMes($mes): void
    {
        $this->mes = $mes;
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
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario): void
    {
        $this->comentario = $comentario;
    }

    /**
     * @return mixed
     */
    public function getCostosEmpleadosCierreRel()
    {
        return $this->costosEmpleadosCierreRel;
    }

    /**
     * @param mixed $costosEmpleadosCierreRel
     */
    public function setCostosEmpleadosCierreRel($costosEmpleadosCierreRel): void
    {
        $this->costosEmpleadosCierreRel = $costosEmpleadosCierreRel;
    }

    /**
     * @return mixed
     */
    public function getCostosEmpleadosServiciosCierreRel()
    {
        return $this->costosEmpleadosServiciosCierreRel;
    }

    /**
     * @param mixed $costosEmpleadosServiciosCierreRel
     */
    public function setCostosEmpleadosServiciosCierreRel($costosEmpleadosServiciosCierreRel): void
    {
        $this->costosEmpleadosServiciosCierreRel = $costosEmpleadosServiciosCierreRel;
    }

    /**
     * @return mixed
     */
    public function getCostosServiciosCierreRel()
    {
        return $this->costosServiciosCierreRel;
    }

    /**
     * @param mixed $costosServiciosCierreRel
     */
    public function setCostosServiciosCierreRel($costosServiciosCierreRel): void
    {
        $this->costosServiciosCierreRel = $costosServiciosCierreRel;
    }

    /**
     * @return mixed
     */
    public function getDistribucionesCierreRel()
    {
        return $this->distribucionesCierreRel;
    }

    /**
     * @param mixed $distribucionesCierreRel
     */
    public function setDistribucionesCierreRel($distribucionesCierreRel): void
    {
        $this->distribucionesCierreRel = $distribucionesCierreRel;
    }

    /**
     * @return mixed
     */
    public function getDistribucionesEmpleadosCierreRel()
    {
        return $this->distribucionesEmpleadosCierreRel;
    }

    /**
     * @param mixed $distribucionesEmpleadosCierreRel
     */
    public function setDistribucionesEmpleadosCierreRel($distribucionesEmpleadosCierreRel): void
    {
        $this->distribucionesEmpleadosCierreRel = $distribucionesEmpleadosCierreRel;
    }



}

