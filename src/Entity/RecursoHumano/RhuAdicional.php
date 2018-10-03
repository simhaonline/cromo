<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAdicionalRepository")
 */
class RhuAdicional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAdicionalPk;
}
