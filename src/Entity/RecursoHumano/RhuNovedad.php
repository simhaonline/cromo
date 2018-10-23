<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuNovedadRepository")
 */
class RhuNovedad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNovedadPk;
    
    /**
     * @ORM\Column(name="codigo_novedad_tipo_fk", type="integer", nullable=true)
     */
    private $codigoNovedadTipoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer", nullable=true)
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="cantidad", options={"deafult" : 0}, type="float", nullable=true)
     */
    private $cantidad = 0;

    /**
     * @ORM\Column(name="codigo_entidad_fk", type="integer", nullable=true)
     */
    private $codigoEntidadFk;

    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="usuario", type="string", length=25, nullable=true)
     */
    private $usuario;

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

    /**
     * @ORM\Column(name="prorroga", options={"deafult" : false}, type="boolean", nullable=true)
     */
    private $prorroga = false;

    /**
     * @ORM\Column(name="transcripcion", options={"deafult" : false}, type="boolean", nullable=true)
     */
    private $transcripcion = false;

    /**
     * @ORM\Column(name="legalizado", options={"deafult" : false}, type="boolean", nullable=true)
     */
    private $legalizado = false;

    /**
     * @ORM\Column(name="porcentaje", options={"deafult" : 0}, type="float", nullable=true)
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="vr_valor", options={"deafult" : 0}, type="float", nullable=true)
     */
    private $vrValor = 0;

    /**
     * @ORM\Column(name="vr_ibc", options={"deafult" : 0}, type="float", nullable=true)
     */
    private $vrIbc = 0;

    /**
     * @ORM\Column(name="dias_ibc", options={"deafult" : 0}, type="float", nullable=true)
     */
    private $diasIbc = 0;

    /**
     * @ORM\Column(name="vr_ibc_propuesto", options={"deafult" : 0}, type="float", nullable=true)
     */
    private $vrIbcPropuesto = 0;

    /**
     * @ORM\Column(name="vr_propuesto", options={"deafult" : 0}, type="float", nullable=true)
     */
    private $vrPropuesto = 0;


}
