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
    /**
     * @var Category[]
     */
    private $categories;

    public function __construct(Hydrator $hydrator)
    {
        $this->finder   = new Finder();
        $this->hydrator = $hydrator;
    }


    /**
     * @param string $type
     *
     * @return Category[]|array
     */
    public function getCategories($type = 'beer')
    {
        if (empty($this->categories)) {
            if (empty($this->styles)) {
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
                $this->categories = [];

                foreach ($this->styles['styleguide']['class'][$index]['category'] as $style) {
                    $this->categories[] = $this->hydrator->hydrate($style, 'category');
                }

                return $this->categories;
            }
        } else {
            return $this->categories;
        }
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

    public function getSubcategoryByLocator($locator)
    {
        if (strpos($locator, 'cs') !== FALSE) {
            $explode = explode('cs', $locator);

            $categories    = $this->getCategories();
            $category      = $categories[$explode[0]];
            $subcategories = $category->getSubcategories();

            if (array_key_exists($explode[1] + 1, $subcategories)) {
                $next = $explode[0] . 'cs' . ($explode[1] + 1);
            } elseif (array_key_exists($explode[0] + 1, $categories)) {
                $next = ($explode[0] + 1) . 'cs' . 0;
            } else {
                $next = NULL;
            }

            if (array_key_exists($explode[1] - 1, $subcategories)) {
                $prev = $explode[0] . 'cs' . ($explode[1] - 1);
            } elseif (array_key_exists($explode[0] - 1, $categories)) {
                $prev_category      = $categories[$explode[0] - 1];
                $prev_subcategories = $prev_category->getSubcategories();
                end($prev_subcategories);

                $prev = ($explode[0] - 1) . 'cs' . key($prev_subcategories);
            } else {
                $prev = NULL;
            }

            return $subcategories[$explode[1]];
        }

        return FALSE;
    }

    public function getSubcategoryInfo(Subcategory $subcategory)
    {
        $categories = $this->getCategories();
        $parent = $this->findSubcategoryParent($subcategory);
        $subcategories = $parent[1]->getSubcategories();

        $subcategory_index = NULL;

        foreach ($subcategories as $index => $potential) {
            if ($subcategory === $potential) {
                $subcategory_index = $index;
            }
        }

        if (array_key_exists($subcategory_index + 1, $subcategories)) {
            $next = $parent[0] . 'cs' . ($subcategory_index + 1);
        } elseif (array_key_exists($parent[0] + 1, $categories)) {
            $next = ($parent[0] + 1) . 'cs' . 0;
        } else {
            $next = NULL;
        }

        if (array_key_exists($subcategory_index - 1, $subcategories)) {
            $prev = $parent[0] . 'cs' . ($subcategory_index - 1);
        } elseif (array_key_exists($parent[0] - 1, $categories)) {
            $prev_category      = $categories[$parent[0] - 1];
            $prev_subcategories = $prev_category->getSubcategories();
            end($prev_subcategories);

            $prev = ($parent[0] - 1) . 'cs' . key($prev_subcategories);
        } else {
            $prev = NULL;
        }

        return [
            'next'        => $next,
            'prev'        => $prev,
            'subcategory' => $subcategories[$subcategory_index]
        ];
    }

    /**
     * @param Subcategory $subcategory
     *
     * @return array|bool
     */
    public function findSubcategoryParent(Subcategory $subcategory)
    {
        $categories = $this->getCategories();

        foreach ($categories as $index => $category) {
            foreach ($category->getSubcategories() as $potential) {
                if ($subcategory === $potential) {
                    return [
                        $index,
                        $category
                    ];
                }
            }
        }

        return FALSE;
    }

    private function loadCategories()
    {
        $this->finder->files()->in(__DIR__ . '/../..');
        $json = NULL;

        foreach ($this->finder as $file) {
            if ($file->getExtension() == 'json') {
                $this->styles = json_decode(file_get_contents($file->getPathname()), TRUE);
            }
        }
    }
}