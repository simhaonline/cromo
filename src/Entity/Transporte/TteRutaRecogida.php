<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteRutaRecogidaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteRutaRecogida
{
    public $infoLog = [
        "primaryKey" => "codigoRutaRecogidaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoRutaRecogidaPk;

    /**
     * @ORM\Column(name="codigo_operacion_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="rutasRecogidasOperacionRel")
     * @ORM\JoinColumn(name="codigo_operacion_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespachoRecogida", mappedBy="rutaRecogidaRel")
     */
    protected $despachosRecogidasRutaRecogidaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogida", mappedBy="rutaRecogidaRel")
     */
    protected $recogidasRutaRecogidaRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogidaProgramada", mappedBy="rutaRecogidaRel")
     */
    protected $recogidasProgramadasRutaRecogidaRel;

    /**
     * @return mixed
     */
    public function getCodigoRutaRecogidaPk()
    {
        return $this->codigoRutaRecogidaPk;
    }

    /**
     * @param mixed $codigoRutaRecogidaPk
     */
    public function setCodigoRutaRecogidaPk($codigoRutaRecogidaPk): void
    {
        $this->codigoRutaRecogidaPk = $codigoRutaRecogidaPk;
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
    public function getDespachosRecogidasRutaRecogidaRel()
    {
        return $this->despachosRecogidasRutaRecogidaRel;
    }

    /**
     * @param mixed $despachosRecogidasRutaRecogidaRel
     */
    public function setDespachosRecogidasRutaRecogidaRel($despachosRecogidasRutaRecogidaRel): void
    {
        $this->despachosRecogidasRutaRecogidaRel = $despachosRecogidasRutaRecogidaRel;
    }

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
    public function getRecogidasRutaRecogidaRel()
    {
        return $this->recogidasRutaRecogidaRel;
    }

    /**
     * @param mixed $recogidasRutaRecogidaRel
     */
    public function setRecogidasRutaRecogidaRel($recogidasRutaRecogidaRel): void
    {
        $this->recogidasRutaRecogidaRel = $recogidasRutaRecogidaRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasProgramadasRutaRecogidaRel()
    {
        return $this->recogidasProgramadasRutaRecogidaRel;
    }

    /**
     * @param mixed $recogidasProgramadasRutaRecogidaRel
     */
    public function setRecogidasProgramadasRutaRecogidaRel($recogidasProgramadasRutaRecogidaRel): void
    {
        $this->recogidasProgramadasRutaRecogidaRel = $recogidasProgramadasRutaRecogidaRel;
    }



}

