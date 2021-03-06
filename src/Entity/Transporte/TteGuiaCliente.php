<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteGuiaClienteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteGuiaCliente
{
    public $infoLog = [
        "primaryKey" => "codigoGuiaClientePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoGuiaClientePk;

    /**
     * @ORM\Column(name="codigo_guia_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoGuiaTipoFk;

    /**
     * @ORM\Column(name="desde", type="float", nullable=true)
     */
    private $desde;

    /**
     * @ORM\Column(name="hasta", type="float", nullable=true)
     */
    private $hasta;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\Column(name="estado_activo", type="boolean", nullable=true, options={"default" : 0})
     */
    private $estadoActivo = false;

    /**
     * @ORM\ManyToOne(targetEntity="TteGuiaTipo", inversedBy="guiasClientesGuiaTipoRel")
     * @ORM\JoinColumn(name="codigo_guia_tipo_fk", referencedColumnName="codigo_guia_tipo_pk")
     * @Assert\NotBlank(
     *     message="Debe seleccionar un tipo"
     * )
     */
    private $guiaTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="guiasClientesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    private $clienteRel;

    /**
     * @return mixed
     */
    public function getCodigoGuiaClientePk()
    {
        return $this->codigoGuiaClientePk;
    }

    /**
     * @param mixed $codigoGuiaClientePk
     */
    public function setCodigoGuiaClientePk($codigoGuiaClientePk): void
    {
        $this->codigoGuiaClientePk = $codigoGuiaClientePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoGuiaTipoFk()
    {
        return $this->codigoGuiaTipoFk;
    }

    /**
     * @param mixed $codigoGuiaTipoFk
     */
    public function setCodigoGuiaTipoFk($codigoGuiaTipoFk): void
    {
        $this->codigoGuiaTipoFk = $codigoGuiaTipoFk;
    }

    /**
     * @return mixed
     */
    public function getDesde()
    {
        return $this->desde;
    }

    /**
     * @param mixed $desde
     */
    public function setDesde($desde): void
    {
        $this->desde = $desde;
    }

    /**
     * @return mixed
     */
    public function getHasta()
    {
        return $this->hasta;
    }

    /**
     * @param mixed $hasta
     */
    public function setHasta($hasta): void
    {
        $this->hasta = $hasta;
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
    public function getEstadoActivo()
    {
        return $this->estadoActivo;
    }

    /**
     * @param mixed $estadoActivo
     */
    public function setEstadoActivo($estadoActivo): void
    {
        $this->estadoActivo = $estadoActivo;
    }

    /**
     * @return mixed
     */
    public function getGuiaTipoRel()
    {
        return $this->guiaTipoRel;
    }

    /**
     * @param mixed $guiaTipoRel
     */
    public function setGuiaTipoRel($guiaTipoRel): void
    {
        $this->guiaTipoRel = $guiaTipoRel;
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



}
