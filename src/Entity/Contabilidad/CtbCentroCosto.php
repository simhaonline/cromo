<?php


namespace App\Entity\Contabilidad;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Contabilidad\CtbCentroCostoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoCentroCostoPk"},message="Ya existe el codigo centro de costo")
 */
class CtbCentroCosto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_centro_costo_pk",type="string", length=20)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     min = "1",
     *     max = 20,
     *     minMessage="El campo no puede contener menos de {{ limit }} caracteres",
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     *
     */
    private $codigoCentroCostoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=false)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     min = "1",
     *     max = 120,
     *     minMessage="El campo no puede contener menos de {{ limit }} caracteres",
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(name="estado_inactivo", type="boolean", nullable=true)
     */
    private $estadoInactivo = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contabilidad\CtbRegistro", mappedBy="centroCostoRel")
     */
    protected $ctbRegistrosCentroCostoRel;

    /**
     * @return mixed
     */
    public function getCodigoCentroCostoPk()
    {
        return $this->codigoCentroCostoPk;
    }

    /**
     * @param mixed $codigoCentroCostoPk
     */
    public function setCodigoCentroCostoPk($codigoCentroCostoPk): void
    {
        $this->codigoCentroCostoPk = $codigoCentroCostoPk;
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
    public function getEstadoInactivo()
    {
        return $this->estadoInactivo;
    }

    /**
     * @param mixed $estadoInactivo
     */
    public function setEstadoInactivo($estadoInactivo): void
    {
        $this->estadoInactivo = $estadoInactivo;
    }

    /**
     * @return mixed
     */
    public function getCtbRegistrosCentroCostoRel()
    {
        return $this->ctbRegistrosCentroCostoRel;
    }

    /**
     * @param mixed $ctbRegistrosCentroCostoRel
     */
    public function setCtbRegistrosCentroCostoRel($ctbRegistrosCentroCostoRel): void
    {
        $this->ctbRegistrosCentroCostoRel = $ctbRegistrosCentroCostoRel;
    }

}

