<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\General\GenBanco;
use App\Entity\RecursoHumano\RhuBanco;
use App\Entity\RecursoHumano\RhuEmpleado;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('bancoRel', EntityType::class, [
                'class' => GenBanco::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Banco:',
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
            ->add('digitoVerificacion', NumberType::class, ['required' => true])
            ->add('nombre1', TextType::class, ['required' => true, 'label' => 'Primer nombre:'])
            ->add('nombre2', TextType::class, ['required' => false, 'label' => 'Segundo nombre:'])
            ->add('apellido1', TextType::class, ['required' => true, 'label' => 'Primer apellido:'])
            ->add('apellido2', TextType::class, ['required' => false, 'label' => 'Segundo apellido:'])
            ->add('telefono', TextType::class, ['required' => false, 'label' => 'Telefono:'])
            ->add('peso', NumberType::class, ['required' => false, 'label' => 'Peso:'])
            ->add('estatura', NumberType::class, ['required' => false, 'label' => 'Estatura:'])
            ->add('celular', TextType::class, ['required' => false, 'label' => 'Celular:'])
            ->add('direccion', TextType::class, ['required' => false, 'label' => 'Direccion:'])
            ->add('tallaCamisa', TextType::class, ['required' => false, 'label' => ''])
            ->add('tallaPantalon', TextType::class, ['required' => false, 'label' => ''])
            ->add('tallaCalzado', TextType::class, ['required' => false, 'label' => ''])
            ->add('codigoCuentaTipoFk', ChoiceType::class, array('choices' => array('AHORRO' => 'S', 'CORRIENTE' => 'D', 'DAVIPLATA' => 'DP')))
            ->add('fechaExpedicionIdentificacion', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('barrio', TextType::class, ['required' => false, 'label' => 'Barrio:'])
            ->add('correo', TextType::class, ['required' => false, 'label' => 'Correo:'])
            ->add('fechaNacimiento', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('cuenta', TextType::class, ['required' => false, 'label' => 'Cuenta:'])
            ->add('discapacidad', CheckboxType::class, ['required' => false, 'label' => 'Discapacidad'])
            ->add('carro', CheckboxType::class, ['required' => false, 'label' => 'Carro'])
            ->add('moto', CheckboxType::class, ['required' => false, 'label' => 'Moto'])
            ->add('padreFamilia', CheckboxType::class, ['required' => false, 'label' => 'Padre familia'])
            ->add('cabezaHogar', CheckboxType::class, ['required' => false, 'label' => 'Cabeza hogar'])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEmpleado::class,
        ]);
    }

}