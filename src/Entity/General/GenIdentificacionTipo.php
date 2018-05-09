<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenIdentificacionTipoRepository")
 */
class GenIdentificacionTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_identificacion_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIdentificacionTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max=50,
     *     maxMessage="El campo no puede contener mas de 50 caracteres"
     * )
     */
    private $nombre;

}

