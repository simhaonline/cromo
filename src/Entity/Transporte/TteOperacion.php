<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteOperacionRepository")
 */
class TteOperacion
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoOperacionPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="operacionIngresoRel")
     */
    protected $guiasOperacionIngresoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="operacionCargoRel")
     */
    protected $guiasOperacionCargoRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogida", mappedBy="operacionRel")
     */
    protected $recogidasOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRecogidaProgramada", mappedBy="operacionRel")
     */
    protected $recogidasProgramadasOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespachoRecogida", mappedBy="operacionRel")
     */
    protected $despachosRecogidasOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteRutaRecogida", mappedBy="operacionRel")
     */
    protected $rutasRecogidasOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="operacionRel")
     */
    protected $despachosOperacionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seguridad\Usuario", mappedBy="operacionRel")
     */
    protected $usuariosOperacionRel;

    /**
     * @return mixed
     */
    public function getCodigoOperacionPk()
    {
        return $this->codigoOperacionPk;
    }

    /**
     * @param mixed $codigoOperacionPk
     */
    public function setCodigoOperacionPk($codigoOperacionPk): void
    {
        $this->codigoOperacionPk = $codigoOperacionPk;
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
    public function getGuiasOperacionIngresoRel()
    {
        return $this->guiasOperacionIngresoRel;
    }

    /**
     * @param mixed $guiasOperacionIngresoRel
     */
    public function setGuiasOperacionIngresoRel($guiasOperacionIngresoRel): void
    {
        $this->guiasOperacionIngresoRel = $guiasOperacionIngresoRel;
    }

    /**
     * @return mixed
     */
    public function getGuiasOperacionCargoRel()
    {
        return $this->guiasOperacionCargoRel;
    }

    /**
     * @param mixed $guiasOperacionCargoRel
     */
    public function setGuiasOperacionCargoRel($guiasOperacionCargoRel): void
    {
        $this->guiasOperacionCargoRel = $guiasOperacionCargoRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasOperacionRel()
    {
        return $this->recogidasOperacionRel;
    }

    /**
     * @param mixed $recogidasOperacionRel
     */
    public function setRecogidasOperacionRel($recogidasOperacionRel): void
    {
        $this->recogidasOperacionRel = $recogidasOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getRecogidasProgramadasOperacionRel()
    {
        return $this->recogidasProgramadasOperacionRel;
    }

    /**
     * @param mixed $recogidasProgramadasOperacionRel
     */
    public function setRecogidasProgramadasOperacionRel($recogidasProgramadasOperacionRel): void
    {
        $this->recogidasProgramadasOperacionRel = $recogidasProgramadasOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasOperacionRel()
    {
        return $this->despachosRecogidasOperacionRel;
    }

    /**
     * @param mixed $despachosRecogidasOperacionRel
     */
    public function setDespachosRecogidasOperacionRel($despachosRecogidasOperacionRel): void
    {
        $this->despachosRecogidasOperacionRel = $despachosRecogidasOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getRutasRecogidasOperacionRel()
    {
        return $this->rutasRecogidasOperacionRel;
    }

    /**
     * @param mixed $rutasRecogidasOperacionRel
     */
    public function setRutasRecogidasOperacionRel($rutasRecogidasOperacionRel): void
    {
        $this->rutasRecogidasOperacionRel = $rutasRecogidasOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getDespachosOperacionRel()
    {
        return $this->despachosOperacionRel;
    }

    /**
     * @param mixed $despachosOperacionRel
     */
    public function setDespachosOperacionRel($despachosOperacionRel): void
    {
        $this->despachosOperacionRel = $despachosOperacionRel;
    }

    /**
     * @return mixed
     */
    public function getUsuariosOperacionRel()
    {
        return $this->usuariosOperacionRel;
    }

    /**
     * @param mixed $usuariosOperacionRel
     */
    public function setUsuariosOperacionRel($usuariosOperacionRel): void
    {
        $this->usuariosOperacionRel = $usuariosOperacionRel;
    }



}

