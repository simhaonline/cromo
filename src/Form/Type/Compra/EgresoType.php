<?php

namespace App\Form\Type\Compra;

use App\Entity\Compra\ComEgreso;
use App\Entity\Compra\ComEgresoTipo;
use App\Entity\General\GenCuenta;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EgresoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comentarios', TextareaType::class, ['label' => 'Comentario:', 'required' => false])
            ->add('egresoTipoRel', EntityType::class, [
                'class' => ComEgresoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                        ->orderBy('et.nombre', 'ASC');
                },
                'choice_label' => 'nombre'

            ])
            ->add('cuentaRel', EntityType::class, [
                'class' => GenCuenta::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre'
            ])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComEgreso::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoEgresoPk",     "tipo":"pk",        "ayuda":"Codigo de compra",   "titulo":"ID"},
            {"campo":"numero",              "tipo":"entero",    "ayuda":"",                     "titulo":"NUMERO"},
            {"campo":"fecha",               "tipo":"fecha",     "ayuda":"Fecha de registro",    "titulo":"FECHA"},
            {"campo":"estadoAutorizado",    "tipo":"bool",      "ayuda":"",                     "titulo":"AUT"},
            {"campo":"estadoAprobado",      "tipo":"bool",      "ayuda":"",                     "titulo":"APR"},
            {"campo":"estadoAnulado",       "tipo":"bool",      "ayuda":"",                     "titulo":"ANU"}                                
        ]';
        return $campos;
    }
}
