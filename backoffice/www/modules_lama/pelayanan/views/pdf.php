<?php
$pdf=new FPDF('L','mm', array(216, 330));
$pdf->setTopMargin(15);
$pdf->setLeftMargin(12);
$pdf->SetFont('helvetica','B', 13);

$pdf->AddPage();
$pdf->Cell(6);
$pdf->AcceptPageBreak();
$pdf->Cell(200, 12,'Hallo Pengguna CI',1,1,'C');
$pdf->Text(200,300,'sdjfgsdh');
$pdf->Output();

?>
