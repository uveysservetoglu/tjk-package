<?php
/**
 * Created by PhpStorm.
 * User: Uveys SERVETOGLU (@uveysservetoglu)
 * Date: 6.07.2018
 * Time: 09:46
 */

namespace TjkBundle\Crawlers;

use AppBundle\Entity\Races;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Constraints\DateTime;

class RaceSchedule extends BaseCrawler
{
    public function crawl($city){

        $date = date("d/m/Y");
        self::getRace($date, $city);
    }

    private function getRace($date,$city){

        $url = "http://www.tjk.org/TR/YarisSever/Info/Sehir/GunlukYarisProgrami?SehirId=4&QueryParameter_Tarih=".$date."&SehirAdi=".$city;
        $crawler = new Crawler(file_get_contents($url));

        $xPathRule = '//div[@class="program"]/div[4]/div';
        $nodeValue[]=$crawler->filterXPath($xPathRule)->each(function (Crawler $nodes){

            echo "[OK] Races List GET".PHP_EOL;
            $ar = [" ","\r\n" ];

            $raceNo   = trim(str_replace($ar,"", $nodes->filterXPath( '//div[@class="race-details"]/h3[1]')->text()));
            $raceDesc = trim(str_replace($ar,"", $nodes->filterXPath( '//div[@class="race-details"]/h3[2]')->text()));


            $bonus[] = $nodes->filterXPath( '//div[@class="race-share"]/dl[1]/dd')->each(function (Crawler $node) use($ar){

                return trim(str_replace($ar,"", $node->text()));
            });


            $famerBonus[] = $nodes->filterXPath( '//div[@class="race-share"]/dl[1]/dd')->each(function (Crawler $node) use($ar){

                return trim(str_replace($ar,"", $node->text()));
            });


            $xPathRule   = '//table[@class="tablesorter"]/tbody[1]/tr';
            $nodeValue[]["cell"][] = $nodes->filterXPath($xPathRule)->each(function (Crawler $node) use ($raceNo, $raceDesc, $bonus, $famerBonus){
                $race = [
                        // "race_image"  => parent::saveImage('', $node->filterXPath('//td[1]/a/img')->attr("src")),
                        "horse_name" => (!empty($node->filterXPath("//td[3]/a")->text())    ? $node->filterXPath("//td[3]/a")->text() : ""),
                        "horse_age"  => (!empty($node->filterXPath("//td[4]")->text())      ? trim($node->filterXPath("//td[4]")->text()) : ""),
                        "dad_name"   => (!empty($node->filterXPath("//td[5]/a[1]")->text()) ? $node->filterXPath("//td[5]/a[1]")->text() : ""),
                        "mom_name"   => (!empty($node->filterXPath("//td[5]/a[2]")->text()) ? $node->filterXPath("//td[5]/a[2]")->text() : ""),
                        "kilogram"   => (!empty($node->filterXPath("//td[6]")->text())      ? trim($node->filterXPath("//td[6]")->text()): ""),
                        "jokey"      => (!empty($node->filterXPath("//td[7]/a")->text())    ? $node->filterXPath("//td[7]/a")->text() : ""),
                        "owner"      => (!empty($node->filterXPath("//td[8]/a")->text())    ? $node->filterXPath("//td[8]/a")->text() : ""),
                        "trainer"    => (!empty($node->filterXPath("//td[9]/a")->text())    ? $node->filterXPath("//td[9]/a")->text() : ""),
                        "start_id"   => (!empty($node->filterXPath("//td[10]")->text())     ? trim($node->filterXPath("//td[10]")->text()) : ""),
                        "hc"         => (!empty($node->filterXPath("//td[11]")->text())     ? trim($node->filterXPath("//td[11]")->text()) : ""),
                        "last_race"  => (!empty($node->filterXPath("//td[12]")->text())     ? trim($node->filterXPath("//td[12]")->text()) : ""),
                        "kgs"        => (!empty($node->filterXPath("//td[13]")->text())     ? trim($node->filterXPath("//td[13]")->text()) : ""),
                        "s20"        => (!empty($node->filterXPath("//td[14]")->text())     ? trim($node->filterXPath("//td[14]")->text()) : ""),
                        // "best20"     => (!empty($node->filterXPath("//td[15]/a")->text())   ? trim($node->filterXPath("//td[15]/a")->text()) : ""),
                        // "agf"        => (!empty($node->filterXPath("//td[16]")->text())     ? trim($node->filterXPath("//td[16]")->text())   : ""),
                ];

                return $race;
            });

            $nodeValue[0]["detail"]=  [
                "race_no"    => $raceNo,
                "description"=> $raceDesc,
                "famer_bonus"=> $famerBonus[0],
                "bonus"      => $bonus[0]
            ];
           return $nodeValue[0];

        });

        self::saveToDb(json_decode(json_encode(array("date" =>$date, "city"=>$city, "data"=>$nodeValue[0]))));
    }

    private function saveToDb($object){

        $entity = new Races();

        $entity->setRaceDate( new \DateTime("now"));
        $entity->setCity($object->city);
        $entity->setData($object->data);

        $this->kernel->getContainer()->get('doctrine')->getManager()->persist($entity);
        $this->kernel->getContainer()->get('doctrine')->getManager()->flush();
    }
}