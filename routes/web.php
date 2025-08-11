<?php

Route::get('/', function () {
    return response()->json(['message' => 'Welcome to the Pawns APP!']);
});
