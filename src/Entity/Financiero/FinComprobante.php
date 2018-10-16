<?php


namespace App\Entity\Financiero;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Financiero\FinComprobanteRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoComprobantePk"},message="Ya existe el código del comprobante")
 */
class FinComprobante
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
     * @ORM\OneToMany(targetEntity="App\Entity\Financiero\FinRegistro", mappedBy="comprobanteRel")
     */
    protected $registrosComprobanteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Financiero\FinAsiento", mappedBy="comprobanteRel")
     */
    protected $asientosComprobanteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Financiero\FinAsientoDetalle", mappedBy="comprobanteRel")
     */
    protected $asientosDetallesComprobanteRel;

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
    public function getRegistrosComprobanteRel()
    {
        return $this->registrosComprobanteRel;
    }

    /**
     * @param mixed $registrosComprobanteRel
     */
    public function setRegistrosComprobanteRel($registrosComprobanteRel): void
    {
        $this->registrosComprobanteRel = $registrosComprobanteRel;
    }

    /**
     * @return mixed
     */
    public function getAsientosComprobanteRel()
    {
        return $this->asientosComprobanteRel;
    }

    /**
     * @param mixed $asientosComprobanteRel
     */
    public function setAsientosComprobanteRel($asientosComprobanteRel): void
    {
        $this->asientosComprobanteRel = $asientosComprobanteRel;
    }

    /**
     * @return mixed
     */
    public function getAsientosDetallesComprobanteRel()
    {
        return $this->asientosDetallesComprobanteRel;
    }

    /**
     * @param mixed $asientosDetallesComprobanteRel
     */
    public function setAsientosDetallesComprobanteRel($asientosDetallesComprobanteRel): void
    {
        $this->asientosDetallesComprobanteRel = $asientosDetallesComprobanteRel;
    }



}

