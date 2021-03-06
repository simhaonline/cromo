<?php

namespace App\Entity\General;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\General\GenImagenRepository")
 */
class GenImagen
{


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", name="codigo_imagen_pk", length=20, nullable=false)
     */
    private $codigoImagenPk;

    /**
     * @ORM\Column(type="blob", name="imagen", nullable=true)
     */
    private $imagen;

    /**
     * @ORM\Column(type="string", length=5, name="extension", nullable=true)
     */
    private $extension;

    /**
     * @return mixed
     */
    public function getCodigoImagenPk()
    {
        return $this->codigoImagenPk;
    }

    /**
     * @param mixed $codigoImagenPk
     */
    public function setCodigoImagenPk($codigoImagenPk): void
    {
        $this->codigoImagenPk = $codigoImagenPk;
    }

    /**
     * @return mixed
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * @param mixed $imagen
     */
    public function setImagen($imagen): void
    {
        $this->imagen = $imagen;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension): void
    {
        $this->extension = $extension;
    }






}
