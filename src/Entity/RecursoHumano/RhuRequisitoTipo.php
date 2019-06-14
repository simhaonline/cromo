<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuRequisitoTipoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuRequisitoTipo
{
    public $infoLog = [
        "primaryKey" => "codigoRequisitoTipo",
        "todos"     => true,
    ];
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_requisito_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRequisitoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuRequisito", mappedBy="requisitoTipoRel")
     */
    protected $requisitosRequisitoTipoRel;

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
    public function getCodigoRequisitoTipoPk()
    {
        return $this->codigoRequisitoTipoPk;
    }

    /**
     * @param mixed $codigoRequisitoTipoPk
     */
    public function setCodigoRequisitoTipoPk($codigoRequisitoTipoPk): void
    {
        $this->codigoRequisitoTipoPk = $codigoRequisitoTipoPk;
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
    public function getRequisitosRequisitoTipoRel()
    {
        return $this->requisitosRequisitoTipoRel;
    }

    /**
     * @param mixed $requisitosRequisitoTipoRel
     */
    public function setRequisitosRequisitoTipoRel($requisitosRequisitoTipoRel): void
    {
        $this->requisitosRequisitoTipoRel = $requisitosRequisitoTipoRel;
    }


}
