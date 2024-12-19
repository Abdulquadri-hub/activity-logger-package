<?php

use AbdulQuadri\ActivityLogger\Http\Controllers\Api\ActivityController;

Route::group(['middleware' => config('activity-logger.routes.api_middleware')], function () {
    Route::get('/activities', [ActivityController::class, 'index']);
    Route::get('/activities/{activity}', [ActivityController::class, 'show']);
    Route::get('/activities/user/{user}', [ActivityController::class, 'userActivities']);
    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy']);
});