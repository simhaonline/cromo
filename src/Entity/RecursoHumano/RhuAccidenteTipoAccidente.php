<?php


namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAccidenteTipoAccidenteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuAccidenteTipoAccidente
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_accidente_tipo_accidente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAccidenteTipoAccidentePk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getCodigoAccidenteTipoAccidentePk()
    {
        return $this->codigoAccidenteTipoAccidentePk;
    }

    /**
     * @param mixed $codigoAccidenteTipoAccidentePk
     */
    public function setCodigoAccidenteTipoAccidentePk($codigoAccidenteTipoAccidentePk): void
    {
        $this->codigoAccidenteTipoAccidentePk = $codigoAccidenteTipoAccidentePk;
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