<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', function (Request $request) {
        // return $request->user();
        return "welcome to api";
    // });

    // Add more API routes here
});
 ?>