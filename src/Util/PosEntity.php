<?php

namespace App\Util;

use Exception;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class PosEntity
 * @package App\Utils
 */
class PosEntity extends DBEntity
{
    const NUMBER_FORM_ATTRIBUTES = 14;

    /**
     * @var RouterInterface
     */
    private $urlGen;

    /**
     * PosEntity constructor.
     * @param RouterInterface $urlGen
     */
    public function __construct(RouterInterface $urlGen)
    {
        $host = $urlGen->getContext()->getHost();
        if ($host !== 'localhost') {
            $urlGen->getContext()->setScheme('https');
        }
        $this->urlGen = $urlGen;
    }

    /**
     * @param array $row
     * @return array
     */
    public function urlMapper(array &$row): array
    {
        if (array_key_exists('url', $row)) {
            $row['url'] = $this->generateUrl('formMedia', [
                'id' => $row['url'],
                'filename' => $row['filename']
            ]);
        }

        if (array_key_exists('marketMedia', $row)) {
            if (empty($row['image'])) {
                $scheme = $this->urlGen->getContext()->getScheme();
                $port = $this->urlGen->getContext()->getHttpPort();
                if ($scheme == 'https') {
                    $port = $this->urlGen->getContext()->getHttpsPort();
                }
                $host = $this->urlGen->getContext()->getHost();
                $link = $scheme . '://' .$host . ':' . $port;

                $isMediaMarkt = strpos($row['name'], ' MM') !== false;
                $isSaturn = strpos($row['name'], ' SAT') !== false;
                if ($isMediaMarkt) {
                    $url = $link.'/assets/mediamarkt.jpg';
                } elseif ($isSaturn) {
                    $url = $link.'/assets/saturn.jpg';
                } else {
                    $url = null;
                }
            } else {
                $attrs = ['id' => $row['marketMedia'], 'filename' => $row['image']];
                $url = $this->generateUrl('marketMedia', $attrs);
            }
            $row['image'] = $url;
        }
        $this->unsetAttributes($row, ['marketMedia', 'filename']);

        return $row;
    }

    /**
     * @param string $routeName
     * @param array $routeParams
     * @return string|null
     */
    public function generateUrl(string $routeName, array $routeParams): ?string
    {
        try {
            return $this->urlGen->generate($routeName, $routeParams, RouterInterface::ABSOLUTE_URL);
        } catch (Exception $e) {
            //Catch errors that occur in unit tests like
            // Unable to generate a URL for the named route "marketMedia" as such route does not exist.
            return '';
        }
    }
}

