<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteRutaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteRuta
{
    public $infoLog = [
        "primaryKey" => "codigoRutaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoRutaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_despacho_clase_fk", type="string", length=5, nullable=true)
     */
    private $codigoDespachoClaseFk;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="rutasOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteCiudad", mappedBy="rutaRel")
     */
    protected $ciudadesRutaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="rutaRel")
     */
    protected $guiasRutaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="rutaRel")
     */
    protected $despachosRutaRel;

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
    public function getCodigoRutaPk()
    {
        return $this->codigoRutaPk;
    }

    /**
     * @param mixed $codigoRutaPk
     */
    public function setCodigoRutaPk($codigoRutaPk): void
    {
        $this->codigoRutaPk = $codigoRutaPk;
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
    public function getCodigoDespachoClaseFk()
    {
        return $this->codigoDespachoClaseFk;
    }

    /**
     * @param mixed $codigoDespachoClaseFk
     */
    public function setCodigoDespachoClaseFk($codigoDespachoClaseFk): void
    {
        $this->codigoDespachoClaseFk = $codigoDespachoClaseFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOperacionFk()
    {
        return $this->codigoOperacionFk;
    }

    /**
     * @param mixed $codigoOperacionFk
     */
    public function setCodigoOperacionFk($codigoOperacionFk): void
    {
        $this->codigoOperacionFk = $codigoOperacionFk;
    }

    /**
     * @return mixed
     */
    public function getOperacionRel()
    {
        return $this->operacionRel;
    }

    /**
     * @param mixed $operacionRel
     */
    public function setOperacionRel($operacionRel): void
    {
        $this->operacionRel = $operacionRel;
    }

    /**
     * @return mixed
     */
    public function getCiudadesRutaRel()
    {
        return $this->ciudadesRutaRel;
    }

    /**
     * @param mixed $ciudadesRutaRel
     */
    public function setCiudadesRutaRel($ciudadesRutaRel): void
    {
        $this->ciudadesRutaRel = $ciudadesRutaRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasRutaRel()
    {
        return $this->guiasRutaRel;
    }

    /**
     * @param mixed $guiasRutaRel
     */
    public function setGuiasRutaRel($guiasRutaRel): void
    {
        $this->guiasRutaRel = $guiasRutaRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosRutaRel()
    {
        return $this->despachosRutaRel;
    }

    /**
     * @param mixed $despachosRutaRel
     */
    public function setDespachosRutaRel($despachosRutaRel): void
    {
        $this->despachosRutaRel = $despachosRutaRel;
    }


}

