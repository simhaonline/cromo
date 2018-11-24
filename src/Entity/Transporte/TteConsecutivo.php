<?php


namespace App\Entity\Transporte;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transporte\TteConsecutivoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class TteConsecutivo
{
    public $infoLog = [
        "primaryKey" => "codigoConsecutivoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoConsecutivoPk;

    /**
     * @ORM\Column(name="intermediacion", type="integer")
     */
    private $intermediacion = 0;

    /**
     * @return mixed
     */
    public function getCodigoConsecutivoPk()
    {
        return $this->codigoConsecutivoPk;
    }

    /**
     * @param mixed $codigoConsecutivoPk
     */
    public function setCodigoConsecutivoPk( $codigoConsecutivoPk ): void
    {
        $this->codigoConsecutivoPk = $codigoConsecutivoPk;
    }

    /**
     * @return mixed
     */
    public function getIntermediacion()
    {
        return $this->intermediacion;
    }

    /**
     * @param mixed $intermediacion
     */
    public function setIntermediacion( $intermediacion ): void
    {
        $this->intermediacion = $intermediacion;
    }



}

