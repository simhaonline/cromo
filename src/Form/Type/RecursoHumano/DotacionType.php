<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuDotacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DotacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaEntrega', DateType::class, ['required' => false])
            ->add('comentarios', TextareaType::class, ['required' => false])
            ->add('afectaInventario', CheckboxType::class, ['required' => false] )
            ->add('tipoDotacion', ChoiceType::class, ['choices' => ['NUEVO' => 'NUEVO', 'DEVOLUCIÓN' => 'DEVOLUCIÓN'], 'required' => false])
            ->add('codigoInternoReferencia', NumberType::class, array('required' => false))

            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuDotacion::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoDotacionPk",      "tipo":"pk"      ,"ayuda":"Codigo del empleado"                   ,"titulo":"ID"},
            {"campo":"codigoEmpleadoFk",  "tipo":"texto"   ,"ayuda":"Numero de identificacion del empleado" ,"titulo":"IDENTIFICACION"}                   
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoDotacionPk",   "tipo":"TextType",   "propiedades":{"label":"Nombre"},   "operador":"="},
            {"child":"codigoInternoReferencia",   "tipo":"TextType",   "propiedades":{"label":"Nombre"},   "operador":"like"}
        ]';

        return $campos;
    }
}