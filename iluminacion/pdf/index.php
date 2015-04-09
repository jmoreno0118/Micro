<?php
 /********** Norma 001 **********/
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';

/**************************************************************************************************/
/* Modificaciones al FPDF */
/**************************************************************************************************/

    //include ('fpdf/fpdf.php');
    include ($_SERVER['DOCUMENT_ROOT'].'/reportes/includes/fpdf/fpdf.php');

    class PDF extends FPDF
    {
        function Header()
        {
            $this->Image("../../img/logolaboratorio3.gif", 20, 15, 165, 33);
        }

        function Footer()
        {
            $this->SetY(-40);

            $this->SetTextColor(125);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(0, 3, utf8_decode('El presente informe no podrá ser alterado ni reproducido total o parcialmente sin autorización previa por escrito del Laboratorio del Grupo Microanálisis, S.A. de C.V.')); //////////// Dirección
            $this->Ln();

            $this->SetTextColor(0);
            $this->SetFont('Arial', '', 7);
            $this->Cell(0, 3, utf8_decode('GENERAL SOSTENES ROCHA 28, MAGDALENA MIXHUCA, MÉXICO D.F. 15850'), 0, 1, 'C');
            $this->Ln(1);
            $this->Cell(0, 3, utf8_decode('Tel: +52 (55)5768-7744, Fax: +52 (55)5764-0295'), 0, 1, 'C');
            $this->Ln(1);
            $this->Cell(0, 3, utf8_decode('E-Mail: ventas@microanalisis.com Web: www.microanalisis.com'), 0, 1, 'C');
        }

        var $widths;
        var $aligns;
        var $fonts;
        var $fontsizes;

        function SetWidths($w)
        {
            //Set the array of column widths
            $this->widths=$w;
        }

        function SetAligns($a)
        {
            //Set the array of column alignments
            $this->aligns=$a;
        }

        function SetFonts($f)
        {
            //Set the array of fonts
            $this->nfonts=$f;
        }

        function SetFontSizes($fs)
        {
            //Set the array of font sizes
            $this->nfontsize=$fs;
        }

        function Row($data)
        {
            //Calculate the height of the row
            $nb=0;
            $sh=array();
            $this->SetFont('Arial', '', 9);
            for($i=0;$i<count($data);$i++){
                $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));

                //Se guarda la altura de cada texto
                $sh[]=$this->NbLines($this->widths[$i],$data[$i]);
            }
            $h=5*$nb;
            //Issue a page break first if needed
            $this->CheckPageBreak($h);
            //Draw the cells of the row
            for($i=0;$i<count($data);$i++)
            {
                $w=$this->widths[$i];
                $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                //Save the current position
                $x=$this->GetX();
                $y=$this->GetY();
                //Draw the border
                $this->Rect($x,$y,$w,$h);

                //Número de renglones de separación arriba y abajo, se resta la altura
                //total menos la altura del texto, se divide entre dos (obtener altura de
                //arriba y de abajo) y esto entre 5 para obtener el número de renglones
                //según la altura del renglón, y así anexar dichos renglones extra al texto
                $nr = (($h-($sh[$i]*5))/2)/5;
                for ($j=0; $j < $nr; $j++){ 
                    $data[$i]="\n".$data[$i]."\n";
                }
                if(count($this->nfonts) > 0 AND count($this->nfontsize) > 0){
                    $b=(count($this->nfonts) === 1) ? $this->nfonts[0] : $this->nfonts[$i];
                    $c=(count($this->nfontsize) === 1) ? $this->nfontsize[0] : $this->nfontsize[$i];
                    $this->SetFont('Arial', $b, $c);
                }

                //Print the text
                $this->MultiCell($w,5,$data[$i],0,$a);
                //Put the position to the right of the cell
                $this->SetXY($x+$w,$y);
            }
            //Go to the next line
            $this->Ln($h);
        }

        function carobsRow($data)
        {
            //Calculate the height of the row
            $nb=0;
            $sh=array();
            $sh[]=$this->NbLines($this->widths[0],$data[0]);
            $this->SetFont('Arial', $this->nfonts[1], $this->nfontsize[1]);
            for($i=0;$i<count($data[1]);$i++){
                 //Se guarda la altura de cada texto
                $sh[]=$this->NbLines($this->widths[1],$data[1][$i]);
            }
            $h=5*($sh[1]+$sh[2]);
            //Issue a page break first if needed
            $this->CheckPageBreak($h);

            //Draw the cells of the row
            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x,$y,$this->widths[0],$h);
            $nr=(($h-($sh[0]*5))/2)/5;

            $this->SetFont('Arial', $this->nfonts[0], $this->nfontsize[0]);
            for ($j=0; $j < number_format($nr , 0); $j++){ 
                $data[0]="\n".$data[0];
            }
            
            $this->MultiCell($this->widths[0],5,$data[0],0,'C');
            $this->SetXY($x+$this->widths[0],$y);

            $this->SetFont('Arial', $this->nfonts[1], $this->nfontsize[1]);
            for($i=0;$i<count($data[1]);$i++)
            {
                //Save the current position
                $x=$this->GetX();
                $y=$this->GetY();
                //Draw the border
                $this->Rect($x,$y,$this->widths[1],$sh[$i+1]*5);

                //Número de renglones de separación arriba y abajo, se resta la altura
                //total menos la altura del texto, se divide entre dos (obtener altura de
                //arriba y de abajo) y esto entre 5 para obtener el número de renglones
                //según la altura del renglón, y así anexar dichos renglones extra al texto
                $nr=((($sh[0]/2)-($sh[$i+1]*5))/2)/5;
                for ($j=0; $j < number_format($nr , 0); $j++){ 
                    $data[1][$i]="\n".$data[1][$i]."\n";
                }
                    
                //Print the text
                $this->MultiCell($this->widths[1],5,$data[1][$i],0, 'J');
                //Put the position to the right of the cell
                if($i === 0)
                    $this->SetXY($x,$y+$sh[$i+1]*5);
            }
        }

        function CheckPageBreak($h)
        {
            //If the height h would cause an overflow, add a new page immediately
            if($this->GetY()+$h>$this->PageBreakTrigger)
                $this->AddPage($this->CurOrientation);
        }

        function NbLines($w,$txt)
        {
            //Computes the number of lines a MultiCell of width w will take
            $cw=&$this->CurrentFont['cw'];
            if($w==0)
                $w=$this->w-$this->rMargin-$this->x;
            $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
            $s=str_replace("\r",'',$txt);
            $nb=strlen($s);
            if($nb>0 and $s[$nb-1]=="\n")
                $nb--;
            $sep=-1;
            $i=0;
            $j=0;
            $l=0;
            $nl=1;
            while($i<$nb)
            {
                $c=$s[$i];
                if($c=="\n")
                {
                    $i++;
                    $sep=-1;
                    $j=$i;
                    $l=0;
                    $nl++;
                    continue;
                }
                if($c==' ')
                    $sep=$i;
                $l+=$cw[$c];
                if($l>$wmax)
                {
                    if($sep==-1)
                    {
                        if($i==$j)
                            $i++;
                    }
                    else
                        $i=$sep+1;
                    $sep=-1;
                    $j=$i;
                    $l=0;
                    $nl++;
                }
                else
                    $i++;
            }
            return $nl;
        }
    }

    $pdf = new PDF();


/**************************************************************************************************/
/********************************************* Hoja 0 *********************************************/
/**************************************************************************************************/
        $pdf->AddPage();
        $pdf->SetMargins(20, 0, 25);
        $pdf->SetLineWidth(.1);

        $pdf->Ln(43);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 3, utf8_decode('AIR-F-2'), 0, 1, 'R');
        $pdf->Ln(2);
        $pdf->Cell(0, 3, utf8_decode('HOJA DE RECONOCIMIENTO INICIAL'), 0, 1, 'C');
        $pdf->Ln(2);
        $pdf->Cell(0, 3, utf8_decode('NOM-025-STPS-2008'), 0, 1, 'C');
        $pdf->Ln(2);

        $pdf->SetFont('Arial', 'B', 9);
        azul($pdf);
        $pdf->Cell(0, 5, utf8_decode('Datos Generales'), 0, 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);
        gris($pdf, '');
        $pdf->Cell(25, 5, utf8_decode('Fecha'), 1, 0, 'C', true);

        blanco($pdf);
        $pdf->Cell(30, 5, utf8_decode('2013-02-20'), 1, 0, 'C', true);

        gris($pdf, '');
        $pdf->Cell(20, 5, utf8_decode('OT No.'), 1, 0, 'C', true);

        blanco($pdf);
        $pdf->Cell(20, 5, utf8_decode('092'), 1, 0, 'C', true);

        gris($pdf, '');
        $pdf->Cell(20, 5, utf8_decode('Hoja'), 1, 0, 'C', true);

        blanco($pdf);
        $pdf->Cell(15, 5, utf8_decode('3'), 1, 0, 'C', true);

        gris($pdf, '');
        $pdf->Cell(20, 5, utf8_decode('De'), 1, 0, 'C', true);

        blanco($pdf);
        $pdf->Cell(15, 5, utf8_decode('3'), 1, 1, 'C', true);

        gris($pdf, '');
        $pdf->Cell(43, 6, utf8_decode('Compañía'), 1, 0, 'L', true);

        blanco($pdf);
        $pdf->Cell(0, 6, utf8_decode('BARNICES Y RESINAS S.A. DE C.V.'), 1, 1, 'L', true);

        gris($pdf, '');
        $pdf->Cell(43, 6, utf8_decode('Planta'), 1, 0, 'L', true);

        blanco($pdf);
        $pdf->Cell(39.5, 6, utf8_decode('ECATEPEC'), 1, 0, 'L', true);

        gris($pdf, '');
        $pdf->Cell(43, 6, utf8_decode('Lugar'), 1, 0, 'L', true);

        blanco($pdf);
        $pdf->Cell(39.5, 6, utf8_decode('Ecatepec, Edo. de México'), 1, 1, 'L', true);

        gris($pdf, '');
        $pdf->Cell(43, 6, utf8_decode('Departamento'), 1, 0, 'L', true);

        blanco($pdf);
        $pdf->Cell(39.5, 6, utf8_decode('Producción'), 1, 0, 'L', true);

        gris($pdf, '');
        $pdf->Cell(43, 6, utf8_decode('Area'), 1, 0, 'L', true);

        blanco($pdf);
        $pdf->Cell(39.5, 6, utf8_decode('Taller de Mantenimiento'), 1, 1, 'L', true);

        azul($pdf);
        $pdf->Cell(0, 6, utf8_decode('Descripción de las instalaciones'), 0, 1, 'C', true);

        gris($pdf, '');
        $pdf->Cell(30, 6, utf8_decode('Largo (mt)'), 1, 0, 'C', true);

        blanco($pdf);
        $pdf->Cell(25, 6, utf8_decode('8'), 1, 0, 'C', true);

        gris($pdf, '');
        $pdf->Cell(30, 6, utf8_decode('Ancho (mt)'), 1, 0, 'C', true);

        blanco($pdf);
        $pdf->Cell(25, 6, utf8_decode('4'), 1, 0, 'C', true);

        gris($pdf, '');
        $pdf->Cell(30, 6, utf8_decode('Alto (mt)'), 1, 0, 'C', true);

        blanco($pdf);
        $pdf->Cell(25, 6, utf8_decode('6'), 1, 1, 'C', true);

        gris($pdf, '');
        $pdf->Cell(30, 6, utf8_decode('Color de techo'), 1, 0, 'C', true);

        blanco($pdf);
        $pdf->Cell(25, 6, utf8_decode('Gris'), 1, 0, 'C', true);

        gris($pdf, '');
        $pdf->Cell(30, 6, utf8_decode('Color de paredes'), 1, 0, 'C', true);

        blanco($pdf);
        $pdf->Cell(25, 6, utf8_decode('Blanco'), 1, 0, 'C', true);

        gris($pdf, '');
        $pdf->Cell(30, 6, utf8_decode('Color de piso'), 1, 0, 'C', true);

        blanco($pdf);
        $pdf->Cell(25, 6, utf8_decode('Gris'), 1, 1, 'C', true);

        azul($pdf);
        $pdf->Cell(0, 6, utf8_decode('Descripción de las lámparas'), 0, 1, 'C', true);

        gris($pdf, '');
        $pdf->Cell(43, 6, utf8_decode('Tipo de lámparas'), 1, 0, 'L', true);

        blanco($pdf);
        $pdf->Cell(39.5, 6, utf8_decode('Fluorescentes'), 1, 0, 'C', true);

        gris($pdf, '');
        $pdf->Cell(43, 6, utf8_decode('Potencia de las lámparas'), 1, 0, 'L', true);

        blanco($pdf);
        $pdf->Cell(39.5, 6, utf8_decode('50 Watts'), 1, 1, 'C', true);

        gris($pdf, '');
        $pdf->Cell(43, 6, utf8_decode('No de lámparas'), 1, 0, 'L', true);

        blanco($pdf);
        $pdf->Cell(39.5, 6, utf8_decode('3'), 1, 0, 'C', true);

        gris($pdf, '');
        $pdf->Cell(43, 6, utf8_decode('Altura (mt)'), 1, 0, 'L', true);

        blanco($pdf);
        $pdf->Cell(39.5, 6, utf8_decode('4'), 1, 1, 'C', true);

        gris($pdf, '');
        $pdf->Cell(43, 6, utf8_decode('Programa de mantenimiento'), 1, 0, 'L', true);

        blanco($pdf);
        $pdf->Cell(39.5, 6, utf8_decode('Preventivo y correctivo'), 1, 0, 'C', true);

        gris($pdf, '');
        $pdf->Cell(43, 6, utf8_decode('Tipo de Iluminación'), 1, 0, 'L', true);

        blanco($pdf);
        $pdf->Cell(39.5, 6, utf8_decode('Natural y artificial'), 1, 1, 'C', true);

        azul($pdf);
        $pdf->Cell(0, 6, utf8_decode('Descripción de los puestos de trabajo'), 0, 1, 'C', true);

        gris($pdf, 'B');
        $pdf->Cell(55, 6, utf8_decode('Puesto'), 1, 0, 'C', true);

        $pdf->Cell(55, 6, utf8_decode('Trabajadores'), 1, 0, 'C', true);

        $pdf->Cell(55, 6, utf8_decode('Tareas visuales'), 1, 1, 'C', true);

        blanco($pdf);
        $pdf->SetWidths(array(55, 55, 55));
        $pdf->SetFonts(array('', '', ''));
        $pdf->SetFontSizes(array(9));
        $pdf->SetAligns(array('C', 'C', 'C'));
        $pdf->Row(array(utf8_decode('Jefe de mantenimiento eléctrico'),
                        utf8_decode('1'),
                        utf8_decode('Programa de mantenimientos eléctricos requeridos por las áreas')));

        $pdf->Row(array(utf8_decode('Jefe de mantenimiento mecánico'),
                        utf8_decode('1'),
                        utf8_decode('Programa de mantenimientos mecánicos programados en las áreas de producción')));

        $pdf->Row(array(utf8_decode('Operador de mantenimiento'),
                        utf8_decode('1'),
                        utf8_decode('Apoyo en actividades de mantenimiento')));

        azul($pdf);
        $pdf->Cell(0, 6, utf8_decode('Descripción general del proceso de producción en el departamento'), 0, 1, 'C', true);

        blanco($pdf, 9);
        $pdf->MultiCell(0, 7, utf8_decode('Se realiza el mantenimiento en áreas de producción y planta en general'), 1, 'C', true);

        azul($pdf);
        $pdf->Cell(0, 6, utf8_decode('Percepción de las condiciones de iluminación por parte del trabajador'), 0, 1, 'C', true);

        blanco($pdf, 9);
        $pdf->MultiCell(0, 7, utf8_decode('Se considera que la iluminación es adecuada'), 1, 'C', true);
 
        $pdf->Ln(5);
        $pdf->Cell(60, 6, utf8_decode('Nombre y firma del reponsable'), 0, 1, 'C');

        $pdf->Cell(0, 5, '', 0, 1);
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'U', 8);
        $pdf->Cell(60, 5, utf8_decode('                                                                        '), 0, 0, 'C');
        $pdf->Cell(45, 5, '', 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(60, 4, utf8_decode('T.S.U. Omar Amador Arellano'), 0, 1, 'C');
        $pdf->Cell(60, 4, utf8_decode('Signatario Autorizado'), 0, 0, 'C');

/**************************************************************************************************/
/********************************************* Hoja 1 *********************************************/
/**************************************************************************************************/
        $pdf->AddPage('L');
        $pdf->SetMargins(20, 0, 25);
        $pdf->SetLineWidth(.1);

        $pdf->Ln(43);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor(0, 51, 105);
        $pdf->Cell(0, 3, utf8_decode('LISTADO DE RESULTADOS'), 0, 1, 'C');
        $pdf->Ln(2);
        $pdf->Cell(0, 3, utf8_decode('EVALUACION DE LOS NIVELES DE ILUMINACION'), 0, 1, 'C');
        $pdf->Ln(2);

        azul($pdf);
        $pdf->Cell(30, 6, utf8_decode('Compañía'), 1, 0, 'L', true);

        gris($pdf, 'B');
        $pdf->Cell(70, 6, utf8_decode('BARNICES Y RESINAS S.A. DE C.V.'), 1, 1, 'L', true);

        azul($pdf);
        $pdf->Cell(30, 6, utf8_decode('Planta'), 1, 0, 'L', true);

        gris($pdf, 'B');
        $pdf->Cell(70, 6, utf8_decode('ECATEPEC'), 1, 1, 'L', true);

        azul($pdf);
        $pdf->Cell(30, 6, utf8_decode('Lugar'), 1, 0, 'L', true);

        gris($pdf, 'B');
        $pdf->Cell(70, 6, utf8_decode('Ecatepec, Edo. de México'), 1, 1, 'L', true);

/**************************************************************************************************/
/********************************************* Hoja 2 *********************************************/
/**************************************************************************************************/


/**************************************************************************************************/
/********************************************* Hoja 3 *********************************************/
/**************************************************************************************************/


/**************************************************************************************************/
/********************************************* Hoja 4 *********************************************/
/**************************************************************************************************/
    

/**************************************************************************************************/
/********************************************* Hoja 5 *********************************************/
/**************************************************************************************************/

        $pdf->Output();
        exit();

/**************************************************************************************************/
/* Acción por defualt, llevar a búsqueda de ordenes */
/**************************************************************************************************/


/**************************************************************************************************/
/* Función para añadir nueva hoja */
/**************************************************************************************************/
//Recibe el objeto pdf, el objeto orden, número de página y el número de páginas totales
    function hojaNueva($pdf, $orden, $pagina, $paginas){
      $pdf->AddPage();
      $pdf->SetMargins(20, 0, 25);

      $pdf->Ln(36);
      $pdf->SetTextColor(100);
      $pdf->SetFont('Arial', 'B', 8);
      $pdf->Cell(0, 3, 'AIR-F-11', 0, 1, 'R');
      $pdf->Ln(7);

      $pdf->SetTextColor(0);
      $pdf->SetFont('Arial', 'B', 9);
      $pdf->Ln();
      $pdf->Cell(0, 3, utf8_decode('CARACTERIZACIÓN DE AGUA'), 0, 1, 'C');
      $pdf->Ln();

      $pdf->Cell(0, 3, utf8_decode('RESIDUAL DE ACUERDO A LA NOM-001-SEMARNAT-1996'), 0, 1, 'C');
      $pdf->Ln();

      $pdf->Cell(70, 4, '');

      $pdf->SetFont('Arial', 'B', 8);
      $pdf->SetFillColor(220);
      $pdf->SetDrawColor(180);
      $pdf->SetLineWidth(1.2);
      $pdf->Cell(20, 4, utf8_decode('N° de O.T.'), 1, 0, 'C', true);

      $pdf->SetFont('Arial', '', 8);
      $pdf->SetFillColor(255);
      $pdf->Cell(15, 4, utf8_decode($orden['ot']), 1, 0, 'C', true);

      $pdf->SetFont('Arial', 'B', 8);
      $pdf->SetFillColor(220);
      $pdf->Cell(15, 4, utf8_decode('Hoja'), 1, 0, 'C', true);

      $pdf->SetFont('Arial', '', 8);
      $pdf->SetFillColor(255);
      $pdf->Cell(15, 4, utf8_decode($pagina), 1, 0, 'C', true);

      $pdf->SetFont('Arial', 'B', 8);
      $pdf->SetFillColor(220);
      $pdf->Cell(15, 4, utf8_decode('De'), 1, 0, 'C', true);

      $pdf->SetFont('Arial', '', 8);
      $pdf->SetFillColor(255);
      $pdf->Cell(15, 4, utf8_decode($paginas), 1, 1, 'C', true);
      $pdf->Ln();

      $pdf->SetFillColor(215, 231, 248);
      $pdf->SetDrawColor(190);
      $pdf->SetLineWidth(.8);
    }

function azul($pdf){
    $pdf->SetFillColor(0, 51, 105);
    $pdf->SetTextColor(255);
}

function gris($pdf, $fuente){
    $pdf->SetFont('Arial', $fuente, 9);
    $pdf->SetFillColor(227, 227, 227);
    $pdf->SetTextColor(0);
}

function blanco($pdf, $size=8){
    $pdf->SetFont('Arial', '', $size);
    $pdf->SetFillColor(255);
    $pdf->SetTextColor(0);
}

    ?>