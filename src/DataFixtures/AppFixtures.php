<?php

namespace App\DataFixtures;

use App\Entity\EventType;
use App\Entity\School;
use App\Operator\SoundExpression;
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

        $schools = [
            [
                'title' => 'Berlin-Kolleg',
                'city' => 'Berlin',
                'district' => 'Mitte',
                'sub_district' => 'Moabit',
                'plz' => '10551',
                'street' => 'Turmstr. 75',
                'bsn' => '01A04',
                'url' => 'https://www.berlin-kolleg.de',
                'lat' => '5.252.585',
                'lon' => '13.341.352.777.778'
            ],
            [
                'title' => 'OSZ Banken und Versicherungen',
                'city' => 'Berlin',
                'district' => 'Mitte',
                'sub_district' => 'Moabit',
                'plz' => '10557',
                'street' => 'Alt-Moabit 10',
                'bsn' => '01B01',
                'url' => 'https://www.osz-banken-versicherungen.de',
                'lat' => '52.523.907',
                'lon' => '13.357.867'
            ],
            [
                'title' => 'Schule am Schillerpark',
                'city' => 'Berlin',
                'district' => 'Mitte',
                'sub_district' => 'Wedding',
                'plz' => '13349',
                'street' => 'Ofener Str. 6',
                'bsn' => '01K08',
                'url' => 'https://www.Schule-am-Schillerpark.de',
                'lat' => '5.255.635',
                'lon' => '13.345.781'
            ],
            [
                'title' => 'Refik-Veseli-Schule',
                'city' => 'Berlin',
                'district' => 'Friedrichshain-Kreuzberg',
                'sub_district' => 'Kreuzberg',
                'plz' => '10997',
                'street' => 'Skalitzer Str. 55',
                'bsn' => '02K08',
                'url' => 'https://www.schule-skalitzer.de',
                'lat' => '52.499.666',
                'lon' => '13.435.733'
            ],
            [
                'title' => 'Abendgymnasium Prenzlauer Berg',
                'city' => 'Berlin',
                'district' => 'Pankow',
                'sub_district' => 'Prenzlauer Berg',
                'plz' => '10439',
                'street' => 'Driesener Str.22',
                'bsn' => '03A04',
                'url' => 'https://www.abendgymnasium-berlin.de',
                'lat' => '52.552.015',
                'lon' => '13.405.672'
            ],
            [
                'title' => 'Volkshochschule Pankow',
                'city' => 'Berlin',
                'district' => 'Pankow',
                'sub_district' => 'Prenzlauer Berg',
                'plz' => '10409',
                'street' => 'Hanns-Eisler-Str. 78-80',
                'bsn' => '03A05',
                'url' => 'https://www.zbw-pankow.de',
                'lat' => '5.254.002',
                'lon' => '13.451.861'
            ],
            [
                'title' => 'Charlotte-Wolff-Kolleg',
                'city' => 'Berlin',
                'district' => 'Charlottenburg-Wilmersdorf',
                'sub_district' => 'Charlottenburg',
                'plz' => '10627',
                'street' => 'Pestalozzistr. 40-41',
                'bsn' => '04A04',
                'url' => 'https://www.charlotte-wolff-kolleg.de',
                'lat' => '525.716.395',
                'lon' => '134.049.527'
            ],
            [
                'title' => 'Peter-A.-Silbermann-Schule',
                'city' => 'Berlin',
                'district' => 'Charlottenburg-Wilmersdorf',
                'sub_district' => 'Wilmersdorf',
                'plz' => '10713',
                'street' => 'Blissestr. 22',
                'bsn' => '04A06',
                'url' => 'https://www.abendgymnasium.de',
                'lat' => '52.484.225',
                'lon' => '13.319.487'
            ],
            [
                'title' => 'OSZ Kraftfahrzeugtechnik',
                'city' => 'Berlin',
                'district' => 'Charlottenburg-Wilmersdorf',
                'sub_district' => 'Charlottenburg',
                'plz' => '10585',
                'street' => 'Gierkeplatz 1-3',
                'bsn' => '04B03',
                'url' => 'https://www.osz-kfz.de',
                'lat' => '52.517.632',
                'lon' => '1.330.217'
            ],
            [
                'title' => 'Kläre-Bloch-Schule',
                'city' => 'Berlin',
                'district' => 'Charlottenburg-Wilmersdorf',
                'sub_district' => 'Wilmersdorf',
                'plz' => '10715',
                'street' => 'Prinzregentenstr. 60',
                'bsn' => '04B08',
                'url' => 'https://www.klaere-bloch-schule.de',
                'lat' => '5.248.237',
                'lon' => '13.333.499'
            ],
            [
                'title' => 'Peter-Ustinov-Schule',
                'city' => 'Berlin',
                'district' => 'Charlottenburg-Wilmersdorf',
                'sub_district' => 'Charlottenburg',
                'plz' => '14057',
                'street' => 'Kuno-Fischer-Str. 22-26',
                'bsn' => '04K08',
                'url' => 'https://www.peter-ustinov-schule.de',
                'lat' => '52.504.753',
                'lon' => '13.290.931'
            ],
            [
                'title' => 'Volkshochschule Spandau',
                'city' => 'Berlin',
                'district' => 'Spandau',
                'sub_district' => 'Spandau',
                'plz' => '13597',
                'street' => 'Moritzstr. 17',
                'bsn' => '05A03',
                'url' => 'https://www.vhs.berlin.de ',
                'lat' => '52.526.678',
                'lon' => '13.225.218'
            ],
            [
                'title' => 'Volkshochschule Steglitz-Zehlendorf',
                'city' => 'Berlin',
                'district' => 'Steglitz-Zehlendorf',
                'sub_district' => 'Lichterfelde',
                'plz' => '12207',
                'street' => 'Goethestr. 9-11',
                'bsn' => '06A05',
                'url' => 'https://www.zbw-berlin.de',
                'lat' => '52.421.122',
                'lon' => '13.306.622'
            ],
            [
                'title' => 'Kolleg Schöneberg',
                'city' => 'Berlin',
                'district' => 'Tempelhof-Schöneberg',
                'sub_district' => 'Schöneberg',
                'plz' => '10787',
                'street' => 'Nürnberger Str. 63',
                'bsn' => '07A05',
                'url' => 'https://www.KollegSchoeneberg.de',
                'lat' => '52.506.857',
                'lon' => '13.342.202'
            ],
            [
                'title' => 'Volkshochschule Tempelhof-Schöneberg',
                'city' => 'Berlin',
                'district' => 'Tempelhof-Schöneberg',
                'sub_district' => 'Friedenau',
                'plz' => '14197',
                'street' => 'Offenbacher Str. 5A',
                'bsn' => '07A06',
                'url' => 'https://www.vhs-tempelhof-schoeneberg.de',
                'lat' => '5.247.524',
                'lon' => '1.332.179'
            ],
            [
                'title' => 'Zuckmayer-Schule',
                'city' => 'Berlin',
                'district' => 'Neukölln',
                'sub_district' => 'Neukölln',
                'plz' => '12053',
                'street' => 'Kopfstr. 55',
                'bsn' => '08K10',
                'url' => 'https://www.zuckmayer-or.cidsnet.de',
                'lat' => '52.475.934',
                'lon' => '13.431.433'
            ],
            [
                'title' => 'Treptow-Kolleg',
                'city' => 'Berlin',
                'district' => 'Treptow-Köpenick',
                'sub_district' => 'Baumschulenweg',
                'plz' => '12437',
                'street' => 'Kiefholzstr. 274',
                'bsn' => '09A05',
                'url' => 'https://www.treptow-kolleg.de',
                'lat' => '52.468.018',
                'lon' => '13.483.923'
            ],
            [
                'title' => 'Volkshochschule Treptow-Köpenick',
                'city' => 'Berlin',
                'district' => 'Treptow-Köpenick',
                'sub_district' => 'Oberschöneweide',
                'plz' => '12459',
                'street' => 'Zeppelinstr. 76-80',
                'bsn' => '09A06',
                'url' => 'nan',
                'lat' => '52.546.984',
                'lon' => '13.184.843'
            ],
            [
                'title' => 'OSZ Wirtschaft und Sozialversicherung',
                'city' => 'Berlin',
                'district' => 'Treptow-Köpenick',
                'sub_district' => 'Oberschöneweide',
                'plz' => '12459',
                'street' => 'Helmholtzstr. 37',
                'bsn' => '09B03',
                'url' => 'https://www.osz-wiso.de',
                'lat' => '52.468.411',
                'lon' => '13.514.612'
            ],
            [
                'title' => 'Victor-Klemperer-Kolleg',
                'city' => 'Berlin',
                'district' => 'Marzahn-Hellersdorf',
                'sub_district' => 'Marzahn',
                'plz' => '12681',
                'street' => 'Martha-Arendsee-Str. 15',
                'bsn' => '10A04',
                'url' => 'https://www.abi-vkk.de',
                'lat' => '52.536.251',
                'lon' => '13.538.921'
            ],
            [
                'title' => 'Oscar-Tietz-Schule',
                'city' => 'Berlin',
                'district' => 'Marzahn-Hellersdorf',
                'sub_district' => 'Marzahn',
                'plz' => '12681',
                'street' => 'Marzahner Chaussee 231',
                'bsn' => '10B01',
                'url' => 'https://www.oscar-tietz-schule.de',
                'lat' => '52.530.273',
                'lon' => '1.353.315'
            ],
            [
                'title' => 'Kerschensteiner-Schule',
                'city' => 'Berlin',
                'district' => 'Marzahn-Hellersdorf',
                'sub_district' => 'Marzahn',
                'plz' => '12689',
                'street' => 'Golliner Str. 2',
                'bsn' => '10K03',
                'url' => 'https://www.k-iss.de',
                'lat' => '52.566.739',
                'lon' => '13.579.472'
            ],
            [
                'title' => 'BmU-Wirtschafts- und Steuerfachschule für den Mittelstand',
                'city' => 'Berlin',
                'district' => 'Lichtenberg',
                'sub_district' => 'Fennpfuhl',
                'plz' => '10369',
                'street' => 'Franz-Jacob-Str. 2C',
                'bsn' => '11E13',
                'url' => 'https://www.bmu-wirtschafts-und-steuerfachschule.de',
                'lat' => '52.524.974',
                'lon' => '13.465.295'
            ],
            [
                'title' => 'Georg-Schlesinger-Schule',
                'city' => 'Berlin',
                'district' => 'Reinickendorf',
                'sub_district' => 'Reinickendorf',
                'plz' => '13409',
                'street' => 'Kühleweinstr. 5',
                'bsn' => '12B01',
                'url' => 'https://www.gs-schule.de',
                'lat' => '52.562.039',
                'lon' => '13.370.665'
            ],
            [
                'title' => 'Emil-Fischer-Schule',
                'city' => 'Berlin',
                'district' => 'Reinickendorf',
                'sub_district' => 'Wittenau',
                'plz' => '13437',
                'street' => 'Cyclopstr. 1-5',
                'bsn' => '12B02',
                'url' => 'https://www.emilfischerschule.de',
                'lat' => '52.600.452',
                'lon' => '13.326.235'
            ],
        ];

        foreach ($eventTypes as $eventType) {
            $type = new EventType();
            $type->setTitle($eventType[0]);
            $type->setDescription($eventType[1]);
            $manager->persist($type);
        }

        foreach ($schools as $school) {
            $schoolObject = new School();
            $schoolObject->setTitle($school[0]);
            $schoolObject->setCity($school[1]);
            $schoolObject->setDistrict($school[2]);
            $schoolObject->setSubDistrict($school[3]);
            $schoolObject->setPlz($school[4]);
            $schoolObject->setStreet($school[5]);
            $schoolObject->setBsn($school[6]);
            $schoolObject->setUrl($school[7]);
            $schoolObject->setLat($school[8]);
            $schoolObject->setLon($school[9]);
            $schoolObject->setTitleSoundEx(SoundExpression::generate($schoolObject->getTitle()));
            $manager->persist($schoolObject);
        }

        $manager->flush();
    }
}
