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

    /**
     * @ORM\Column(name="configuracion", type="string", length=20, nullable=true)
     */
    private $configuracion;

    /**
     * @ORM\Column(name="fecha_vence_tecnicomecanica", type="date", nullable=true)
     */
    private $fechaVenceTecnicomecanica;

    /**
     * @ORM\Column(name="codigo_aseguradora_fk", type="string", length=20, nullable=true)
     */
    private $codigoAseguradoraFk;

    /**
     * @ORM\Column(name="codigo_color_fk", type="string", length=20, nullable=true)
     */
    private $codigoColorFk;

    /**
     * @ORM\Column(name="codigo_marca_fk", type="string", length=20, nullable=true)
     */
    private $codigoMarcaFk;

    /**
     * @ORM\Column(name="codigo_linea_fk", type="string", length=20, nullable=true)
     */
    private $codigoLineaFk;

    /**
     * @ORM\Column(name="comentario", type="string", length=2000, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\OneToMany(targetEntity="DespachoRecogida", mappedBy="vehiculoRel")
     */
    protected $despachosRecogidasVehiculoRel;

}

