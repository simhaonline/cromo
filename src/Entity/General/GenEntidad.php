<?php


namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenEntidadRepository")
 */
class GenEntidad
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=80)
     */
    private $codigoEntidadPk;

    /**
     * @ORM\Column(name="modulo",type="string",length=20)
     */
    private $modulo;

    /**
     * @ORM\Column(name="funcion",type="string",length=100)
     */
    private $funcion;

    /**
     * @ORM\Column(name="grupo",type="string",length=100)
     */
    private $grupo;

    /**
     * @ORM\Column(name="entidad",type="string",length=100)
     */
    private $entidad;

    /**
     * @ORM\Column(name="ruta_repositorio",type="string",length=100)
     */
    private $rutaRepositorio;

    /**
     * @ORM\Column(name="ruta_entidad",type="string",length=100)
     */
    private $rutaEntidad;

    /**
     * @ORM\Column(name="activo",type="boolean")
     */
    private $activo = true;

    /**
     * @ORM\Column(name="ruta_formulario",type="string",length=50)
     */
    private $rutaFormulario;

    /**
     * @ORM\Column(name="json_lista", type="text", nullable=true)
     */
    private $jsonLista;

    /**
     * @ORM\Column(name="json_excel", type="text", nullable=true)
     */
    private $jsonExcel;

    /**
     * @ORM\Column(name="json_filtro", type="text", nullable=true)
     */
    private $jsonFiltro;

    /**
     * @ORM\Column(name="dql_lista", type="text", nullable=true)
     */
    private $dqlLista;

    /**
     * @ORM\Column(name="personalizado",type="boolean")
     */
    private $personalizado = false;

    /**
     * @return mixed
     */
    public function getCodigoEntidadPk()
    {
        return $this->codigoEntidadPk;
    }

    /**
     * @param mixed $codigoEntidadPk
     */
    public function setCodigoEntidadPk($codigoEntidadPk): void
    {
        $this->codigoEntidadPk = $codigoEntidadPk;
    }

    /**
     * @return mixed
     */
    public function getRutaRepositorio()
    {
        return $this->rutaRepositorio;
    }

    /**
     * @param mixed $rutaRepositorio
     */
    public function setRutaRepositorio($rutaRepositorio): void
    {
        $this->rutaRepositorio = $rutaRepositorio;
    }

    /**
     * @return mixed
     */
    public function getRutaEntidad()
    {
        return $this->rutaEntidad;
    }

    /**
     * @param mixed $rutaEntidad
     */
    public function setRutaEntidad($rutaEntidad): void
    {
        $this->rutaEntidad = $rutaEntidad;
    }

    /**
     * @return mixed
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * @param mixed $activo
     */
    public function setActivo($activo): void
    {
        $this->activo = $activo;
    }

    /**
     * @return mixed
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * @param mixed $modulo
     */
    public function setModulo($modulo): void
    {
        $this->modulo = $modulo;
    }

    /**
     * @return mixed
     */
    public function getRutaFormulario()
    {
        return $this->rutaFormulario;
    }

    /**
     * @param mixed $rutaFormulario
     */
    public function setRutaFormulario($rutaFormulario): void
    {
        $this->rutaFormulario = $rutaFormulario;
    }

    /**
     * @return mixed
     */
    public function getJsonLista()
    {
        return $this->jsonLista;
    }

    /**
     * @param mixed $jsonLista
     */
    public function setJsonLista($jsonLista): void
    {
        $this->jsonLista = $jsonLista;
    }

    /**
     * @return mixed
     */
    public function getJsonExcel()
    {
        return $this->jsonExcel;
    }

    /**
     * @param mixed $jsonExcel
     */
    public function setJsonExcel($jsonExcel): void
    {
        $this->jsonExcel = $jsonExcel;
    }

    /**
     * @return mixed
     */
    public function getJsonFiltro()
    {
        return $this->jsonFiltro;
    }

    /**
     * @param mixed $jsonFiltro
     */
    public function setJsonFiltro($jsonFiltro): void
    {
        $this->jsonFiltro = $jsonFiltro;
    }

    /**
     * @return mixed
     */
    public function getDqlLista()
    {
        return $this->dqlLista;
    }

    /**
     * @param mixed $dqlLista
     */
    public function setDqlLista($dqlLista): void
    {
        $this->dqlLista = $dqlLista;
    }

    /**
     * @return mixed
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * @param mixed $entidad
     */
    public function setEntidad($entidad): void
    {
        $this->entidad = $entidad;
    }

    /**
     * @return mixed
     */
    public function getPersonalizado()
    {
        return $this->personalizado;
    }

    /**
     * @param mixed $personalizado
     */
    public function setPersonalizado($personalizado): void
    {
        $this->personalizado = $personalizado;
    }

    /**
     * @return mixed
     */
    public function getFuncion()
    {
        return $this->funcion;
    }

    /**
     * @param mixed $funcion
     */
    public function setFuncion($funcion): void
    {
        $this->funcion = $funcion;
    }

    /**
     * @return mixed
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * @param mixed $grupo
     */
    public function setGrupo($grupo): void
    {
        $this->grupo = $grupo;
    }



}

