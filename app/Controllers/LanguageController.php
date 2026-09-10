<?php

namespace App\Controllers;

class LanguageController extends BaseController
{
    public function switchLanguage($lang = 'id')
    {
        $supportedLanguages = ['id', 'en'];

        if (in_array($lang, $supportedLanguages)) {
            session()->set('lang', $lang);
        } else {
            session()->set('lang', 'id');
        }

        return redirect()->back();
    }
}
