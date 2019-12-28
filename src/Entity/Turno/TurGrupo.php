<?php

namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 *  @ORM\Entity(repositoryClass="App\Repository\Turno\TurGrupoRepository")
 */
class TurGrupo
{

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_grupo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoGrupoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="abreviatura", type="string", length=10, nullable=true)
     * @Assert\Length(
     *     max=10,
     *     maxMessage="El campo no puede contener mas de 10 caracteres"
     * )
     */
    private $abreviatura;

    /**
     * @ORM\Column(name="concepto", type="text", nullable=true)
     */
    private $concepto;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turno\TurCliente", inversedBy="gruposClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurContratoDetalle", mappedBy="grupoRel")
     */
    protected $contratosDetallesGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurPedidoDetalle", mappedBy="grupoRel")
     */
    protected $pedidosDetallesGrupoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurFacturaDetalle", mappedBy="grupoRel")
     */
    protected $facturasDetallesGrupoRel;

    /**
     * @return mixed
     */
    public function getCodigoGrupoPk()
    {
        return $this->codigoGrupoPk;
    }

    /**
     * @param mixed $codigoGrupoPk
     */
    public function setCodigoGrupoPk($codigoGrupoPk): void
    {
        $this->codigoGrupoPk = $codigoGrupoPk;
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
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * @param mixed $abreviatura
     */
    public function setAbreviatura($abreviatura): void
    {
        $this->abreviatura = $abreviatura;
    }

    /**
     * @return mixed
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    /**
     * @param mixed $concepto
     */
    public function setConcepto($concepto): void
    {
        $this->concepto = $concepto;
    }

    /**
     * @return mixed
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * @param mixed $codigoClienteFk
     */
    public function setCodigoClienteFk($codigoClienteFk): void
    {
        $this->codigoClienteFk = $codigoClienteFk;
    }

    /**
     * @return mixed
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * @param mixed $clienteRel
     */
    public function setClienteRel($clienteRel): void
    {
        $this->clienteRel = $clienteRel;
    }

    /**
     * @return mixed
     */
    public function getContratosDetallesGrupoRel()
    {
        return $this->contratosDetallesGrupoRel;
    }

    /**
     * @param mixed $contratosDetallesGrupoRel
     */
    public function setContratosDetallesGrupoRel($contratosDetallesGrupoRel): void
    {
        $this->contratosDetallesGrupoRel = $contratosDetallesGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getPedidosDetallesGrupoRel()
    {
        return $this->pedidosDetallesGrupoRel;
    }

    /**
     * @param mixed $pedidosDetallesGrupoRel
     */
    public function setPedidosDetallesGrupoRel($pedidosDetallesGrupoRel): void
    {
        $this->pedidosDetallesGrupoRel = $pedidosDetallesGrupoRel;
    }

    /**
     * @return mixed
     */
    public function getFacturasDetallesGrupoRel()
    {
        return $this->facturasDetallesGrupoRel;
    }

    /**
     * @param mixed $facturasDetallesGrupoRel
     */
    public function setFacturasDetallesGrupoRel($facturasDetallesGrupoRel): void
    {
        $this->facturasDetallesGrupoRel = $facturasDetallesGrupoRel;
    }



}
