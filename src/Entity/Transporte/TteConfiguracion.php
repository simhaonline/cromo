<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteConfiguracionRepository")
 */
class TteConfiguracion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoConfiguracionPk;

    /**
     * @ORM\Column(name="usuario_rndc", type="string", length=50, nullable=true)
     */
    private $usuarioRndc;

    /**
     * @ORM\Column(name="clave_rndc", type="string", length=50, nullable=true)
     */
    private $claveRndc;

    /**
     * @ORM\Column(name="empresa_rndc", type="string", length=50, nullable=true)
     */
    private $empresaRndc;

    /**
     * @return mixed
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * @param mixed $codigoConfiguracionPk
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk): void
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;
    }

    /**
     * @return mixed
     */
    public function getUsuarioRndc()
    {
        return $this->usuarioRndc;
    }

    /**
     * @param mixed $usuarioRndc
     */
    public function setUsuarioRndc($usuarioRndc): void
    {
        $this->usuarioRndc = $usuarioRndc;
    }

    /**
     * @return mixed
     */
    public function getClaveRndc()
    {
        return $this->claveRndc;
    }

    /**
     * @param mixed $claveRndc
     */
    public function setClaveRndc($claveRndc): void
    {
        $this->claveRndc = $claveRndc;
    }

    /**
     * @return mixed
     */
    public function getEmpresaRndc()
    {
        return $this->empresaRndc;
    }

    /**
     * @param mixed $empresaRndc
     */
    public function setEmpresaRndc($empresaRndc): void
    {
        $this->empresaRndc = $empresaRndc;
    }



}

