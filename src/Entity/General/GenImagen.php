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
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="string", name="codigo_imagen_pk", nullable=false)
     */
    private $codigoImagenPk;

    /**
     * @ORM\Column(type="blob", length=500, name="logo", nullable=false)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=5, name="extension", nullable=false)
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
    public function setCodigoImagenPk($codigoImagenPk)
    {
        $this->codigoImagenPk = $codigoImagenPk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
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
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }



}
