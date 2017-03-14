<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('@App/index.html.twig', []);
    }

    public function quizAction(Request $request)
    {
        $beer_info = $this->get('beer.info');

        $info = $beer_info->getRandomSubcategoryInfo(['id', 'tags']);

        return $this->render('@App/quiz.html.twig', [
            'info' => $info
        ]);
    }

    public function studyAction(Request $request)
    {
        $beer_info = $this->get('beer.info');

        $locator = $request->query->get('l');

        if ($locator) {
            $subcategory = $beer_info->getSubcategoryByLocator($locator);
        } else {
            $subcategory = $beer_info->getRandomSubcategory();
        }

        $subcategory_info = $beer_info->getSubcategoryInfo($subcategory);

        return $this->render('@App/study.html.twig', [
            'subcategory' => $subcategory_info
        ]);
    }
}
