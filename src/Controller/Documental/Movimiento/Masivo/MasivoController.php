<?php

namespace App\Controller\Documental\Movimiento\Masivo;


use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocMasivo;
use App\Entity\Documental\DocMasivoCarga;
use App\Entity\Documental\DocMasivoTipo;
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
class MasivoController extends Controller
{
   /**
    * @Route("/documental/movimiento/masivo/masivo/lista", name="documental_movimiento_masivo_masivo_lista")
    */    
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtIdentificador', TextType::class, ['required' => false, 'data' => $session->get('filtroDocMasivoIdentificador')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroDocMasivoIdentificador', $form->get('txtIdentificador')->getData());
            }
        }
        $arMasivos = $paginator->paginate($em->getRepository(DocMasivo::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('documental/movimiento/masivo/masivo/lista.html.twig', [
            'arMasivos' => $arMasivos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/documental/movimiento/masivo/masivo/carga", name="documental_movimiento_masivo_masivo_carga")
     */
    public function carga(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtIdentificador', TextType::class, ['required' => false, 'data' => $session->get('filtroDocMasivoIdentificador')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminarDetalle', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnAnalizarBandeja', SubmitType::class, ['label' => 'Analizar bandeja', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnCargar', SubmitType::class, ['label' => 'Cargar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroDocMasivoIdentificador', $form->get('txtNumero')->getData());
            }
            if ($form->get('btnAnalizarBandeja')->isClicked()) {
                $directorioBandeja = "/bandeja";
                $ficheros  = scandir($directorioBandeja);
                foreach ($ficheros as $fichero) {
                    if($fichero != "." && $fichero != "..") {
                        $partes = explode(".", $fichero);
                        if(count($partes) == 2 ) {
                            $nombre = $partes[0];
                            $extension = $partes[1];
                            $archivo = $directorioBandeja . "/" . $fichero;
                            $tamano = filesize($archivo) / 1000;
                            if($tamano < 8000) {
                                if($extension == 'pdf') {
                                    $arMasivoCarga = new DocMasivoCarga();
                                    $arMasivoCarga->setIdentificador($nombre);
                                    $arMasivoCarga->setExtension($extension);
                                    $arMasivoCarga->setArchivo($fichero);
                                    $arMasivoCarga->getTamano($tamano);
                                    $em->persist($arMasivoCarga);
                                }
                            }
                            else {
                                Mensajes::info("El archivo " . $fichero . " no fue procesado por que excede los 8M");
                            }
                        }
                    }
                }
                $em->flush();
            }
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(DocMasivoCarga::class)->eliminar($arrDetallesSeleccionados);
                return $this->redirect($this->generateUrl('documental_movimiento_masivo_masivo_carga'));
            }
            if ($form->get('btnCargar')->isClicked()) {
                $tipo = "guia";
                $arMasivoTipo = $em->getRepository(DocMasivoTipo::class)->find($tipo);
                $directorioBandeja = "/bandeja";
                $directorioDestino = "/almacenamiento/masivo/";
                if(file_exists($directorioDestino)) {
                    $arDirectorio = $em->getRepository(DocDirectorio::class)->findOneBy(array('tipo' => 'M', 'codigoMasivoTipoFk' => $tipo));
                    if(!$arDirectorio) {
                        $arDirectorio = new DocDirectorio();
                        $arDirectorio->setCodigoMasivoTipoFk($tipo);
                        $arDirectorio->setDirectorio(1);
                        $arDirectorio->setNumeroArchivos(0);
                        $arDirectorio->setTipo('M');
                        $em->persist($arDirectorio);
                        $em->flush();
                    }
                    if($arDirectorio) {
                        $arDirectorio = $em->getRepository(DocDirectorio::class)->find($arDirectorio->getCodigoDirectorioPk());
                        $arMasivosCargas = $em->getRepository(DocMasivoCarga::class)->findAll();
                        foreach ($arMasivosCargas as $arMasivoCarga) {
                            if($arDirectorio->getNumeroArchivos() >= 50000) {
                                $arDirectorio->setNumeroArchivos(0);
                                $arDirectorio->setDirectorio($arDirectorio->getDirectorio()+1);
                                $em->persist($arDirectorio);
                                $em->flush();
                            }
                            $directorio = $directorioDestino . $tipo . "/" . $arDirectorio->getDirectorio() . "/";
                            if(!file_exists($directorio)) {
                                if(!mkdir($directorio, 0777, true)) {
                                    die('Fallo al crear directorio...' . $directorio);
                                    break;
                                }
                            }
                            $origen = $directorioBandeja . "/" . $arMasivoCarga->getArchivo();
                            if(file_exists($origen)) {
                                $arMasivo = new DocMasivo();
                                $arMasivo->setIdentificador($arMasivoCarga->getIdentificador());
                                $arMasivo->setMasivoTipoRel($arMasivoTipo);
                                $arMasivo->setArchivo($arMasivoCarga->getArchivo());
                                $arMasivo->setExtension($arMasivoCarga->getExtension());
                                $arMasivo->setDirectorio($arDirectorio->getDirectorio());
                                $archivoDestino = rand(100000, 999999) . "_" . $arMasivoCarga->getIdentificador();
                                $arMasivo->setArchivoDestino($archivoDestino);
                                $destino = $directorio . $archivoDestino . "." . $arMasivoCarga->getExtension();
                                $em->persist($arMasivo);

                                $arDirectorio->setNumeroArchivos($arDirectorio->getNumeroArchivos()+1);
                                $em->persist($arDirectorio);
                                $em->remove($arMasivoCarga);
                                $em->flush();
                                copy($origen, $destino);
                                unlink($origen);
                            }
                        }
                    }
                } else {
                    Mensajes::error("No existe el directorio principal " . $directorioDestino);
                }
                return $this->redirect($this->generateUrl('documental_movimiento_masivo_masivo_carga'));
            }
        }
        $arMasivosCargas = $paginator->paginate($em->getRepository(DocMasivoCarga::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('documental/movimiento/masivo/masivo/carga.html.twig', [
            'arMasivosCargas' => $arMasivosCargas,
            'form' => $form->createView()
        ]);
    }



}

