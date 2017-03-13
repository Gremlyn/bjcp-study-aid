<?php

namespace AppBundle\Service;

use AppBundle\Entity\Category;
use AppBundle\Entity\Subcategory;
use Symfony\Component\Finder\Finder;

class BeerInfo
{
    /**
     * @var Finder
     */
    private $finder;
    /**
     * @var Hydrator
     */
    private $hydrator;
    /**
     * @var array
     */
    private $styles;

    public function __construct(Hydrator $hydrator)
    {
        $this->finder = new Finder();
        $this->hydrator = $hydrator;
    }


    /**
     * @param string $type
     *
     * @return Category[]|bool
     */
    public function getCategories($type = 'beer')
    {
        if(empty($this->styles)) {
            $this->loadCategories();
        }

        switch ($type) {
            case 'beer':
                $index = 0;
                break;
            case 'mead':
                $index = 1;
                break;
            case 'cider':
                $index = 2;
                break;
        }

        if (isset($index)) {
            $styles = [];

            foreach ($this->styles['styleguide']['class'][$index]['category'] as $style) {
                $styles[] = $this->hydrator->hydrate($style, 'category');
            }

            return $styles;
        }

        return FALSE;
    }

    /**
     * @return Category|bool
     */
    public function getRandomCategory()
    {
        $styles = $this->getCategories();

        if ($styles) {
            $key = array_rand($styles);

            return $styles[$key];
        }

        return FALSE;
    }

    /**
     * @return Subcategory|bool
     */
    public function getRandomSubcategory()
    {
        $category = $this->getRandomCategory();

        if ($category) {
            return $category->getRandomSubcategory();
        }

        return FALSE;
    }

    /**
     * @return array|bool
     */
    public function getRandomSubcategoryInfo($exclude = [])
    {
        $subcategory = $this->getRandomSubcategory();

        if ($subcategory) {
            return $subcategory->getRandomInfo($exclude);
        }

        return FALSE;
    }

    private function loadCategories()
    {
        $this->finder->files()->in(__DIR__ . '/../..');
        $json = NULL;

        foreach ($this->finder as $file) {
            if($file->getExtension() == 'json') {
                $this->styles = json_decode(file_get_contents($file->getPathname()), TRUE);
            }
        }
    }
}