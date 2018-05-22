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
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="genCiudadRel")
     */
    protected $rhuAspirantesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="genCiudadExpedicionRel")
     */
    protected $rhuAspirantesCiudadExpedicionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="genCiudadNacimientoRel")
     */
    protected $rhuAspirantesCiudadNacimientoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="genCiudadRel")
     */
    protected $rhuSolicitudesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="genCiudadRel")
     */
    protected $rhuSeleccionCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="genCiudadExpedicionRel")
     */
    protected $rhuSeleccionCiudadExpedicionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="genCiudadNacimientoRel")
     */
    protected $rhuSeleccionCiudadNacimientoRel;

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

}

