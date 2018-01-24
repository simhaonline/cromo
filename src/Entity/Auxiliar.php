<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuxiliarRepository")
 */
class Auxiliar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoAuxiliarPk;

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */
    private $numeroIdentificacion;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=150, nullable=true)
     */
    private $nombreCorto;

    /**
     * @ORM\OneToMany(targetEntity="DespachoRecogidaAuxiliar", mappedBy="auxiliarRel")
     */
    protected $despachosRecogidasAuxiliaresAuxiliarRel;

    /**
     * @return mixed
     */
    public function getCodigoAuxiliarPk()
    {
        return $this->codigoAuxiliarPk;
    }

    /**
     * @param mixed $codigoAuxiliarPk
     */
    public function setCodigoAuxiliarPk($codigoAuxiliarPk): void
    {
        $this->codigoAuxiliarPk = $codigoAuxiliarPk;
    }

    /**
     * @return mixed
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * @param mixed $numeroIdentificacion
     */
    public function setNumeroIdentificacion($numeroIdentificacion): void
    {
        $this->numeroIdentificacion = $numeroIdentificacion;
    }

    /**
     * @return mixed
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * @param mixed $nombreCorto
     */
    public function setNombreCorto($nombreCorto): void
    {
        $this->nombreCorto = $nombreCorto;
    }

    /**
     * @return mixed
     */
    public function getDespachosRecogidasAuxiliaresAuxiliarRel()
    {
        return $this->despachosRecogidasAuxiliaresAuxiliarRel;
    }

    /**
     * @param mixed $despachosRecogidasAuxiliaresAuxiliarRel
     */
    public function setDespachosRecogidasAuxiliaresAuxiliarRel($despachosRecogidasAuxiliaresAuxiliarRel): void
    {
        $this->despachosRecogidasAuxiliaresAuxiliarRel = $despachosRecogidasAuxiliaresAuxiliarRel;
    }



}

