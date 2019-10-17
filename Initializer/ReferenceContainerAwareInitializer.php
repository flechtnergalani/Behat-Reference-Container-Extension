<?php


namespace Atamis\Extension\ReferenceContainer\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Atamis\Extension\ReferenceContainer\Interfaces\ReferenceContainerAware;

class ReferenceContainerAwareInitializer implements ContextInitializer
{
    protected $referenceContainer;
    
    public function __construct(string $referenceContainer)
    {
        $container = "Atamis\Extension\\ReferenceContainer\\Container\\$referenceContainer";
        $this->referenceContainer = new $container;
    }
    
    public function initializeContext(Context $context)
    {
        if (!$this->supportsContext($context)) {
            return;
        }
        $context->setReferenceContainer($this->referenceContainer);

    }

    public function supportsContext(Context $context)
    {
        return $context instanceof ReferenceContainerAware;
    }
}
