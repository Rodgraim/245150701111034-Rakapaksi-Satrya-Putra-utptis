<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "API sederhana untuk e-commerce backend menggunakan mock data JSON.",
    title: "E-commerce Backend API"
)]
abstract class Controller
{
}
