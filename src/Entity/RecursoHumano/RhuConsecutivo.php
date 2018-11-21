<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConsecutivoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuConsecutivo
{
    public $infoLog = [
        "primaryKey" => "codigoConsecutivoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_consecutivo_pk", type="integer")
     */
    private $codigoConsecutivoPk;

    /**
     * @ORM\Column(name="pago", type="integer", nullable=true, options={"default":0})
     */
    private $pago = 0;

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
    public function getPago()
    {
        return $this->pago;
    }

    /**
     * @param mixed $pago
     */
    public function setPago( $pago ): void
    {
        $this->pago = $pago;
    }



}
