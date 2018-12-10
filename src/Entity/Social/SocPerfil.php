<?php

namespace App\Entity\Social;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Social\SocPerfilRepository")
 */
class SocPerfil
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="codigo_perfil_pk", type="string", nullable=false, length=100)
     */
    private $codigoPerfilPk;

    /**
     * @ORM\Column(name="codigo_usuario_fk", type="string", length=25, nullable=false)
     */
    private $codigoUsuarioFk;

    /**
     * @ORM\Column(name="estado_cuenta", type="boolean", options={"default":true})
     */
    private $estadoCuenta;

    /**
     * @ORM\Column(name="empresa_pertenece", type="string", nullable=true, length=100)
     */
    private $empresaPertenece;

    /**
     * @ORM\Column(name="acerca_de_mi", type="string", nullable=true, length=512)
     */
    private $acercaDeMi;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Social\SocAmigo", mappedBy="perfilRel", cascade={"remove","persist"})
     */
    protected $amigoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Social\SocSolicitudAmigo", mappedBy="perfilSolicitanteRel", cascade={"remove","persist"})
     */
    protected $solicitanteRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Social\SocSolicitudAmigo", mappedBy="perfilSolicitadoRel", cascade={"remove","persist"})
     */
    protected $solicitadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Seguridad\Usuario", inversedBy="perfilUsuarioRel")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="username")
     */
    protected $usuarioRel;

    /**
     * @return mixed
     */
    public function getCodigoPerfilPk()
    {
        return $this->codigoPerfilPk;
    }

    /**
     * @param mixed $codigoPerfilPk
     */
    public function setCodigoPerfilPk($codigoPerfilPk)
    {
        $this->codigoPerfilPk = $codigoPerfilPk;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getEstadoCuenta()
    {
        return $this->estadoCuenta;
    }

    /**
     * @param mixed $estadoCuenta
     */
    public function setEstadoCuenta($estadoCuenta)
    {
        $this->estadoCuenta = $estadoCuenta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmpresaPertenece()
    {
        return $this->empresaPertenece;
    }

    /**
     * @param mixed $empresaPertenece
     */
    public function setEmpresaPertenece($empresaPertenece)
    {
        $this->empresaPertenece = $empresaPertenece;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAcercaDeMi()
    {
        return $this->acercaDeMi;
    }

    /**
     * @param mixed $acercaDeMi
     */
    public function setAcercaDeMi($acercaDeMi)
    {
        $this->acercaDeMi = $acercaDeMi;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmigoRel()
    {
        return $this->amigoRel;
    }

    /**
     * @param mixed $amigoRel
     */
    public function setAmigoRel($amigoRel)
    {
        $this->amigoRel = $amigoRel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSolicitanteRel()
    {
        return $this->solicitanteRel;
    }

    /**
     * @param mixed $solicitanteRel
     */
    public function setSolicitanteRel($solicitanteRel)
    {
        $this->solicitanteRel = $solicitanteRel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSolicitadoRel()
    {
        return $this->solicitadoRel;
    }

    /**
     * @param mixed $solicitadoRel
     */
    public function setSolicitadoRel($solicitadoRel)
    {
        $this->solicitadoRel = $solicitadoRel;
        return $this;
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
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsuarioRel()
    {
        return $this->usuarioRel;
    }

    /**
     * @param mixed $usuarioRel
     */
    public function setUsuarioRel($usuarioRel)
    {
        $this->usuarioRel = $usuarioRel;
        return $this;
    }




}
