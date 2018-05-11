<?php


namespace App\Entity\Contabilidad;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Contabilidad\CtbCuentaRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoCuentaPk"},message="Ya existe el codigo de cuenta")
 */
class CtbCuenta
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_pk",type="string", length=20)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     min = "1",
     *     max = 20,
     *     minMessage="El campo no puede contener menos de {{ limit }} caracteres",
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     *
     */
    private $codigoCuentaPk;

    /**
     * @ORM\Column(name="nombre_cuenta", type="string", length=120, nullable=false)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(
     *     min = "1",
     *     max = 120,
     *     minMessage="El campo no puede contener menos de {{ limit }} caracteres",
     *     maxMessage = "El campo no puede contener mas de {{ limit }} caracteres"
     * )
     */
    private $nombreCuenta;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contabilidad\CtbRegistro", mappedBy="cuentaRel")
     */
    protected $ctbRegistrosCuentaRel;

    /**
     * @return mixed
     */
    public function getCodigoCuentaPk()
    {
        return $this->codigoCuentaPk;
    }

    /**
     * @param mixed $codigoCuentaPk
     */
    public function setCodigoCuentaPk($codigoCuentaPk): void
    {
        $this->codigoCuentaPk = $codigoCuentaPk;
    }

    /**
     * @return mixed
     */
    public function getNombreCuenta()
    {
        return $this->nombreCuenta;
    }

    /**
     * @param mixed $nombreCuenta
     */
    public function setNombreCuenta($nombreCuenta): void
    {
        $this->nombreCuenta = $nombreCuenta;
    }

    /**
     * @return mixed
     */
    public function getCtbRegistrosCuentaRel()
    {
        return $this->ctbRegistrosCuentaRel;
    }

    /**
     * @param mixed $ctbRegistrosCuentaRel
     */
    public function setCtbRegistrosCuentaRel($ctbRegistrosCuentaRel): void
    {
        $this->ctbRegistrosCuentaRel = $ctbRegistrosCuentaRel;
    }

}

