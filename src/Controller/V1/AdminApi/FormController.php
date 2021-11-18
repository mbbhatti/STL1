<?php

namespace App\Controller\V1\AdminApi;

use App\Service\CSV;
use App\Repository\FormRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    /**
     * @Route("/adminapi/v1/csv/form", name="csvForms", methods="GET")
     *
     * @param FormRepository $form
     * @param CSV $csv
     * @return StreamedResponse
     */
    public function getFormCsvFile(FormRepository $form, CSV $csv)
    {
        $filename = 'universal-pos-forms.csv';
        $headers = $form->getFormsCsvHeader();
        $data = $form->getFormsCsvData();
        $response = new StreamedResponse();
        $response->setCallback(function() use ($csv, $headers, $data) {
            $csv->createCsv($headers, $data);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);

        return $response;
    }
}

