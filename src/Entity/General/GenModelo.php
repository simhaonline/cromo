<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenModeloRepository")
 */
class GenModelo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=80, name="codigo_modelo_pk")
     */
    private $codigoModeloPk;

    /**
     * @ORM\Column(name="codigo_modulo_fk", length=80, type="string")
     */
    private $codigoModuloFk;

    /**
     * @ORM\Column(name="codigo_funcion_fk", length=30, type="string")
     */
    private $codigoFuncionFk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", length=50, type="string")
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="descontinuado", type="boolean", options={"default" : false}, nullable=true)
     */
    private $descontinuado = false;

    /**
     * @return mixed
     */
    public function getCodigoModeloPk()
    {
        return $this->codigoModeloPk;
    }

    /**
     * @param mixed $codigoModeloPk
     */
    public function setCodigoModeloPk($codigoModeloPk): void
    {
        $this->codigoModeloPk = $codigoModeloPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoModuloFk()
    {
        return $this->codigoModuloFk;
    }

    /**
     * @param mixed $codigoModuloFk
     */
    public function setCodigoModuloFk($codigoModuloFk): void
    {
        $this->codigoModuloFk = $codigoModuloFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoFuncionFk()
    {
        return $this->codigoFuncionFk;
    }

    /**
     * @param mixed $codigoFuncionFk
     */
    public function setCodigoFuncionFk($codigoFuncionFk): void
    {
        $this->codigoFuncionFk = $codigoFuncionFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoGrupoFk()
    {
        return $this->codigoGrupoFk;
    }

    /**
     * @param mixed $codigoGrupoFk
     */
    public function setCodigoGrupoFk($codigoGrupoFk): void
    {
        $this->codigoGrupoFk = $codigoGrupoFk;
    }

    /**
     * @return mixed
     */
    public function getDescontinuado()
    {
        return $this->descontinuado;
    }

    /**
     * @param mixed $descontinuado
     */
    public function setDescontinuado($descontinuado): void
    {
        $this->descontinuado = $descontinuado;
    }




}

