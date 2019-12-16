<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurNovedadTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TurNovedadTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNovedadTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_turno_fk", type="string", length=5, nullable=true)
     */
    private $codigoTurnoFk;

    /**
     * @ORM\Column(name="es_incapacidad", type="boolean", nullable=true)
     */
    private $estadoIncapacidad = false;

    /**
     * @ORM\Column(name="es_licencia", type="boolean", nullable=true)
     */
    private $estadoLicencia = false;

    /**
     * @ORM\Column(name="es_ingreso", type="boolean", nullable=true)
     */
    private $estadoIngreso = false;

    /**
     * @ORM\Column(name="es_retiro", type="boolean", nullable=true)
     */
    private $estadoRetiro = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurTurno", inversedBy="novedadesTiposTurnoRel")
     * @ORM\JoinColumn(name="codigo_turno_fk", referencedColumnName="codigo_turno_pk")
     */
    protected $turnoRel;

    /**
     * @ORM\OneToMany(targetEntity="TurNovedad", mappedBy="novedadTipoRel")
     */
    protected $novedadesNovedadTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoNovedadTipoPk()
    {
        return $this->codigoNovedadTipoPk;
    }

    /**
     * @param mixed $codigoNovedadTipoPk
     */
    public function setCodigoNovedadTipoPk($codigoNovedadTipoPk): void
    {
        $this->codigoNovedadTipoPk = $codigoNovedadTipoPk;
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
    public function getCodigoTurnoFk()
    {
        return $this->codigoTurnoFk;
    }

    /**
     * @param mixed $codigoTurnoFk
     */
    public function setCodigoTurnoFk($codigoTurnoFk): void
    {
        $this->codigoTurnoFk = $codigoTurnoFk;
    }

    /**
     * @return bool
     */
    public function getEstadoIncapacidad()
    {
        return $this->estadoIncapacidad;
    }

    /**
     * @param bool $estadoIncapacidad
     */
    public function setEstadoIncapacidad(bool $estadoIncapacidad): void
    {
        $this->estadoIncapacidad = $estadoIncapacidad;
    }

    /**
     * @return bool
     */
    public function getEstadoLicencia()
    {
        return $this->estadoLicencia;
    }

    /**
     * @param bool $estadoLicencia
     */
    public function setEstadoLicencia(bool $estadoLicencia): void
    {
        $this->estadoLicencia = $estadoLicencia;
    }

    /**
     * @return bool
     */
    public function getEstadoIngreso()
    {
        return $this->estadoIngreso;
    }

    /**
     * @param bool $estadoIngreso
     */
    public function setEstadoIngreso(bool $estadoIngreso): void
    {
        $this->estadoIngreso = $estadoIngreso;
    }

    /**
     * @return bool
     */
    public function getEstadoRetiro()
    {
        return $this->estadoRetiro;
    }

    /**
     * @param bool $estadoRetiro
     */
    public function setEstadoRetiro(bool $estadoRetiro): void
    {
        $this->estadoRetiro = $estadoRetiro;
    }

    /**
     * @return mixed
     */
    public function getTurnoRel()
    {
        return $this->turnoRel;
    }

    /**
     * @param mixed $turnoRel
     */
    public function setTurnoRel($turnoRel): void
    {
        $this->turnoRel = $turnoRel;
    }

    /**
     * @return mixed
     */
    public function getNovedadesNovedadTipoRel()
    {
        return $this->novedadesNovedadTipoRel;
    }

    /**
     * @param mixed $novedadesNovedadTipoRel
     */
    public function setNovedadesNovedadTipoRel($novedadesNovedadTipoRel): void
    {
        $this->novedadesNovedadTipoRel = $novedadesNovedadTipoRel;
    }

}
