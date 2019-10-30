<?php

namespace App\Controller\Documental\Movimiento;


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
            ->add('txtIdentificador', TextType::class, ['required' => false])
            ->add('estadoDigitalizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('existe', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminarDetalle', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnAnalizarBandeja', SubmitType::class, ['label' => 'Analizar bandeja', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnCargar', SubmitType::class, ['label' => 'Cargar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => '100000'
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnAnalizarBandeja')->isClicked()) {
                $this->analizarBandeja();
            }
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                $arrConfiguracion = $em->getRepository(DocConfiguracion::class)->archivoMasivo();
                $directorioBandeja = $arrConfiguracion['rutaBandeja'];
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(DocMasivoCarga::class)->eliminar($arrDetallesSeleccionados, $directorioBandeja);
                return $this->redirect($this->generateUrl('documental_movimiento_masivo_masivo_carga'));
            }
            if ($form->get('btnCargar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $tipo = "TteGuia";
                $arMasivoTipo = $em->getRepository(DocMasivoTipo::class)->find($tipo);
                $arrConfiguracion = $em->getRepository(DocConfiguracion::class)->archivoMasivo();
                $directorioBandeja = $arrConfiguracion['rutaBandeja'];
                $directorioDestino = $arrConfiguracion['rutaAlmacenamiento'] . "/masivo/";
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
                                $arMasivo->setTamano($arMasivoCarga->getTamano());
                                $archivoDestino = rand(100000, 999999) . "_" . $arMasivoCarga->getIdentificador();
                                $arMasivo->setArchivoDestino($archivoDestino . "." . $arMasivoCarga->getExtension());
                                $destino = $directorio . $archivoDestino . "." . $arMasivoCarga->getExtension();
                                $em->persist($arMasivo);

                                $arDirectorio->setNumeroArchivos($arDirectorio->getNumeroArchivos()+1);
                                $em->persist($arDirectorio);
                                $em->remove($arMasivoCarga);
                                if($arMasivoCarga->getIdentificador()){
                                    $arGuia = $em->getRepository(TteGuia::class)->find($arMasivoCarga->getIdentificador());
                                    if($arGuia) {
                                        $arGuia->setEstadoDigitalizado(1);
                                        $em->persist($arGuia);
                                    }
                                }
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
        $arMasivosCargas = $paginator->paginate($em->getRepository(DocMasivoCarga::class)->lista($raw), $request->query->getInt('page', 1), 500);
        return $this->render('documental/movimiento/masivo/masivo/carga.html.twig', [
            'arMasivosCargas' => $arMasivosCargas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/documental/movimiento/masivo/masivo/descargar/{codigoArchivo}", name="documental_movimiento_masivo_masivo_descargar")
     */
    public function descargarAction($codigoArchivo) {
        $em = $this->getDoctrine()->getManager();
        $arrConfiguracion = $em->getRepository(DocConfiguracion::class)->archivoMasivo();
        $arArchivo = $em->getRepository(DocMasivo::class)->find($codigoArchivo);
        $strRuta = $arrConfiguracion['rutaAlmacenamiento'] . "/masivo/" . $arArchivo->getCodigoMasivoTipoFk() . "/" .  $arArchivo->getDirectorio() . "/" . $arArchivo->getArchivoDestino();
        // Generate response
        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', "application/pdf");
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $arArchivo->getArchivo() . '";');
        $response->headers->set('Content-length', $arArchivo->getTamano());
        $response->sendHeaders();
        if(file_exists ($strRuta)){
            $response->setContent(readfile($strRuta));
        }else{
            echo "<script>alert('No existe el archivo en el servidor a pesar de estar asociado en base de datos, por favor comuniquese con soporte');window.close()</script>";
        }
        return $response;

    }

    /**
     * @Route("/documental/movimiento/masivo/masivo/paquete", name="documental_movimiento_masivo_masivo_paquete")
     */
    public function alizarPaqueteAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $arrConfiguracion = $em->getRepository(DocConfiguracion::class)->archivoMasivo();
        $directorioBandeja = $arrConfiguracion['rutaBandeja'];
        $directorioTemporal = $arrConfiguracion['rutaTemporal'];
        $form = $this->createFormBuilder()
            ->add('attachment', fileType::class)
            ->add('BtnCargar', SubmitType::class, array('label' => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnCargar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                if(file_exists($directorioBandeja)) {
                    if(file_exists($directorioBandeja)) {
                        $objArchivo = $form['attachment']->getData();
                        if ($objArchivo->getClientSize()) {
                            $directorioTemporalPaquete = $directorioTemporal . "/paquete/";
                            if (!file_exists($directorioTemporalPaquete)) {
                                if (!mkdir($directorioTemporalPaquete, 0777, true)) {
                                    Mensajes::error('Fallo al crear directorio...' . $directorioTemporalPaquete);
                                    $error = true;
                                }
                            }
                            $form['attachment']->getData()->move($directorioTemporal, "paquete.zip");
                            $zip = new \ZipArchive();
                            if ($zip->open($directorioTemporal . '/paquete.zip') === TRUE) {
                                $zip->extractTo($directorioTemporalPaquete);
                                $zip->close();
                            } else {
                                Mensajes::error("Fallo la descompesion");
                            }

                            $ficheros  = scandir($directorioTemporalPaquete);
                            foreach ($ficheros as $fichero) {
                                if($fichero != "." && $fichero != "..") {
                                    copy($directorioTemporalPaquete . $fichero, $directorioBandeja . "/" . $fichero);
                                    unlink($directorioTemporalPaquete . $fichero);
                                }
                            }
                            rmdir($directorioTemporalPaquete);
                            unlink($directorioTemporal . '/paquete.zip');
                            $this->analizarBandeja();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        } else {
                            Mensajes::error("El archivo tiene un tamaño mayor al permitido");
                        }
                    } else {
                        Mensajes::error("No existe el directorio temporal" . $directorioTemporal);
                    }
                } else {
                    Mensajes::error("No existe el directorio de bandeja" . $directorioBandeja);
                }
            }
        }
        return $this->render('documental/movimiento/masivo/masivo/paquete.html.twig', array(
            'directorioBandeja' => $directorioBandeja,
            'directorioTemporal' => $directorioTemporal,
            'form' => $form->createView()
        ));
    }

    private function analizarBandeja() {
        $em = $this->getDoctrine()->getManager();
        $em->createQueryBuilder()->delete(DocMasivoCarga::class, 'mc')->getQuery()->execute();
        $arrConfiguracion = $em->getRepository(DocConfiguracion::class)->archivoMasivo();
        $directorioBandeja = $arrConfiguracion['rutaBandeja'];
        $ficheros  = scandir($directorioBandeja);
        foreach ($ficheros as $fichero) {
            if($fichero != "." && $fichero != "..") {
                $partes = explode(".", $fichero);
                if(count($partes) == 2 ) {
                    $nombre = $partes[0];
                    $extension = $partes[1];
                    $archivo = $directorioBandeja . "/" . $fichero;
                    $tamano = filesize($archivo);
                    if($tamano < 8000000) {
                        if($extension == 'pdf' || $extension == 'tif') {
                            $arMasivoCarga = new DocMasivoCarga();
                            $arMasivoCarga->setIdentificador($nombre);
                            $arMasivoCarga->setExtension($extension);
                            $arMasivoCarga->setArchivo($fichero);
                            $arMasivoCarga->setTamano($tamano);
                            if($nombre) {
                                $arGuia = $em->getRepository(TteGuia::class)->find($nombre);
                                if($arGuia) {
                                    $arMasivoCarga->setEstadoDigitalizado($arGuia->getEstadoDigitalizado());
                                    $arMasivoCarga->setExiste(1);
                                } else {
                                    $arMasivoCarga->setExiste(0);
                                }
                            }
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

    public function getFiltros($form)
    {
        $filtro = [
            'estadoDigitalizado' => $form->get('estadoDigitalizado')->getData(),
            'existe' => $form->get('existe')->getData(),
            'identificador' => $form->get('txtIdentificador')->getData()
        ];
        return $filtro;
    }
}

