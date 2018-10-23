<?php

namespace App\Entity\RecursoHumano;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuConceptoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoPagoConceptoPk"},message="Ya existe el código del pago concepto")
 */
class RhuConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_concepto_pk", type="string", length=10)
     */
    private $codigoPagoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */
    private $nombre;


    /**
     * @ORM\Column(name="porcentaje", type="float")
     */
    private $porcentaje = 0;

    /**
     * @ORM\Column(name="genera_ingreso_base_prestacion", type="boolean")
     */
    private $generaIngresoBasePrestacion = false;

    /**
     * @ORM\Column(name="genera_ingreso_base_cotizacion", type="boolean")
     */
    private $generaIngresoBaseCotizacion = false;

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;

    /**
     * @ORM\Column(name="adicional", type="boolean")
     */
    private $adicional = false;

    /**
     * BON=Bonificacion, DES=Descuento, COM=Comision
     * @ORM\Column(name="adicional_tipo", type="string", length=3, nullable=true)
     */
    private $adicionalTipo = 1;

    /**
     * @ORM\Column(name="auxilio_transporte", type="boolean")
     */
    private $auxilioTransporte = false;

    /**
     * @ORM\Column(name="incapacidad", type="boolean")
     */
    private $incapacidad = false;

    /**
     * @ORM\Column(name="incapacidad_entidad", type="boolean", nullable=true)
     */
    private $incapacidadEntidad = false;

    /**
     * @ORM\Column(name="pension", type="boolean")
     */
    private $pension = false;

    /**
     * @ORM\Column(name="salud", type="boolean")
     */
    private $salud = false;

    /**
     * @ORM\Column(name="vacacion", type="boolean")
     */
    private $vacacion = false;

    /**
     * @ORM\Column(name="comision", type="boolean")
     */
    private $comision = false;

    /**
     * @ORM\Column(name="cesantia", type="boolean")
     */
    private $cesantia = false;

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\Column(name="numero_dian", type="integer", nullable=true)
     */
    private $numeroDian;

    /**
     * @ORM\Column(name="recargo_nocturno", type="boolean", nullable=true)
     */
    private $recargoNocturno = false;

    /**
     * @ORM\Column(name="fondo_solidaridad_pensional", type="boolean")
     */
    private $fondoSolidaridadPensional = false;


}