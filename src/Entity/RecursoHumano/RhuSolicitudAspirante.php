<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuSolicitudAspiranteRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuSolicitudAspirante
{
    public $infoLog = [
        "primaryKey" => "codigoSolicitudAspirantePk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_solicitud_aspirante_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoSolicitudAspirantePk;

    /**
     * @ORM\Column(name="codigo_solicitud_fk", type="integer", nullable=true)
     */
    private $codigoSolicitudFk;

    /**
     * @ORM\Column(name="codigo_aspirante_fk", type="integer", nullable=true)
     */
    private $codigoAspiranteFk;

    /**
     * @ORM\Column(name="estado_aprobado", type="boolean", options={"default":false})
     */
    private $estadoAprobado=false;

    /**
     * @ORM\Column(name="codigo_motivo_descarte_solicitud_aspirante_fk", type="integer", nullable=true)
     */
    private $codigoMotivoDescarteSolicitudAspiranteFk;

    /**
     * @ORM\Column(name="fecha_descarte", type="datetime", nullable=true)
     */
    private $fechaDescarte;

    /**
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuAspirante", inversedBy="solicitudesAspiranteRel")
     * @ORM\JoinColumn(name="codigo_aspirante_fk", referencedColumnName="codigo_aspirante_pk")
     */
    protected $aspiranteRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", inversedBy="solicitudesAspirantesSolicitudRel")
     * @ORM\JoinColumn(name="codigo_solicitud_fk", referencedColumnName="codigo_solicitud_pk")
     */
    protected $seleccionRequisitoRel;

    /**
     * @return array
     */
    public function getInfoLog(): array
    {
        return $this->infoLog;
    }

    /**
     * @param array $infoLog
     */
    public function setInfoLog(array $infoLog): void
    {
        $this->infoLog = $infoLog;
    }

    /**
     * @return mixed
     */
    public function getCodigoSolicitudAspirantePk()
    {
        return $this->codigoSolicitudAspirantePk;
    }

    /**
     * @param mixed $codigoSolicitudAspirantePk
     */
    public function setCodigoSolicitudAspirantePk($codigoSolicitudAspirantePk): void
    {
        $this->codigoSolicitudAspirantePk = $codigoSolicitudAspirantePk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSolicitudFk()
    {
        return $this->codigoSolicitudFk;
    }

    /**
     * @param mixed $codigoSolicitudFk
     */
    public function setCodigoSolicitudFk($codigoSolicitudFk): void
    {
        $this->codigoSolicitudFk = $codigoSolicitudFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoAspiranteFk()
    {
        return $this->codigoAspiranteFk;
    }

    /**
     * @param mixed $codigoAspiranteFk
     */
    public function setCodigoAspiranteFk($codigoAspiranteFk): void
    {
        $this->codigoAspiranteFk = $codigoAspiranteFk;
    }

    /**
     * @return mixed
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * @param mixed $estadoAprobado
     */
    public function setEstadoAprobado($estadoAprobado): void
    {
        $this->estadoAprobado = $estadoAprobado;
    }

    /**
     * @return mixed
     */
    public function getCodigoMotivoDescarteSolicitudAspiranteFk()
    {
        return $this->codigoMotivoDescarteSolicitudAspiranteFk;
    }

    /**
     * @param mixed $codigoMotivoDescarteSolicitudAspiranteFk
     */
    public function setCodigoMotivoDescarteSolicitudAspiranteFk($codigoMotivoDescarteSolicitudAspiranteFk): void
    {
        $this->codigoMotivoDescarteSolicitudAspiranteFk = $codigoMotivoDescarteSolicitudAspiranteFk;
    }

    /**
     * @return mixed
     */
    public function getFechaDescarte()
    {
        return $this->fechaDescarte;
    }

    /**
     * @param mixed $fechaDescarte
     */
    public function setFechaDescarte($fechaDescarte): void
    {
        $this->fechaDescarte = $fechaDescarte;
    }

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
    }

    /**
     * @return mixed
     */
    public function getAspiranteRel()
    {
        return $this->aspiranteRel;
    }

    /**
     * @param mixed $aspiranteRel
     */
    public function setAspiranteRel($aspiranteRel): void
    {
        $this->aspiranteRel = $aspiranteRel;
    }

    /**
     * @return mixed
     */
    public function getSeleccionRequisitoRel()
    {
        return $this->seleccionRequisitoRel;
    }

    /**
     * @param mixed $seleccionRequisitoRel
     */
    public function setSeleccionRequisitoRel($seleccionRequisitoRel): void
    {
        $this->seleccionRequisitoRel = $seleccionRequisitoRel;
    }



}
