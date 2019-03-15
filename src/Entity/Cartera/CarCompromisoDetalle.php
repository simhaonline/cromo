<?php

namespace App\Entity\Cartera;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cartera\CarCompromisoDetalleRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class CarCompromisoDetalle
{
    public $infoLog = [
        "primaryKey" => "codigoCompromisoDetallePk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_compromiso_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCompromisoDetallePk;

    /**
     * @ORM\Column(name="codigo_compromiso_fk", type="integer", nullable=true)
     */
    private $codigoCompromisoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk", type="integer", nullable=true)
     */
    private $codigoCuentaCobrarFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarCompromiso", inversedBy="compromisosDetallesCompromisoRel")
     * @ORM\JoinColumn(name="codigo_compromiso_fk", referencedColumnName="codigo_compromiso_pk")
     */
    protected $compromisoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cartera\CarCuentaCobrar", inversedBy="compromisosDetallesCuentaCobrarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarRel;

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
    public function getCodigoCompromisoDetallePk()
    {
        return $this->codigoCompromisoDetallePk;
    }

    /**
     * @param mixed $codigoCompromisoDetallePk
     */
    public function setCodigoCompromisoDetallePk($codigoCompromisoDetallePk): void
    {
        $this->codigoCompromisoDetallePk = $codigoCompromisoDetallePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCompromisoFk()
    {
        return $this->codigoCompromisoFk;
    }

    /**
     * @param mixed $codigoCompromisoFk
     */
    public function setCodigoCompromisoFk($codigoCompromisoFk): void
    {
        $this->codigoCompromisoFk = $codigoCompromisoFk;
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
    public function setCodigoCuentaCobrarFk($codigoCuentaCobrarFk): void
    {
        $this->codigoCuentaCobrarFk = $codigoCuentaCobrarFk;
    }

    /**
     * @return mixed
     */
    public function getCompromisoRel()
    {
        return $this->compromisoRel;
    }

    /**
     * @param mixed $compromisoRel
     */
    public function setCompromisoRel($compromisoRel): void
    {
        $this->compromisoRel = $compromisoRel;
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
    public function setCuentaCobrarRel($cuentaCobrarRel): void
    {
        $this->cuentaCobrarRel = $cuentaCobrarRel;
    }



}
