<?php


namespace App\Entity\Contabilidad;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Contabilidad\CtbComprobanteRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoComprobantePk"},message="Ya existe el código del comprobante")
 */
class CtbComprobante
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_comprobante_pk", type="string", length=20)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     min = "1",
     *     max = 20,
     *     minMessage="El campo no puede contener menos de {{ limit }} caracteres",
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     *
     */
    private $codigoComprobantePk;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Contabilidad\CtbRegistro", mappedBy="comprobanteRel")
     */
    protected $ctbRegistrosComprobanteRel;

    /**
     * @return mixed
     */
    public function getCodigoComprobantePk()
    {
        return $this->codigoComprobantePk;
    }

    /**
     * @param mixed $codigoComprobantePk
     */
    public function setCodigoComprobantePk($codigoComprobantePk): void
    {
        $this->codigoComprobantePk = $codigoComprobantePk;
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
    public function getCtbRegistrosComprobanteRel()
    {
        return $this->ctbRegistrosComprobanteRel;
    }

    /**
     * @param mixed $ctbRegistrosComprobanteRel
     */
    public function setCtbRegistrosComprobanteRel($ctbRegistrosComprobanteRel): void
    {
        $this->ctbRegistrosComprobanteRel = $ctbRegistrosComprobanteRel;
    }

}

