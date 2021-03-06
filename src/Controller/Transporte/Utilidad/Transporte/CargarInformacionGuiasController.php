<?php

namespace App\Controller\Transporte\Utilidad\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteGuiaCarga;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CargarInformacionGuiasController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "tteu0006";



    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/utilidad/transporte/cargarinformacionguias/lista", name="transporte_utilidad_transporte_cargarinformacionguias_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('txtCliente', TextType::class, ['required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger', 'style' => 'float:right']])
            ->add('btnEliminarTodo', SubmitType::class, ['label' => 'Eliminar todo', 'attr' => ['class' => 'btn btn-sm btn-danger', 'style' => 'float:right']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteGuiaCargaCliente', $form->get('txtCliente')->getData());
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    $em->getRepository(TteGuiaCarga::class)->eliminar($arrSeleccionados);
                }
                return $this->redirect($this->generateUrl('transporte_utilidad_transporte_cargarinformacionguias_lista'));
            }
            if ($form->get('btnEliminarTodo')->isClicked()) {
                $em->getRepository(TteGuiaCarga::class)->eliminarTodo();
                return $this->redirect($this->generateUrl('transporte_utilidad_transporte_cargarinformacionguias_lista'));
            }
        }
        $arGuiasCargas = $paginator->paginate($arGuiasCargas = $em->getRepository(TteGuiaCarga::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/utilidad/transporte/cargarInformacionGuias/lista.html.twig', [
            'arGuiasCargas' => $arGuiasCargas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @Route("/transporte/utilidad/transporte/cargarinformacionguias/cargar", name="transporte_utilidad_transporte_cargarinformacionguias_cargar")
     */
    public function cargarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $arConfiguracion = $em->getRepository(GenConfiguracion::class)->find(1);
        if (!$arConfiguracion) {
            Mensajes::error('Debe de registrar una configuracion general en el sistema');
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('attachment', FileType::class, array('attr' => ['class' => 'btn btn-sm btn-default']))
            ->add('btnCargar', SubmitType::class, array('label' => 'Cargar', 'attr' => ['class' => 'btn btn-sm btn-primary']))
            ->add('txtCliente', TextType::class, ['required' => true])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnCargar')->isClicked()) {
            $ruta = $arConfiguracion->getRutaTemporal();
//            $ruta = '/var/www/temporal/';
            if (!$ruta) {
                Mensajes::error('Debe de ingresar una ruta temporal en la configuracion general del sistema');
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
//            $ruta = "/var/www/temporal/";
            $form['attachment']->getData()->move($ruta, "archivo.xls");
            $rutaArchivo = $ruta . "archivo.xls";
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaArchivo);
            $arrCargas = [];
            $i = 0;
            if ($reader->getSheetCount() > 1) {
                Mensajes::error('El documento debe contener solamente una hoja');
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
                foreach ($reader->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    for ($row = 2; $row <= $highestRow; ++$row) {
                        $cell = $worksheet->getCellByColumnAndRow(1, $row);
                        $arrCargas [$i]['codigoGuia'] = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(2, $row);
                        $arrCargas [$i]['remitente'] = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(3, $row);
                        $arrCargas [$i]['relacion'] = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(4, $row);
                        $arrCargas [$i]['documento'] = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(5, $row);
                        $arrCargas [$i]['nombre'] = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(6, $row);
                        $arrCargas [$i]['direccion'] = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(7, $row);
                        $arrCargas [$i]['telefono'] = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(8, $row);
                        $arrCargas [$i]['ciudadOrigen'] = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(9, $row);
                        $arrCargas [$i]['ciudadDestino'] = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(10, $row);
                        $arrCargas [$i]['comentario'] = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(11, $row);
                        $arrCargas [$i]['vrDeclarado'] = $cell->getValue();
                        $i++;
                    }
                }
                if (count($arrCargas) > 0) {
                    foreach ($arrCargas as $arrCarga) {
                        $documento = str_replace("'", '', $arrCarga['codigoGuia']);
                        if($documento != "") {
                            $arGuiaCarga = new TteGuiaCarga();
                            $arGuiaCarga->setNumero($documento);
                            $arGuiaCarga->setRemitente($arrCarga['remitente']);
                            $arGuiaCarga->setCliente($form->get('txtCliente')->getData());
                            $arGuiaCarga->setRelacionCliente($arrCarga['relacion']);
                            $arGuiaCarga->setDocumentoCliente($documento);
                            $arGuiaCarga->setFechaRegistro(new \DateTime('now'));
                            $arGuiaCarga->setNombreDestinatario($arrCarga['nombre']);
                            $arGuiaCarga->setDireccionDestinatario($arrCarga['direccion']);
                            $arGuiaCarga->setTelefonoDestinatario($arrCarga['telefono']);
                            $arGuiaCarga->setCodigoCiudadOrigenFk($arrCarga['ciudadOrigen']);
                            $arGuiaCarga->setCodigoCiudadDestinoFk($arrCarga['ciudadDestino']);
                            $arGuiaCarga->setComentario($arrCarga['comentario']);
                            $arGuiaCarga->setVrDeclarado((float)$arrCarga['vrDeclarado']);
                            $em->persist($arGuiaCarga);
                        }
                    }
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }

        return $this->render('transporte/utilidad/transporte/cargarInformacionGuias/cargar.html.twig', [
            'form' => $form->createView()
        ]);
    }

}

