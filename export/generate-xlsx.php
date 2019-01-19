<?php
session_start();
/*
    Credit: 
    https://github.com/PHPOffice/PhpSpreadsheet
*/

include("../inc/inc.path.php");
require_once($path."class/class.db.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");
// CREATE PHPSPREADSHEET OBJECT
require($path."vendor/autoload.php");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_GET['export'])){
        $vpd = new VISUALDB;
        $vail = new VALIDATE;
        $sku = $_GET['sku'];
        $type = $_GET['export'];
        $sku = $vail->sanitizeString($sku);
        $type = $vail->sanitizeString($type);
        $today = date("Y-m-d");
        

        // CREATE A NEW SPREADSHEET + POPULATE DATA
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getProperties()
        ->setCreator("VisualPartsDB.com")
        ->setLastModifiedBy("VisualPartsDB.com")
        ->setTitle("VisualPartsDB.com ".$sku)
        ->setSubject("VisualPartsDB.com ".$sku)
        ->setDescription(
            "This document was auto generated by VisualPartsDB.com"
        )
        ->setKeywords("skus parts dims weights")
        ->setCategory("parts database");
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // set the footer, header, font size, bold, 
        // https://phpspreadsheet.readthedocs.io/en/develop/topics/recipes/
        $spreadsheet->getActiveSheet()->getHeaderFooter()
            ->setOddHeader('&C&H&B&25'.$sku);
        $spreadsheet->getActiveSheet()->getHeaderFooter()
            ->setOddFooter('&L&B' . $spreadsheet->getProperties()->getTitle() . '&RPage &P of &N');
        // instead of writing a ton of code for each letter lets make a loop to take care of it
        $letter = 'A';
        for ($x = 0; $x <= 26; $x++) {
            $spreadsheet->getActiveSheet()->getColumnDimension($letter)->setAutoSize(true);
            $letter++;
        }
        $sheet->setTitle('VisualPartsDB-'.$sku);
        try 
        {
            $database = new Database();
            $db = $database->dbConnection();
            $conn = $db;
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Sets the error mode
            $stmt = $conn->prepare("SELECT * FROM sku WHERE sku_id = :sku_id");
            $stmt->bindparam(":sku_id", $sku);
            $stmt->execute();
            $user = new USER;
            $i = 2;
            if($user->accessCheck() == 'ADMIN')
            {
                // SET HEADERS
                $sheet->setCellValue('A1', "Part Number");
                $sheet->setCellValue('B1', "Description");
                $sheet->setCellValue('C1', "Unit Length");
                $sheet->setCellValue('D1', "Unit Width");
                $sheet->setCellValue('E1', "Unit Height");
                $sheet->setCellValue('F1', "Unit Weight");
                $sheet->setCellValue('G1', "Case Qty");
                $sheet->setCellValue('H1', "Case Length");
                $sheet->setCellValue('I1', "Case Width");
                $sheet->setCellValue('J1', "Case Height");
                $sheet->setCellValue('K1', "Case Weight");
                $sheet->setCellValue('L1', "Pallet Qty");
                $sheet->setCellValue('M1', "Pallet Length");
                $sheet->setCellValue('N1', "Pallet Width");
                $sheet->setCellValue('O1', "Pallet Height");
                $sheet->setCellValue('P1', "Pallet Weight");
                $sheet->setCellValue('Q1', "Date Created");
                $sheet->setCellValue('R1', "Created By");
                $sheet->setCellValue('S1', "Date Updated");
                $sheet->setCellValue('T1', "Updated By");
                while ($row = $stmt->fetch(PDO::FETCH_NAMED)) {
                    $sheet->setCellValue('A'.$i, $row['sku_id']);
                    $sheet->setCellValue('B'.$i, $row['sku_desc']);
                    $sheet->setCellValue('C'.$i, $row['sku_sig_length']);
                    $sheet->setCellValue('D'.$i, $row['sku_sig_width']);
                    $sheet->setCellValue('E'.$i, $row['sku_sig_height']);
                    $sheet->setCellValue('F'.$i, $row['sku_sig_weight']);
                    $sheet->setCellValue('G'.$i, $row['sku_case_qty']);
                    $sheet->setCellValue('H'.$i, $row['sku_case_length']);
                    $sheet->setCellValue('I'.$i, $row['sku_case_width']);
                    $sheet->setCellValue('J'.$i, $row['sku_case_height']);
                    $sheet->setCellValue('K'.$i, $row['sku_case_weight']);
                    $sheet->setCellValue('L'.$i, $row['sku_pallet_qty']);
                    $sheet->setCellValue('M'.$i, $row['sku_pallet_length']);
                    $sheet->setCellValue('N'.$i, $row['sku_pallet_width']);
                    $sheet->setCellValue('O'.$i, $row['sku_pallet_height']);
                    $sheet->setCellValue('P'.$i, $row['sku_pallet_weight']);
                    $sheet->setCellValue('Q'.$i, $row['sku_rec_date']);
                    $sheet->setCellValue('R'.$i, $row['sku_rec_added']);
                    $sheet->setCellValue('S'.$i, $row['sku_rec_update']);
                    $sheet->setCellValue('T'.$i, $row['sku_rec_update_by']);
                    $i++;
                }
                
            } elseif($user->accessCheck() == 'USER')
            {
                // SET HEADERS
                $sheet->setCellValue('A1', "Part Number");
                $sheet->setCellValue('B1', "Description");
                $sheet->setCellValue('C1', "Unit Length");
                $sheet->setCellValue('D1', "Unit Width");
                $sheet->setCellValue('E1', "Unit Height");
                $sheet->setCellValue('F1', "Unit Weight");
                $sheet->setCellValue('G1', "Case Qty");
                $sheet->setCellValue('H1', "Case Length");
                $sheet->setCellValue('I1', "Case Width");
                $sheet->setCellValue('J1', "Case Height");
                $sheet->setCellValue('K1', "Case Weight");
                $sheet->setCellValue('L1', "Pallet Qty");
                $sheet->setCellValue('M1', "Pallet Length");
                $sheet->setCellValue('N1', "Pallet Width");
                $sheet->setCellValue('O1', "Pallet Height");
                $sheet->setCellValue('P1', "Pallet Weight");
                while ($row = $stmt->fetch(PDO::FETCH_NAMED)) {
                    $sheet->setCellValue('A'.$i, $row['sku_id']);
                    $sheet->setCellValue('B'.$i, $row['sku_desc']);
                    $sheet->setCellValue('C'.$i, $row['sku_sig_length']);
                    $sheet->setCellValue('D'.$i, $row['sku_sig_width']);
                    $sheet->setCellValue('E'.$i, $row['sku_sig_height']);
                    $sheet->setCellValue('F'.$i, $row['sku_sig_weight']);
                    $sheet->setCellValue('G'.$i, $row['sku_case_qty']);
                    $sheet->setCellValue('H'.$i, $row['sku_case_length']);
                    $sheet->setCellValue('I'.$i, $row['sku_case_width']);
                    $sheet->setCellValue('J'.$i, $row['sku_case_height']);
                    $sheet->setCellValue('K'.$i, $row['sku_case_weight']);
                    $sheet->setCellValue('L'.$i, $row['sku_pallet_qty']);
                    $sheet->setCellValue('M'.$i, $row['sku_pallet_length']);
                    $sheet->setCellValue('N'.$i, $row['sku_pallet_width']);
                    $sheet->setCellValue('O'.$i, $row['sku_pallet_height']);
                    $sheet->setCellValue('P'.$i, $row['sku_pallet_weight']);
                    $i++;
                }
            } else 
            {
                // SET HEADERS
                $sheet->setCellValue('A1', "Part Number");
                $sheet->setCellValue('B1', "Description");
                $sheet->setCellValue('C1', "Unit Length");
                $sheet->setCellValue('D1', "Unit Width");
                $sheet->setCellValue('E1', "Unit Height");
                $sheet->setCellValue('F1', "Unit Weight");
                while ($row = $stmt->fetch(PDO::FETCH_NAMED)) {
                    $sheet->setCellValue('A'.$i, $row['sku_id']);
                    $sheet->setCellValue('B'.$i, $row['sku_desc']);
                    $sheet->setCellValue('C'.$i, $row['sku_sig_length']);
                    $sheet->setCellValue('D'.$i, $row['sku_sig_width']);
                    $sheet->setCellValue('E'.$i, $row['sku_sig_height']);
                    $sheet->setCellValue('F'.$i, $row['sku_sig_weight']);
                    $i++;
                }
            }
            
            // OUTPUT
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="visualpartdb-'.$sku.'-'.$today.'.xlsx"');
            header('Cache-Control: max-age=0');
            header('Expires: Fri, 11 Nov 2011 11:11:11 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
            $writer->save('php://output');

        } catch (PDOException $e)
        {
            echo "Connection error: " . $e->getMessage(); // return error message
        }
}

?>