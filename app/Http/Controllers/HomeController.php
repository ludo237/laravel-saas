<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controller;
use Inertia\Inertia;
use Inertia\Response;

final class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('welcome');
    }
}
