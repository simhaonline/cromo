<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehiculoRepository")
 */
class Vehiculo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false, unique=true)
     */
    private $codigoVehiculoPk;

    /**
     * @ORM\Column(name="placa", type="string", length=10, nullable=true)
     */
    private $placa;

    /**
     * @ORM\Column(name="placa_remolque", type="string", length=10, nullable=true)
     */
    private $placaRemolque;

    /**
     * @ORM\Column(name="modelo", type="integer", nullable=true)
     */
    private $modelo;

    /**
     * @ORM\Column(name="modelo_repotenciado", type="integer", nullable=true)
     */
    private $modeloRepotenciado;

    /**
     * @ORM\Column(name="motor", type="string", length=50, nullable=true)
     */
    private $motor;

    /**
     * @ORM\Column(name="numero_ejes", type="integer", nullable=true)
     */
    private $numeroEjes;

    /**
     * @ORM\Column(name="chasis", type="string", length=50, nullable=true)
     */
    private $chasis;

    /**
     * @ORM\Column(name="serie", type="string", length=50, nullable=true)
     */
    private $serie;

    /**
     * @ORM\Column(name="peso_vacio", type="integer", nullable=true)
     */
    private $pesoVacio = 0;

    /**
     * @ORM\Column(name="capacidad", type="integer", nullable=true)
     */
    private $capacidad = 0;

    /**
     * @ORM\Column(name="celular", type="string", length=30, nullable=true)
     */
    private $celular;

    /**
     * @ORM\Column(name="registro_nacional_carga", type="string", length=50, nullable=true)
     */
    private $registroNacionalCarga;

}

