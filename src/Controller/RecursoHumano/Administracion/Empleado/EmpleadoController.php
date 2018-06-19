<?php

namespace App\Controller\RecursoHumano\Administracion\Empleado;


use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\EmpleadoType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmpleadoController extends Controller
{
    /**
     * @Route("rhu/adm/empleado/empleado/nuevo/{id}", name="rhu_adm_empleado_empleado_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new RhuEmpleado();
        if ($id != 0) {
            $arEmpleado = $em->getRepository('App:RecursoHumano\RhuEmpleado')->find($id);
            if (!$arEmpleado) {
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano', 'entidad' => 'empleado']));
            }
        }
        $form = $this->createForm(EmpleadoType::class, $arEmpleado);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado->setFecha(new \DateTime('now'));
                $em->persist($arEmpleado);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano','entidad' => 'empleado']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arEmpleado);
                $em->flush($arEmpleado);
                return $this->redirect($this->generateUrl('rhu_adm_empleado_empleado_nuevo', ['codigoEmpleado' => 0]));
            }
        }
        return $this->render('recursoHumano/administracion/empleado/nuevo.html.twig', [
            'form' => $form->createView(), 'arEmpleado' => $arEmpleado
        ]);
    }
}

