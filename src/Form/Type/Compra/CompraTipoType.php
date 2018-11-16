<?php

namespace App\Form\Type\Compra;

use App\Entity\Compra\ComCompraTipo;
use App\Entity\Compra\ComCuentaPagarTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompraTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCompraTipoPk', TextType::class, ['label' => 'Código:'])
            ->add('cuentaPagarTipoRel', EntityType::class, [
                'class' => ComCuentaPagarTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cp')
                        ->orderBy('cp.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cuenta pagar tipo:'
            ])
            ->add('nombre', TextType::class, ['label' => 'Nombre:'])
            ->add('operacion', ChoiceType::class, ['label' => 'Operación:', 'choices' => ['SUMA' => 1, 'RESTA' => -1]])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComCompraTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCompraTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro",                    "titulo":"ID"},
            {"campo":"nombre",             "tipo":"texto", "ayuda":"Nombre del registro",                    "titulo":"NOMBRE"}            
        ]';
        return $campos;
    }
}
