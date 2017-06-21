<?php

namespace AppBundle\Command;

use AppBundle\Service\BeerInfo;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildSitemapCommand extends ContainerAwareCommand
{
    private $beerInfo;

    public function __construct(BeerInfo $beerInfo)
    {
        $this->beerInfo = $beerInfo;

        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('app:build:sitemap');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $echo = function ($param) {
            return $param;
        };

        $now  = new \DateTime('now');
        $url = 'https://bjcp.thegremlyn.com/study';

        $categories = $this->beerInfo->getCategories();

        if (count($categories) > 0) {
            $intro = <<<INTRO
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
INTRO;

            $outro   = <<<OUTRO
</urlset>
OUTRO;
            $sitemap = $intro;
            $sitemap .= "\n";

            $sitemap .= <<<ENTRY
    <url>
        <loc>https://bjcp.thegremlyn.com</loc>
        <lastmod>{$echo($now->format('Y-m-d'))}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
        <url>
        <loc>https://bjcp.thegremlyn.com/quiz</loc>
        <lastmod>{$echo($now->format('Y-m-d'))}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{$url}</loc>
        <lastmod>{$echo($now->format('Y-m-d'))}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
ENTRY;
            $sitemap .= "\n";

            foreach ($categories as $cindex => $category) {
                foreach ($category->getSubcategories() as $sindex => $subcategory) {
                    $sitemap .= <<<ENTRY
    <url>
        <loc>{$url}?l={$cindex}cs{$sindex}</loc>
        <lastmod>{$echo($now->format('Y-m-d'))}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
ENTRY;
                    $sitemap .= "\n";
                }
            }

            $sitemap .= $outro;

            $path = $this->getContainer()->get('kernel')->getRootDir() . '/../web/';
            $output->writeln($path);
            file_put_contents($path . 'sitemap.xml', $sitemap);
        }
    }
}