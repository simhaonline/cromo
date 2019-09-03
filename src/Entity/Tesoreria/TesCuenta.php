<?php


namespace App\Entity\Tesoreria;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Tesoreria\TesCuentaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TesCuenta
{
    public $infoLog = [
        "primaryKey" => "codigoCuentaPagarPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_cuenta_pk",type="integer")
     */
    private $codigoCuentaPk;

    /**
     * @ORM\Column(name="codigo_cuenta_tipo_fk" , type="integer", nullable=true)
     */
    private $codigoCuentaTipoFk;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="TesCuentaTipo" ,inversedBy="cuentaCuentaTipoRel")
     * @ORM\JoinColumn(name="codigo_cuenta_tipo_fk" , referencedColumnName="codigo_cuenta_tipo_pk")
     */
    private $cuentaTipoRel;

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
    public function getCodigoCuentaTipoFk()
    {
        return $this->codigoCuentaTipoFk;
    }

    /**
     * @param mixed $codigoCuentaTipoFk
     */
    public function setCodigoCuentaTipoFk($codigoCuentaTipoFk): void
    {
        $this->codigoCuentaTipoFk = $codigoCuentaTipoFk;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getCuentaTipoRel()
    {
        return $this->cuentaTipoRel;
    }

    /**
     * @param mixed $cuentaTipoRel
     */
    public function setCuentaTipoRel($cuentaTipoRel): void
    {
        $this->cuentaTipoRel = $cuentaTipoRel;
    }



}