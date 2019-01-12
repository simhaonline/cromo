<?php

namespace App\Controller\General\Seguridad;

use App\Entity\Seguridad\Usuario;
use App\Entity\Transporte\TteOperacion;
use App\General\General;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class SeguridadController extends Controller
{
    /**
     * @Route("/general/seguridad/usuario/lista", name="general_seguridad_usuario_lista")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arUsuarios = $em->getRepository('App:Seguridad\Usuario')->findAll();
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcelPermiso', SubmitType::class, ['label' => 'Excel permiso', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnExcel')->isClicked()) {
                $this->generarExcel();
            }
            if ($form->get('btnExcelPermiso')->isClicked()) {
                $arUsuariosPermisos=$em->getRepository('App:Seguridad\Usuario')->exportarExcelPermisos();
                $permisos=array('LISTA','NUEVO','DETALLE','AUTORIZAR','APROBAR','ANULAR');
                for($i=0; $i<count($arUsuariosPermisos);$i++){
                    foreach ($permisos as $permiso){
                        if(!$arUsuariosPermisos[$i][$permiso]){
                            $arUsuariosPermisos[$i][$permiso]="NO";
                        }
                        else{
                            $arUsuariosPermisos[$i][$permiso]="SI";
                        }
                    }
                }
                General::get()->setExportar($arUsuariosPermisos,'ExcelPermiso');
            }
        }
        return $this->render('general/seguridad/lista.html.twig', [
            'arUsuarios' => $arUsuarios,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/general/seguridad/usuario/nuevo/{hash}", name="general_seguridad_usuario_nuevo")
     */
    public function nuevo(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $arUsuario = new Usuario();
        $respuesta = '';
        $arrPropiedadesClaves = ['required' => true];
        $id = $this->verificarUsuario($hash);
        if ($id != "") {
            $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
            if (!$arUsuario) {
                return $this->redirect($this->generateUrl('general_seguridad_usuario_lista'));
            } else {
                $arrPropiedadesClaves = ['attr' => ['readonly' => 'readonly']];
            }
        }
        $arrPropiedadesOperacionRel = [
            'required' => false,
            'class' => 'App\Entity\Transporte\TteOperacion',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('op')
                    ->orderBy('op.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'label' => 'Operacion:',
            'empty_data' => "",
            'placeholder' => "SIN OPERACION",
            'data' => ""
        ];
        if($arUsuario->getOperacionRel()){
            $arrPropiedadesOperacionRel["data"] = $em->getReference(TteOperacion::class, $arUsuario->getCodigoOperacionFk());
        }
        $form = $this->createFormBuilder()
            ->add('operacionRel',EntityType::class, $arrPropiedadesOperacionRel)
            ->add('txtUser', TextType::class, ['data' => $arUsuario->getUsername(), 'required'=>true, 'constraints'=>array(
                new NotBlank(array("message"=>"El nombre de usuario es obligatorio")),
                new Regex(array('pattern'=>"/[A-Za-z0-9]/",'message'=>"El nombre de usuario no puedo contener caracteres")),
            ),
                'disabled'=> $arUsuario->getUsername()?true:false
            ])
            ->add('txtEmail', TextType::class, ['data' => $arUsuario->getEmail(),'required'=>true,'constraints'=>array(
                new NotBlank(array("message"=>"El email es obligatorio")),
                new Regex(array('pattern'=>"/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i",'message'=>"El email es invalido")),
            ),
                ])
            ->add('txtCargo', TextType::class, ['data' => $arUsuario->getCargo(),'required' => false])
            ->add('txtNombreCorto', TextType::class, ['data' => $arUsuario->getNombreCorto(),'required' => false])
            ->add('txtIdentificacion', NumberType::class, ['data' => $arUsuario->getNumeroIdentificacion(),'required' => false])
            ->add('txtTelefono', TextType::class, ['data' => $arUsuario->getTelefono(),'required' => false])
            ->add('txtExtension', TextType::class, ['data' => $arUsuario->getExtension(),'required' => false])
            ->add('txtClaveEscritorio', TextType::class, ['data' => $arUsuario->getClaveEscritorio(),'required' => false])
            ->add('cboRol', ChoiceType::class, ['data' => $arUsuario->getRoles()[0],'required' => true, 'choices'=>array('Usuario'=>"ROLE_USER",'Administrador'=>"ROLE_ADMIN")])
            ->add('txtNuevaClave', PasswordType::class, $arrPropiedadesClaves)
            ->add('txtConfirmacionClave', PasswordType::class, $arrPropiedadesClaves)
            ->add('boolActivo', CheckboxType::class, ['data' => $arUsuario->getisActive(), 'label' => ' ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $userName = $form->get('txtUser')->getData();
                $arUsuario->setUsername($userName);
                $arUsuario->setEmail($form->get('txtEmail')->getData());
                $arUsuario->setCargo($form->get('txtCargo')->getData());
                $arUsuario->setIsActive($form->get('boolActivo')->getData());
                $arUsuario->setNumeroIdentificacion($form->get('txtIdentificacion')->getData());
                $arUsuario->setNombreCorto($form->get('txtNombreCorto')->getData());
                $arUsuario->setTelefono($form->get('txtTelefono')->getData());
                $arUsuario->setExtension($form->get('txtExtension')->getData());
                $arUsuario->setClaveEscritorio($form->get('txtClaveEscritorio')->getData());
                $arUsuario->setOperacionRel($form->get('operacionRel')->getData());
                $arUsuario->setRol($form->get('cboRol')->getData());
                if ($id === 0) {
                    $claveNueva = $form->get('txtNuevaClave')->getData();
                    $claveConfirmacion = $form->get('txtConfirmacionClave')->getData();
                    if ($claveNueva != $claveConfirmacion) {
                        Mensajes::error('Las contraseñas no coinciden');
                    } else {
                        $arUsuario->setPassword(password_hash($claveNueva, PASSWORD_BCRYPT));
                    }


                }

                    if($id === 0){
                        $arUsuarioExistente=$em->getRepository('App:Seguridad\Usuario')->find($form->get('txtUser')->getData());
                        if($arUsuarioExistente){
                            Mensajes::error("Ya existe un usuario con el Nombre de usuario '{$form->get('txtUser')->getData()}'");
                        }else{
                            $em->persist($arUsuario);
                            $em->flush();
                            return $this->redirect($this->generateUrl('general_seguridad_usuario_lista'));
                        }

                    }else{
                        $em->persist($arUsuario);
                        $em->flush();

                        return $this->redirect($this->generateUrl('general_seguridad_usuario_lista'));
                    }

            }
        }
        return $this->render('general/seguridad/nuevo.html.twig', [
            'arUsuario' => $arUsuario,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/general/seguridad/usuario/nuevo/clave/{hash}", name="general_seguridad_usuario_nuevo_clave")
     */
    public function cambiarClave(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $this->verificarUsuario($hash);
        $respuesta = '';
        if ($id != 0) {
            $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
            if (!$arUsuario) {
                return $this->redirect($this->generateUrl('general_seguridad_usuario_lista'));
            }
        }
        $form = $this->createFormBuilder()
            ->add('txtNuevaClave', PasswordType::class, ['required' => true])
            ->add('txtConfirmacionClave', PasswordType::class, ['required' => true])
            ->add('btnActualizar', SubmitType::class, ['label' => 'Actualizar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnActualizar')->isClicked()) {
                $claveNueva = $form->get('txtNuevaClave')->getData();
                $claveConfirmacion = $form->get('txtConfirmacionClave')->getData();
                    if ($claveNueva == $claveConfirmacion) {
                        $arUsuario->setPassword(password_hash($claveNueva, PASSWORD_BCRYPT));
                        $em->persist($arUsuario);
                    } else {
                        $respuesta = "Las claves ingresadas no coindicen";
                    }
            }
            if ($respuesta != '') {
                Mensajes::error($respuesta);
            } else {
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('general/seguridad/cambioClave.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function verificarUsuario($hash)
    {
        $em = $this->getDoctrine()->getManager();
        $id = 0;
        if ($hash != '0') {
            $arUsuarios = $em->getRepository('App:Seguridad\Usuario')->findAll();
            if (count($arUsuarios) > 0) {
                $hash = str_replace('&', '/', $hash);
                foreach ($arUsuarios as $arUsuario) {
                    if (password_verify($arUsuario->getUsername(), $hash)) {
                        $id = $arUsuario->getUsername();
                    }
                }
            }
        }
        return $id;
    }

    private function generarExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $i = 'A';
        do {
            $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
            $i++;
        } while ($i !== 'Z');
        $sheet->setCellValue('A1', 'USUARIO');
        $sheet->setCellValue('B1', 'NOMBRE');
        $sheet->setCellValue('C1', 'IDENTIFICACION');
        $sheet->setCellValue('D1', 'CARGO');
        $sheet->setCellValue('E1', 'EMAIL');
        $sheet->setCellValue('F1', 'TELEFONO');
        $sheet->setCellValue('G1', 'EXT.');
        $sheet->setCellValue('H1', 'ACTIVO');

        $j = 2;
        $arUsuarios = $this->getDoctrine()->getManager()->getRepository('App:Seguridad\Usuario')->findAll();
        foreach ($arUsuarios as $arUsuario) {
            $sheet->setCellValue('A' . $j, $arUsuario->getUsername());
            $sheet->setCellValue('B' . $j, $arUsuario->getNombreCorto());
            $sheet->setCellValue('C' . $j, $arUsuario->getNumeroIdentificacion());
            $sheet->setCellValue('D' . $j, $arUsuario->getCargo());
            $sheet->setCellValue('E' . $j, $arUsuario->getEmail());
            $sheet->setCellValue('F' . $j, $arUsuario->getTelefono());
            $sheet->setCellValue('G' . $j, $arUsuario->getExtension());
            $sheet->setCellValue('H' . $j, $arUsuario->getisActive() == 1 ? 'SI' : 'NO');
            $j++;
        }
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='usuario.xls'");
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }
}

