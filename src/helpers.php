<?php
if (!function_exists('banner')) {
    function banner(&$params, &$banners, $thisKey, $thisCount, $key, $label=null)
    {
        return \Zakhayko\Banners\Widget::render($params,$banners, $thisKey, $thisCount, $key, $label);
    }
}