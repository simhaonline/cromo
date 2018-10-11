<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuCargoRepository")
 */
class RhuEntidad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_pk", type="integer")
     */
    private $codigoEntidadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_entiadad_tipo_fk", type="string", length=10, nullable=true)
     */
    private $codigoEntiadadTipoFk;

    /**
     * @ORM\Column(name="nit", type="string", length=10, nullable=true)
     *   @Assert\Length(
     *     max = 10,
     *     maxMessage="El campo no puede contener mas de 10 caracteres"
     * )
     */
    private $nit;

    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=20, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadSaludRel")
     */
    protected $contratosEntidadSaludRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadPensionRel")
     */
    protected $contratosEntidadPensionRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadCesantiaRel")
     */
    protected $contratosEntidadCesantiaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadCajaRel")
     */
    protected $contratosEntidadCajaRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadTipo", inversedBy="entidadesEntidadTipoRel")
     * @ORM\JoinColumn(name="codigo_entiadad_tipo_fk", referencedColumnName="codigo_entidad_tipo_pk")
     */
    protected $entidadTipoRel;
}
