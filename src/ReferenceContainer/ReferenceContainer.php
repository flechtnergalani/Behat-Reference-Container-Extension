<?php

namespace ReferenceContainer;

use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Behat extension to store references
 */
class ReferenceContainer implements Extension
{
    /**
     * Extension configuration ID.
     */
    const MOD_ID = 'reference_container';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return self::MOD_ID;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()
            ->scalarNode('container')->defaultValue('ArrayContainer')->end();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $definition = new Definition('ReferenceContainer\Initializer\ReferenceContainerAwareInitializer', [
            $config['container']
        ]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, ['priority' => 0]);
        $container->setDefinition('reference_container.context_initializer', $definition);
    }
}
