<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenModeloRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenModelo
{
    public $infoLog = [
        "primaryKey" => "codigoModeloPk",
        "todos"     => true,
    ];
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
     * @ORM\OneToMany(targetEntity="App\Entity\Seguridad\SegUsuarioModelo", mappedBy="modeloRel", cascade={"persist", "remove"})
     */
    protected $seguridadUsuarioModeloModeloRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\General\GenNotificacionTipo", mappedBy="modeloRel", cascade={"persist", "remove"})
     */
    protected $notificacionTipoModeloRel;

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
    public function setCodigoModuloFk($codigoModuloFk)
    {
        $this->codigoModuloFk = $codigoModuloFk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotificacionTipoModeloRel()
    {
        return $this->notificacionTipoModeloRel;
    }

    /**
     * @param mixed $notificacionTipoModeloRel
     */
    public function setNotificacionTipoModeloRel($notificacionTipoModeloRel)
    {
        $this->notificacionTipoModeloRel = $notificacionTipoModeloRel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeguridadUsuarioModeloModeloRel()
    {
        return $this->seguridadUsuarioModeloModeloRel;
    }

    /**
     * @param mixed $seguridadUsuarioModeloModeloRel
     */
    public function setSeguridadUsuarioModeloModeloRel($seguridadUsuarioModeloModeloRel)
    {
        $this->seguridadUsuarioModeloModeloRel = $seguridadUsuarioModeloModeloRel;
        return $this;
    }



}

