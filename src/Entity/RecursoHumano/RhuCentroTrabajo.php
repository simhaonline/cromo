<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_centro_trabajo")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCentroTrabajoRepository")
 */
class RhuCentroTrabajo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_trabajo_pk", type="string", length=10)
     */
    private $codigoCentroTrabajoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=160, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="centroTrabajoRel")
     */
    protected $contratosCentroTrabajoRel;

    /**
     * @return mixed
     */
    public function getCodigoCentroTrabajoPk()
    {
        return $this->codigoCentroTrabajoPk;
    }

    /**
     * @param mixed $codigoCentroTrabajoPk
     */
    public function setCodigoCentroTrabajoPk($codigoCentroTrabajoPk): void
    {
        $this->codigoCentroTrabajoPk = $codigoCentroTrabajoPk;
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
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * @param mixed $contratoRel
     */
    public function setContratoRel($contratoRel): void
    {
        $this->contratoRel = $contratoRel;
    }


}
