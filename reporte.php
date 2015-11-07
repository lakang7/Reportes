<?php 
     require_once('recursos/tcpdf/tcpdf.php');
     require_once("funciones/funciones.php");
     $con = Conexion();
     $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
     
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nicola Asuni');
    $pdf->SetTitle('TCPDF Example 012');
    $pdf->SetSubject('TCPDF Tutorial');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

    // disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->AddPage();

    $style1 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(50, 128, 100));    
    $pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));      
    $pdf->Circle(105,135,55);  
        
    for($i=0;$i<50;$i++){
        $pdf->StartTransform();
        $pdf->Rotate(($i*7.2), 105, 135);
        $pdf->Polygon(array(156.715,129.565,157, 135,163, 135,162.682,128.937), 'F', array($style1), array(208, 0, 10));
        $pdf->StopTransform();        
    }
    
    /*for($i=0;$i<50;$i++){
        $pdf->StartTransform();
        $pdf->Rotate(($i*7.2)-2, 105,135);
        $pdf->Text(105,135, '                                                                          Deuda total');
        $pdf->StopTransform();        
    }*/
    

    
    $pdf->StartTransform();
    $pdf->Rotate(-86, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos (a)', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();    
    
    $pdf->StartTransform();
    $pdf->Rotate(-79, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Razón de Circulante', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(-72, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Razón de Efectivo', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(-65, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(-58, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(-51, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(-43, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(-36, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(-29, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(-22, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(-15, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(-7, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(0, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();    
    
    $pdf->StartTransform();
    $pdf->Rotate(7, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();      
    
    $pdf->StartTransform();
    $pdf->Rotate(14, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(22, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(29, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(36, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(43, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(50, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(58, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(65, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(72, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(79, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(86, 105,135);
    $pdf->SetX(0);
    $pdf->SetY(135.5);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'R', 0, '', 0);
    $pdf->StopTransform();    
    
    $pdf->StartTransform();
    $pdf->Rotate(0, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(7, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(15, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Rotación de Cobros', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(22, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(29, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(36, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(43, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(51, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(58, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();    
    
    $pdf->StartTransform();
    $pdf->Rotate(65, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(72, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(79, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();    
    
    $pdf->StartTransform();
    $pdf->Rotate(87, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(-7, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(-14, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(-22, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(-29, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(-36, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(-43, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(-50, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(-57, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();  
    
    $pdf->StartTransform();
    $pdf->Rotate(-64, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();    
    
    $pdf->StartTransform();
    $pdf->Rotate(-71, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform(); 
    
    $pdf->StartTransform();
    $pdf->Rotate(-78, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();
    
    $pdf->StartTransform();
    $pdf->Rotate(-86, 105,135);    
    $pdf->SetXY(163, 130);
    $pdf->Cell(32, 5, 'Capital de trabajo a activos', 0, 1, 'L', 0, '', 0);    
    $pdf->StopTransform();    
    
    
/*                                             
                   
    /*$pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica', 'B', 8);
    for($i=0;$i<50;$i++){
        $pdf->StartTransform();
        $pdf->Rotate(($i*7.2)-2, 105,135);
        $pdf->Text(105,135, '                                                                    X');
        $pdf->StopTransform();        
    }   */  
    
    
    $pdf->Output('example_012.pdf', 'I');    
     
?>