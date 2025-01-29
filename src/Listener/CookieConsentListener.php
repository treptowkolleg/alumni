<?php

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CookieConsentListener
{

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

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