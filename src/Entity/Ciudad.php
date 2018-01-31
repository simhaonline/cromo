<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CiudadRepository")
 */
class Ciudad
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoCiudadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_division", type="string", length=10, nullable=true)
     */
    private $codigoDivision;

    /**
     * @ORM\Column(name="nombre_division", type="string", length=100, nullable=true)
     */
    private $nombreDivision;

    /**
     * @ORM\Column(name="codigo_zona", type="string", length=10, nullable=true)
     */
    private $codigoZona;

    /**
     * @ORM\Column(name="nombre_zona", type="string", length=100, nullable=true)
     */
    private $nombreZona;

    /**
     * @ORM\Column(name="codigo_municipio", type="string", length=10, nullable=true)
     */
    private $codigoMunicipio;

    /**
     * @ORM\Column(name="nombre_municipio", type="string", length=100, nullable=true)
     */
    private $nombreMunicipio;

    /**
     * @ORM\Column(name="codigo_departamento_fk", type="string", length=2, nullable=true)
     */
    private $codigoDepartamentoFk;

    /**
     * @ORM\Column(name="codigo_ruta_fk", type="string", length=20, nullable=true)
     */
    private $codigoRutaFk;

    /**
     * @ORM\ManyToOne(targetEntity="Ruta", inversedBy="ciudadesRutaRel")
     * @ORM\JoinColumn(name="codigo_ruta_fk", referencedColumnName="codigo_ruta_pk")
     */
    private $rutaRel;

    /**
     * @ORM\OneToMany(targetEntity="Guia", mappedBy="ciudadOrigenRel")
     */
    protected $guiasCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="Guia", mappedBy="ciudadDestinoRel")
     */
    protected $guiasCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="Despacho", mappedBy="ciudadOrigenRel")
     */
    protected $despachosCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="Despacho", mappedBy="ciudadDestinoRel")
     */
    protected $despachosCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="Recogida", mappedBy="ciudadRel")
     */
    protected $recogidasCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="Recogida", mappedBy="ciudadDestinoRel")
     */
    protected $recogidasCiudadDestinoRel;

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
    public function getGuiasCiudadOrigenRel()
    {
        return $this->guiasCiudadOrigenRel;
    }

    /**
     * @param mixed $guiasCiudadOrigenRel
     */
    public function setGuiasCiudadOrigenRel($guiasCiudadOrigenRel): void
    {
        $this->guiasCiudadOrigenRel = $guiasCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasCiudadDestinoRel()
    {
        return $this->guiasCiudadDestinoRel;
    }

    /**
     * @param mixed $guiasCiudadDestinoRel
     */
    public function setGuiasCiudadDestinoRel($guiasCiudadDestinoRel): void
    {
        $this->guiasCiudadDestinoRel = $guiasCiudadDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosCiudadOrigenRel()
    {
        return $this->despachosCiudadOrigenRel;
    }

    /**
     * @param mixed $despachosCiudadOrigenRel
     */
    public function setDespachosCiudadOrigenRel($despachosCiudadOrigenRel): void
    {
        $this->despachosCiudadOrigenRel = $despachosCiudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosCiudadDestinoRel()
    {
        return $this->despachosCiudadDestinoRel;
    }

    /**
     * @param mixed $despachosCiudadDestinoRel
     */
    public function setDespachosCiudadDestinoRel($despachosCiudadDestinoRel): void
    {
        $this->despachosCiudadDestinoRel = $despachosCiudadDestinoRel;
    }


}

