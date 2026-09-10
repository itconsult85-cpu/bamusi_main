<?php

if (!function_exists('get_text')) {
    /**
     * Mengambil teks sesuai bahasa yang aktif di session.
     * 
     * @param array $item Array data dari database (misal: $program)
     * @param string $key Kunci kolom dasar (misal: 'name' atau 'description')
     * @return string
     */
    function get_text($item, $key)
    {
        $lang = session()->get('lang') ?? 'id';
        $enKey = $key . '_en';

        // Jika user memilih Inggris DAN data terjemahannya ada/tidak kosong
        if ($lang === 'en' && !empty($item[$enKey])) {
            return $item[$enKey];
        }

        // Fallback: Tampilkan bahasa Indonesia
        return $item[$key] ?? '';
    }
}
