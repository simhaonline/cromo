<?php

namespace App\Controller\Documental\Administracion\Documental;


use App\Controller\MaestroController;
use App\Entity\Documental\DocArchivo;
use App\Entity\Documental\DocConfiguracion;
use App\Form\Type\Documental\ConfiguracionType;
use App\Utilidades\Mensajes;
use PhpParser\Comment\Doc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocArchivoController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "DocArchivo";

    protected $clase = DocArchivo::class;
    protected $claseNombre = "DocArchivo";
    protected $modulo = "Documental";
    protected $funcion = "Administracion";
    protected $grupo = "Documental";
    protected $nombre = "DocArchivo";


}