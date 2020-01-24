<?php


namespace App\Entity\Turno;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Turno\TurOperacionRepository")
 */
class TurOperacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_operacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoOperacionPk;

    /**
     * @ORM\Column(name="codigo_operacion_tipo_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionTipoFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */
    private $codigoClienteFk;

    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="operacionesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="operacionRel")
     */
    protected $puestosOperacionRel;

    /**
     * @return mixed
     */
    public function getCodigoOperacionPk()
    {
        return $this->codigoOperacionPk;
    }

    /**
     * @param mixed $codigoOperacionPk
     */
    public function setCodigoOperacionPk($codigoOperacionPk): void
    {
        $this->codigoOperacionPk = $codigoOperacionPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOperacionTipoFk()
    {
        return $this->codigoOperacionTipoFk;
    }

    /**
     * @param mixed $codigoOperacionTipoFk
     */
    public function setCodigoOperacionTipoFk($codigoOperacionTipoFk): void
    {
        $this->codigoOperacionTipoFk = $codigoOperacionTipoFk;
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
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * @param mixed $nombreCorto
     */
    public function setNombreCorto($nombreCorto): void
    {
        $this->nombreCorto = $nombreCorto;
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
    public function getPuestosOperacionRel()
    {
        return $this->puestosOperacionRel;
    }

    /**
     * @param mixed $puestosOperacionRel
     */
    public function setPuestosOperacionRel($puestosOperacionRel): void
    {
        $this->puestosOperacionRel = $puestosOperacionRel;
    }

}