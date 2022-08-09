<?php

namespace App\Form;
use App\Entity\Discount;
use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('image')
            ->add('availability')
            ->add('fk_category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('fk_discount', EntityType::class, [
                'class' => Discount::class,
                'choice_label' => 'name',
            ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}