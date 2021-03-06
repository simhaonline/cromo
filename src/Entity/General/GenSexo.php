<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenSexoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenSexo
{
    public $infoLog = [
        "primaryKey" => "codigoSexoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sexo_pk", type="string", length=1, nullable=true)
     */
    private $codigoSexoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="sexoRel")
     */
    protected $rhuSolicitudesSexoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="sexoRel")
     */
    protected $rhuAspirantesSexoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="sexoRel")
     */
    protected $rhuSeleccionesSexoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="sexoRel")
     */
    protected $rhuEmpleadosSexoRel;

    /**
     * @return mixed
     */
    public function getCodigoSexoPk()
    {
        return $this->codigoSexoPk;
    }

    /**
     * @param mixed $codigoSexoPk
     */
    public function setCodigoSexoPk($codigoSexoPk): void
    {
        $this->codigoSexoPk = $codigoSexoPk;
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
    public function getRhuSolicitudSexoRel()
    {
        return $this->rhuSolicitudSexoRel;
    }

    /**
     * @param mixed $rhuSolicitudSexoRel
     */
    public function setRhuSolicitudSexoRel($rhuSolicitudSexoRel): void
    {
        $this->rhuSolicitudSexoRel = $rhuSolicitudSexoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuAspirantesSexoRel()
    {
        return $this->rhuAspirantesSexoRel;
    }

    /**
     * @param mixed $rhuAspirantesSexoRel
     */
    public function setRhuAspirantesSexoRel($rhuAspirantesSexoRel): void
    {
        $this->rhuAspirantesSexoRel = $rhuAspirantesSexoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuSeleccionSexoRel()
    {
        return $this->rhuSeleccionSexoRel;
    }

    /**
     * @param mixed $rhuSeleccionSexoRel
     */
    public function setRhuSeleccionSexoRel($rhuSeleccionSexoRel): void
    {
        $this->rhuSeleccionSexoRel = $rhuSeleccionSexoRel;
    }

    /**
     * @return mixed
     */
    public function getRhuEmpleadoSexoRel()
    {
        return $this->rhuEmpleadoSexoRel;
    }

    /**
     * @param mixed $rhuEmpleadoSexoRel
     */
    public function setRhuEmpleadoSexoRel($rhuEmpleadoSexoRel): void
    {
        $this->rhuEmpleadoSexoRel = $rhuEmpleadoSexoRel;
    }



}

