<?php

use Slim\App;
use App\Controllers\CSVController;
use App\Controllers\ScoreController;

return function (App $app) {
    $app->post('/aplicar-bonus', ScoreController::class . ':aplicarBonus');
    $app->post('/import', CSVController::class . ':import');
};
