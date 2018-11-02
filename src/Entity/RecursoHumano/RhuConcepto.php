<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConceptoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoConceptoPk"},message="Ya existe el código del pago concepto")
 */
class RhuConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_concepto_pk", type="string", length=10)
     */
    private $codigoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="porcentaje", type="float", options={"default":0} , nullable=true)
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="genera_ingreso_base_prestacion" ,options={"default":false} ,type="boolean", nullable=true)
     */
    private $generaIngresoBasePrestacion = false;

    /**
     * @ORM\Column(name="genera_ingreso_base_cotizacion",options={"default":false} , type="boolean", nullable=true)
     */
    private $generaIngresoBaseCotizacion = false;

    /**
     * @ORM\Column(name="operacion", type="integer",options={"default":0} , nullable=true)
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="adicional",options={"default":false} , type="boolean", nullable=true)
     */
    private $adicional = false;

    /**
     * BON=Bonificacion, DES=Descuento, COM=Comision
     * @ORM\Column(name="adicional_tipo", type="string", length=3, nullable=true)
     */
    private $adicionalTipo;

    /**
     * @ORM\Column(name="auxilio_transporte",options={"default":false} , type="boolean", nullable=true)
     */
    private $auxilioTransporte = false;

    /**
     * @ORM\Column(name="incapacidad",options={"default":false} , type="boolean", nullable=true)
     */
    private $incapacidad = false;

    /**
     * @ORM\Column(name="incapacidad_entidad",options={"default":false} , type="boolean", nullable=true)
     */
    private $incapacidadEntidad = false;

    /**
     * @ORM\Column(name="pension",options={"default":false} , type="boolean", nullable=true)
     */
    private $pension = false;

    /**
     * @ORM\Column(name="salud",options={"default":false} , type="boolean", nullable=true)
     */
    private $salud = false;

    /**
     * @ORM\Column(name="vacacion",options={"default":false} , type="boolean", nullable=true)
     */
    private $vacacion = false;

    /**
     * @ORM\Column(name="comision",options={"default":false} , type="boolean", nullable=true)
     */
    private $comision = false;

    /**
     * @ORM\Column(name="cesantia",options={"default":false} , type="boolean", nullable=true)
     */
    private $cesantia = false;

    /**
     * @ORM\Column(name="numero_dian", type="integer", nullable=true)
     */
    private $numeroDian;

    /**
     * @ORM\Column(name="recargo_nocturno",options={"default":false} ,type="boolean", nullable=true)
     */
    private $recargoNocturno = false;

    /**
     * @ORM\Column(name="fondo_solidaridad_pensional",options={"default":false} , type="boolean", nullable=true)
     */
    private $fondoSolidaridadPensional = false;

    /**
     * @ORM\OneToMany(targetEntity="RhuNovedadTipo", mappedBy="conceptoRel")
     */
    protected $novedadesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuCreditoTipo", mappedBy="conceptoRel")
     */
    protected $creditosTiposConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuEmbargoTipo", mappedBy="conceptoRel")
     */
    protected $embargosTiposConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAdicional", mappedBy="conceptoRel")
     */
    protected $adicionalesConceptoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoDetalle", mappedBy="conceptoRel")
     */
    protected $pagosDetallesConceptoRel;
}