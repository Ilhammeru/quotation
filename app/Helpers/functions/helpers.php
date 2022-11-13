<?php

use App\Models\Material;
use Illuminate\Support\Facades\Route;

if (! function_exists('areActiveRoutes')) {
    function areActiveRoutes(Array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

if (!function_exists('getUrlSegment')) {
    function getUrlSegment($num) {
        $name = request()->segment($num);
        return strtolower($name);
    }
}

if (!function_exists('setPageTitle')) {
    function setPageTitle($title) {
        return '<p class="main-page-title">'. $title .'</p>';
    }
}

if (!function_exists('dt_table_class')) {
    function dt_table_class()
    {
        return 'align-middle table-bordered fs-6 gy-5 mb-0';
    }
}

if (!function_exists('dt_head_class')) {
    function dt_head_class()
    {
        return 'text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0';
    }
}