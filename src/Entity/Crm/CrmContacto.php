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
     * @ORM\Column(name="direccion", type="string", length=200,nullable=true)
     */
    private $direccion;
    /**
     * @ORM\Column(name="telefono", type="string", length=80,nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="saludo", type="string", length=20,nullable=true)
     */
    private $saludo;

    /**
     * @ORM\Column(name="correo", type="string", length=150,nullable=true)
     */
    private $correo;

    /**
     * @ORM\Column(name="cargo", type="string", length=100,nullable=true)
     */
    private $cargo;

    /**
     * @ORM\Column(name="especialidad", type="string", length=100,nullable=true)
     */
    private $especialidad;

    /**
     * @ORM\Column(name="horario_visita", type="string", length=100,nullable=true)
     */
    private $horarioVisita;

    /**
     * @ORM\Column(name="secretaria", type="string", length=150,nullable=true)
     */
    private $secretaria;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Crm\CrmVisita", mappedBy="contactoRel", cascade={"remove","persist"})
     */
    protected $visitaContactoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Crm\CrmNegocio", mappedBy="contactoRel")
     */
    protected $negociosContactoRel;

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

    /**
     * @return mixed
     */
    public function getSaludo()
    {
        return $this->saludo;
    }

    /**
     * @param mixed $saludo
     */
    public function setSaludo($saludo): void
    {
        $this->saludo = $saludo;
    }

    /**
     * @return mixed
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param mixed $correo
     */
    public function setCorreo($correo): void
    {
        $this->correo = $correo;
    }

    /**
     * @return mixed
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * @param mixed $cargo
     */
    public function setCargo($cargo): void
    {
        $this->cargo = $cargo;
    }

    /**
     * @return mixed
     */
    public function getEspecialidad()
    {
        return $this->especialidad;
    }

    /**
     * @param mixed $especialidad
     */
    public function setEspecialidad($especialidad): void
    {
        $this->especialidad = $especialidad;
    }

    /**
     * @return mixed
     */
    public function getHorarioVisita()
    {
        return $this->horarioVisita;
    }

    /**
     * @param mixed $horarioVisita
     */
    public function setHorarioVisita($horarioVisita): void
    {
        $this->horarioVisita = $horarioVisita;
    }

    /**
     * @return mixed
     */
    public function getNegociosContactoRel()
    {
        return $this->negociosContactoRel;
    }

    /**
     * @param mixed $negociosContactoRel
     */
    public function setNegociosContactoRel($negociosContactoRel): void
    {
        $this->negociosContactoRel = $negociosContactoRel;
    }

    /**
     * @return mixed
     */
    public function getSecretaria()
    {
        return $this->secretaria;
    }

    /**
     * @param mixed $secretaria
     */
    public function setSecretaria($secretaria): void
    {
        $this->secretaria = $secretaria;
    }



}

