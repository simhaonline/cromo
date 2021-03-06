<?php


namespace App\Entity\Documental;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Documental\DocConfiguracionRepository")
 * @ORM\EntityListeners({"App\Controller\Estructura\EntityListener"})
 */
class DocConfiguracion
{
    public $infoLog = [
        "primaryKey" => "codigoConfiguracionPk",
        "todos"     => true,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $codigoConfiguracionPk;

    /**
     * @ORM\Column(name="ruta_bandeja", type="string", length=1000, nullable=true)
     */
    private $rutaBandeja;

    /**
     * @ORM\Column(name="ruta_almacenamiento", type="string", length=1000, nullable=true)
     */
    private $rutaAlmacenamiento;

    /**
     * @ORM\Column(name="ruta_temporal", type="string", length=1000, nullable=true)
     */
    private $rutaTemporal;

    /**
     * @return mixed
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * @param mixed $codigoConfiguracionPk
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk): void
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;
    }

    /**
     * @return mixed
     */
    public function getRutaBandeja()
    {
        return $this->rutaBandeja;
    }

    /**
     * @param mixed $rutaBandeja
     */
    public function setRutaBandeja($rutaBandeja): void
    {
        $this->rutaBandeja = $rutaBandeja;
    }

    /**
     * @return mixed
     */
    public function getRutaAlmacenamiento()
    {
        return $this->rutaAlmacenamiento;
    }

    /**
     * @param mixed $rutaAlmacenamiento
     */
    public function setRutaAlmacenamiento($rutaAlmacenamiento): void
    {
        $this->rutaAlmacenamiento = $rutaAlmacenamiento;
    }

    /**
     * @return mixed
     */
    public function getRutaTemporal()
    {
        return $this->rutaTemporal;
    }

    /**
     * @param mixed $rutaTemporal
     */
    public function setRutaTemporal($rutaTemporal): void
    {
        $this->rutaTemporal = $rutaTemporal;
    }



}

