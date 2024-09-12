<?php

use App\Models\Technology;
use Illuminate\Support\Facades\Route;

Route::get('/technologies/{categoryId}', function ($categoryId) {
    // Path to the JSON file in the public folder
    $filePath = public_path('testApi.json');

    // Check if the file exists
    if (!file_exists($filePath)) {
        return response('File not found', 404)
            ->header('Content-Type', 'text/plain');
    }

    // Get the content of the file
    $fileContent = file_get_contents($filePath);

    // Return the content as plain text
    return response($fileContent, 200)
        ->header('Content-Type', 'text/plain');
});



