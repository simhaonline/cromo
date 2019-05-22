<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_dotacion_elemento")
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuDotacionElementoRepository")
 */
class RhuDotacionElemento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dotacion_elemento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDotacionElementoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuDotacionDetalle", mappedBy="dotacionElementoRel")
     */
    protected $elementosDotacionesDetalleDotacionElementoRel;

    /**
     * @return mixed
     */
    public function getCodigoDotacionElementoPk()
    {
        return $this->codigoDotacionElementoPk;
    }

    /**
     * @param mixed $codigoDotacionElementoPk
     */
    public function setCodigoDotacionElementoPk($codigoDotacionElementoPk): void
    {
        $this->codigoDotacionElementoPk = $codigoDotacionElementoPk;
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
    public function getElementosDotacionesDetalleDotacionElementoRel()
    {
        return $this->elementosDotacionesDetalleDotacionElementoRel;
    }

    /**
     * @param mixed $elementosDotacionesDetalleDotacionElementoRel
     */
    public function setElementosDotacionesDetalleDotacionElementoRel($elementosDotacionesDetalleDotacionElementoRel): void
    {
        $this->elementosDotacionesDetalleDotacionElementoRel = $elementosDotacionesDetalleDotacionElementoRel;
    }


}