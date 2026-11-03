<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/notes');
Route::redirect('/admin/reproduction', '/admin/notes');
