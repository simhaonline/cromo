<?php


namespace App\Controller\RecursoHumano\Administracion\Recurso\Contrato;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuCambioSalario;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Utilidades\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class cambioSalarioController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "RhuCambioSalario";

    protected $clase = RhuCambioSalario::class;
    protected $claseNombre = "RhuCambioSalario";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Contrato";
    protected $nombre = "CambioSalario";




    /**
     * @Route("/recursohumano/cambiosalario/nuevo/{codigoContrato}/{codigoCambioSalario}", name="recursohumano_cambio_salario_nuevo")
     */
    public function nuevoAction(Request $request, $codigoContrato, $codigoCambioSalario = 0)
    {
        $em = $this->getDoctrine()->getManager();
        $arCambioSalario = new RhuCambioSalario();
        $arContrato = $em->getRepository(RhuContrato::class)->find($codigoContrato);
        $fechaActual = new \DateTime('now');
        if ($codigoCambioSalario != 0) {
            $arCambioSalario = $em->getRepository(RhuCambioSalario::class)->find($codigoCambioSalario);
            $dateAplicacion = $arCambioSalario->getFechaInicio();
        } else {
            $dateAplicacion = new \DateTime('now');
        }
        $form = $this->createFormBuilder()
            ->add('salarioNuevo', NumberType::class, array('required' => true, 'data' => $arCambioSalario->getVrSalarioNuevo()))
            ->add('fechaAplicacion', DateType::class, array('data' => $dateAplicacion))
            ->add('detalle', TextType::class, array('required' => true, 'data' => $arCambioSalario->getDetalle()))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->get('btnGuardar')->isClicked()) {
                $arUsuario = $this->getUser();
                if ($arContrato->getFechaUltimoPago() > $form->get('fechaAplicacion')->getData()){
                    Mensajes::error("El cambio de salario se debe realizar despues del pago del { $arContrato->getFechaUltimoPago()->format('Y/m/d')}");
                }else {
                    $arCambioSalario->setContratoRel($arContrato);
                    $arCambioSalario->setEmpleadoRel($arContrato->getEmpleadoRel());
                    $arCambioSalario->setFechaInicio($form->get('fechaAplicacion')->getData());
                    $arCambioSalario->setVrSalarioNuevo($form->get('salarioNuevo')->getData());
                    if ($codigoCambioSalario == 0){
                        $arCambioSalario->setFecha($fechaActual);
                        $arCambioSalario->setVrSalarioAnterior($arContrato->getVrSalario());
                        $arCambioSalario->setUsuario($arUsuario->getUserName());
                    }
                    $arCambioSalario->setDetalle($form->get('detalle')->getData());
                    $arContrato->setVrSalario($form->get('salarioNuevo')->getData());
                    $arContrato->setVrSalarioPago($form->get('salarioNuevo')->getData());
                    $em->persist($arCambioSalario);
                    $em->persist($arContrato);
                    $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);//SALARIO MINIMO
                    $douSalarioMinimo = $arConfiguracion->getVrSalarioMinimo();
                    if($arContrato->getVrSalario() <= $douSalarioMinimo * 2) {
                        $arContrato->setAuxilioTransporte(1);
                    } else {
                        $arContrato->setAuxilioTransporte(0);
                    }
                    $em->persist($arContrato);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('recursohumano/administracion/recurso/contrato/cambioSalario.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}