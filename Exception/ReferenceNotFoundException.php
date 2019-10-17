<?php

namespace Atamis\Extension\ReferenceContainer\Exception;

use Exception;

/**
 * Exception thrown if a reference container can't find the reference requested

 */
class ReferenceNotFoundException extends Exception
{
    /**
     * Constructor
     *
     * @param string $reference
     * @param int $position
     */
    public function __construct($reference, $position = 'unspecified by user')
    {
        parent::__construct(
            "Reference '$reference' at position $position not found"
            );
    }
}