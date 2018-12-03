<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenCiudadRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class GenCiudad
{
    public $infoLog = [
        "primaryKey" => "codigoCiudadPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_ciudad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCiudadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="codigo_departamento_fk", type="integer")
     */
    private $codigoDepartamentoFk;

    /**
     * @ORM\Column(name="codigo_dane", type="string", length=5)
     */
    private $codigoDane;

    /**
     * @ORM\ManyToOne(targetEntity="GenDepartamento", inversedBy="ciudadesRel")
     * @ORM\JoinColumn(name="codigo_departamento_fk", referencedColumnName="codigo_departamento_pk")
     */
    protected $departamentoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cartera\CarCliente", mappedBy="ciudadRel")
     */
    protected $carClientesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transporte\TteCliente", mappedBy="ciudadRel")
     */
    protected $tteClientesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Turno\TurCliente", mappedBy="ciudadRel")
     */
    protected $turClientesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Financiero\FinTercero", mappedBy="ciudadRel")
     */
    protected $finTercerosCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="ciudadRel")
     */
    protected $rhuAspirantesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="ciudadExpedicionRel")
     */
    protected $rhuAspirantesCiudadExpedicionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuAspirante", mappedBy="ciudadNacimientoRel")
     */
    protected $rhuAspirantesCiudadNacimientoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSolicitud", mappedBy="ciudadRel")
     */
    protected $rhuSolicitudesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="ciudadRel")
     */
    protected $rhuSeleccionesCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="ciudadExpedicionRel")
     */
    protected $rhuSeleccionesCiudadExpedicionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuSeleccion", mappedBy="ciudadNacimientoRel")
     */
    protected $rhuSeleccionesCiudadNacimientoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="ciudadRel")
     */
    protected $rhuEmpleadosCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="ciudadExpedicionRel")
     */
    protected $rhuEmpleadosCiudadExpedicionRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvSucursal", mappedBy="ciudadRel")
     */
    protected $invSucursalesCiuidadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inventario\InvTercero", mappedBy="ciudadRel")
     */
    protected $invTercerosCiuidadRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuEmpleado", mappedBy="ciudadNacimientoRel")
     */
    protected $rhuEmpleadosCiudadNacimientoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuContrato", mappedBy="ciudadContratoRel")
     */
    protected $rhuContratosCiudadContratoRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecursoHumano\RhuContrato", mappedBy="ciudadLaboraRel")
     */
    protected $rhuContratosCiudadLaboraRel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compra\ComProveedor", mappedBy="ciudadRel")
     */
    protected $comProveedoresCiudadRel;

    /**
     * @ORM\OneToMany(targetEntity="GenConfiguracion", mappedBy="ciudadRel")
     */
    protected $configuracionesCiudadRel;
}

