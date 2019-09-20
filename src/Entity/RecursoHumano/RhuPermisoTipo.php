<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuPermisoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuPermisoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoPermisoTipoPk",
        "todos" => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_permiso_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPermisoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuPermiso", mappedBy="permisoTipoRel")
     */
    protected $permisosPermisoTipoRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoPermisoTipoPk()
    {
        return $this->codigoPermisoTipoPk;
    }

    /**
     * @param mixed $codigoPermisoTipoPk
     */
    public function setCodigoPermisoTipoPk($codigoPermisoTipoPk): void
    {
        $this->codigoPermisoTipoPk = $codigoPermisoTipoPk;
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
    public function getPermisosPermisoTipoRel()
    {
        return $this->permisosPermisoTipoRel;
    }

    /**
     * @param mixed $permisosPermisoTipoRel
     */
    public function setPermisosPermisoTipoRel($permisosPermisoTipoRel): void
    {
        $this->permisosPermisoTipoRel = $permisosPermisoTipoRel;
    }


}
