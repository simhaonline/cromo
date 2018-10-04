<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuProgramacionRepository")
 */
class RhuProgramacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPk;

    /**
     * @ORM\Column(name="codigo_pago_tipo_fk", options={"default": 0}, type="integer", nullable=false)
     */
    private $codigoPagoTipoFk;

    /**
     * @ORM\Column(name="fecha_desde",  type="date", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(name="fecha_hasta",  type="date", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(name="nombre", options={"default": 0}, type="string",length=80, nullable=true)
     * @Assert\Length(
     *      max = 80,
     *      maxMessage = "La cantidad máxima de caracteres para el nombre es de 80"
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(name="dias", options={"default": 0}, type="integer")
     */
    private $dias = 0;

    /**
     * @ORM\Column(name="codigo_grupo_fk", options={"default": 0}, type="integer", nullable=true)
     */
    private $codigoGrupoFk;

    /**
     * @ORM\Column(name="fecha_pagado", type="datetime", nullable=true)
     */
    private $fechaPagado;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default": false}, type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="estado_aprobado", options={"default": false}, type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAprobado = false;

    /**
     * @ORM\Column(name="estado_anulado", options={"default": false}, type="boolean",options={"default" : false}, nullable=true)
     */
    private $estadoAnulado = false;



}
