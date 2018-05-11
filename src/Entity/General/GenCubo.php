<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenCuboRepository")
 */
class GenCubo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoCuboPk;

    /**
     * @ORM\Column(name="codigo_entidad_fk",type="string", length=80)
     */
    private $codigoEntidadFk;

    /**
     * @ORM\Column(name="codigo_usuario_fk",type="string",length=25)
     */
    private $codigoUsuarioFk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="json_cubo", type="text", nullable=true)
     */
    private $jsonCubo;

    /**
     * @ORM\Column(name="sql_cubo", type="text", nullable=true)
     */
    private $sqlCubo;

    /**
     * @return mixed
     */
    public function getCodigoCuboPk()
    {
        return $this->codigoCuboPk;
    }

    /**
     * @param mixed $codigoCuboPk
     */
    public function setCodigoCuboPk($codigoCuboPk): void
    {
        $this->codigoCuboPk = $codigoCuboPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoEntidadFk()
    {
        return $this->codigoEntidadFk;
    }

    /**
     * @param mixed $codigoEntidadFk
     */
    public function setCodigoEntidadFk($codigoEntidadFk): void
    {
        $this->codigoEntidadFk = $codigoEntidadFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * @param mixed $codigoUsuarioFk
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk): void
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;
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
    public function getJsonCubo()
    {
        return $this->jsonCubo;
    }

    /**
     * @param mixed $jsonCubo
     */
    public function setJsonCubo($jsonCubo): void
    {
        $this->jsonCubo = $jsonCubo;
    }

    /**
     * @return mixed
     */
    public function getSqlCubo()
    {
        return $this->sqlCubo;
    }

    /**
     * @param mixed $sqlCubo
     */
    public function setSqlCubo($sqlCubo): void
    {
        $this->sqlCubo = $sqlCubo;
    }

}

