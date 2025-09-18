<?php

namespace App\Helper;

use Proengsoft\JsValidation\Facades\JsValidatorFacade;

class Helper
{
    public static function generateValidator(string $formRequest, string $formSelector): string
    {
        return preg_replace(
            "/<\/?script>/",
            "",
            JsValidatorFacade::formRequest($formRequest, $formSelector)
        );
    }
}