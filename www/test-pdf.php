<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $pdf = app('dompdf.wrapper')->loadView('pdf.recovery-codes', ['codes' => ['AAA', 'BBB', 'CCC', 'DDD', 'EEE', 'FFF', 'GGG', 'HHH']]);
    $out = $pdf->output();
    echo "SUCCESS: PDF Size is " . strlen($out) . " bytes\n";
} catch (\Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
