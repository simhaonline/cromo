<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurOperacionTipoRepository")
 */
class TurOperacionTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_operacion_tipo_pk", type="string", length=20)
     */
    private $codigoOperacionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurOperacion", mappedBy="operacionTipoRel")
     */
    protected $operacionesOperacionTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoOperacionTipoPk()
    {
        return $this->codigoOperacionTipoPk;
    }

    /**
     * @param mixed $codigoOperacionTipoPk
     */
    public function setCodigoOperacionTipoPk($codigoOperacionTipoPk): void
    {
        $this->codigoOperacionTipoPk = $codigoOperacionTipoPk;
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
    public function getOperacionesOperacionTipoRel()
    {
        return $this->operacionesOperacionTipoRel;
    }

    /**
     * @param mixed $operacionesOperacionTipoRel
     */
    public function setOperacionesOperacionTipoRel($operacionesOperacionTipoRel): void
    {
        $this->operacionesOperacionTipoRel = $operacionesOperacionTipoRel;
    }



}