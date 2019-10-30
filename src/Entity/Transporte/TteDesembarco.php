<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteDesembarcoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteDesembarco
{
    public $infoLog = [
        "primaryKey" => "codigoDesembarcoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_desembarco_pk")
     */
    private $codigoDesembarcoPk;

    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */
    private $codigoGuiaFk;

    /**
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */
    private $codigoDespachoFk;

    /**
     * @ORM\Column(name="codigo_operacion_origen_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionOrigenFk;

    /**
     * @ORM\Column(name="codigo_operacion_destino_fk", type="string", length=20, nullable=true)
     */
    private $codigoOperacionDestinoFk;

    /**
     * @ORM\ManyToOne(targetEntity="TteGuia", inversedBy="desembarcosGuiaRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    private $guiaRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteDespacho", inversedBy="desembarcosDespachoRel")
     * @ORM\JoinColumn(name="codigo_despacho_fk", referencedColumnName="codigo_despacho_pk")
     */
    private $despachoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="desembarcosOperacionOrigenRel")
     * @ORM\JoinColumn(name="codigo_operacion_origen_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionOrigenRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteOperacion", inversedBy="desembarcosOperacionDestinoRel")
     * @ORM\JoinColumn(name="codigo_operacion_destino_fk", referencedColumnName="codigo_operacion_pk")
     */
    private $operacionDestinoRel;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

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
    public function getCodigoDesembarcoPk()
    {
        return $this->codigoDesembarcoPk;
    }

    /**
     * @param mixed $codigoDesembarcoPk
     */
    public function setCodigoDesembarcoPk($codigoDesembarcoPk): void
    {
        $this->codigoDesembarcoPk = $codigoDesembarcoPk;
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
    public function getCodigoGuiaFk()
    {
        return $this->codigoGuiaFk;
    }

    /**
     * @param mixed $codigoGuiaFk
     */
    public function setCodigoGuiaFk($codigoGuiaFk): void
    {
        $this->codigoGuiaFk = $codigoGuiaFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoDespachoFk()
    {
        return $this->codigoDespachoFk;
    }

    /**
     * @param mixed $codigoDespachoFk
     */
    public function setCodigoDespachoFk($codigoDespachoFk): void
    {
        $this->codigoDespachoFk = $codigoDespachoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOperacionOrigenFk()
    {
        return $this->codigoOperacionOrigenFk;
    }

    /**
     * @param mixed $codigoOperacionOrigenFk
     */
    public function setCodigoOperacionOrigenFk($codigoOperacionOrigenFk): void
    {
        $this->codigoOperacionOrigenFk = $codigoOperacionOrigenFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoOperacionDestinoFk()
    {
        return $this->codigoOperacionDestinoFk;
    }

    /**
     * @param mixed $codigoOperacionDestinoFk
     */
    public function setCodigoOperacionDestinoFk($codigoOperacionDestinoFk): void
    {
        $this->codigoOperacionDestinoFk = $codigoOperacionDestinoFk;
    }

    /**
     * @return mixed
     */
    public function getGuiaRel()
    {
        return $this->guiaRel;
    }

    /**
     * @param mixed $guiaRel
     */
    public function setGuiaRel($guiaRel): void
    {
        $this->guiaRel = $guiaRel;
    }

    /**
     * @return mixed
     */
    public function getDespachoRel()
    {
        return $this->despachoRel;
    }

    /**
     * @param mixed $despachoRel
     */
    public function setDespachoRel($despachoRel): void
    {
        $this->despachoRel = $despachoRel;
    }

    /**
     * @return mixed
     */
    public function getOperacionOrigenRel()
    {
        return $this->operacionOrigenRel;
    }

    /**
     * @param mixed $operacionOrigenRel
     */
    public function setOperacionOrigenRel($operacionOrigenRel): void
    {
        $this->operacionOrigenRel = $operacionOrigenRel;
    }

    /**
     * @return mixed
     */
    public function getOperacionDestinoRel()
    {
        return $this->operacionDestinoRel;
    }

    /**
     * @param mixed $operacionDestinoRel
     */
    public function setOperacionDestinoRel($operacionDestinoRel): void
    {
        $this->operacionDestinoRel = $operacionDestinoRel;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }


}

