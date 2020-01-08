<?php

namespace App\Entity\Seguridad;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Seguridad\SegUsuarioSegmentoRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class SegUsuarioSegmento
{
    public $infoLog = [
        "primaryKey" => "codigoSeguridadUsuarioSegmentoPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="codigo_seguridad_usuario_segmento_pk", unique=true)
     */
    private $codigoSeguridadUsuarioSegmentoPk;

    /**
     * @ORM\Column(name="codigo_usuario_fk", type="string")
     */
    private $codigoUsuarioFk;

    /**
     * @ORM\Column(name="codigo_segmento_fk", type="string",precision=10)
     */
    private $codigoSegmentoFk;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Seguridad\Usuario", inversedBy="usuariosSegmentosSegmento")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="username")
     */
    protected $usuarioRel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\General\GenSegmento", inversedBy="usuariosSegmentosSegmentoRel")
     * @ORM\JoinColumn(name="codigo_segmento_fk", referencedColumnName="codigo_segmento_pk")
     */
    protected $segmentoRel;

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
    public function getCodigoSeguridadUsuarioSegmentoPk()
    {
        return $this->codigoSeguridadUsuarioSegmentoPk;
    }

    /**
     * @param mixed $codigoSeguridadUsuarioSegmentoPk
     */
    public function setCodigoSeguridadUsuarioSegmentoPk($codigoSeguridadUsuarioSegmentoPk): void
    {
        $this->codigoSeguridadUsuarioSegmentoPk = $codigoSeguridadUsuarioSegmentoPk;
    }

    /**
     * @return mixed
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * @param mixed $codigoUsuarioFk
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk): void
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;
    }

    /**
     * @return mixed
     */
    public function getCodigoSegmentoFk()
    {
        return $this->codigoSegmentoFk;
    }

    /**
     * @param mixed $codigoSegmentoFk
     */
    public function setCodigoSegmentoFk($codigoSegmentoFk): void
    {
        $this->codigoSegmentoFk = $codigoSegmentoFk;
    }

    /**
     * @return mixed
     */
    public function getUsuarioRel()
    {
        return $this->usuarioRel;
    }

    /**
     * @param mixed $usuarioRel
     */
    public function setUsuarioRel($usuarioRel): void
    {
        $this->usuarioRel = $usuarioRel;
    }

    /**
     * @return mixed
     */
    public function getSegmentoRel()
    {
        return $this->segmentoRel;
    }

    /**
     * @param mixed $segmentoRel
     */
    public function setSegmentoRel($segmentoRel): void
    {
        $this->segmentoRel = $segmentoRel;
    }


}
