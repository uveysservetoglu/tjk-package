<?php
/**
 * Created by PhpStorm.
 * User: Uveys-Mac
 * Date: 30.05.2018
 * Time: 13:41
 */
namespace TjkBundle\Crawlers;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use GuzzleHttp\Client as GuzzleClient;
class BaseCrawler
{
    public $kernel;
    public $rootDir;
    public $fs;

    /**
     * BaseCrawler constructor.
     * @param $kernel
     */
    public function __construct($kernel)
    {
        $this->kernel = $kernel;
        $this->rootDir = $kernel->getRootDir();
        $this->fs = new Filesystem();
    }

    public function tagContent($link, $xPathRule){
        $crawler = new Crawler(file_get_contents($link));
        $filter = $crawler->filterXPath($xPathRule);
        return $filter->count() < 1 ?  null : $filter->text();
    }

    public function getHtml($link, $xPathRule){
        $crawler = new Crawler(file_get_contents($link));
        $filter = $crawler->filterXPath($xPathRule);
        return $filter->count() < 1 ?  null : $filter->html();
    }

    public function crawlAttr($link, $xPathRule, $attr){
        $crawler = new Crawler(file_get_contents($link));
        $filter = $crawler->filterXPath($xPathRule);
        return $filter->count() < 1 ?  null : $filter->attr($attr);
    }
    public function saveImage($folder, $link,$preView = null){

        echo "[OK] The picture of the race is taken".PHP_EOL;
        $media = null;

        $imageDir = $this->rootDir.'/../web/upload/'.$folder;

        if(!$this->fs->exists($imageDir)){
            $this->fs->mkdir($imageDir );
        }
        $file = explode(".",$link);
        $fileName = md5(microtime());
        $target = $imageDir.'/'.$fileName;

        $client = new GuzzleClient();
        $client->get($link, array('save_to' => $target));

        if (!$this->fs->exists($target)) {
            throw new \Exception('Image of second hand vehicle could not saved to target : ' . $target);
        }

        $file = new File($target);
        $mime = $file->getMimeType();
        //Add extension to the file
        $extension = 'png';
        if (is_null($file->getExtension())) {
            $arr = explode('.', $fileName);
            if (count($arr) > 0) {
                $extension = $arr[count($arr) - 1];
            } else {
                $arr = explode('/', $file->getMimeType());
                if (count($arr) > 0) {
                    $extension = $arr[1];
                }
            }
        }
        $source = $target;
        $target .= '.' . $extension;
        $this->fs->rename($source, $target);
        $fileName .= '.'.$extension;
        //$this->createThumbs($target,$fileName);

        $media = array("url"=>$folder.'/'.$fileName, "type"=>"i", "mime"=>$mime);

        return $media;

    }
}