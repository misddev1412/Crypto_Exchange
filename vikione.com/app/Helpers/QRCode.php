<?php

namespace App\Helpers;

use App\Helpers\QRCodeFactory;
use Illuminate\Support\Facades\Facade;

/**
 * Class QRCode
 *
 * Laravel QR Code Generator is distributed under MIT
 * Copyright (C) 2019 Softnio
 *
 * @package TokenLite
 */
class QRCode extends Facade
{
    protected static function getFacadeAccessor() {
        return new QRCodeFactory();
    }
}