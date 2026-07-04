<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
use Illuminate\Contracts\Console\Kernel;
$app->make(Kernel::class)->bootstrap();

Log::info('TEST LOG FROM PHP FILE');
echo "Log written!\n";
