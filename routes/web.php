<?php

use AbdulQuadri\ActivityLogger\Http\Controllers\ActivityController;

Route::group(['middleware' => config('activity-logger.routes.middleware')], function () {
    Route::get('/', [ActivityController::class, 'index'])->name('activity-logger.index');
    Route::get('/{activity}', [ActivityController::class, 'show'])->name('activity-logger.show');
    Route::delete('/{activity}', [ActivityController::class, 'destroy'])->name('activity-logger.destroy');
});