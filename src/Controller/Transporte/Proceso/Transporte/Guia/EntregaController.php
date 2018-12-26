<?php

namespace App\Controller\Transporte\Proceso\Transporte\Guia;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class EntregaController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/guia/entrega", name="transporte_proceso_transporte_guia_entrega")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $codigoDespacho = 0;
        $form = $this->createFormBuilder()
            ->add('txtDespachoCodigo', TextType::class, array('data' => $session->get('filtroTteDespachoCodigo')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEntrega', SubmitType::class, array('label' => 'Entregar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session = new session;
                $session->set('filtroTteDespachoCodigo', $form->get('txtDespachoCodigo')->getData());
                $codigoDespacho = $form->get('txtDespachoCodigo')->getData();
            }
            if ($form->get('btnEntrega')->isClicked()) {
                $arrGuias = $request->request->get('chkSeleccionar');
                $arrControles = $request->request->All();
                $respuesta = $this->getDoctrine()->getRepository(TteGuia::class)->entrega($arrGuias, $arrControles);
                $codigoDespacho = $form->get('txtDespachoCodigo')->getData();
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->listaEntrega($codigoDespacho), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/proceso/transporte/guia/entrega.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @Route("/transporte/proceso/transporte/guia/entrega/archivo", name="transporte_proceso_transporte_guia_entrega_archivo")
     */
    public function entregarGuia(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('flArchivo', FileType::class)
            ->add('chkSoporte', CheckboxType::class, ['label' => ' ', 'required' => false])
            ->add('btnEntregar', SubmitType::class, ['label' => 'Entregar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEntregar')->isClicked()) {
//                $ruta = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
                $ruta = "/var/www/temporal/";
                if (!$ruta) {
                    Mensajes::error('Debe de ingresar una ruta temporal en la configuracion general del sistema');
                    echo "<script language='Javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
//                $ruta = "/var/www/temporal/";
                $form['flArchivo']->getData()->move($ruta, "archivo.xls");
                $rutaArchivo = $ruta . "archivo.xls";
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaArchivo);
                $arrCargas = [];
                $arrSinFecha = [];
                $arrNoEncontrado = [];
                $arrSinNumero = [];
                $arrSinHora = [];
                $i = 0;
                if ($reader->getSheetCount() > 1) {
                    Mensajes::error('El documento debe contener solamente una hoja');
                    echo "<script language='Javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    foreach ($reader->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow();
                        for ($row = 2; $row <= $highestRow; ++$row) {
                            $cell = $worksheet->getCellByColumnAndRow(1, $row);
                            if($cell->getValue() != ''){
                                $arrCargas [$i]['guia'] = $cell->getValue();
                            } else {
                                $arrSinNumero[] = $cell->getRow();
                            }
                            $cell = $worksheet->getCellByColumnAndRow(2, $row);
                            if($cell->getValue() != ''){
                                $arrCargas [$i]['fecha'] = $cell->getValue();
                            } else {
                                $arrSinFecha[] = $cell->getRow();
                            }
                            $cell = $worksheet->getCellByColumnAndRow(3, $row);
                            if($cell->getValue() != ''){
                                $arrCargas [$i]['hora'] = $cell->getValue();
                            } else {
                                $arrSinHora[] = $cell->getRow();
                            }
                            $i++;
                        }
                    }
                    if(count($arrSinNumero) > 0){
                        Mensajes::error('Las siguientes filas no tienen numero de guia: '. implode(', ', $arrSinNumero));
                    } elseif(count($arrSinFecha)){
                        Mensajes::error('Las siguientes filas no tienen fecha de entrega: '. implode(', ', $arrSinFecha));
                    } elseif (count($arrSinHora)){
                        Mensajes::error('Las siguientes filas no tienen hora de entrega: '. implode(', ', $arrSinHora));
                    } else {
                        if (count($arrCargas) > 0) {
                            foreach ($arrCargas as $arrCarga) {
                                $arrCarga['fecha'] = str_replace("'", '', $arrCarga['fecha']);
                                $arrCarga['hora'] = str_replace("'", '', $arrCarga['hora']);
                                $arGuia = $em->getRepository(TteGuia::class)->findOneBy(['numero' => $arrCarga['guia']]);
                                if ($arGuia) {
                                    if ($arGuia->getEstadoDespachado() && !$arGuia->getEstadoEntregado()) {
                                        $arGuia->setEstadoEntregado(1);
                                        $arGuia->setFechaEntrega(date_create($arrCarga['fecha'] . ' ' . $arrCarga['hora']));
                                        if ($form->get('chkSoporte')->getData()) {
                                            $arGuia->setEstadoSoporte(1);
                                        }
                                        $em->persist($arGuia);
                                    }
                                } else {
                                    $arrNoEncontrado[] = $arrCarga['guia'];
                                }
                            }
                        }
                        if(count($arrNoEncontrado) > 0){
                            Mensajes::error('Las guías con los siguientes numero no fueron encontradas: '.implode(', ',$arrNoEncontrado));
                        } else {
                            $em->flush();
                            echo "<script language='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        }
                    }
                }
            }
        }
        return $this->render('transporte/proceso/transporte/guia/entregaArchivo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

