<?php

namespace App\Entity\Seguridad;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Seguridad\SegUsuarioModeloRepository")
 */
class SegUsuarioModelo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_seguridad_usuario_modelo_pk", unique=true)
     */
    private $codigoSeguridadUsuarioModeloPk;

    /**
     * @ORM\Column(name="codigo_usuario_fk", type="string")
     */
    private $codigoUsuarioFk;

    /**
     * @ORM\Column(name="codigo_gen_modelo_fk", type="string", length=80)
     */
    private $codigoGenModeloFk;

    /**
     * @ORM\Column(name="nuevo", type="boolean", options={"default"=false})
     */
    private $nuevo;

    /**
     * @ORM\Column(name="lista", type="boolean", options={"default"=false})
     */
    private $lista;

    /**
     * @ORM\Column(name="detalle", type="boolean", options={"default"=false})
     */
    private $detalle;

    /**
     * @ORM\Column(name="autorizar", type="boolean", options={"default"=false})
     */
    private $autorizar;

    /**
     * @ORM\Column(name="aprobar", type="boolean", options={"default"=false})
     */
    private $aprobar;

    /**
     * @ORM\Column(name="anular", type="boolean", options={"default"=false})
     */
    private $anular;

//    /**
//     * @ORM\ManyToOne(targetEntity="App\Entity\Seguridad\Usuario", inversedBy="seguridadUsuarioModeloUsuarioRel")
//     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="username")
//     */
//    protected $usuarioRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenModelo", inversedBy="seguridadUsuarioModeloGenModeloRel")
     * @ORM\JoinColumn(name="codigo_gen_modelo_fk", referencedColumnName="codigo_modelo_pk")
     */
    protected $genModeloRel;
    /**
     * @return mixed
     */
    public function getCodigoSeguridadUsuarioModeloPk()
    {
        return $this->codigoSeguridadUsuarioModeloPk;
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
    public function getCodigoGenModeloFk()
    {
        return $this->codigoGenModeloFk;
    }

    /**
     * @param mixed $codigoGenModeloFk
     */
    public function setCodigoGenModeloFk($codigoGenModeloFk)
    {
        $this->codigoGenModeloFk = $codigoGenModeloFk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNuevo()
    {
        return $this->nuevo;
    }

    /**
     * @param mixed $nuevo
     */
    public function setNuevo($nuevo)
    {
        $this->nuevo = $nuevo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLista()
    {
        return $this->lista;
    }

    /**
     * @param mixed $lista
     */
    public function setLista($lista)
    {
        $this->lista = $lista;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param mixed $detalle
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAutorizar()
    {
        return $this->autorizar;
    }

    /**
     * @param mixed $autorizar
     */
    public function setAutorizar($autorizar)
    {
        $this->autorizar = $autorizar;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAprobar()
    {
        return $this->aprobar;
    }

    /**
     * @param mixed $aprobar
     */
    public function setAprobar($aprobar)
    {
        $this->aprobar = $aprobar;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnular()
    {
        return $this->anular;
    }

    /**
     * @param mixed $anular
     */
    public function setAnular($anular)
    {
        $this->anular = $anular;
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

    /**
     * @return mixed
     */
    public function getGenModeloRel()
    {
        return $this->genModeloRel;
    }

    /**
     * @param mixed $genModeloRel
     */
    public function setGenModeloRel($genModeloRel)
    {
        $this->genModeloRel = $genModeloRel;
        return $this;
    }



}
