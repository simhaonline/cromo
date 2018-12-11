<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenEventoRepository")
 */
class GenEvento
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="codigo_evento_pk")
     */
    private $codigoEventoPk;

    /**
     * @ORM\Column(type="string", length=20, name="icono",nullable =true)
     */
    private $icono;

    /**
     * @ORM\Column(type="string", length=60, name="titulo",nullable=true)
     */
    private $titulo;

    /**
     * @ORM\Column(type="datetime", name="desde", nullable=true)
     */
    private $fechaDesde;

    /**
     * @ORM\Column(type="datetime", name="hasta", nullable=true)
     */
    private $fechaHasta;

    /**
     * @ORM\Column(type="string", length=20, name="descripcion",nullable =true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=100, name="color",nullable =true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=20, name="usuario",nullable =true)
     */
    private $usuario;

    /**
     * @return mixed
     */
    public function getCodigoEventoPk()
    {
        return $this->codigoEventoPk;
    }

    /**
     * @param mixed $codigoEventoPk
     */
    public function setCodigoEventoPk($codigoEventoPk): void
    {
        $this->codigoEventoPk = $codigoEventoPk;
    }

    /**
     * @return mixed
     */
    public function getIcono()
    {
        return $this->icono;
    }

    /**
     * @param mixed $icono
     */
    public function setIcono($icono): void
    {
        $this->icono = $icono;
    }

    /**
     * @return mixed
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo): void
    {
        $this->titulo = $titulo;
    }

    /**
     * @return mixed
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * @param mixed $fechaDesde
     */
    public function setFechaDesde($fechaDesde): void
    {
        $this->fechaDesde = $fechaDesde;
    }

    /**
     * @return mixed
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * @param mixed $fechaHasta
     */
    public function setFechaHasta($fechaHasta): void
    {
        $this->fechaHasta = $fechaHasta;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }
}
