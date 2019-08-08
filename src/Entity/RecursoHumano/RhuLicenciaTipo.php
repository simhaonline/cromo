<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuAdicionalRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuLicenciaTipo
{
    public $infoLog = [
        "primaryKey" => "codigoLicenciaTipoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_licencia_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLicenciaTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */
    private $nombre;

    /**
     * @ORM\Column(name="afecta_salud", type="boolean")
     */
    private $afectaSalud = false;
    /**
     * @ORM\Column(name="ausentismo", type="boolean")
     */
    private $ausentismo = false;
    /**
     * @ORM\Column(name="maternidad", type="boolean")
     */
    private $maternidad = false;

    /**
     * @ORM\Column(name="paternidad", type="boolean")
     */
    private $paternidad = false;
    /**
     * @ORM\Column(name="remunerada", type="boolean", nullable=true)
     */
    private $remunerada = false;

    /**
     * @ORM\Column(name="suspension_contrato_trabajo", type="boolean", nullable=false)
     */
    private $suspensionContratoTrabajo = false;

    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */
    private $codigoPagoConceptoFk;

    /**
     * @ORM\Column(name="tipo_novedad_turno", type="string", length=5, nullable=true)
     */
    private $tipoNovedadTurno;
}
