<?php


namespace App\Entity\Crm;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Crm\CrmContactoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CrmContacto
{
    public $infoLog = [
        "primaryKey" => "codigoContactoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="codigo_contacto_pk",type="integer")
     */
    private $codigoContactoPk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=80)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=150, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\Column(name="direccion", type="string", length=80,nullable=true)
     */
    private $direccion;
    /**
     * @ORM\Column(name="telefono", type="string", length=20,nullable=true)
     */
    private $telefono;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Crm\CrmVisita", mappedBy="contactoRel", cascade={"remove","persist"})
     */
    protected $visitaContactoRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoContactoPk()
    {
        return $this->codigoContactoPk;
    }

    /**
     * @param mixed $codigoContactoPk
     */
    public function setCodigoContactoPk($codigoContactoPk): void
    {
        $this->codigoContactoPk = $codigoContactoPk;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param mixed $numeroIdentificacion
     */
    public function setNumeroIdentificacion($numeroIdentificacion): void
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
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
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion): void
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono): void
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getVisitaContactoRel()
    {
        return $this->visitaContactoRel;
    }

    /**
     * @param mixed $visitaContactoRel
     */
    public function setVisitaContactoRel($visitaContactoRel): void
    {
        $this->visitaContactoRel = $visitaContactoRel;
    }



}

