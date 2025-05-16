<?php

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CookieConsentListener
{

    private string $logFile;

    public function __construct(string $logFile = null)
    {
        $yearMonth = date('Y-m'); // z.B. "2025-05"

        $this->logFile = $logFile ?? __DIR__ . '/../../var/log/url_tracking_'.$yearMonth.'.csv';

        // Falls Datei nicht existiert, Header schreiben
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, "user;timestamp;referer;target;method;status\n");
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if ($event->isMainRequest()) {
            $timestamp = (new \DateTime())->format('Y-m-d H:i:s');
            $referer = str_replace(";", ",", $request->headers->get('referer', '')); // Semikolons ersetzen
            $target = str_replace(";", ",", $request->getRequestUri());

            $clientIp = $request->getClientIp();
            $anonymizedIp = hash('sha256', $clientIp);

            $method = $request->getMethod();
            $status = $response->getStatusCode();

            $refererPath = '';
            if ($referer) {
                $parts = parse_url($referer);
                // Pfad und Query zusammensetzen
                $refererPath = $parts['path'] ?? '';
                if (isset($parts['query'])) {
                    $refererPath .= '?' . $parts['query'];
                }
            }

            if (!str_starts_with($target, '/_fragment') and $request->getMethod() !== 'POST') {
                $line = "$anonymizedIp;$timestamp;$refererPath;$target;$method;$status\n";
                file_put_contents($this->logFile, $line, FILE_APPEND);
            }


        }

        if (str_starts_with($request->getPathInfo(), '/media/cache/')) {
            $response = $event->getResponse();
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        }

        // Cookie aus der Anfrage lesen (Fallback auf leeres Array, falls Cookie nicht gesetzt)
        $cookieConsent = $request->cookies->get('cookie_consent');

        if($cookieConsent) {
            $cookieConsentArray = json_decode($cookieConsent, true);

            // Sicherstellen, dass die JSON-Daten korrekt verarbeitet wurden (Fallback auf leeres Array)
            if (!is_array($cookieConsentArray)) {
                $cookieConsentArray = [];
            }

            // Prüfen, ob die Zustimmung für "preferences" vorliegt
            if (!in_array('preferences', $cookieConsentArray)) {
                // Lösche Cookies, da "preferences" nicht akzeptiert wurde
                $response->headers->clearCookie('dt-demo-runtime');
                $response->headers->clearCookie('dt-demo-scheme');
                $response->headers->clearCookie('dt-demo-style');
            }
            $request->getSession()->set('cookie_consent', $cookieConsent);
        } else {
            $request->getSession()->remove('cookie_consent');
        }
    }

}