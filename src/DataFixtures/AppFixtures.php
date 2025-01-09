<?php

namespace App\DataFixtures;

use App\Entity\EventType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $eventTypes = [
            ['Seminar', 'Fachvorträge und Workshops zu einem spezifischen Thema'],
            ['Konferenz', 'Treffen von Fachleuten zum Austausch und Präsentation neuer Erkenntnisse'],
            ['Fest', 'Feierlichkeiten mit Unterhaltung, Musik und Essen'],
            ['Workshop', 'Praktische Schulungen und Übungen zu einem Thema'],
            ['Messe', 'Ausstellung von Produkten und Dienstleistungen'],
            ['Tagung', 'Fachveranstaltung mit Vorträgen und Diskussionen'],
            ['Kongress', 'Großes Treffen von Fachleuten aus einem bestimmten Bereich'],
            ['Webinar', 'Online-Seminar oder Präsentation'],
            ['Symposium', 'Wissenschaftliche Diskussion oder Präsentation zu einem Fachgebiet'],
            ['Festival', 'Kulturelles Ereignis mit Musik, Kunst oder Film'],
            ['Ausstellung', 'Präsentation von Kunst, Produkten oder Innovationen'],
            ['Konzert', 'Musikalische Darbietung'],
            ['Theateraufführung', 'Inszenierung eines Theaterstücks'],
            ['Sportveranstaltung', 'Wettbewerbe oder Turniere im Sportbereich'],
            ['Gala', 'Elegante Abendveranstaltung mit Essen und Unterhaltung'],
            ['Lesung', 'Vorlesen literarischer Werke'],
            ['Podiumsdiskussion', 'Diskussionsrunde mit Experten zu einem Thema'],
            ['Networking-Event', 'Treffen zum Aufbau von Kontakten und Beziehungen'],
            ['Vortrag', 'Präsentation zu einem speziellen Thema'],
            ['Filmvorführung', 'Präsentation eines Films'],
            ['Benefizveranstaltung', 'Event zur Spendensammlung für wohltätige Zwecke'],
            ['Eröffnungsfeier', 'Feier zur Einweihung eines Gebäudes oder Projekts'],
            ['Preisverleihung', 'Veranstaltung zur Ehrung von Personen oder Leistungen'],
            ['Modenschau', 'Präsentation von Modekollektionen'],
            ['Open-Air-Event', 'Veranstaltung im Freien, z.B. Konzerte oder Märkte'],
            ['Flohmarkt', 'Verkauf von gebrauchten Gegenständen'],
            ['Schulungsprogramm', 'Ausbildung oder Fortbildung für bestimmte Fähigkeiten'],
            ['Gemeindeveranstaltung', 'Treffen oder Feierlichkeiten auf lokaler Ebene'],
            ['Familienfest', 'Zusammenkünfte für Familien mit Unterhaltung für alle Altersgruppen'],
            ['Kulturabend', 'Präsentation kultureller Darbietungen']
        ];

        foreach ($eventTypes as $eventType) {
            $type = new EventType();
            $type->setTitle($eventType[0]);
            $type->setDescription($eventType[1]);
            $manager->persist($type);
        }
        $manager->flush();



        $manager->flush();
    }
}
