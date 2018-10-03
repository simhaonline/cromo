<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuRecaudoRepository")
 */
class RhuRecaudo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recaudo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRecaudoPk;
}
