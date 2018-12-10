<?php

namespace App\Entity\Social;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Social\SocAmigoRepository")
 */
class SocAmigo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_amigo_pk", unique=true)
     */
    private $codigoAmigoPk;

    /**
     * @ORM\Column(name="codigo_perfil_fk", type="string", nullable=false, length=100)
     */
    private $codigoPerfilFk;

    /**
     * @ORM\Column(name="codigo_perfil_amigo_fk", type="string", nullable=false, length=100)
     */
    private $codigoPerfilAmigoFk;

    /**
     * @ORM\Column(name="fecha_agregado", type="datetime", nullable=false)
     */
    private $fechaAgregado;

    /**
     * @ORM\Column(name="estado_amistad", type="boolean", options={"default":true})
     */
    private $estadoAmistad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Social\SocPerfil", inversedBy="amigoRel")
     * @ORM\JoinColumn(name="codigo_perfil_fk", referencedColumnName="codigo_perfil_pk")
     */
    protected $perfilRel;

    /**
     * @return mixed
     */
    public function getCodigoAmigoPk()
    {
        return $this->codigoAmigoPk;
    }

    /**
     * @param mixed $codigoAmigoPk
     */
    public function setCodigoAmigoPk($codigoAmigoPk)
    {
        $this->codigoAmigoPk = $codigoAmigoPk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoPerfilFk()
    {
        return $this->codigoPerfilFk;
    }

    /**
     * @param mixed $codigoPerfilFk
     */
    public function setCodigoPerfilFk($codigoPerfilFk)
    {
        $this->codigoPerfilFk = $codigoPerfilFk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoPerfilAmigoFk()
    {
        return $this->codigoPerfilAmigoFk;
    }

    /**
     * @param mixed $codigoPerfilAmigoFk
     */
    public function setCodigoPerfilAmigoFk($codigoPerfilAmigoFk)
    {
        $this->codigoPerfilAmigoFk = $codigoPerfilAmigoFk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaAgregado()
    {
        return $this->fechaAgregado;
    }

    /**
     * @param mixed $fechaAgregado
     */
    public function setFechaAgregado($fechaAgregado)
    {
        $this->fechaAgregado = $fechaAgregado;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEstadoAmistad()
    {
        return $this->estadoAmistad;
    }

    /**
     * @param mixed $estadoAmistad
     */
    public function setEstadoAmistad($estadoAmistad)
    {
        $this->estadoAmistad = $estadoAmistad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPerfilRel()
    {
        return $this->perfilRel;
    }

    /**
     * @param mixed $perfilRel
     */
    public function setPerfilRel($perfilRel)
    {
        $this->perfilRel = $perfilRel;
        return $this;
    }



}
