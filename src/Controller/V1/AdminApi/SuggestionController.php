<?php

namespace App\Controller\V1\AdminApi;

use App\Service\CSV;
use App\Repository\SuggestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use CRUDlex\Service;

class SuggestionController extends AbstractController
{
    /**
     * @Route("/adminapi/v1/csv/sugestion", name="csvSuggestions", methods="GET")
     *
     * @param SuggestionRepository $suggestion
     * @param CSV $csv
     * @return StreamedResponse
     */
    public function exportSuggestion(SuggestionRepository $suggestion, CSV $csv)
    {
        $filename = 'universal-pos-suggestions.csv';
        $headers = ['type', 'text'];
        $data = $suggestion->getSuggestionCsvData();
        $response = new StreamedResponse();
        $response->setCallback(function() use ($csv, $headers, $data) {
            $csv->createCsv($headers, $data);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);

        return $response;
    }

    /**
     * @Route("/adminapi/v1/sugestion", name="importSuggestions", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Service $service
     * @param CSV $csv
     * @param SuggestionRepository $suggestion
     * @return RedirectResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function importSuggestion(
        Request $request,
        Service $service,
        CSV $csv,
        SuggestionRepository $suggestion
    )
    {
        if ($request->isMethod('post')) {
            $file = $request->files->get('file');
            if ($file !== null) {
                $content = $csv->readCsv($file);
                if ($content === null) {
                    $this->addFlash('danger', 'Invalid CSV file');
                } else {
                    $reset = $request->request->get('reset') === 'true';
                    $suggestion->insertSuggestion($content, $reset);
                }
            }

            return new RedirectResponse(
                $this->generateUrl('crudList', ['entity' => 'suggestion'])
            );
        }

        return $this->render('csvImport.twig', [
            'crudEntity' => 'suggestion',
            'crud' => $service,
        ]);
    }
}

