<?php


namespace App\Entity\Financiero;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Financiero\FinRegistroInconsistenciaRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class FinRegistroInconsistencia
{
    public $infoLog = [
        "primaryKey" => "codigoRegistroInconsistenciaPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_registro_inconsistencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRegistroInconsistenciaPk;

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="numero_prefijo", type="string", length=20, nullable=true)
     */
    private $numeroPrefijo;

    /**
     * @ORM\Column(name="descripcion", type="string", length=100, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="codigo_comprobante_fk", type="string", length=20, nullable=true)
     */
    private $codigoComprobanteFk;

    /**
     * @ORM\Column(name="utilidad", type="string", length=100, nullable=true)
     */
    private $utilidad;

    /**
     * @ORM\Column(name="diferencia", type="float", nullable=true)
     */
    private $diferencia = 0;

    /**
     * @return mixed
     */
    public function getCodigoRegistroInconsistenciaPk()
    {
        return $this->codigoRegistroInconsistenciaPk;
    }

    /**
     * @param mixed $codigoRegistroInconsistenciaPk
     */
    public function setCodigoRegistroInconsistenciaPk($codigoRegistroInconsistenciaPk): void
    {
        $this->codigoRegistroInconsistenciaPk = $codigoRegistroInconsistenciaPk;
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
    public function getNumeroPrefijo()
    {
        return $this->numeroPrefijo;
    }

    /**
     * @param mixed $numeroPrefijo
     */
    public function setNumeroPrefijo($numeroPrefijo): void
    {
        $this->numeroPrefijo = $numeroPrefijo;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getCodigoComprobanteFk()
    {
        return $this->codigoComprobanteFk;
    }

    /**
     * @param mixed $codigoComprobanteFk
     */
    public function setCodigoComprobanteFk($codigoComprobanteFk): void
    {
        $this->codigoComprobanteFk = $codigoComprobanteFk;
    }

    /**
     * @return mixed
     */
    public function getUtilidad()
    {
        return $this->utilidad;
    }

    /**
     * @param mixed $utilidad
     */
    public function setUtilidad($utilidad): void
    {
        $this->utilidad = $utilidad;
    }

    /**
     * @return mixed
     */
    public function getDiferencia()
    {
        return $this->diferencia;
    }

    /**
     * @param mixed $diferencia
     */
    public function setDiferencia($diferencia): void
    {
        $this->diferencia = $diferencia;
    }


}

