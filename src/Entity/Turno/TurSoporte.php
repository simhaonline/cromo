<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurSoporteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurSoporte
{
    public $infoLog = [
        "primaryKey" => "codigoSoportePk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePk;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="string", length=10)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="contrato_terminado", type="boolean", options={"default":false})
     */
    private $contratoTerminado = false;

    /**
     * @ORM\Column(name="dias", type="integer", nullable=true)
     */
    private $dias;

    /**
     * @ORM\Column(name="comentario", type="string", length=500, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */
    private $usuario;

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
     * @ORM\Column(name="dia31_solo_extra", type="boolean", options={"default":false})
     */
    private $dia31SoloExtra = false;

    /**
     * @ORM\Column(name="cargado_nomina", type="boolean", options={"default":false})
     */
    private $cargadoNomina = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuGrupo", inversedBy="soportesGrupoRel")
     * @ORM\JoinColumn(name="codigo_grupo_fk", referencedColumnName="codigo_grupo_pk")
     */
    protected $grupoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurSoporteContrato", mappedBy="soporteRel")
     */
    protected $soportesContratosSoporteRel;

    /**
     * @ORM\OneToMany(targetEntity="TurSoporteHora", mappedBy="soporteRel")
     */
    protected $soportesHorasSoporteRel;

    /**
     * @return mixed
     */
    public function getCodigoSoportePk()
    {
        return $this->codigoSoportePk;
    }

    /**
     * @param mixed $codigoSoportePk
     */
    public function setCodigoSoportePk($codigoSoportePk): void
    {
        $this->codigoSoportePk = $codigoSoportePk;
    }

    /**
     * @return mixed
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * @param mixed $fechaDesde
     */
    public function setFechaDesde($fechaDesde): void
    {
        $this->fechaDesde = $fechaDesde;
    }

    /**
     * @return mixed
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * @param mixed $fechaHasta
     */
    public function setFechaHasta($fechaHasta): void
    {
        $this->fechaHasta = $fechaHasta;
    }

    /**
     * @return mixed
     */
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk($codigoGrupoFk): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
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
    public function getGrupoRel()
    {
        return $this->grupoRel;
    }

    /**
     * @param mixed $grupoRel
     */
    public function setGrupoRel($grupoRel): void
    {
        $this->grupoRel = $grupoRel;
    }

    /**
     * @return mixed
     */
    public function getSoportesContratosSoporteRel()
    {
        return $this->soportesContratosSoporteRel;
    }

    /**
     * @param mixed $soportesContratosSoporteRel
     */
    public function setSoportesContratosSoporteRel($soportesContratosSoporteRel): void
    {
        $this->soportesContratosSoporteRel = $soportesContratosSoporteRel;
    }

    /**
     * @return mixed
     */
    public function getContratoTerminado()
    {
        return $this->contratoTerminado;
    }

    /**
     * @param mixed $contratoTerminado
     */
    public function setContratoTerminado($contratoTerminado): void
    {
        $this->contratoTerminado = $contratoTerminado;
    }

    /**
     * @return mixed
     */
    public function getSoportesHorasSoporteRel()
    {
        return $this->soportesHorasSoporteRel;
    }

    /**
     * @param mixed $soportesHorasSoporteRel
     */
    public function setSoportesHorasSoporteRel($soportesHorasSoporteRel): void
    {
        $this->soportesHorasSoporteRel = $soportesHorasSoporteRel;
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
    public function getDia31SoloExtra()
    {
        return $this->dia31SoloExtra;
    }

    /**
     * @param mixed $dia31SoloExtra
     */
    public function setDia31SoloExtra($dia31SoloExtra): void
    {
        $this->dia31SoloExtra = $dia31SoloExtra;
    }

    /**
     * @return mixed
     */
    public function getCargadoNomina()
    {
        return $this->cargadoNomina;
    }

    /**
     * @param mixed $cargadoNomina
     */
    public function setCargadoNomina($cargadoNomina): void
    {
        $this->cargadoNomina = $cargadoNomina;
    }



}

