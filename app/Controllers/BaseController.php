<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Stichoza\GoogleTranslate\GoogleTranslate;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    protected function translateText(?string $text, ?string $fallback = null): ?string
    {
        $text = trim((string) $text);
        if ($text === '') return null;
        if (mb_strlen($text) > 4000) {
            $segments = preg_split('/(?<=<\/p>|<\/li>|<br\s*\/?>|\r?\n)/i', $text, -1, PREG_SPLIT_NO_EMPTY);
            if (count($segments) < 2) {
                $segments = mb_str_split($text, 3500);
            }
            $translatedSegments = [];
            foreach ($segments as $segment) {
                $translated = $this->translateTextChunk($segment);
                if ($translated === null) return $fallback;
                $translatedSegments[] = $translated;
            }
            return implode('', $translatedSegments);
        }
        return $this->translateTextChunk($text) ?? $fallback;
    }

    private function translateTextChunk(string $text): ?string
    {
        try {
            $translator = new GoogleTranslate('en');
            $translator->setSource('id');
            $translated = trim((string) $translator->translate($text));
            return $translated !== '' ? $translated : null;
        } catch (\Throwable $e) {
            log_message('error', 'Translation failed: ' . $e->getMessage());
            return null;
        }
    }
}
