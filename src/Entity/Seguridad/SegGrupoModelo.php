<?php

namespace App\Entity\Seguridad;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Seguridad\SegGrupoModeloRepository")
 */
class SegGrupoModelo
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_grupo_modelo_pk", unique=true)
     */
    private $codigoGrupoModeloPk;

    /**
     * @ORM\Column(name="codigo_grupo_fk", type="integer")
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="codigo_modelo_fk", type="string", length=80)
     */
    private $codigoModeloFk;

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

    /**
     * @return mixed
     */
    public function getCodigoGrupoModeloPk()
    {
        return $this->codigoGrupoModeloPk;
    }

    /**
     * @param mixed $codigoGrupoModeloPk
     */
    public function setCodigoGrupoModeloPk($codigoGrupoModeloPk): void
    {
        $this->codigoGrupoModeloPk = $codigoGrupoModeloPk;
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
    public function getCodigoModeloFk()
    {
        return $this->codigoModeloFk;
    }

    /**
     * @param mixed $codigoModeloFk
     */
    public function setCodigoModeloFk($codigoModeloFk): void
    {
        $this->codigoModeloFk = $codigoModeloFk;
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
    public function setNuevo($nuevo): void
    {
        $this->nuevo = $nuevo;
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
    public function setLista($lista): void
    {
        $this->lista = $lista;
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
    public function setDetalle($detalle): void
    {
        $this->detalle = $detalle;
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
    public function setAutorizar($autorizar): void
    {
        $this->autorizar = $autorizar;
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
    public function setAprobar($aprobar): void
    {
        $this->aprobar = $aprobar;
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
    public function setAnular($anular): void
    {
        $this->anular = $anular;
    }



}
