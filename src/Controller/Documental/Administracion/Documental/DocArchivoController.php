<?php

namespace App\Controller\Administracion\Documental;


use App\Controller\MaestroController;
use App\Entity\Documental\DocArchivo;
use App\Entity\Documental\DocConfiguracion;
use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocMasivo;
use App\Entity\Documental\DocMasivoCarga;
use App\Entity\Documental\DocMasivoTipo;
use App\Entity\Financiero\FinRegistroInconsistencia;
use App\Entity\Transporte\TteGuia;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Utilidades\Mensajes;
class DocArchivoController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "DocArchivo";

    protected $clase = DocArchivo::class;
    protected $claseNombre = "Administraion";
    protected $modulo = "General";
    protected $funcion = "Administracion";
    protected $grupo = "Documental";
    protected $nombre = "Documental";



}

