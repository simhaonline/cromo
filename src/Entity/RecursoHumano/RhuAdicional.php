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

    /**
     * @ORM\Column(name="codigo_concepto_fk", type="string", length=10, nullable=true)
     */
    private $codigoConceptoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="vr_valor", options={"default":0}, type="float")
     */
    private $vrValor = 0;

    /**
     * @ORM\Column(name="permanente", options={"default":false}, type="boolean")
     */
    private $permanente = false;

    /**
     * @ORM\Column(name="aplica_dia_laborado", options={"default":false}, type="boolean")
     */
    private $aplicaDiaLaborado = false;

    /**
     * @ORM\Column(name="aplica_prima", options={"default":false}, options={"default":false}, type="boolean", nullable=true)
     */
    private $aplicaPrima = false;

    /**
     * @ORM\Column(name="aplica_cesantia", options={"default":false}, type="boolean", nullable=true)
     */
    private $aplicaCesantia = false;

    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     * @Assert\Length(
     *     max=250,
     *     maxMessage="El campo no puede contener mas de 250 caracteres"
     * )
     */
    private $detalle;

    /**
     * @ORM\Column(name="estado_inactivo", options={"default":false}, type="boolean")
     */
    private $estadoInactivo = false;

    /**
     * @ORM\Column(name="estado_inactivo_periodo", options={"default":false}, type="boolean")
     */
    private $estadoInactivoPeriodo = false;

    /**
     * @ORM\Column(name="estado_autorizado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;


}
