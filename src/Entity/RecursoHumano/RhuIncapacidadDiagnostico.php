<?php

namespace App\Entity\RecursoHumano;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecursoHumano\RhuIncapacidadDiagnosticoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class RhuIncapacidadDiagnostico
{
    public $infoLog = [
        "primaryKey" => "codigoIncapacidadDiagnosticoPk",
        "todos"     => true,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_diagnostico_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncapacidadDiagnosticoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=800, nullable=true)
     */

    private $nombre;
    /**
     * @ORM\Column(name="codigo", type="string", length=10, nullable=true)
     */

    private $codigo;

    /**
     * @ORM\Column(name="codigo_grupo_enfermedad_fk", type="integer", nullable=true)
     */
    private $codigoGrupoEnfermedadFk;




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
    public function getCodigoIcapacidadDiagnosticoPk()
    {
        return $this->codigoIcapacidadDiagnosticoPk;
    }

    /**
     * @param mixed $codigoIcapacidadDiagnosticoPk
     */
    public function setCodigoIcapacidadDiagnosticoPk($codigoIcapacidadDiagnosticoPk): void
    {
        $this->codigoIcapacidadDiagnosticoPk = $codigoIcapacidadDiagnosticoPk;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo): void
    {
        $this->codigo = $codigo;
    }

    /**
     * @return mixed
     */
    public function getCodigoGrupoEnfermedadFk()
    {
        return $this->codigoGrupoEnfermedadFk;
    }

    /**
     * @param mixed $codigoGrupoEnfermedadFk
     */
    public function setCodigoGrupoEnfermedadFk($codigoGrupoEnfermedadFk): void
    {
        $this->codigoGrupoEnfermedadFk = $codigoGrupoEnfermedadFk;
    }

    /**
     * @return mixed
     */
    public function getIncapacidadesIncapacidadDiagnosticoRel()
    {
        return $this->incapacidadesIncapacidadDiagnosticoRel;
    }

    /**
     * @param mixed $incapacidadesIncapacidadDiagnosticoRel
     */
    public function setIncapacidadesIncapacidadDiagnosticoRel($incapacidadesIncapacidadDiagnosticoRel): void
    {
        $this->incapacidadesIncapacidadDiagnosticoRel = $incapacidadesIncapacidadDiagnosticoRel;
    }



}
