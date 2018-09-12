<?php

namespace App\Controller\Documental\Movimiento\Masivo;


use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocMasivoTipo;
use App\Entity\Documental\DocRegistro;
use App\Entity\Documental\DocRegistroCarga;
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
    * @Route("/documental/movimiento/masivo/registro/lista", name="documental_movimiento_masivo_registro_lista")
    */    
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtIdentificador', TextType::class, ['required' => false, 'data' => $session->get('filtroDocRegistroIdentificador')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroDocRegistroIdentificador', $form->get('txtNumero')->getData());
            }
        }
        $arRegistros = $paginator->paginate($em->getRepository(DocRegistro::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('documental/movimiento/masivo/registro/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/documental/movimiento/masivo/registro/carga", name="documental_movimiento_masivo_registro_carga")
     */
    public function carga(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtIdentificador', TextType::class, ['required' => false, 'data' => $session->get('filtroDocRegistroIdentificador')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminarDetalle', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnAnalizarBandeja', SubmitType::class, ['label' => 'Analizar bandeja', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnCargar', SubmitType::class, ['label' => 'Cargar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroDocRegistroIdentificador', $form->get('txtNumero')->getData());
            }
            if ($form->get('btnAnalizarBandeja')->isClicked()) {
                $directorioBandeja = "/bandeja";
                $ficheros  = scandir($directorioBandeja);
                foreach ($ficheros as $fichero) {
                    if($fichero != "." && $fichero != "..") {
                        $partes = explode(".", $fichero);
                        if(count($partes) == 2 ) {
                            $extension = $partes[1];
                            if($extension == 'pdf') {
                                $arRegistroCarga = new DocRegistroCarga();
                                $arRegistroCarga->setIdentificador($partes[0]);
                                $arRegistroCarga->setExtension($extension);
                                $arRegistroCarga->setArchivo($fichero);
                                $em->persist($arRegistroCarga);
                            }
                        }
                    }
                }
                $em->flush();
            }
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(DocRegistroCarga::class)->eliminar($arrDetallesSeleccionados);
                return $this->redirect($this->generateUrl('documental_movimiento_masivo_registro_carga'));
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
                        $arRegistrosCargas = $em->getRepository(DocRegistroCarga::class)->findAll();
                        foreach ($arRegistrosCargas as $arRegistroCarga) {
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

                            $arRegistro = new DocRegistro();
                            $arRegistro->setIdentificador($arRegistroCarga->getIdentificador());
                            $arRegistro->setMasivoTipoRel($arMasivoTipo);
                            $arRegistro->setArchivo($arRegistroCarga->getArchivo());
                            $arRegistro->setExtension($arRegistroCarga->getExtension());
                            $arRegistro->setDirectorio($arDirectorio->getDirectorio());
                            $archivoDestino = rand(100000, 999999) . "_" . $arRegistroCarga->getIdentificador();
                            $arRegistro->setArchivoDestino($archivoDestino);
                            $em->persist($arRegistro);

                            $arDirectorio->setNumeroArchivos($arDirectorio->getNumeroArchivos()+1);
                            $em->persist($arDirectorio);

                            $em->remove($arRegistroCarga);

                            $em->flush();


                            $origen = $directorioBandeja . "/" . $arRegistroCarga->getArchivo();
                            $destino = $directorio . $arRegistroCarga->getArchivo();
                            copy($origen, $destino);
                        }
                    }
                } else {
                    Mensajes::error("No existe el directorio principal " . $directorioDestino);
                }
                return $this->redirect($this->generateUrl('documental_movimiento_masivo_registro_carga'));
            }
        }
        $arRegistrosCargas = $paginator->paginate($em->getRepository(DocRegistroCarga::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('documental/movimiento/masivo/registro/carga.html.twig', [
            'arRegistrosCargas' => $arRegistrosCargas,
            'form' => $form->createView()
        ]);
    }



}

