<?php
// app/Http/Controllers/OfflineController.php

namespace App\Http\Controllers;

class OfflineController extends Controller
{
    public function index()
    {
        return view('offline');
    }
}