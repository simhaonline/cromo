<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConfiguracionProvisionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuConfiguracionProvision
{
    public $infoLog = [
        "primaryKey" => "codigoConfiguracionProvisionPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_provision_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoConfiguracionProvisionPk;

    /**
     * @ORM\Column(name="tipo", type="string", length=20, nullable=true)
     */
    private $tipo;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_costo_clase_fk", type="string", length=10, nullable=true)
     */
    private $codigoCostoClaseFk;

    /**
     * @ORM\Column(name="codigo_cuenta_debito_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaDebitoFk;

    /**
     * @ORM\Column(name="codigo_cuenta_credito_fk", type="string", length=20, nullable=true)
     */
    private $codigoCuentaCreditoFk;

    /**
     * @return mixed
     */
    public function getCodigoConfiguracionProvisionPk()
    {
        return $this->codigoConfiguracionProvisionPk;
    }

    /**
     * @param mixed $codigoConfiguracionProvisionPk
     */
    public function setCodigoConfiguracionProvisionPk($codigoConfiguracionProvisionPk): void
    {
        $this->codigoConfiguracionProvisionPk = $codigoConfiguracionProvisionPk;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
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
    public function getCodigoCostoClaseFk()
    {
        return $this->codigoCostoClaseFk;
    }

    /**
     * @param mixed $codigoCostoClaseFk
     */
    public function setCodigoCostoClaseFk($codigoCostoClaseFk): void
    {
        $this->codigoCostoClaseFk = $codigoCostoClaseFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaDebitoFk()
    {
        return $this->codigoCuentaDebitoFk;
    }

    /**
     * @param mixed $codigoCuentaDebitoFk
     */
    public function setCodigoCuentaDebitoFk($codigoCuentaDebitoFk): void
    {
        $this->codigoCuentaDebitoFk = $codigoCuentaDebitoFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoCuentaCreditoFk()
    {
        return $this->codigoCuentaCreditoFk;
    }

    /**
     * @param mixed $codigoCuentaCreditoFk
     */
    public function setCodigoCuentaCreditoFk($codigoCuentaCreditoFk): void
    {
        $this->codigoCuentaCreditoFk = $codigoCuentaCreditoFk;
    }




}
