<?php


namespace App\Entity\Compra;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Compra\ComCompraTipoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoCompraTipoPk"},message="Ya existe un registro con el mismo codigo")
 */
class ComCompraTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_compra_tipo_pk", type="string", length=10)
     */
    private $codigoCompraTipoPk;

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
     * @ORM\Column(name="operacion" , type="integer")
     *
     */
    private $operacion;

    /**
     * @ORM\Column(name="consecutivo" , type="integer")
     */
    private $consecutivo = 0;

    /**
     * @ORM\Column(name="codigo_cuenta_pagar_tipo_fk" , type="string" , length=10, nullable=true)
     */
    private $codigoCuentaPagarTipoFk;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compra\ComCompra", mappedBy="compraTipoRel")
     */
    private $comprasCompraTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compra\ComCuentaPagarTipo" , inversedBy="comprasTipoCuentaPagarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_pagar_tipo_fk" , referencedColumnName="codigo_cuenta_pagar_tipo_pk")
     */
    private $cuentaPagarTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoCompraTipoPk()
    {
        return $this->codigoCompraTipoPk;
    }

    /**
     * @param mixed $codigoCompraTipoPk
     */
    public function setCodigoCompraTipoPk($codigoCompraTipoPk): void
    {
        $this->codigoCompraTipoPk = $codigoCompraTipoPk;
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
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * @param mixed $operacion
     */
    public function setOperacion($operacion): void
    {
        $this->operacion = $operacion;
    }

    /**
     * @return mixed
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * @param mixed $consecutivo
     */
    public function setConsecutivo($consecutivo): void
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaPagarTipoFk()
    {
        return $this->codigoCuentaPagarTipoFk;
    }

    /**
     * @param mixed $codigoCuentaPagarTipoFk
     */
    public function setCodigoCuentaPagarTipoFk($codigoCuentaPagarTipoFk): void
    {
        $this->codigoCuentaPagarTipoFk = $codigoCuentaPagarTipoFk;
    }

    /**
     * @return mixed
     */
    public function getComprasCompraTipoRel()
    {
        return $this->comprasCompraTipoRel;
    }

    /**
     * @param mixed $comprasCompraTipoRel
     */
    public function setComprasCompraTipoRel($comprasCompraTipoRel): void
    {
        $this->comprasCompraTipoRel = $comprasCompraTipoRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaPagarTipoRel()
    {
        return $this->cuentaPagarTipoRel;
    }

    /**
     * @param mixed $cuentaPagarTipoRel
     */
    public function setCuentaPagarTipoRel($cuentaPagarTipoRel): void
    {
        $this->cuentaPagarTipoRel = $cuentaPagarTipoRel;
    }

}

