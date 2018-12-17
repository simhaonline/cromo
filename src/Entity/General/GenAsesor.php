<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenAsesorRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenAsesor
{
    public $infoLog = [
        "primaryKey" => "codigoAsesorPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_asesor_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAsesorPk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=15, nullable=false)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */
    private $celular;

    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvMovimiento",mappedBy="asesorRel")
     */
    protected $movimientosAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarCuentaCobrar",mappedBy="asesorRel")
     */
    protected $cuentasCobrarAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvRemision",mappedBy="asesorRel")
     */
    protected $remisionesAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarRecibo",mappedBy="asesorRel")
     */
    protected $reciboAsesorRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarAnticipo",mappedBy="asesorRel")
     */
    protected $anticipoAsesorRel;

    /**
     * @return mixed
     */
    public function getCodigoAsesorPk()
    {
        return $this->codigoAsesorPk;
    }

    /**
     * @param mixed $codigoAsesorPk
     */
    public function setCodigoAsesorPk($codigoAsesorPk): void
    {
        $this->codigoAsesorPk = $codigoAsesorPk;
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
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param mixed $celular
     */
    public function setCelular($celular): void
    {
        $this->celular = $celular;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getMovimientosAsesorRel()
    {
        return $this->movimientosAsesorRel;
    }

    /**
     * @param mixed $movimientosAsesorRel
     */
    public function setMovimientosAsesorRel($movimientosAsesorRel): void
    {
        $this->movimientosAsesorRel = $movimientosAsesorRel;
    }

    /**
     * @return mixed
     */
    public function getRemisionesAsesorRel()
    {
        return $this->remisionesAsesorRel;
    }

    /**
     * @param mixed $remisionesAsesorRel
     */
    public function setRemisionesAsesorRel($remisionesAsesorRel): void
    {
        $this->remisionesAsesorRel = $remisionesAsesorRel;
    }

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
    public function getCuentasCobrarAsesorRel()
    {
        return $this->cuentasCobrarAsesorRel;
    }

    /**
     * @param mixed $cuentasCobrarAsesorRel
     */
    public function setCuentasCobrarAsesorRel($cuentasCobrarAsesorRel): void
    {
        $this->cuentasCobrarAsesorRel = $cuentasCobrarAsesorRel;
    }

    /**
     * @return mixed
     */
    public function getReciboAsesorRel()
    {
        return $this->reciboAsesorRel;
    }

    /**
     * @param mixed $reciboAsesorRel
     */
    public function setReciboAsesorRel($reciboAsesorRel): void
    {
        $this->reciboAsesorRel = $reciboAsesorRel;
    }

    /**
     * @return mixed
     */
    public function getAnticipoAsesorRel()
    {
        return $this->anticipoAsesorRel;
    }

    /**
     * @param mixed $anticipoAsesorRel
     */
    public function setAnticipoAsesorRel($anticipoAsesorRel): void
    {
        $this->anticipoAsesorRel = $anticipoAsesorRel;
    }



}

