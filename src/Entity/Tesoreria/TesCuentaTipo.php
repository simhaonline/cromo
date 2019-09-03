<?php


namespace App\Entity\Tesoreria;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Tesoreria\TesCuentaTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TesCuentaTipo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="codigo_cuenta_tipo_pk",type="integer")
     */
    private $codigoCuentaTipoPK;

    /**
     * @ORM\Column(name="nombre" ,type="string")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TesCuenta", mappedBy="cuentaTipoRel")
     */
    private $cuentaCuentaTipoRel;

    /**
     * @return mixed
     */
    public function getCodigoCuentaTipoPK()
    {
        return $this->codigoCuentaTipoPK;
    }

    /**
     * @param mixed $codigoCuentaTipoPK
     */
    public function setCodigoCuentaTipoPK($codigoCuentaTipoPK): void
    {
        $this->codigoCuentaTipoPK = $codigoCuentaTipoPK;
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
    public function getCuentaCuentaTipoRel()
    {
        return $this->cuentaCuentaTipoRel;
    }

    /**
     * @param mixed $cuentaCuentaTipoRel
     */
    public function setCuentaCuentaTipoRel($cuentaCuentaTipoRel): void
    {
        $this->cuentaCuentaTipoRel = $cuentaCuentaTipoRel;
    }

}