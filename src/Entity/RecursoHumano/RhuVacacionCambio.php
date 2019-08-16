<?php


namespace App\Entity\RecursoHumano;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuVacacionCambioRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuVacacionCambio
{
    public $infoLog = [
        "primaryKey" => "codigoVacacionCambioPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_vacacion_cambio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVacacionCambioPk;

    /**
     * @ORM\Column(name="codigo_vacacion_fk", type="integer", nullable=true)
     */
    private $codigoVacacionFk;

    /**
     * @ORM\Column(name="fecha_desde_disfrute", type="date")
     */
    private $fechaDesdeDisfrute;

    /**
     * @ORM\Column(name="fecha_hasta_disfrute", type="date")
     */
    private $fechaHastaDisfrute;

    /**
     * @ORM\Column(name="fecha_inicio_labor", type="date", nullable=true)
     */
    private $fechaInicioLabor;

    /**
     * @ORM\Column(name="dias", type="integer", nullable=true)
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\ManyToOne(targetEntity="RhuVacacion", inversedBy="vacacionesCambiosVacacionRel")
     * @ORM\JoinColumn(name="codigo_vacacion_fk", referencedColumnName="codigo_vacacion_pk")
     */
    protected $vacacionRel;

    /**
     * @return mixed
     */
    public function getCodigoVacacionCambioPk()
    {
        return $this->codigoVacacionCambioPk;
    }

    /**
     * @param mixed $codigoVacacionCambioPk
     */
    public function setCodigoVacacionCambioPk($codigoVacacionCambioPk): void
    {
        $this->codigoVacacionCambioPk = $codigoVacacionCambioPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoVacacionFk()
    {
        return $this->codigoVacacionFk;
    }

    /**
     * @param mixed $codigoVacacionFk
     */
    public function setCodigoVacacionFk($codigoVacacionFk): void
    {
        $this->codigoVacacionFk = $codigoVacacionFk;
    }

    /**
     * @return mixed
     */
    public function getFechaDesdeDisfrute()
    {
        return $this->fechaDesdeDisfrute;
    }

    /**
     * @param mixed $fechaDesdeDisfrute
     */
    public function setFechaDesdeDisfrute($fechaDesdeDisfrute): void
    {
        $this->fechaDesdeDisfrute = $fechaDesdeDisfrute;
    }

    /**
     * @return mixed
     */
    public function getFechaHastaDisfrute()
    {
        return $this->fechaHastaDisfrute;
    }

    /**
     * @param mixed $fechaHastaDisfrute
     */
    public function setFechaHastaDisfrute($fechaHastaDisfrute): void
    {
        $this->fechaHastaDisfrute = $fechaHastaDisfrute;
    }

    /**
     * @return mixed
     */
    public function getFechaInicioLabor()
    {
        return $this->fechaInicioLabor;
    }

    /**
     * @param mixed $fechaInicioLabor
     */
    public function setFechaInicioLabor($fechaInicioLabor): void
    {
        $this->fechaInicioLabor = $fechaInicioLabor;
    }

    /**
     * @return mixed
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * @param mixed $dias
     */
    public function setDias($dias): void
    {
        $this->dias = $dias;
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
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * @param mixed $codigoUsuario
     */
    public function setCodigoUsuario($codigoUsuario): void
    {
        $this->codigoUsuario = $codigoUsuario;
    }

    /**
     * @return mixed
     */
    public function getVacacionRel()
    {
        return $this->vacacionRel;
    }

    /**
     * @param mixed $vacacionRel
     */
    public function setVacacionRel($vacacionRel): void
    {
        $this->vacacionRel = $vacacionRel;
    }

}