<?php

namespace App\Controller;

use App\Repository\ConseilRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use DateTimeInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController
{
    private $repo;

    public function __construct(ConseilRepository $repo)
    {
        $this->repo = $repo;
    }

    public function generateConseilsExcel(): string
    {
        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

       // Add headers
       $sheet->setCellValue('A1', 'ID');
       $sheet->setCellValue('B1', 'NOM CONSEIL');
       $sheet->setCellValue('C1', 'VIDEO'); // Add column for materials
       $sheet->setCellValue('D1', 'DESCRIPTION'); // Add column for height
       $sheet->setCellValue('E1', 'DATE'); // Add column for width
       $sheet->setCellValue('F1', 'CATEGORY'); // Add column for type
       $sheet->setCellValue('G1', 'PRODUIT'); // Add column for city

   
    // Add more headers for other artwork details

    // Fetch artworks from repository
    $conseils = $this->repo->findAll();

    // Populate data
    $row = 2; // Start from row 2 (assuming row 1 is for headers)
    foreach ($conseils as $conseil) {
        // Populate each cell with conseil data
        $sheet->setCellValue('A'.$row, $conseil->getIdConseil());
        $sheet->setCellValue('B'.$row, $conseil->getNomConseil());
        $sheet->setCellValue('C'.$row, $conseil->getVideo());
        $sheet->setCellValue('D'.$row, $conseil->getDescription());
        
        // Ensure date is formatted correctly
        $dateFormatted = '';
        $date = $conseil->getDatecreation();
        if ($date instanceof DateTimeInterface) {
            $dateFormatted = $date->format('Y-m-d H:i:s');
        }
        $sheet->setCellValue('E'.$row, $dateFormatted);
        
        // Ensure these values are valid for string conversion
        $typeConseil = $conseil->getIdTypec();
        $produit = $conseil->getIdProduit();
        
        // Example: Access a property or method from Typeconseil object
        $typeConseilName = $typeConseil->getNomtypec(); // Assuming 'name' is a property of Typeconseil
        $produitName = $produit->getNomProduit(); // Assuming 'name' is a property of Produit
        
        // Set cell values with appropriate string representations
        $sheet->setCellValue('F'.$row, $typeConseilName);
        $sheet->setCellValue('G'.$row, $produitName);
    
        // Move to the next row
        $row++;
    }
    

    

        // Save Excel file
        $filename = 'conseils' . date('Ymd_His') . '.xlsx';
        $excelDirectory = __DIR__ . '/../../public/excel';
        if (!is_dir($excelDirectory)) {
            mkdir($excelDirectory, 0777, true);
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save($excelDirectory . '/' . $filename);

        return $filename;
    }
}
