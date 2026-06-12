<?php

if (!function_exists('setting')) {
    function setting($key)
    {
        $model = new \App\Models\PengaturanSekolahModel();
        $data = $model->first();
        return $data[$key] ?? null;
    }
}