<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenCiudadRepository")
 */
class GenCiudad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_ciudad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCiudadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_departamento_fk", type="integer")
     */
    private $codigoDepartamentoFk;

    /**
     * @ORM\Column(name="codigo_dane", type="string", length=5)
     */
    private $codigoDane;

    /**
     * @ORM\ManyToOne(targetEntity="GenDepartamento", inversedBy="ciudadesRel")
     * @ORM\JoinColumn(name="codigo_departamento_fk", referencedColumnName="codigo_departamento_pk")
     */
    protected $departamentoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarCliente", mappedBy="ciudadRel")
     */
    protected $carClientesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contabilidad\CtbTercero", mappedBy="ciudadRel")
     */
    protected $ctbTercerosCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="ciudadRel")
     */
    protected $rhuAspirantesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="ciudadExpedicionRel")
     */
    protected $rhuAspirantesCiudadExpedicionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="ciudadNacimientoRel")
     */
    protected $rhuAspirantesCiudadNacimientoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="ciudadRel")
     */
    protected $rhuSolicitudesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="ciudadRel")
     */
    protected $rhuSeleccionCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="ciudadExpedicionRel")
     */
    protected $rhuSeleccionCiudadExpedicionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="ciudadNacimientoRel")
     */
    protected $rhuSeleccionCiudadNacimientoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="ciudadRel")
     */
    protected $rhuEmpleadoCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="ciudadExpedicionRel")
     */
    protected $rhuEmpleadoCiudadExpedicionRel;

    /**
     * @return mixed
     */
    public function getCodigoCiudadPk()
    {
        return $this->codigoCiudadPk;
    }

    /**
     * @param mixed $codigoCiudadPk
     */
    public function setCodigoCiudadPk($codigoCiudadPk): void
    {
        $this->codigoCiudadPk = $codigoCiudadPk;
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
    public function getCodigoDepartamentoFk()
    {
        return $this->codigoDepartamentoFk;
    }

    /**
     * @param mixed $codigoDepartamentoFk
     */
    public function setCodigoDepartamentoFk($codigoDepartamentoFk): void
    {
        $this->codigoDepartamentoFk = $codigoDepartamentoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDane()
    {
        return $this->codigoDane;
    }

    /**
     * @param mixed $codigoDane
     */
    public function setCodigoDane($codigoDane): void
    {
        $this->codigoDane = $codigoDane;
    }

    /**
     * @return mixed
     */
    public function getDepartamentoRel()
    {
        return $this->departamentoRel;
    }

    /**
     * @param mixed $departamentoRel
     */
    public function setDepartamentoRel($departamentoRel): void
    {
        $this->departamentoRel = $departamentoRel;
    }

    /**
     * @return mixed
     */
    public function getCarClientesCiudadRel()
    {
        return $this->carClientesCiudadRel;
    }

    /**
     * @param mixed $carClientesCiudadRel
     */
    public function setCarClientesCiudadRel($carClientesCiudadRel): void
    {
        $this->carClientesCiudadRel = $carClientesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getCtbTercerosCiudadRel()
    {
        return $this->ctbTercerosCiudadRel;
    }

    /**
     * @param mixed $ctbTercerosCiudadRel
     */
    public function setCtbTercerosCiudadRel($ctbTercerosCiudadRel): void
    {
        $this->ctbTercerosCiudadRel = $ctbTercerosCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuAspirantesCiudadRel()
    {
        return $this->rhuAspirantesCiudadRel;
    }

    /**
     * @param mixed $rhuAspirantesCiudadRel
     */
    public function setRhuAspirantesCiudadRel($rhuAspirantesCiudadRel): void
    {
        $this->rhuAspirantesCiudadRel = $rhuAspirantesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuAspirantesCiudadExpedicionRel()
    {
        return $this->rhuAspirantesCiudadExpedicionRel;
    }

    /**
     * @param mixed $rhuAspirantesCiudadExpedicionRel
     */
    public function setRhuAspirantesCiudadExpedicionRel($rhuAspirantesCiudadExpedicionRel): void
    {
        $this->rhuAspirantesCiudadExpedicionRel = $rhuAspirantesCiudadExpedicionRel;
    }

    /**
     * @return mixed
     */
    public function getRhuAspirantesCiudadNacimientoRel()
    {
        return $this->rhuAspirantesCiudadNacimientoRel;
    }

    /**
     * @param mixed $rhuAspirantesCiudadNacimientoRel
     */
    public function setRhuAspirantesCiudadNacimientoRel($rhuAspirantesCiudadNacimientoRel): void
    {
        $this->rhuAspirantesCiudadNacimientoRel = $rhuAspirantesCiudadNacimientoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSolicitudesCiudadRel()
    {
        return $this->rhuSolicitudesCiudadRel;
    }

    /**
     * @param mixed $rhuSolicitudesCiudadRel
     */
    public function setRhuSolicitudesCiudadRel($rhuSolicitudesCiudadRel): void
    {
        $this->rhuSolicitudesCiudadRel = $rhuSolicitudesCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionCiudadRel()
    {
        return $this->rhuSeleccionCiudadRel;
    }

    /**
     * @param mixed $rhuSeleccionCiudadRel
     */
    public function setRhuSeleccionCiudadRel($rhuSeleccionCiudadRel): void
    {
        $this->rhuSeleccionCiudadRel = $rhuSeleccionCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionCiudadExpedicionRel()
    {
        return $this->rhuSeleccionCiudadExpedicionRel;
    }

    /**
     * @param mixed $rhuSeleccionCiudadExpedicionRel
     */
    public function setRhuSeleccionCiudadExpedicionRel($rhuSeleccionCiudadExpedicionRel): void
    {
        $this->rhuSeleccionCiudadExpedicionRel = $rhuSeleccionCiudadExpedicionRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionCiudadNacimientoRel()
    {
        return $this->rhuSeleccionCiudadNacimientoRel;
    }

    /**
     * @param mixed $rhuSeleccionCiudadNacimientoRel
     */
    public function setRhuSeleccionCiudadNacimientoRel($rhuSeleccionCiudadNacimientoRel): void
    {
        $this->rhuSeleccionCiudadNacimientoRel = $rhuSeleccionCiudadNacimientoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuEmpleadoCiudadRel()
    {
        return $this->rhuEmpleadoCiudadRel;
    }

    /**
     * @param mixed $rhuEmpleadoCiudadRel
     */
    public function setRhuEmpleadoCiudadRel($rhuEmpleadoCiudadRel): void
    {
        $this->rhuEmpleadoCiudadRel = $rhuEmpleadoCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRhuEmpleadoCiudadExpedicionRel()
    {
        return $this->rhuEmpleadoCiudadExpedicionRel;
    }

    /**
     * @param mixed $rhuEmpleadoCiudadExpedicionRel
     */
    public function setRhuEmpleadoCiudadExpedicionRel($rhuEmpleadoCiudadExpedicionRel): void
    {
        $this->rhuEmpleadoCiudadExpedicionRel = $rhuEmpleadoCiudadExpedicionRel;
    }


}

