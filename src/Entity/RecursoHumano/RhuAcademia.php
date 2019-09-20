<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAcademinaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAcademia
{
    public $infoLog = [
        "primaryKey" => "codigoAcademiaPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_academia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAcademiaPk;

    /**
     * @ORM\Column(name="nit", type="string", length=21, nullable=false, unique=true)
     */
    private $nit;

    /**
     * @ORM\Column(name="nombre", type="string", length=160, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="sede", type="string", length=60, nullable=true)
     */
    private $sede;

    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenCiudad",inversedBy="rhuAcademiaCiuidadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk",referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;

//    /**
//     * @ORM\OneToMany(targetEntity="RhuEmpleadoEstudio", mappedBy="academiaRel")
//     */
//    protected $empleadosEstudiosAcademiaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAcreditacion", mappedBy="academiaRel")
     */
    protected $acreditacionesAcademiaRel;

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
    public function getCodigoAcademiaPk()
    {
        return $this->codigoAcademiaPk;
    }

    /**
     * @param mixed $codigoAcademiaPk
     */
    public function setCodigoAcademiaPk($codigoAcademiaPk): void
    {
        $this->codigoAcademiaPk = $codigoAcademiaPk;
    }

    /**
     * @return mixed
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * @param mixed $nit
     */
    public function setNit($nit): void
    {
        $this->nit = $nit;
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
    public function getSede()
    {
        return $this->sede;
    }

    /**
     * @param mixed $sede
     */
    public function setSede($sede): void
    {
        $this->sede = $sede;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
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
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * @param mixed $codigoCiudadFk
     */
    public function setCodigoCiudadFk($codigoCiudadFk): void
    {
        $this->codigoCiudadFk = $codigoCiudadFk;
    }

    /**
     * @return mixed
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * @param mixed $ciudadRel
     */
    public function setCiudadRel($ciudadRel): void
    {
        $this->ciudadRel = $ciudadRel;
    }

    /**
     * @return mixed
     */
    public function getAcreditacionesAcademiaRel()
    {
        return $this->acreditacionesAcademiaRel;
    }

    /**
     * @param mixed $acreditacionesAcademiaRel
     */
    public function setAcreditacionesAcademiaRel($acreditacionesAcademiaRel): void
    {
        $this->acreditacionesAcademiaRel = $acreditacionesAcademiaRel;
    }


}