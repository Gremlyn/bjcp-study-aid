<?php

namespace AppBundle\Entity;

class Subcategory
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $impression;

    /**
     * @var string
     */
    protected $aroma;

    /**
     * @var string
     */
    protected $appearance;

    /**
     * @var string
     */
    protected $flavor;

    /**
     * @var string
     */
    protected $mouthfeel;

    /**
     * @var string
     */
    protected $comments;

    /**
     * @var string
     */
    protected $history;

    /**
     * @var string
     */
    protected $ingredients;

    /**
     * @var string
     */
    protected $comparison;

    /**
     * @var array
     */
    protected $examples;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @var array
     */
    protected $stats;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Subcategory
     */
    public function setId($id)
    {
        if (strpos($id,'-') !== FALSE) {
            $explode = explode('-',$id);
            $id = $explode[0] . ' (' . $explode[1] .')';
        }

        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Subcategory
     */
    public function setName($name)
    {
        if (strpos($name,': ') !== FALSE) {
            $explode = explode(': ',$name);
            $name = $explode[1];
        }

        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getImpression()
    {
        return $this->impression;
    }

    /**
     * @param string $impression
     *
     * @return Subcategory
     */
    public function setImpression($impression)
    {
        $this->impression = $impression;

        return $this;
    }

    /**
     * @return string
     */
    public function getAroma()
    {
        return $this->aroma;
    }

    /**
     * @param string $aroma
     *
     * @return Subcategory
     */
    public function setAroma($aroma)
    {
        $this->aroma = $aroma;

        return $this;
    }

    /**
     * @return string
     */
    public function getAppearance()
    {
        return $this->appearance;
    }

    /**
     * @param string $appearance
     *
     * @return Subcategory
     */
    public function setAppearance($appearance)
    {
        $this->appearance = $appearance;

        return $this;
    }

    /**
     * @return string
     */
    public function getFlavor()
    {
        return $this->flavor;
    }

    /**
     * @param string $flavor
     *
     * @return Subcategory
     */
    public function setFlavor($flavor)
    {
        $this->flavor = $flavor;

        return $this;
    }

    /**
     * @return string
     */
    public function getMouthfeel()
    {
        return $this->mouthfeel;
    }

    /**
     * @param string $mouthfeel
     *
     * @return Subcategory
     */
    public function setMouthfeel($mouthfeel)
    {
        $this->mouthfeel = $mouthfeel;

        return $this;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     *
     * @return Subcategory
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return string
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @param string $history
     *
     * @return Subcategory
     */
    public function setHistory($history)
    {
        $this->history = $history;

        return $this;
    }

    /**
     * @return string
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * @param string $ingredients
     *
     * @return Subcategory
     */
    public function setIngredients($ingredients)
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    /**
     * @return string
     */
    public function getComparison()
    {
        return $this->comparison;
    }

    /**
     * @param string $comparison
     *
     * @return Subcategory
     */
    public function setComparison($comparison)
    {
        $this->comparison = $comparison;

        return $this;
    }

    /**
     * @return array
     */
    public function getExamples()
    {
        return $this->examples;
    }

    /**
     * @param array $examples
     *
     * @return Subcategory
     */
    public function setExamples($examples)
    {
        $this->examples = $examples;

        return $this;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     *
     * @return Subcategory
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @param array $stats
     *
     * @return Subcategory
     */
    public function setStats($stats)
    {
        $this->stats = $stats;

        return $this;
    }

    public function getRandomInfo(array $exclude = [])
    {
        $properties = (array)$this;

        $key = array_rand($properties);

        if (empty($key)) {
            return FALSE;
        }

        $key = preg_replace('/[^a-z0-9 -]+/', '', $key);

        if (in_array($key, $exclude)) {
            return $this->getRandomInfo($exclude);
        } else {
            $method = 'get' . ucwords($key);

            return [
                'id'       => $this->getId(),
                'name'     => $this->getName(),
                'property' => $key,
                'info'     => $this->$method()
            ];
        }
    }
}