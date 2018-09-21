<?php

namespace App\Controller\Documental\General;

use App\Entity\Documental\DocArchivo;
use App\Entity\Documental\DocConfiguracion;
use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocMasivo;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class InicioController extends Controller
{
   /**
    * @Route("/documental/general/general/inicio/ver", name="documental_general_general_inicio_ver")
    */    
    public function inicio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('documental/general/inicio.html.twig');
    }

    /**
     * @Route("/documental/general/lista/{tipo}/{codigo}", name="documental_general_general_lista")
     */
    public function listaAction(Request $request, $tipo, $codigo) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $query = $em->createQuery($em->getRepository(DocMasivo::class)->listaArchivo($tipo, $codigo));
        $arMasivos = $paginator->paginate($query, $request->query->get('page', 1), 50);
        $query = $em->createQuery($em->getRepository(DocArchivo::class)->listaArchivo($tipo, $codigo));
        $arArchivos = $paginator->paginate($query, $request->query->get('page', 1), 50);
        return $this->render('documental/general/lista.html.twig', array(
            'arMasivos' => $arMasivos,
            'arArchivos' => $arArchivos,
            'tipo' => $tipo,
            'codigo' => $codigo,
        ));
    }

    /**
     * @Route("/documental/general/cargar/{tipo}/{codigo}", name="documental_general_general_cargar")
     */
    public function cargarAction(Request $request, $tipo, $codigo) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('attachment', fileType::class)
            ->add('descripcion', textType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('BtnCargar', SubmitType::class, array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $objArchivo = $form['attachment']->getData();
                if ($objArchivo->getClientSize()){
                    $arArchivo = new DocArchivo();
                    $arArchivo->setNombre($objArchivo->getClientOriginalName());
                    $arArchivo->setExtensionOriginal($objArchivo->getClientOriginalExtension());
                    $arArchivo->setTamano($objArchivo->getClientSize());
                    $arArchivo->setTipo($objArchivo->getClientMimeType());
                    $arArchivo->setCodigoArchivoTipoFk($tipo);
                    $arArchivo->setCodigo($codigo);
                    $dateFecha = new \DateTime('now');
                    $arArchivo->setFecha($dateFecha);
                    $arArchivo->setDescripcion($form->get('descripcion')->getData());
                    $arArchivo->setComentarios($form->get('comentarios')->getData());
                    $directorio = $em->getRepository(DocDirectorio::class)->devolverDirectorio("A", $tipo);
                    $arArchivo->setDirectorio($directorio);
                    $arArchivo->setCodigoArchivoTipoFk($tipo);
                    $error = false;
                    $arrConfiguracion = $em->getRepository(DocConfiguracion::class)->archivoMasivo();
                    $directorioDestino = $arrConfiguracion['rutaAlmacenamiento'] . "/archivo/" . $tipo . "/" . $directorio . "/";
                    if(!file_exists($directorioDestino)) {
                        if(!mkdir($directorioDestino, 0777, true)) {
                            Mensajes::error('Fallo al crear directorio...' . $directorioDestino);
                            $error = true;
                        }
                    }
                    if($error == false) {
                        $em->persist($arArchivo);
                        $em->flush();
                        $strArchivo = $arArchivo->getCodigoArchivoPk() . "_" . $objArchivo->getClientOriginalName();
                        $form['attachment']->getData()->move($directorioDestino, $strArchivo);
                    }
                    return $this->redirect($this->generateUrl('documental_general_general_lista', array('tipo' => $tipo, 'codigo' => $codigo)));
                } else {
                    Mensajes::error("El archivo tiene un tamaño mayor al permitido");
                }
            }
        }
        return $this->render('documental/general/cargar.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/documental/general/descargar/{codigoArchivo}", name="documental_general_general_descargar")
     */
    public function descargarAction($codigoArchivo) {
        $em = $this->getDoctrine()->getManager();
        $arrConfiguracion = $em->getRepository(DocConfiguracion::class)->archivoMasivo();
        $arArchivo = $em->getRepository(DocArchivo::class)->find($codigoArchivo);
        $strRuta = $arrConfiguracion['rutaAlmacenamiento'] . "/archivo/" . $arArchivo->getCodigoArchivoTipoFk() . "/" . $arArchivo->getDirectorio() . "/" . $arArchivo->getCodigoArchivoPk() . "_" . $arArchivo->getNombre();
        //$strRuta = $arArchivo->getDirectorioRel()->getRutaPrincipal() . $arArchivo->getDirectorioRel()->getNumero() . "/" . $arArchivo->getCodigoArchivoPk() . "_" . $arArchivo->getNombre();
        // Generate response
        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', $arArchivo->getTipo());
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $arArchivo->getNombre() . '";');
        $response->headers->set('Content-length', $arArchivo->getTamano());
        $response->sendHeaders();
//        $response->setContent(readfile($strRuta));
        if(file_exists ($strRuta)){
            $response->setContent(readfile($strRuta));
        }else{
            echo "<script>alert('No existe el archivo en el servidor a pesar de estar asociado en base de datos, por favor comuniquese con soporte');window.close()</script>";
        }
        return $response;


//        $response2 = new Response();
//        $response2->setContent(true);
//        return $response2;

    }

}

