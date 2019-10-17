<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteFrecuenciaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteFrecuencia
{
    public $infoLog = [
        "primaryKey" => "codigoFrecuenciaPk",
        "todos" => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="codigo_frecuencia_pk")
     */
    private $codigoFrecuenciaPk;

    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadOrigenFk;

    /**
     * @ORM\Column(name="codigo_ciudad_destino_fk", type="string", length=20, nullable=true)
     */
    private $codigoCiudadDestinoFk;

    /**
     * @ORM\Column(name="lunes", type="boolean", nullable=true, options={"default" : 0})
     */
    private $lunes = false;

    /**
     * @ORM\Column(name="martes", type="boolean", nullable=true, options={"default" : 0})
     */
    private $martes = false;

    /**
     * @ORM\Column(name="miercoles", type="boolean", nullable=true, options={"default" : 0})
     */
    private $miercoles = false;

    /**
     * @ORM\Column(name="jueves", type="boolean", nullable=true, options={"default" : 0})
     */
    private $jueves = false;

    /**
     * @ORM\Column(name="viernes", type="boolean", nullable=true, options={"default" : 0})
     */
    private $viernes = false;

    /**
     * @ORM\Column(name="sabado", type="boolean", nullable=true, options={"default" : 0})
     */
    private $sabado = false;

    /**
     * @ORM\Column(name="domingo", type="boolean", nullable=true, options={"default" : 0})
     */
    private $domingo = false;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="frecuenciasCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadOrigenRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCiudad", inversedBy="frecuenciasCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    private $ciudadDestinoRel;

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
    public function getCodigoFrecuenciaPk()
    {
        return $this->codigoFrecuenciaPk;
    }

    /**
     * @param mixed $codigoFrecuenciaPk
     */
    public function setCodigoFrecuenciaPk($codigoFrecuenciaPk): void
    {
        $this->codigoFrecuenciaPk = $codigoFrecuenciaPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadOrigenFk()
    {
        return $this->codigoCiudadOrigenFk;
    }

    /**
     * @param mixed $codigoCiudadOrigenFk
     */
    public function setCodigoCiudadOrigenFk($codigoCiudadOrigenFk): void
    {
        $this->codigoCiudadOrigenFk = $codigoCiudadOrigenFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCiudadDestinoFk()
    {
        return $this->codigoCiudadDestinoFk;
    }

    /**
     * @param mixed $codigoCiudadDestinoFk
     */
    public function setCodigoCiudadDestinoFk($codigoCiudadDestinoFk): void
    {
        $this->codigoCiudadDestinoFk = $codigoCiudadDestinoFk;
    }

    /**
     * @return mixed
     */
    public function getLunes()
    {
        return $this->lunes;
    }

    /**
     * @param mixed $lunes
     */
    public function setLunes($lunes): void
    {
        $this->lunes = $lunes;
    }

    /**
     * @return mixed
     */
    public function getMartes()
    {
        return $this->martes;
    }

    /**
     * @param mixed $martes
     */
    public function setMartes($martes): void
    {
        $this->martes = $martes;
    }

    /**
     * @return mixed
     */
    public function getMiercoles()
    {
        return $this->miercoles;
    }

    /**
     * @param mixed $miercoles
     */
    public function setMiercoles($miercoles): void
    {
        $this->miercoles = $miercoles;
    }

    /**
     * @return mixed
     */
    public function getJueves()
    {
        return $this->jueves;
    }

    /**
     * @param mixed $jueves
     */
    public function setJueves($jueves): void
    {
        $this->jueves = $jueves;
    }

    /**
     * @return mixed
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * @param mixed $viernes
     */
    public function setViernes($viernes): void
    {
        $this->viernes = $viernes;
    }

    /**
     * @return mixed
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * @param mixed $sabado
     */
    public function setSabado($sabado): void
    {
        $this->sabado = $sabado;
    }

    /**
     * @return mixed
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * @param mixed $domingo
     */
    public function setDomingo($domingo): void
    {
        $this->domingo = $domingo;
    }

    /**
     * @return mixed
     */
    public function getCiudadOrigenRel()
    {
        return $this->ciudadOrigenRel;
    }

    /**
     * @param mixed $ciudadOrigenRel
     */
    public function setCiudadOrigenRel($ciudadOrigenRel): void
    {
        $this->ciudadOrigenRel = $ciudadOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadDestinoRel()
    {
        return $this->ciudadDestinoRel;
    }

    /**
     * @param mixed $ciudadDestinoRel
     */
    public function setCiudadDestinoRel($ciudadDestinoRel): void
    {
        $this->ciudadDestinoRel = $ciudadDestinoRel;
    }


}
