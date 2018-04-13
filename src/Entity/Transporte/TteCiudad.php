<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteCiudadRepository")
 */
class TteCiudad
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
     * @ORM\Column(name="orden_ruta", type="integer", nullable=true)
     */
    private $ordenRuta = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TteRuta", inversedBy="ciudadesRutaRel")
     * @ORM\JoinColumn(name="codigo_ruta_fk", referencedColumnName="codigo_ruta_pk")
     */
    private $rutaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="ciudadOrigenRel")
     */
    protected $guiasCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="ciudadDestinoRel")
     */
    protected $guiasCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="ciudadOrigenRel")
     */
    protected $despachosCiudadOrigenRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="ciudadDestinoRel")
     */
    protected $despachosCiudadDestinoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogida", mappedBy="ciudadRel")
     */
    protected $recogidasCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogidaProgramada", mappedBy="ciudadRel")
     */
    protected $recogidasProgramadasCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteOperacion", mappedBy="ciudadRel")
     */
    protected $operacionesCiudadRel;

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
    public function getCodigoDivision()
    {
        return $this->codigoDivision;
    }

    /**
     * @param mixed $codigoDivision
     */
    public function setCodigoDivision($codigoDivision): void
    {
        $this->codigoDivision = $codigoDivision;
    }

    /**
     * @return mixed
     */
    public function getNombreDivision()
    {
        return $this->nombreDivision;
    }

    /**
     * @param mixed $nombreDivision
     */
    public function setNombreDivision($nombreDivision): void
    {
        $this->nombreDivision = $nombreDivision;
    }

    /**
     * @return mixed
     */
    public function getCodigoZona()
    {
        return $this->codigoZona;
    }

    /**
     * @param mixed $codigoZona
     */
    public function setCodigoZona($codigoZona): void
    {
        $this->codigoZona = $codigoZona;
    }

    /**
     * @return mixed
     */
    public function getNombreZona()
    {
        return $this->nombreZona;
    }

    /**
     * @param mixed $nombreZona
     */
    public function setNombreZona($nombreZona): void
    {
        $this->nombreZona = $nombreZona;
    }

    /**
     * @return mixed
     */
    public function getCodigoMunicipio()
    {
        return $this->codigoMunicipio;
    }

    /**
     * @param mixed $codigoMunicipio
     */
    public function setCodigoMunicipio($codigoMunicipio): void
    {
        $this->codigoMunicipio = $codigoMunicipio;
    }

    /**
     * @return mixed
     */
    public function getNombreMunicipio()
    {
        return $this->nombreMunicipio;
    }

    /**
     * @param mixed $nombreMunicipio
     */
    public function setNombreMunicipio($nombreMunicipio): void
    {
        $this->nombreMunicipio = $nombreMunicipio;
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
    public function getCodigoRutaFk()
    {
        return $this->codigoRutaFk;
    }

    /**
     * @param mixed $codigoRutaFk
     */
    public function setCodigoRutaFk($codigoRutaFk): void
    {
        $this->codigoRutaFk = $codigoRutaFk;
    }

    /**
     * @return mixed
     */
    public function getRutaRel()
    {
        return $this->rutaRel;
    }

    /**
     * @param mixed $rutaRel
     */
    public function setRutaRel($rutaRel): void
    {
        $this->rutaRel = $rutaRel;
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

    /**
     * @return mixed
     */
    public function getRecogidasCiudadRel()
    {
        return $this->recogidasCiudadRel;
    }

    /**
     * @param mixed $recogidasCiudadRel
     */
    public function setRecogidasCiudadRel($recogidasCiudadRel): void
    {
        $this->recogidasCiudadRel = $recogidasCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasProgramadasCiudadRel()
    {
        return $this->recogidasProgramadasCiudadRel;
    }

    /**
     * @param mixed $recogidasProgramadasCiudadRel
     */
    public function setRecogidasProgramadasCiudadRel($recogidasProgramadasCiudadRel): void
    {
        $this->recogidasProgramadasCiudadRel = $recogidasProgramadasCiudadRel;
    }

    /**
     * @return mixed
     */
    public function getOrdenRuta()
    {
        return $this->ordenRuta;
    }

    /**
     * @param mixed $ordenRuta
     */
    public function setOrdenRuta($ordenRuta): void
    {
        $this->ordenRuta = $ordenRuta;
    }

    /**
     * @return mixed
     */
    public function getOperacionesCiudadRel()
    {
        return $this->operacionesCiudadRel;
    }

    /**
     * @param mixed $operacionesCiudadRel
     */
    public function setOperacionesCiudadRel($operacionesCiudadRel): void
    {
        $this->operacionesCiudadRel = $operacionesCiudadRel;
    }



}

