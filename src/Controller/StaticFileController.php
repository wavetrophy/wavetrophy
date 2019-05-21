<?php

namespace App\Controller;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class StaticFileController
 */
class StaticFileController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @Route(name="docs", path="/docs{file}", methods={"GET"}, defaults={"file"=null})
     *
     * @param $file
     *
     * @return BinaryFileResponse
     */
    public function docs($file = null)
    {
        try {
            if (strpos($file, '/') !== 0) {
                $file = '/' . $file;
            }
            $file = str_replace('..', '__', $this->params->get('kernel.project_dir')  . '/public/docs' . $file);
            if (!is_file($file)) {
                $file = $file . 'index.html';
                if (!is_file($file)) {
                    throw new Exception('Not found');
                }
            }
            $response = new BinaryFileResponse($file);
            return $response;
        } catch (Exception $exception) {
            throw new NotFoundHttpException();
        }
    }
}
