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

class SoporteController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/guia/soporte", name="transporte_proceso_transporte_guia_soporte")
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
            ->add('btnSoporte', SubmitType::class, array('label' => 'Soporte'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session = new session;
                $session->set('filtroTteDespachoCodigo', $form->get('txtDespachoCodigo')->getData());
                $codigoDespacho = $form->get('txtDespachoCodigo')->getData();
            }
            if ($form->get('btnSoporte')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteGuia::class)->soporte($arrGuias);
                $codigoDespacho = $form->get('txtDespachoCodigo')->getData();
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->listaSoporte($codigoDespacho), $request->query->getInt('page', 1), 500);
        return $this->render('transporte/proceso/transporte/guia/soporte.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @Route("/transporte/proceso/transporte/guia/soporte/archivo", name="transporte_proceso_transporte_guia_soporte_archivo")
     */
    public function entregarGuia(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('flArchivo', FileType::class)
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
                $form['flArchivo']->getData()->move($ruta, "archivo.xls");
                $rutaArchivo = $ruta . "archivo.xls";
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaArchivo);
                $arrCargas = [];
                $arrNoEncontrado = [];
                $arrSinNumero = [];
                $i = 0;
                if ($reader->getSheetCount() > 1) {
                    Mensajes::error('El documento debe contener solamente una hoja');
                    echo "<script language='Javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    foreach ($reader->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow();
                        for ($row = 2; $row <= $highestRow; ++$row) {
                            $cell = $worksheet->getCellByColumnAndRow(1, $row);
                            if ($cell->getValue() != '') {
                                $arrCargas [$i]['guia'] = $cell->getValue();
                            } else {
                                $arrSinNumero[] = $cell->getRow();
                            }
                            $i++;
                        }
                    }
                    if (count($arrSinNumero) > 0) {
                        Mensajes::error('Las siguientes filas no tienen id de guia: ' . implode(', ', $arrSinNumero));
                    } else {
                        if ($arrCargas) {
                            foreach ($arrCargas as $arrCarga) {
                                $arGuia = $em->find(TteGuia::class, $arrCarga['guia']);
                                if ($arGuia) {
                                    if ($arGuia->getEstadoDespachado() && !$arGuia->getEstadoSoporte()) {
                                        $arGuia->setEstadoSoporte(1);
                                        $arGuia->setFechaSoporte(new \DateTime('now'));
                                        $em->persist($arGuia);
                                    }
                                } else {
                                    $arrNoEncontrado[] = $arrCarga['guia'];
                                }
                            }
                        }
                        if (count($arrNoEncontrado) > 0) {
                            Mensajes::error('Las guías con los siguientes numero no fueron encontradas: ' . implode(', ', $arrNoEncontrado));
                        } else {
                            $em->flush();
                            echo "<script language='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        }
                    }
                }
            }
        }
        return $this->render('transporte/proceso/transporte/guia/soporteArchivo.html.twig', [
            'form' => $form->createView()
        ]);
    }

}

