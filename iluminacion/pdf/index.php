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
            $this->Image("../../img/logolaboratorio3.gif", 10, 15, 165, 33);
        }

        function Footer()
        {
            $this->SetY(-15);

            $this->SetTextColor(125);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(0, 3, utf8_decode('El presente informe no podrá ser alterado ni reproducido total o parcialmente sin autorización previa por escrito del Laboratorio del Grupo Microanálisis, S.A. de C.V.'), 0, 'C'); //////////// Dirección
            /*$this->Ln();

            $this->SetTextColor(0);
            $this->SetFont('Arial', '', 7);
            $this->Cell(0, 3, utf8_decode('GENERAL SOSTENES ROCHA 28, MAGDALENA MIXHUCA, MÉXICO D.F. 15850'), 0, 1, 'C');
            $this->Ln(1);
            $this->Cell(0, 3, utf8_decode('Tel: +52 (55)5768-7744, Fax: +52 (55)5764-0295'), 0, 1, 'C');
            $this->Ln(1);
            $this->Cell(0, 3, utf8_decode('E-Mail: ventas@microanalisis.com Web: www.microanalisis.com'), 0, 1, 'C');*/
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

            for($i=0;$i<count($data);$i++){
                if(count($this->nfonts) > 0 AND count($this->nfontsize) > 0){
                    $b=(count($this->nfonts) === 1) ? $this->nfonts[0] : $this->nfonts[$i];
                    $c=(count($this->nfontsize) === 1) ? $this->nfontsize[0] : $this->nfontsize[$i];
                    $this->SetFont('Arial', $b, $c);
                }
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
                $this->Rect($x,$y,$w,$h, 'DF');

                //Número de renglones de separación arriba y abajo, se resta la altura
                //total menos la altura del texto, se divide entre dos (obtener altura de
                //arriba y de abajo) y esto entre 5 para obtener el número de renglones
                //según la altura del renglón, y así anexar dichos renglones extra al texto
                $nr = (($h-($sh[$i]*5))/2)/5;
                for ($j=0; $j < $nr; $j++){ 
                    $data[$i]="\n".$data[$i]."\n";
                }
                
                //Print the text
                $this->MultiCell($w,5,$data[$i],1,$a, true);
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
            $this->SetFont('Arial', $this->nfonts[0], $this->nfontsize[0]);
            $sh[]=$this->NbLines($this->widths[0], $data[0]);
            for($i=0;$i<count($data[1]);$i++){
                $nb=max($nb,$this->NbLines($this->widths[$i+1],$data[1][$i]));
                 //Se guarda la altura de cada texto
                $sh[]=$this->NbLines($this->widths[$i+1],$data[1][$i]);
            }
            $h=5*($sh[0]+$nb);
            //Issue a page break first if needed
            $this->CheckPageBreak($h);

            //Draw the cells of the row
            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x,$y,$this->widths[0],$sh[0]*5,'DF');
            
            $this->MultiCell($this->widths[0],5,$data[0],0,'C');
            $this->SetXY($x,$y+$sh[0]*5);

            //print_r($data);

            for($i=0;$i<count($data[1]);$i++)
            {
                //Save the current position
                $x=$this->GetX();
                $y=$this->GetY();
                //Draw the border
                $this->Rect($x,$y,$this->widths[$i+1],$nb*3, 'DF');

                //Número de renglones de separación arriba y abajo, se resta la altura
                //total menos la altura del texto, se divide entre dos (obtener altura de
                //arriba y de abajo) y esto entre 5 para obtener el número de renglones
                //según la altura del renglón, y así anexar dichos renglones extra al texto
                $nr=(($nb-$sh[$i+1]))/3;
                //print_r($nr);
                for ($j=0; $j < number_format($nr, 0); $j++){ 
                    $data[1][$i]="\n".$data[1][$i]."\n";
                }
                    
                //Print the text
                $this->MultiCell($this->widths[$i+1],3,$data[1][$i],0, 'C');
                //Put the position to the right of the cell
                $this->SetXY($x+$this->widths[$i+1],$y);
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


        function MultiCellBltArray($w, $h, $blt_array, $border=0, $align='J', $fill=0)
        {
            if (!is_array($blt_array))
            {
                die('MultiCellBltArray requires an array with the following keys: bullet, margin, text, indent, spacer');
                exit;
            }
                    
            //Save x
            $bak_x = $this->x;
            
            for ($i=0; $i<sizeof($blt_array['text']); $i++)
            {
                //Get bullet width including margin
                $blt_width = $this->GetStringWidth($blt_array['bullet'] . $blt_array['margin'])+$this->cMargin*2;
                
                // SetX
                $this->SetX($bak_x);
                
                //Output indent
                if ($blt_array['indent'] > 0)
                    $this->Cell($blt_array['indent']);
                
                //Output bullet
                $this->Cell($blt_width, $h, $blt_array['bullet'] . $blt_array['margin'], 0, '', $fill);
                
                //Output text
                $this->MultiCell($w-$blt_width, $h, $blt_array['text'][$i], $border, $align, $fill);
                
                //Insert a spacer between items if not the last item
                if ($i != sizeof($blt_array['text'])-1)
                    $this->Ln($blt_array['spacer']);
                
                //Increment bullet if it's a number
                if (is_numeric($blt_array['bullet']))
                    $blt_array['bullet']++;
            }
        
            //Restore x
            $this->x = $bak_x;
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
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, utf8_decode('AIR-F-2'), 0, 1, 'R');
    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(0, 3, utf8_decode('HOJA DE RECONOCIMIENTO INICIAL'), 0, 1, 'C');
    $pdf->Ln(2);
    $pdf->Cell(0, 3, utf8_decode('NOM-025-STPS-2008'), 0, 1, 'C');
    $pdf->Ln(2);

    azul($pdf);
    $pdf->Cell(0, 5, utf8_decode('Datos Generales'), 0, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(25, 5, utf8_decode('Fecha'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(30, 5, utf8_decode('2013-02-20'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(20, 5, utf8_decode('OT No.'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(20, 5, utf8_decode('092'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(20, 5, utf8_decode('Hoja'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(15, 5, utf8_decode('3'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(20, 5, utf8_decode('De'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(15, 5, utf8_decode('3'), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Compañía'), 1, 0, 'L', true);

    blanco($pdf, 8 ,'B');
    $pdf->Cell(0, 6, utf8_decode('BARNICES Y RESINAS S.A. DE C.V.'), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Planta'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode('ECATEPEC'), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Lugar'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode('Ecatepec, Edo. de México'), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Departamento'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode('Producción'), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Area'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode('Taller de Mantenimiento'), 1, 1, 'L', true);

    azul($pdf);
    $pdf->Cell(0, 6, utf8_decode('Descripción de las instalaciones'), 0, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Largo (mt)'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode('8'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Ancho (mt)'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode('4'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Alto (mt)'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode('6'), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Color de techo'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode('Gris'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Color de paredes'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode('Blanco'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Color de piso'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode('Gris'), 1, 1, 'C', true);

    azul($pdf);
    $pdf->Cell(0, 6, utf8_decode('Descripción de las lámparas'), 0, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Tipo de lámparas'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode('Fluorescentes'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Potencia de las lámparas'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode('50 Watts'), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('No de lámparas'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode('3'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Altura (mt)'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode('4'), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Programa de mantenimiento'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode('Preventivo y correctivo'), 1, 0, 'C', true);

    gris($pdf);
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
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, utf8_decode('AIR-F-2'), 0, 1, 'R');
    $pdf->Ln(2);

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
    $pdf->Ln(2);


    gris($pdf, 'B');
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(10, 8.5, utf8_decode('No. Med'), 1, 'C', true);
    $pdf->SetXY($x+10,$y);
    $pdf->Cell(16, 17, utf8_decode('Fecha'), 1, 0, 'C', true);
    $pdf->Cell(20, 17, utf8_decode('Área'), 1, 0, 'C', true);
    $pdf->Cell(23, 17, utf8_decode('Ubicación'), 1, 0, 'C', true);
    $pdf->Cell(27, 17, utf8_decode('Identificación'), 1, 0, 'C', true);

    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->SetWidths(array(52,13,10,14,15));
    $pdf->SetAligns(array('C'));
    $pdf->SetFonts(array('B'));
    $pdf->SetFontSizes(array(6));
    $pdf->carobsRow(array(utf8_decode('Resultados 1er Ciclo de Medición'),
                        array(utf8_decode('N.I. (Lux)'),
                            utf8_decode('NIMR (lux)'),
                              utf8_decode('Reflexión paredes (60%)'),
                              utf8_decode('Reflexión  plano de trabajo (50%)')
                            )
                        )
                    );

    $pdf->SetXY($x+52,$y);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->carobsRow(array(utf8_decode('Resultados 2do Ciclo de Medición'),
                        array(utf8_decode('N.I. (Lux)'),
                              utf8_decode('NIMR (lux)'),
                              utf8_decode('Reflexión paredes (60%)'),
                              utf8_decode('Reflexión  plano de trabajo (50%)')
                            )
                        )
                    );

    $pdf->SetXY($x+52,$y);
    $pdf->carobsRow(array(utf8_decode('Resultados 3er Ciclo de Medición'),
                        array(utf8_decode('N.I. (Lux)'),
                              utf8_decode('NIMR (lux)'),
                              utf8_decode('Reflexión paredes (60%)'),
                              utf8_decode('Reflexión  plano de trabajo (50%)')
                            )
                        )
                    );
    $pdf->Ln(12);

    blanco($pdf, 8, 'B');
    $pdf->Cell(0, 5, utf8_decode('Departamento: Producción'), 1, 1, 'C', true);

    $pdf->SetWidths(array(10,16,20,23,27,13,10,14,15,13,10,14,15,13,10,14,15));
    $pdf->SetFonts(array(''));
    $pdf->SetFontSizes(array(6));
    $pdf->SetAligns(array('C'));

    for ($i=0; $i <6 ; $i++) { 
        $pdf->Row(array(utf8_decode($i+1),
                    utf8_decode('2013-02-20'),
                    utf8_decode('Descarga'),
                    utf8_decode('Descarga'),
                    utf8_decode('Pasillo entre R2 y R-5'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica')
                )
            );
    }

    $pdf->Cell(0, 5, utf8_decode('Departamento: Mantenimiento'), 1, 1, 'C', true);
    $pdf->Row(array(utf8_decode('7'),
                    utf8_decode('2013-02-20'),
                    utf8_decode('Descarga'),
                    utf8_decode('Descarga'),
                    utf8_decode('Pasillo entre R2 y R-5'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica')
                )
            );

    $pdf->Cell(0, 5, utf8_decode('Departamento: Oficinas'), 1, 1, 'C', true);
    $pdf->Row(array(utf8_decode('8'),
                    utf8_decode('2013-02-20'),
                    utf8_decode('Descarga'),
                    utf8_decode('Descarga'),
                    utf8_decode('Pasillo entre R2 y R-5'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica')
                )
            );

    $pdf->Row(array(utf8_decode('9'),
                    utf8_decode('2013-02-20'),
                    utf8_decode('Descarga'),
                    utf8_decode('Descarga'),
                    utf8_decode('Pasillo entre R2 y R-5'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica'),
                    utf8_decode('168 ± 20'),
                    utf8_decode('100'),
                    utf8_decode('No Aplica'),
                    utf8_decode('No Aplica')
                )
            );

    blanco($pdf, 6, 'B');
    $pdf->Cell(20, 3, utf8_decode('No. Med:'), 0, 0, 'R', true);
    blanco($pdf, 6, '');
    $pdf->Cell(70, 3, utf8_decode('Número de medición'), 0, 1, 'L', true);

    blanco($pdf, 6, 'B');
    $pdf->Cell(20, 3, utf8_decode('N.I. (lux):'), 0, 0, 'R', true);
    blanco($pdf, 6, '');
    $pdf->Cell(70, 3, utf8_decode('Nivel de Iluminación en el punto (lux)'), 0, 1, 'L', true);

    blanco($pdf, 6, 'B');
    $pdf->Cell(20, 3, utf8_decode('N.I.M.R. (lux):'), 0, 0, 'R', true);
    blanco($pdf, 6, '');
    $pdf->Cell(70, 3, utf8_decode('Nivel de Iluminación Mínimo Recomendado (lux)'), 0, 1, 'L', true);

    blanco($pdf, 6, 'B');
    $pdf->Cell(20, 3, utf8_decode('F.R:'), 0, 0, 'R', true);
    blanco($pdf, 6, '');
    $pdf->Cell(70, 3, utf8_decode('Factor de Reflexión (%)'), 0, 1, 'L', true);

    blanco($pdf, 6, 'B');
    $pdf->Cell(20, 3, utf8_decode('F.R.M.'), 0, 0, 'R', true);
    blanco($pdf, 6, '');
    $pdf->Cell(70, 3, utf8_decode('Factor de Reflexión Máximo (%)'), 0, 1, 'L', true);

    $pdf->Image("../../img/semaforo 5.png", 20, 170, 70, 17);


/**************************************************************************************************/
/********************************************* Hoja 2 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(43);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, utf8_decode('AIR-F-2'), 0, 1, 'R');
    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(0, 3, utf8_decode('INFORME DE EVALUACIÓN DE ILUMINACIÓN'), 0, 1, 'C');
    $pdf->Ln(2);
    $pdf->Cell(0, 3, utf8_decode('NOM-025-STPS-2008'), 0, 1, 'C');
    $pdf->Ln(2);

    gris($pdf);
    $pdf->Cell(25, 6, utf8_decode('Medición No'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(30, 6, utf8_decode('1'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Fecha'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(30, 6, utf8_decode('2013-02-20'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(20, 6, utf8_decode('O.T.'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(0, 6, utf8_decode('092'), 1, 1, 'C', true);

    blanco($pdf, 8, 'B');
    $pdf->Cell(0, 8, utf8_decode('DATOS DE LA EMPRESA'), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Compañía'), 1, 0, 'L', true);

    blanco($pdf, 8 ,'B');
    $pdf->Cell(0, 7, utf8_decode('BARNICES Y RESINAS S.A. DE C.V.'), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Domicilio'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(0, 7, utf8_decode('Calle Acero, Manzana 8, Lote 13, Col. Esfuerzo Nacional, C.P. 55320'), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Representante'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(0, 7, utf8_decode('Lic. Moisés Preciado Hop'), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Planta'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(50, 7, utf8_decode('ECATEPEC'), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Lugar'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(0, 7, utf8_decode('Ecatepec, Estado de México'), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Departamento'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(50, 7, utf8_decode('Producción'), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Área'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(0, 7, utf8_decode('Descarga'), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Identificación'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(50, 7, utf8_decode('Pasillo entre R2 y R-5'), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Ubicación'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(0, 7, utf8_decode('Descarga'), 1, 1, 'L', true);

    blanco($pdf, 8, 'B');
    $pdf->Cell(0, 8, utf8_decode('DATOS DEL LABORATORIO DE PRUEBAS'), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(25, 8, utf8_decode('Razón Social'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(60, 8, utf8_decode('Laboratorio del Grupo Microanalisis, S.A. de C.V.'), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 8, utf8_decode('Acred.. EMA'), 1, 0, 'L', true);

    blanco($pdf, 8);
    $pdf->MultiCell(0, 3.5, utf8_decode("Al-0102-015/12 \n Vigencia a partir del 2012-08-10"), 1, 'L', true);

    blanco($pdf, 8, 'B');
    $pdf->Cell(0, 8, utf8_decode('DATOS DEL EQUIPO DE MEDICIÓN'), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Marca'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(30, 7, utf8_decode('TESTO'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 7, utf8_decode('Modelo'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(30, 7, utf8_decode('545'), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(20, 7, utf8_decode('No. de Serie'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(0, 7, utf8_decode('01200990/603'), 1, 1, 'C', true);
    $pdf->Ln(3);

    azul($pdf);
    $pdf->Cell(0, 8, utf8_decode('RESULTADOS DE LA EVALUACIÓN'), 1, 1, 'C', true);

    gris($pdf);
    $pdf->SetWidths(array(20,30,31,28,28,28));
    $pdf->SetFonts(array(''));
    $pdf->SetFontSizes(array(8));
    $pdf->SetAligns(array('C','C','C','C','C','C'));

    $pdf->Row(array(utf8_decode('Hora de medición'),
                utf8_decode('Nivel de iluminación obtenido (lux)'),
                utf8_decode('Nivel Ilum. Mín. Recomendado (lux)'),
                utf8_decode('Factor de Reflexión (%) 50%'),
                utf8_decode('Nivel de iluminación en pared (lux)'),
                utf8_decode('Factor de Reflexión pared (%) 60%')
            )
        );

    blanco($pdf);
    $pdf->SetFontSizes(array(7));

    $pdf->Row(array(utf8_decode('medición'),
                utf8_decode('Nivel'),
                utf8_decode('Nivel'),
                utf8_decode('Factor'),
                utf8_decode('Nivel'),
                utf8_decode('Factor')
            )
        );

    $pdf->Row(array(utf8_decode('medición'),
                utf8_decode('Nivel'),
                utf8_decode('Nivel'),
                utf8_decode('Factor'),
                utf8_decode('Nivel'),
                utf8_decode('Factor')
            )
        );

    $pdf->Row(array(utf8_decode('medición'),
                utf8_decode('Nivel'),
                utf8_decode('Nivel'),
                utf8_decode('Factor'),
                utf8_decode('Nivel'),
                utf8_decode('Factor')
            )
        );

    $pdf->Ln(3);

    $pdf->Cell(100, 6, '', 0, 0);
    gris($pdf);
    $pdf->Cell(0, 6, utf8_decode('Observaciones'), 1, 1, 'C', true);

    $pdf->Cell(100, 6, '', 0, 0);
    blanco($pdf);
    $pdf->Cell(0, 20, utf8_decode('Iluminacion natural y artificial'), 1, 1, 'C', true);

    $pdf->Ln(2);

    $pdf->Cell(100, 6, '', 0, 0);
    gris($pdf);
    $pdf->Cell(0, 6, utf8_decode('Análisis de resultados'), 1, 1, 'C', true);

    $pdf->Cell(100, 6, '', 0, 0);
    blanco($pdf);
    $pdf->MultiCell(0, 7, utf8_decode('Se observa una iluminación adecuada en el primer y segundo ciclo medido e inadecuada para el tercer ciclo para el trabajo que ahí se realiza'), 1, 'C', true);

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
/********************************************* Hoja 3 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(40, 0, 30);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(60);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->MultiCell(0, 6, utf8_decode("REPORTE DE EVALUACIÓN DE LOS \n NIVELES DE ILUMINACIÓN \n NOM-025-STPS-2008"), 0, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->MultiCell(0, 7, utf8_decode("ESTUDIO DE RECONOCIMIENTO Y EVALUACIÓN"), 0, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->MultiCell(0, 7, utf8_decode("Practicado en la empresa"), 0, 'C');
    $pdf->Ln(10);
    
    $pdf->SetFont('Arial', 'B', 28);
    $pdf->MultiCell(0, 10, utf8_decode("ALCOCER AGUILAR JOSÉ MANUEL"), 0, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 24);
    $pdf->MultiCell(0, 9, utf8_decode("PLANTA ACEROS LAMASI MATRÍZ "), 0, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 5, utf8_decode('Registro Federal de Contribuyentes'), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode('AOAM801017'), 0, 1, 'L');
    $pdf->Ln(7);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 6, utf8_decode('Domicilio completo'), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode('Avenida Carranza No. 246, C.P. 77000'), 0, 1, 'L');
    $pdf->Ln(3);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 6, utf8_decode('Teléfono'), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode('01 (983) 129 20 07'), 0, 1, 'L');
    $pdf->Ln(3);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 6, utf8_decode('Actividad principal '), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode('Laminas, polines, tubular, acero y solera'), 0, 1, 'L');
    $pdf->Ln(3);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 6, utf8_decode('Representante '), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode('María del Pilar Aguilar Ortega'), 0, 1, 'L');
    $pdf->Ln(3);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 6, utf8_decode('Atención'), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode('Lic. José Manuel Alcocer Aguilar'), 0, 1, 'L');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 4, utf8_decode('CHETUMAL, QUINTANA ROO'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('MARZO DEL 2015'), 0, 1, 'C');




/**************************************************************************************************/
/********************************************* Hoja 4 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(60);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->MultiCell(0, 6, utf8_decode("REPORTE DE EVALUACIÓN DE LOS \n NIVELES DE ILUMINACIÓN \n NOM-025-STPS-2008"), 0, 'C');
    $pdf->Ln(30);

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 6, utf8_decode("CONTENIDO"), 0, 'C');
    $pdf->Ln(10);

    $pdf->Cell(0, 4, utf8_decode('INTRODUCCIÓN _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 3'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('1. JUSTIFICACIÓN _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 3'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('2. OBJETIVO _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 3'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('3. METODOLOGÍA _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 4'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('4. EVALUACIÓN _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _  4'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('5. REFERENCIAS PARA LA EVALUACIÓN _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 7'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('6. RESULTADOS _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 8'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('7. DESVIACIONES _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 8'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('8. CONCLUSIONES _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _  8'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('9. LISTADO DE ANEXOS _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 10'), 0, 1, 'C');

/**************************************************************************************************/
/********************************************* Hoja 5 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(43);
    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-2', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 3 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln();

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('INTRODUCCIÓN'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("La iluminación industrial es uno de los principales factores ambientales de carácter microclimático, que tiene como principal finalidad facilitar la visualización de las cosas dentro del contexto espacial, de modo que el trabajo se pueda realizar en unas condiciones aceptables de eficacia, comodidad y seguridad."), 0, 'J');
    $pdf->Ln();
    $pdf->MultiCell(0, 5, utf8_decode("Si se consiguen estos objetivos, las consecuencias no sólo repercuten favorablemente sobre las personas, reduciendo la fatiga, la tasa de errores y accidentes, sino que además contribuyen a aumentar la cantidad y calidad de trabajo."), 0, 'J');
    $pdf->Ln();
    $pdf->MultiCell(0, 5, utf8_decode("En el presente reporte se establecen los niveles de iluminación en las diferentes áreas de la empresa ALCOCER AGUILAR JOSÉ MANUEL, en la planta ACEROS LAMASI MATRÍZ, ubicada en Chetumal, Quintana Roo, para control interno, a través de la medición directa de los mismos y su correlación con los niveles mínimos recomendados establecidos en la NOM-025-STPS-2008."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('1. JUSTIFICACIÓN'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("Según el art. 95 del Reglamento Federal de Seguridad, Higiene y Medio Ambiente, las áreas,  planos y lugares de trabajo, deberán contar con las condiciones y niveles de iluminación adecuadas al tipo de actividad que se realice."), 0, 'J');
    $pdf->Ln();
    $pdf->MultiCell(0, 5, utf8_decode("De acuerdo a lo anterior, se debe efectuar el reconocimiento, evaluación y control de los niveles de iluminación en el centro de trabajo,  según lo establecido en los numerales 8, 9 y 10 de la NOM-025-STPS-2008."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('2. OBJETIVO'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('2.1 General'), 0, 1, 'L');

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("Evaluar los niveles de iluminación y establecer sus características de forma que este no sea un factor de riesgo para los trabajadores en el centro de trabajo."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('2.2 Específicos'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("2.2.1    Realizar evaluaciones directas en los sitios de trabajo, de forma que se abarque en forma total la maquinaria, equipo y el puesto de trabajo."), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("2.2.2    Comparar los valores obtenidos con los niveles mínimos recomendados de acuerdo  al tipo de actividad que se desarrolla en las áreas evaluadas."), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("2.2.3    Obtener los porcientos de reflexión de las áreas de trabajo para determinar si estos representan un problema en el campo visual de los trabajadores."), 0, 'J');
    $pdf->Ln();


/**************************************************************************************************/
/********************************************* Hoja 6 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(43);
    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-2', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 4 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln();

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('3. METODOLOGÍA'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("A continuación se describen las actividades realizadas para la evaluación de las condiciones de iluminación:"), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('3.1  Reconocimiento inicial'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("Tal y como se establece en la norma vigente, el propósito del reconocimiento es determinar las áreas y puestos de trabajo que cuenten con una deficiente iluminación o que presenten deslumbramiento, para lo cual se deben considerar los reportes de los trabajadores y realizar un recorrido por todas las áreas del centro de trabajo en donde existan trabajadores, así como, recabar la información técnica y administrativa que permita seleccionar las áreas y puestos de trabajo por evaluar."), 0, 'J');
    $pdf->Ln();

    $pdf->Cell(10, 5, '  ', 0, 0, 'R');
    $pdf->Cell(0, 5, utf8_decode('La información que se incluye en este informe es:'), 0, 1, 'L');

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->Cell(0, 5, utf8_decode('Plano de distribución de áreas, luminarias, maquinaria y equipo'), 0, 1, 'L');

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->Cell(0, 5, utf8_decode('Descripción del proceso de trabajo'), 0, 1, 'L');

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->Cell(0, 5, utf8_decode('Descripción de los puestos de trabajo'), 0, 1, 'L');

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->Cell(0, 5, utf8_decode('Número de trabajadores por área de trabajo'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('3.2 Ubicación de los puntos de medición'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("Los puntos de medición fueron seleccionados en función de las necesidades y características de cada centro de trabajo, de tal manera que se describiera el entorno ambiental de la iluminación de una forma confiable, considerando el proceso de producción, la ubicación de las luminarias, de las áreas y puestos de trabajo y la posición de la maquinaria y equipo, según lo establece la norma de referencia, en el numeral A.2.3.1."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('3.3 Instrumentación'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(115, 5, utf8_decode("Luminómetro marca International Light, modelo ILT1400, con corrección cosenoidal y desviación máxima de su detector de +/- 5% de su respuesta espectral fotópica y con exactitud de +/- 5%,  No. de Serie ILT14000655."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('4. EVALUACIÓN'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("La evaluación de los niveles de iluminación se llevó a cabo conforme lo establece el apéndice A de  la NOM-025-STPS-2008."), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("A partir de los registros del reconocimiento se llevó a cabo la evaluación de los niveles de iluminación en las áreas y puestos de trabajo bajo los siguientes criterios:"), 0, 'J');
    $pdf->Ln();


/**************************************************************************************************/
/********************************************* Hoja 7 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(43);
    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-2', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 5 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln();

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Los puntos de medición se seleccionaron en función de la ubicación y actividades del puesto, el proceso de producción, la ubicación de las luminarias y la posición de la maquinaria y equipo.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('En lo posible las áreas de trabajo valoradas se ajustaron al mínimo de zonas a evaluar de acuerdo con la norma y se consideraron los lugares en donde existe mayor concentración de trabajadores o el centro geométrico de cada una de estas zonas. Para este caso,  se realizaron las mediciones en los lugares en donde el trabajador realiza sus actividades.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Se realizaron 3 mediciones en horarios con influencia de luz natural, en condiciones normales de operación.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Los puntos de medición fueron identificados con números consecutivos.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Las lecturas fueron registradas en el formato de evaluación. '), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Se verificó que las lámparas estuvieran encendidas y si había sistemas de ventilación que éstos operaran normalmente.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('En cada medición el luminómetro se ajusto a lectura cero antes y después del ciclo de valoraciones.'), 0, 'L');

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('La medición en pasillos o escaleras se llevó a cabo en el plano horizontal a 75 +/- 10cm sobre el nivel del piso, de preferencia en los puntos medios entre luminarias contiguas.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Las mediciones en los puestos de trabajo se llevaron a cabo colocando el luminómetro tan cerca como es posible al plano de trabajo con las debidas precauciones para no proyectar sombras ni reflejar luz adicional sobre el plano.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Las mediciones que se practicaron fueron las de nivel de iluminación en lux y factor de reflexión en porciento.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, '-  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Para la medición del factor de reflexión se utilizaron los mismos puntos seleccionados para el nivel y el procedimiento seguido fue el indicado en la NOM de referencia que expresa que con la foto celda del luminómetro colocado de cara a la superficie, se debe cuantificar la intensidad luminosa a una distancia de 10 cm y posteriormente orientar la celda en sentido contrario y apoyada en la superficie medir la luz incidente.'), 0, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('4.1 Reporte del estudio'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("Se elaboró un reporte con la información proporcionada por el cliente (Anexo 1) conforme lo marca el punto 12 de la NOM-025-STPS-2008, los datos obtenidos durante la evaluación y la siguiente información:"), 0, 'J');
    $pdf->Ln();

/**************************************************************************************************/
/********************************************* Hoja 8 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(43);
    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-2', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No.  de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln();

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(10, 5, 'a.', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('  informe descriptivo de las condiciones normales de operación, en las cuales se realizó la evaluación, incluyendo las descripciones del proceso, instalaciones, puestos de trabajo y el número de trabajadores expuestos por área y puesto de trabajo; Anexo 1.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, 'b.', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('plano de distribución del área evaluada, en el que se indique la ubicación de los puntos de medición; Anexo 4.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, 'c.', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Listado de resultados de la medición de los niveles de iluminación; Anexo 2.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, 'd.', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('comparación e interpretación de los resultados obtenidos, contra lo establecido en las tablas 1 y 2; Anexo 3.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, 'e.  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('hora en que se efectuaron las mediciones; Anexo 3.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, 'f.  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('programa de mantenimiento. Anexo 1.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, 'g.  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('copia del documento que avale la calibración del luxómetro; Anexo 5.'), 0, 'L');

    $pdf->Cell(10, 5, 'h.  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('conclusión técnica del estudio; Punto 8 Conclusiones.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, 'i.  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('las medidas de control a desarrollar y el programa de implantación; Punto 8 Recomendaciones de control.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, 'j.  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('nombre y firma del responsable del estudio; Anexo 3.'), 0, 'L');
    $pdf->Ln();

    $pdf->Cell(10, 5, 'k.  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('resultados de las evaluaciones hasta cumplir con lo establecido en las tablas 1 y 2. Anexo 2.'), 0, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(15, 5, 'Nota:  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('La vigencia de este reporte será de dos años, a menos que las tareas visuales, áreas de trabajo o sistemas de iluminación se modifiquen.'), 0, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('4.2. Formulación matemática'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("El número de zonas a evaluar se obtuvo conforme lo dice la norma con la tabla indicada en ella y la siguiente fórmula:"), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(50, 5, '', 0, 0, 'R');
    $pdf->MultiCell(0, 4, utf8_decode("Donde: \n IC   :  Índice de Área \n x,y  :  dimensiones del área (largo y ancho) en metros \n h  :  Altura de la luminaria respecto al plano de trabajo,  en  metros"), 0, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("El factor de reflexión se obtiene con la siguiente fórmula"), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(50, 5, '', 0, 0, 'R');
    $pdf->MultiCell(0, 4, utf8_decode("Donde:\nKƒ    :  Factor de reflexión\nE1  :  Nivel de Iluminación reflejada\nE2  :  Nivel de iluminación incidente"), 0, 'L');
    $pdf->Ln();

/**************************************************************************************************/
/********************************************* Hoja 9 *********************************************/
/**************************************************************************************************/

/**************************************************************************************************/
/********************************************* Hoja 10 ********************************************/
/**************************************************************************************************/

/**************************************************************************************************/
/********************************************* Hoja 11 ********************************************/
/**************************************************************************************************/

/**************************************************************************************************/
/********************************************* Hoja 12 ********************************************/
/**************************************************************************************************/

/**************************************************************************************************/
/********************************************* Hoja 13 ********************************************/
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
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(0, 51, 105);
    $pdf->SetTextColor(255);
}

function gris($pdf, $fuente='', $size=9){
    $pdf->SetFont('Arial', $fuente, $size);
    $pdf->SetFillColor(227, 227, 227);
    $pdf->SetTextColor(0);
}

function blanco($pdf, $size=8, $fuente=''){
    $pdf->SetFont('Arial', $fuente, $size);
    $pdf->SetFillColor(255);
    $pdf->SetTextColor(0);
}

function inlineBold($pdf, $text){
    $pdf->SetFont('Arial', 'B');
    return $pdf->Text(0, 0, $text);
}

    ?>