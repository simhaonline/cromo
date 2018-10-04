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

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */
    private $codigoEntidadSaludFk;

    /**
     * @ORM\Column(name="numero", options={"default":0}, type="integer", nullable=true)
     */
    private $numero = 0;

    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\Column(name="vr_total", options={"default":0}, type="float", nullable=true)
     */
    private $vrTotal = 0;

    /**
     * @ORM\Column(name="estado_autorizado", options={"default":false}, type="boolean", nullable=true)
     */
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */
    private $fechaPago;

    /**
     * @ORM\Column(name="estado_impreso", options={"default":false}, type="boolean", nullable=true)
     */
    private $estadoImpreso = false;

    /**
     * @ORM\Column(name="recibo_caja", type="string", length=20 , nullable=true)
     */
    private  $reciboCaja;

    /**
     * @ORM\Column(name="valor_recibo_caja", type="float", nullable=true)
     */
    private  $ValorReciboCaja;

    /**
     * @ORM\Column(name="estado_cerrado", options={"default":false}, type="boolean", nullable=true)
     */
    private $estadoCerrado = false;

    /**
     * @ORM\Column(name="vr_total_entidad", options={"default":0}, type="float", nullable=true)
     */
    private $vrTotalEntidad = 0;
}
