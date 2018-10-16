<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmpleado;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EmpleadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacionRel', EntityType::class, [
                'class' => 'App\Entity\General\GenIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Identificacion tipo:',
                'required' => true
            ])
            ->add('ciudadRel', EntityType::class, [
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gc')
                        ->orderBy('gc.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('ciudadExpedicionRel', EntityType::class, [
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ce')
                        ->orderBy('ce.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad residencia:',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('sexoRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\General\GenSexo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Sexo:'
            ])
            ->add('ciudadNacimientoRel', EntityType::class, [
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ce')
                        ->orderBy('ce.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad residencia:',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('estadoCivilRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\General\GenEstadoCivil',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ec')
                        ->orderBy('ec.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Estado civil:'
            ])
            ->add('cargoRel', EntityType::class, [
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ac')
                        ->orderBy('ac.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cargo interno:'
            ])
            ->add('rhRel', EntityType::class, [
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuRh',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ac')
                        ->orderBy('ac.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Rh:'
            ])
            ->add('numeroIdentificacion', NumberType::class, ['required' => true, 'label' => 'numero identificacion:'])
            ->add('nombre1', TextType::class, ['required' => true, 'label' => 'Primer nombre:'])
            ->add('nombre2', TextType::class, ['required' => false, 'label' => 'Segundo nombre:'])
            ->add('apellido1', TextType::class, ['required' => true, 'label' => 'Primer apellido:'])
            ->add('apellido2', TextType::class, ['required' => false, 'label' => 'Segundo apellido:'])
            ->add('telefono', NumberType::class, ['required' => false, 'label' => 'Telefono:'])
            ->add('peso', NumberType::class, ['required' => false, 'label' => 'Peso:'])
            ->add('estatura', NumberType::class, ['required' => false, 'label' => 'Estatura:'])
            ->add('celular', NumberType::class, ['required' => false, 'label' => 'Celular:'])
            ->add('direccion', TextType::class, ['required' => false, 'label' => 'Direccion:'])
            ->add('fechaExpedicionIdentificacion', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('barrio', TextType::class, ['required' => false, 'label' => 'Barrio:'])
            ->add('correo', TextType::class, ['required' => false, 'label' => 'Correo:'])
            ->add('fechaNacimiento', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('cuenta', TextType::class, ['required' => false, 'label' => 'Cuenta:'])
            ->add('vrSalario', NumberType::class, ['required' => true, 'label' => 'Salario:'])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEmpleado::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoEmpleadoPk",      "tipo":"pk" ,"ayuda":"Codigo del empleado"                           ,"titulo":"ID"},
            {"campo":"numeroIdentificacion",  "tipo":"texto"   ,"ayuda":"Numero de identificacion del empleado"    ,"titulo":"IDENTIFICACION"},
            {"campo":"nombreCorto",           "tipo":"texto"   ,"ayuda":"Nombre del empleado"                      ,"titulo":"NOMBRE"},
            {"campo":"codigoContratoFk",      "tipo":"texto"   ,"ayuda":"Codigo del contrato"                      ,"titulo":"CONTRATO"},
            {"campo":"telefono",              "tipo":"texto"   ,"ayuda":"Telefono del empleado"                    ,"titulo":"TELEFONO"},
            {"campo":"correo",                "tipo":"texto"   ,"ayuda":"Correo del empleado"                      ,"titulo":"CORREO"},
            {"campo":"direccion",             "tipo":"texto"   ,"ayuda":"Direccion de residencia del empleado"     ,"titulo":"DIRECCION"}                     
        ]';
        return $campos;
    }
}