<?php

namespace App\Service;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Repository\FormRepository;

/**
 * Class TwigExtension
 * @package App\Service
 */
class TwigExtension extends AbstractExtension
{
    /**
     * @var FormRepository
     */
    private $form;

    /**
     * TwigExtension constructor.
     * @param FormRepository $form
     */
    public function __construct(FormRepository $form)
    {
        $this->form = $form;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('mediaUrls', [$this, 'findMediaUrls']),
        ];
    }

    /**
     * @return array|null
     */
    public function findMediaUrls()
    {
        return $this->form->getMediaUrls();
    }
}

