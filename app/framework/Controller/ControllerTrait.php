<?php

namespace Framework\Controller;

/**
 * To know about traits, please refer to the link below:
 * https://www.php.net/manual/en/language.oop5.traits.php
 */
trait ControllerTrait
{
    protected static function render(string $template = null, array $parameters = []): array
    {
        return [
            'component' => $template,
            $parameters
        ];
    }
}