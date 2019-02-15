<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarAplicacioneRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarAplicacion
{
    public $infoLog = [
        "primaryKey" => "codigoAplicacionPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_aplicacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoAplicacionPk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk", type="integer", nullable=true)
     */
    private $codigoCuentaCobrarFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_aplicacion_fk", type="integer", nullable=true)
     */
    private $codigoCuentaCobrarAplicacionFk;

    /**
     * @ORM\Column(name="vr_aplicacion", type="float", nullable=true)
     */
    private $vrAplicacion = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarCuentaCobrar", inversedBy="aplicacionsCuentaCobrarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarRel;

    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrar", inversedBy="aplicacionsCuentaCobrarAplicacionRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_aplicacion_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarAplicacionRel;

    /**
     * @return mixed
     */
    public function getCodigoAplicacionPk()
    {
        return $this->codigoAplicacionPk;
    }

    /**
     * @param mixed $codigoAplicacionPk
     */
    public function setCodigoAplicacionPk( $codigoAplicacionPk ): void
    {
        $this->codigoAplicacionPk = $codigoAplicacionPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarFk()
    {
        return $this->codigoCuentaCobrarFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarFk
     */
    public function setCodigoCuentaCobrarFk( $codigoCuentaCobrarFk ): void
    {
        $this->codigoCuentaCobrarFk = $codigoCuentaCobrarFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCobrarAplicacionFk()
    {
        return $this->codigoCuentaCobrarAplicacionFk;
    }

    /**
     * @param mixed $codigoCuentaCobrarAplicacionFk
     */
    public function setCodigoCuentaCobrarAplicacionFk( $codigoCuentaCobrarAplicacionFk ): void
    {
        $this->codigoCuentaCobrarAplicacionFk = $codigoCuentaCobrarAplicacionFk;
    }

    /**
     * @return mixed
     */
    public function getVrAplicacion()
    {
        return $this->vrAplicacion;
    }

    /**
     * @param mixed $vrAplicacion
     */
    public function setVrAplicacion( $vrAplicacion ): void
    {
        $this->vrAplicacion = $vrAplicacion;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarRel()
    {
        return $this->cuentaCobrarRel;
    }

    /**
     * @param mixed $cuentaCobrarRel
     */
    public function setCuentaCobrarRel( $cuentaCobrarRel ): void
    {
        $this->cuentaCobrarRel = $cuentaCobrarRel;
    }

    /**
     * @return mixed
     */
    public function getCuentaCobrarAplicacionRel()
    {
        return $this->cuentaCobrarAplicacionRel;
    }

    /**
     * @param mixed $cuentaCobrarAplicacionRel
     */
    public function setCuentaCobrarAplicacionRel( $cuentaCobrarAplicacionRel ): void
    {
        $this->cuentaCobrarAplicacionRel = $cuentaCobrarAplicacionRel;
    }


}
