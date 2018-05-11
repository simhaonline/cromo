<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenIdentificacionTipoRepository")
 */
class GenIdentificacionTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_identificacion_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIdentificacionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max=50,
     *     maxMessage="El campo no puede contener mas de 50 caracteres"
     * )
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contabilidad\CtbTercero",mappedBy="identificacionTipoRel")
     */
    protected $ctbTercerosIdentificacionTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoIdentificacionTipoPk()
    {
        return $this->codigoIdentificacionTipoPk;
    }

    /**
     * @param mixed $codigoIdentificacionTipoPk
     */
    public function setCodigoIdentificacionTipoPk($codigoIdentificacionTipoPk): void
    {
        $this->codigoIdentificacionTipoPk = $codigoIdentificacionTipoPk;
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
    public function getCtbTercerosIdentificacionTipoRel()
    {
        return $this->ctbTercerosIdentificacionTipoRel;
    }

    /**
     * @param mixed $ctbTercerosIdentificacionTipoRel
     */
    public function setCtbTercerosIdentificacionTipoRel($ctbTercerosIdentificacionTipoRel): void
    {
        $this->ctbTercerosIdentificacionTipoRel = $ctbTercerosIdentificacionTipoRel;
    }

}

