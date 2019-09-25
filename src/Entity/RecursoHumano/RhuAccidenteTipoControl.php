<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAccidenteTipoControlRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAccidenteTipoControl
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_accidente_tipo_control_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccidenteTipoControlPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoAccidenteTipoControlPk()
    {
        return $this->codigoAccidenteTipoControlPk;
    }

    /**
     * @param mixed $codigoAccidenteTipoControlPk
     */
    public function setCodigoAccidenteTipoControlPk($codigoAccidenteTipoControlPk): void
    {
        $this->codigoAccidenteTipoControlPk = $codigoAccidenteTipoControlPk;
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


}