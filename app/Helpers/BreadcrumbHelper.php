<?php

namespace App\Helpers;

class BreadcrumbHelper
{
    protected static $breadcrumbs = [];
    protected static $currentPage = '';

    public static function add($title, $url = null)
    {
        self::$breadcrumbs[] = [
            'title' => $title,
            'url' => $url
        ];
    }

    public static function setCurrentPage($title)
    {
        self::$currentPage = $title;
    }

    public static function render()
    {
        return view('components.breadcrumb', [
            'breadcrumbs' => self::$breadcrumbs,
            'currentPage' => self::$currentPage
        ]);
    }

    public static function get()
    {
        return [
            'breadcrumbs' => self::$breadcrumbs,
            'currentPage' => self::$currentPage
        ];
    }

    public static function clear()
    {
        self::$breadcrumbs = [];
        self::$currentPage = '';
    }
}