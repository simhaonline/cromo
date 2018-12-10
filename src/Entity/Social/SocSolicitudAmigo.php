<?php

namespace App\Entity\Social;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Social\SocSolicitudAmigoRepository")
 */
class SocSolicitudAmigo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_solicitud_amigo_pk", unique=true)
     */
    private $codigoSolicitudAmigoPk;

    /**
     * @ORM\Column(name="codigo_perfil_solicitante_fk", type="string", length=100, nullable=false)
     */
    private $codigoPerfilSolicitanteFk;

    /**
     * @ORM\Column(name="codigo_perfil_solicitado_fk", type="string", length=100, nullable=false)
     */
    private $codigoPerfilSolicitadoFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Social\SocPerfil", inversedBy="solicitanteRel")
     * @ORM\JoinColumn(name="codigo_perfil_solicitante_fk", referencedColumnName="codigo_perfil_pk")
     */
    protected $perfilSolicitanteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Social\SocPerfil", inversedBy="solicitadoRel")
     * @ORM\JoinColumn(name="codigo_perfil_solicitado_fk", referencedColumnName="codigo_perfil_pk")
     */
    protected $perfilSolicitadoRel;

    /**
     * @return mixed
     */
    public function getCodigoSolicitudAmigoPk()
    {
        return $this->codigoSolicitudAmigoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoPerfilSolicitanteFk()
    {
        return $this->codigoPerfilSolicitanteFk;
    }

    /**
     * @param mixed $codigoPerfilSolicitanteFk
     */
    public function setCodigoPerfilSolicitanteFk($codigoPerfilSolicitanteFk)
    {
        $this->codigoPerfilSolicitanteFk = $codigoPerfilSolicitanteFk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoPerfilSolicitadoFk()
    {
        return $this->codigoPerfilSolicitadoFk;
    }

    /**
     * @param mixed $codigoPerfilSolicitadoFk
     */
    public function setCodigoPerfilSolicitadoFk($codigoPerfilSolicitadoFk)
    {
        $this->codigoPerfilSolicitadoFk = $codigoPerfilSolicitadoFk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPerfilSolicitanteRel()
    {
        return $this->perfilSolicitanteRel;
    }

    /**
     * @param mixed $perfilSolicitanteRel
     */
    public function setPerfilSolicitanteRel($perfilSolicitanteRel)
    {
        $this->perfilSolicitanteRel = $perfilSolicitanteRel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPerfilSolicitadoRel()
    {
        return $this->perfilSolicitadoRel;
    }

    /**
     * @param mixed $perfilSolicitadoRel
     */
    public function setPerfilSolicitadoRel($perfilSolicitadoRel)
    {
        $this->perfilSolicitadoRel = $perfilSolicitadoRel;
        return $this;
    }



}
