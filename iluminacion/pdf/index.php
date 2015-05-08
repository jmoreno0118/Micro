<?php
 /********** Iluminació **********/
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/jpgraph-3.5.0b1/src/jpgraph.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/jpgraph-3.5.0b1/src/jpgraph_pie.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/jpgraph-3.5.0b1/src/jpgraph_pie3d.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/jpgraph-3.5.0b1/src/jpgraph_bar.php';

/**************************************************************************************************/
/* Modificaciones al FPDF */
/**************************************************************************************************/

    //include ('fpdf/fpdf.php');
    include ($_SERVER['DOCUMENT_ROOT'].'/reportes/includes/fpdf/fpdf.php');

    class PDF extends FPDF
    {
        function Header()
        {
            $this->Image("../../img/logolaboratorio3.gif", 10, 15, 165, 31.5);
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

        function Row($data, $fill=false)
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
                $this->Rect($x, $y, $w, $h, 'DF');

                //Número de renglones de separación arriba y abajo, se resta la altura
                //total menos la altura del texto, se divide entre dos (obtener altura de
                //arriba y de abajo) y esto entre 5 para obtener el número de renglones
                //según la altura del renglón, y así anexar dichos renglones extra al texto
                $nr = (($h-($sh[$i]*5))/2)/5;
                for ($j=0; $j < $nr; $j++){ 
                    $data[$i]="\n".$data[$i]."\n";
                }
                
                //Print the text
                $this->MultiCell($w,5,$data[$i],0,$a, $fill);
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

        function noEnterRow($data)
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
                $this->Rect($x, $y, $w, $h, 'DF');
                
                //Print the text
                $this->MultiCell($w,5,$data[$i],0,$a);
                //Put the position to the right of the cell
                $this->SetXY($x+$w,$y);
            }
            //Go to the next line
            $this->Ln($h);
        }

        function RowColor($data, $fill=false)
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
                $nb=max($nb,$this->NbLines($this->widths[$i], $data[$i]));

                //Se guarda la altura de cada texto
                $sh[]=$this->NbLines($this->widths[$i], $data[$i]);
            }
            $h=5*$nb;
            //Issue a page break first if needed
            $this->CheckPageBreak($h);
            //Draw the cells of the row
            for($i=0;$i<count($data);$i++)
            {
                if($i === 5 OR $i === 9 OR $i === 13){
                    $fill = true;
                    $valor = explode(' ± ', $data[$i]);
                    if(intval($valor[0]) >= $data[$i+1]){
                        $this->SetFillColor(0, 255, 0);
                    }else{
                        $this->SetFillColor(255, 0, 0);
                    }
                }else{
                    $this->SetFillColor(255, 255, 255);
                }
                $w=$this->widths[$i];
                $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                //Save the current position
                $x=$this->GetX();
                $y=$this->GetY();
                //Draw the border
                $this->Rect($x, $y, $w, $h, 'DF');

                //Número de renglones de separación arriba y abajo, se resta la altura
                //total menos la altura del texto, se divide entre dos (obtener altura de
                //arriba y de abajo) y esto entre 5 para obtener el número de renglones
                //según la altura del renglón, y así anexar dichos renglones extra al texto
                $nr = (($h-($sh[$i]*5))/2)/5;
                for ($j=0; $j < $nr; $j++){ 
                    $data[$i]="\n".$data[$i]."\n";
                }
                
                //Print the text
                $this->MultiCell($w,5,$data[$i],0,$a, $fill);
                //Put the position to the right of the cell
                $this->SetXY($x+$w,$y);
            }
            //Go to the next line
            $this->Ln($h);
        }

        function RowColor2($data, $fill=false)
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
                if($i === 1){
                    $fill = true;
                    $valor = explode(' ± ', $data[$i]);
                    if(intval($valor[0]) >= $data[$i+1]){
                        $this->SetFillColor(0, 255, 0);
                    }else{
                        $this->SetFillColor(255, 0, 0);
                    }
                }else{
                    $this->SetFillColor(255, 255, 255);
                }

                $w=$this->widths[$i];
                $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                //Save the current position
                $x=$this->GetX();
                $y=$this->GetY();
                //Draw the border
                $this->Rect($x, $y, $w, $h, 'DF');

                //Número de renglones de separación arriba y abajo, se resta la altura
                //total menos la altura del texto, se divide entre dos (obtener altura de
                //arriba y de abajo) y esto entre 5 para obtener el número de renglones
                //según la altura del renglón, y así anexar dichos renglones extra al texto
                $nr = (($h-($sh[$i]*5))/2)/5;
                for ($j=0; $j < $nr; $j++){ 
                    $data[$i]="\n".$data[$i]."\n";
                }
                
                //Print the text
                $this->MultiCell($w,5,$data[$i],0,$a, $fill);
                //Put the position to the right of the cell
                $this->SetXY($x+$w,$y);
            }
            //Go to the next line
            $this->Ln($h);
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

        var $B=0;
        var $I=0;
        var $U=0;
        var $HREF='';
        var $ALIGN='';

        function WriteHTML($html)
        {
            //HTML parser
            $html=str_replace("\n", ' ', $html);
            $a=preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
            foreach($a as $i=>$e)
            {
                if($i%2==0)
                {
                    //Text
                    if($this->HREF)
                        $this->PutLink($this->HREF, $e);
                    elseif($this->ALIGN == 'center')
                        $this->Cell(0, 5, $e, 0, 1, 'C');
                    else
                        $this->Write(5, $e);
                }
                else
                {
                    //Tag
                    if($e{0}=='/')
                        $this->CloseTag(strtoupper(substr($e, 1)));
                    else
                    {
                        //Extract properties
                        $a2=split(' ', $e);
                        $tag=strtoupper(array_shift($a2));
                        $prop=array();
                        foreach($a2 as $v)
                            if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$', $v, $a3))
                                $prop[strtoupper($a3[1])]=$a3[2];
                        $this->OpenTag($tag, $prop);
                    }
                }
            }
        }

        function OpenTag($tag, $prop)
        {
            //Opening tag
            if($tag=='B' or $tag=='I' or $tag=='U')
                $this->SetStyle($tag, true);
            if($tag=='A')
                $this->HREF=$prop['HREF'];
            if($tag=='BR')
                $this->Ln(5);
            if($tag=='P')
                $this->ALIGN=$prop['ALIGN'];
            if($tag=='HR')
            {
                if( $prop['WIDTH'] != '' )
                    $Width = $prop['WIDTH'];
                else
                    $Width = $this->w - $this->lMargin-$this->rMargin;
                $this->Ln(2);
                $x = $this->GetX();
                $y = $this->GetY();
                $this->SetLineWidth(0.4);
                $this->Line($x, $y, $x+$Width, $y);
                $this->SetLineWidth(0.2);
                $this->Ln(2);
            }
        }

        function CloseTag($tag)
        {
            //Closing tag
            if($tag=='B' or $tag=='I' or $tag=='U')
                $this->SetStyle($tag, false);
            if($tag=='A')
                $this->HREF='';
            if($tag=='P')
                $this->ALIGN='';
        }

        function SetStyle($tag, $enable)
        {
            //Modify style and select corresponding font
            $this->$tag+=($enable ? 1 : -1);
            $style='';
            foreach(array('B', 'I', 'U') as $s)
                if($this->$s>0)
                    $style.=$s;
            $this->SetFont('', $style);
        }

        function PutLink($URL, $txt)
        {
            //Put a hyperlink
            $this->SetTextColor(0, 0, 255);
            $this->SetStyle('U', true);
            $this->Write(5, $txt, $URL);
            $this->SetStyle('U', false);
            $this->SetTextColor(0);
        }

        var $angle=0;

        function Rotate($angle,$x=-1,$y=-1)
        {
            if($x==-1)
                $x=$this->x;
            if($y==-1)
                $y=$this->y;
            if($this->angle!=0)
                $this->_out('Q');
            $this->angle=$angle;
            if($angle!=0)
            {
                $angle*=M_PI/180;
                $c=cos($angle);
                $s=sin($angle);
                $cx=$x*$this->k;
                $cy=($this->h-$y)*$this->k;
                $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
            }
        }

    }

    $pdf = new PDF();
    $pdf->SetDrawColor(0);

    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try   
    {
        $sql='SELECT ordenestbl.id, ordenestbl.ot, ordenestbl.signatario, ordenestbl.plantaidfk, ordenestbl.clienteidfk, ordenestbl.atencion
                FROM  ordenestbl
                INNER JOIN estudiostbl ON ordenestbl.id = estudiostbl.ordenidfk';
        if(isset($_GET['ot']) AND isset($_GET['id'])){
            $where=' WHERE estudiostbl.nombre="Iluminacion" AND ordenestbl.ot = :ot AND ordenestbl.id = :id';
            $s=$pdo->prepare($sql.$where);
            $s->bindValue(':ot', $_GET['ot']);
            $s->bindValue(':id', $_GET['id']);
            $s->execute();
            $orden = $s->fetch();
        }else{
            $where=' WHERE estudiostbl.nombre="Iluminacion" AND ordenestbl.ot = :ot';
            $s=$pdo->prepare($sql.$where);
            $s->bindValue(':ot', /*$_POST['ot']*/ 'LO002');
            $s->execute();
            $orden = $s->fetch();
        }

        $sql='SELECT puntostbl.*, puntorecilumtbl.*, equipostbl.marca, equipostbl.modelo, equipostbl.serie, equipostbl.correccion
            FROM  puntostbl
            INNER JOIN puntorecilumtbl ON puntostbl.id = puntorecilumtbl.puntoidfk
            INNER JOIN recsilumtbl ON puntorecilumtbl.recilumidfk = recsilumtbl.id
            INNER JOIN ordenestbl ON recsilumtbl.ordenidfk = ordenestbl.id
            INNER JOIN equipostbl ON puntorecilumtbl.equiposidfk = equipostbl.id
            WHERE ordenestbl.ot = :ot';
        $s=$pdo->prepare($sql);
        $s->bindValue(':ot', 'LO002');
        $s->execute();
        $puntos = $s->fetchAll();

        //echo "<pre>";
        //var_dump($orden);
        //echo "<br><br>";

        if($orden['plantaidfk'] !== NULL){
            $sql='SELECT plantastbl.razonsocial, plantastbl.calle, plantastbl.colonia, plantastbl.ciudad, 
                    plantastbl.estado, plantastbl.cp, plantastbl.planta
                FROM plantastbl
                WHERE plantastbl.id = :id';
            $s=$pdo->prepare($sql);
            $s->bindValue(':id', $orden['plantaidfk']);
            $s->execute();
            $resultado = $s->fetch();

            $cliente = array('Razon_Social' => $resultado['razonsocial'],
                            'Calle_Numero' => $resultado['calle'],
                            'Colonia' => $resultado['colonia'],
                            'Ciudad' => $resultado['ciudad'],
                            'Estado' => $resultado['estado'],
                            'Planta' => $resultado['planta']);
        }else{
            $sql='SELECT clientestbl.Razon_Social, clientestbl.Calle_Numero, clientestbl.Colonia, clientestbl.Ciudad, 
                    clientestbl.Estado, clientestbl.Giro_Empresa, clientestbl.Codigo_Postal
                FROM clientestbl
                WHERE clientestbl.Numero_Cliente = :id';
            $s=$pdo->prepare($sql);
            $s->bindValue(':id', $orden['clienteidfk']);
            $s->execute();
            $cliente = $s->fetch();
        }

        //var_dump($cliente);
        //echo "<br><br>";

        $sql='SELECT *, deptostbl.id AS "deptoid"
            FROM recsilumtbl
            INNER JOIN deptorecilumtbl ON recsilumtbl.id = deptorecilumtbl.recilumidfk
            INNER JOIN deptostbl ON deptorecilumtbl.deptoidfk = deptostbl.id
            WHERE ordenidfk = :id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $orden['id']);
        $s->execute();
        $recinis = $s->fetchAll();

        //var_dump($recini);
        //echo "<br><br>";

        foreach ($recinis as $key => $value) {
            $sql='SELECT * FROM descripuestostbl
                WHERE deptoidfk = :id';
            $s=$pdo->prepare($sql);
            $s->bindValue(':id', $value['deptoid']);
            $s->execute();
            $puestos[] = $s->fetchAll();

        //var_dump($puestos);
        //echo "<br><br>";
        }
        //echo "</pre>";
        
    }
    catch (PDOException $e)
    {
        /*$mensaje='Error al tratar de obtener información de la orden.'.$e;
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
        exit();*/
    }



/**************************************************************************************************/
/********************************************* Hoja 0 *********************************************/
/**************************************************************************************************/
foreach ($recinis as $key => $recini) {
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.2);

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
    $pdf->Cell(30, 5, utf8_decode($recini['fecha']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(20, 5, utf8_decode('OT No.'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(20, 5, utf8_decode($orden['ot']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(20, 5, utf8_decode('Hoja'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(15, 5, utf8_decode(($key+1)), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(20, 5, utf8_decode('De'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(15, 5, utf8_decode(count($recinis)), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Compañía'), 1, 0, 'L', true);

    blanco($pdf, 8 ,'B');
    $pdf->Cell(0, 6, utf8_decode($cliente['Razon_Social']), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Planta'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode(isset($cliente['Planta']) ? $cliente['Planta'] : ''), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Lugar'), 1, 0, 'L', true);

    blanco($pdf, 6);
    $pdf->Cell(39.5, 6, utf8_decode($cliente['Ciudad'].' '.$cliente['Estado']), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Departamento'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode($recini['departamento']), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Area'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode($recini['area']), 1, 1, 'L', true);

    azul($pdf);
    $pdf->Cell(0, 6, utf8_decode('Descripción de las instalaciones'), 0, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Largo (mt)'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode($recini['largo']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Ancho (mt)'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode($recini['ancho']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Alto (mt)'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode($recini['alto']), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Color de techo'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode($recini['techocolor']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Color de paredes'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode($recini['paredcolor']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 6, utf8_decode('Color de piso'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(25, 6, utf8_decode($recini['pisocolor']), 1, 1, 'C', true);

    azul($pdf);
    $pdf->Cell(0, 6, utf8_decode('Descripción de las lámparas'), 0, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Tipo de lámparas'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode($recini['tipolampara']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Potencia de las lámparas'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode($recini['potencialamp']), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('No de lámparas'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode($recini['numlamp']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Altura (mt)'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode($recini['alturalamp']), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Programa de mantenimiento'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode($recini['mantenimiento']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(43, 6, utf8_decode('Tipo de Iluminación'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(39.5, 6, utf8_decode(($recini['influencia'] === 0) ? 'Natural y artificial' : 'Artificial'), 1, 1, 'C', true);

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

    foreach ($puestos[$key] as $key => $puesto) {
        $pdf->Row(array(utf8_decode($puesto['puesto']),
                    utf8_decode($puesto['numtrabajadores']),
                    utf8_decode($puesto['actividades'])));
    }

    azul($pdf);
    $pdf->Cell(0, 6, utf8_decode('Descripción general del proceso de producción en el departamento'), 0, 1, 'C', true);

    blanco($pdf, 9);
    $pdf->MultiCell(0, 7, utf8_decode($recini['descriproceso']), 1, 'C', true);

    azul($pdf);
    $pdf->Cell(0, 6, utf8_decode('Percepción de las condiciones de iluminación por parte del trabajador'), 0, 1, 'C', true);

    blanco($pdf, 9);
    $pdf->MultiCell(0, 7, utf8_decode($recini['percepcion']), 1, 'C', true);

    $pdf->Ln(5);
    $pdf->Cell(60, 6, utf8_decode('Nombre y firma del reponsable'), 0, 1, 'C');

    $pdf->Cell(0, 5, '', 0, 1);
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'U', 8);
    $pdf->Cell(60, 5, utf8_decode('                                                                        '), 0, 0, 'C');
    $pdf->Cell(45, 5, '', 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(60, 4, utf8_decode($orden['signatario']), 0, 1, 'C');
    $pdf->Cell(60, 4, utf8_decode('Signatario Autorizado'), 0, 0, 'C');
}
/**************************************************************************************************/
/********************************************* Hoja 1 *********************************************/
/**************************************************************************************************/
    foreach($puntos as $punto){
        $deptos[] = $punto['departamento'];
    }
    $deptos = array_unique($deptos);

    $verde = array();
    $rojo = array();
    $verde1 = array(0, 0, 0);
    $rojo1 = array(0, 0, 0);
    $listado = array();
    $i = 0;
    foreach ($deptos as $numdepto => $depto) {
        foreach ($puntos as $numpunto => $punto) {
            if($punto['departamento'] === $depto){
                try   
                {
                    $sql="SELECT *, DATE_FORMAT(hora, '%H:%i') as 'hora' FROM medsilumtbl
                        WHERE puntoidfk = :id";
                    $s=$pdo->prepare($sql);
                    $s->bindValue(':id', $punto['id']);
                    $s->execute();
                    $mediciones = $s->fetchAll();

                    $listado[$i] = array('medicion' => $punto['medicion'],
                                        'fecha' => $punto['fecha'],
                                        'area' => $punto['area'],
                                        'ubicacion' => $punto['ubicacion'],
                                        'identificacion' => $punto['identificacion'],
                                        'departamento' => $punto['departamento'],
                                        '0' => array('ni' => '--- ± ---',
                                                    'nimr' => $punto['nirm'],
                                                    'reflexpared' => 'No Aplica',
                                                    'reflexplano' => 'No Aplica'),
                                        '1' => array('ni' => '--- ± ---',
                                                    'nimr' => $punto['nirm'],
                                                    'reflexpared' => 'No Aplica',
                                                    'reflexplano' => 'No Aplica'),
                                        '2' => array('ni' => '--- ± ---',
                                                    'nimr' => $punto['nirm'],
                                                    'reflexpared' => 'No Aplica',
                                                    'reflexplano' => 'No Aplica'),
                                        );

                    $correccion = json_decode($punto['correccion'], TRUE);
                    //var_dump($correccion);
                    foreach ($mediciones as $key => $medicion) {
                        foreach ($correccion as $num => $rango) {
                            if($num < count($correccion)-1){
                                if($medicion['e2plano'] >= $correccion[$num]['Rango'] AND $medicion['e2plano'] < $correccion[$num+1]['Rango']){
                                    $factor1 = $correccion[$num]['Correccion1'];
                                    $factor2 = $correccion[$num]['Correccion2'];
                                    break;
                                }
                            }elseif($num === count($correccion)-1){
                                $factor1 = $rango['Correccion1'];
                                $factor2 = $rango['Correccion2'];
                                break;
                            }
                        }

                        $medplanocorregida = (intval($medicion['e2plano']) !== 0) ? round(floatval($medicion['e2plano']) * floatval($factor1) + floatval($factor2)) : '---';

                        $incertidumbreplano = ($medplanocorregida !== '---') ? round($medplanocorregida * 0.107632, 0) : '---';

                        $reflexplano = (intval($medicion['e1plano']) !== 0) ? (round(floatval($medicion['e1plano']) * floatval($factor1) + floatval($factor2)) / $medplanocorregida) * 100 : 'No Aplica';

                        $medparedcorregida = (intval($medicion['e2pared']) !== 0) ? round(floatval($medicion['e2pared']) * floatval($factor1) + floatval($factor2)) : '---';
                        $incertidumbrepared = ($medparedcorregida !== '---') ? round($medparedcorregida * 0.107632, 0) : '---';

                        $reflexpared = (intval($medicion['e1pared']) !== 0) ?  (round(floatval($medicion['e1pared']) * floatval($factor1) + floatval($factor2)) / $medparedcorregida) * 100 : 'No Aplica';

                        $listado[$i][$key]['ni'] = $medplanocorregida.' ± '.$incertidumbreplano;
                        $listado[$i][$key]['reflexpared'] = $reflexpared;
                        $listado[$i][$key]['reflexplano'] = $reflexplano;

                        $verde[$numpunto][$key] = 0;
                        $rojo[$numpunto][$key] = 0;
                        if($medplanocorregida >= $punto['nirm']){
                            $verde[$numpunto][$key]++;
                            $verde1[$key]++;
                        }else{
                            $rojo[$numpunto][$key]++;
                            $rojo1[$key]++;
                        }
                    }
                }catch (PDOException $e){
                    /*$mensaje='Error al tratar de obtener información de la orden.'.$e;
                    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
                    exit();*/
                }

                $i++;
            }
        }
    }

    echo '<pre>';
    /*var_dump($verde);
    echo '<br>';
    var_dump($rojo);*/
    var_dump($verde1);
    echo '<br>';
    var_dump($rojo1);
    echo '</pre>';

    $pdf->AddPage('L');
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    //$pdf->Rotate(90, 150, 145);

    $pdf->Ln(20.5);

    headerTablaListado1($pdf);

    azul($pdf);
    $pdf->Cell(20, 6, utf8_decode('Compañía'), 1, 0, 'L', true);

    gris($pdf, 'B', 8);
    $pdf->Cell(120, 6, utf8_decode($cliente['Razon_Social']), 1, 1, 'L', true);

    azul($pdf);
    $pdf->Cell(20, 6, utf8_decode('Planta'), 1, 0, 'L', true);

    gris($pdf, 'B', 8);
    $pdf->Cell(120, 6, utf8_decode(isset($cliente['Planta']) ? $cliente['Planta'] : ''), 1, 1, 'L', true);

    azul($pdf);
    $pdf->Cell(20, 6, utf8_decode('Lugar'), 1, 0, 'L', true);

    $x=$pdf->GetX();
    $y=$pdf->GetY();

    gris($pdf, 'B', 8);
    $pdf->Cell(120, 6, utf8_decode($cliente['Ciudad'].' '.$cliente['Estado']), 1, 1, 'L', true);
    $pdf->Ln(2);

    $pdf->SetXY($x,$y+4);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(205, 3, utf8_decode('AIR-F-2'), 0, 1, 'R');

    headerTablaListado2($pdf);

    $j = 0;
    $pag = 0;
    foreach ($listado as $key => $value) {
        if( ( ($j === 15 OR $j === 16) AND $pag === 0) OR ( ($j % 20 === 0 OR $j % 21 === 0) AND $pag === 1) ){
            $pdf->AddPage('L');
            $pdf->SetMargins(20, 0, 25);
            $pdf->SetLineWidth(.1);

            //$pdf->Rotate(90, 150, 145);

            $pdf->Ln(20.5);

            headerTablaListado1($pdf);
            headerTablaListado2($pdf);
            $pag = 1;
            $j = 1;
        }
        if( ( ($j !== 15 AND $j !== 16) AND $pag === 0) OR ( ($j % 20 !== 0 AND $j % 21 !== 0) AND $pag === 1) ){
            if($key !== 0 AND $value['departamento'] === $listado[$key-1]['departamento']){
                medListado($pdf, $value);
                $j++;
            }else{
                deptoListado($pdf, $value);
                $j++;

                medListado($pdf, $value);
                $j++;
            }

            if( ( ($j === 15 OR $j === 16) AND $pag === 0) OR ($key === (count($listado)-1)) ){
                $pdf->Ln(1);

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

                $y=$pdf->GetY();
                if($pag === 0){
                    $pdf->Image("../../img/semaforo 5.png", 20, 160, 70, 17);
                }else{
                    $pdf->Image("../../img/semaforo 5.png", 20, $y+2, 70, 17);
                }
            }
        }
    }

    $data = array(6,3);
    $labels = array("Valores\ndentro de\nnorma\n(%.1f%%)",
                    "Valores\nfuera de\nnorma\n(%.1f%%)");
     
    $graph = new PieGraph(310,200);
    $graph->SetShadow();
     
    $graph->title->Set(utf8_decode("Análisis de resultados 1er Ciclo"));
    $graph->title->SetFont(FF_FONT1,FS_BOLD);
     
    $p1 = new PiePlot3D($data);
    $p1->SetSize(0.5);
    $p1->SetCenter(0.5);
    $p1->ExplodeAll(15);
    $p1->SetStartAngle(60);
    $p1->SetLabels($labels);
    $p1->SetLabelPos(0);
    $p1->value->SetColor('black');
    
    $graph->Add($p1);
    $p1->SetSliceColors(array('green','red'));
    //$graph->Stroke();

    $nombreImagen = '' . uniqid() . '.png';
    // Display the graph
    $graph->Stroke($nombreImagen);

    //Aqui agrego la imagen que acabo de crear con jpgraph
    $pdf->Image($nombreImagen, 90, 142  , 55, 45);

    unlink($nombreImagen);

    $data = array(2,7);
    $labels = array("Valores\ndentro de\nnorma\n(%.1f%%)",
                "Valores\nfuera de\nnorma\n(%.1f%%)");

    $graph = new PieGraph(310,200);
    $graph->SetShadow();
     
    $graph->title->Set(utf8_decode("Análisis de resultados 2do Ciclo"));
    $graph->title->SetFont(FF_FONT1,FS_BOLD);
     
    $p1 = new PiePlot3D($data);
    $p1->SetSize(0.5);
    $p1->SetCenter(0.5);
    $p1->ExplodeAll(15);
    $p1->SetStartAngle(60);
    $p1->SetLabels($labels);
    $p1->SetLabelPos(0);
    $p1->value->SetColor('black');
    
    $graph->Add($p1);
    $p1->SetSliceColors(array('green','red'));
    //$graph->Stroke();

    $nombreImagen = '' . uniqid() . '.png';
    // Display the graph
    $graph->Stroke($nombreImagen);

    //Aqui agrego la imagen que acabo de crear con jpgraph
    $pdf->Image($nombreImagen, 145, 142, 55, 45);

    unlink($nombreImagen);

    $data = array(0,9);
    $labels = array("Valores\ndentro de\nnorma\n(%.1f%%)",
                "Valores\nfuera de\nnorma\n(%.1f%%)");

    $graph = new PieGraph(310,200);
    $graph->SetShadow();
     
    $graph->title->Set(utf8_decode("Análisis de resultados 3er Ciclo"));
    $graph->title->SetFont(FF_FONT1,FS_BOLD);
     
    $p1 = new PiePlot3D($data);
    $p1->SetSize(0.5);
    $p1->SetCenter(0.5);
    $p1->ExplodeAll(15);
    $p1->SetStartAngle(60);
    $p1->SetLabels($labels);
    $p1->SetLabelPos(0);
    $p1->value->SetColor('black');
    
    $graph->Add($p1);
    $p1->SetSliceColors(array('green','red'));
    //$graph->Stroke();

    $nombreImagen = '' . uniqid() . '.png';
    // Display the graph
    $graph->Stroke($nombreImagen);

    //Aqui agrego la imagen que acabo de crear con jpgraph
    $pdf->Image($nombreImagen, 195, 142, 55, 45);

    unlink($nombreImagen);
    $pdf->Rotate(0);

/**************************************************************************************************/
/********************************************* Hoja 2 *********************************************/
/**************************************************************************************************/
foreach ($puntos as $key => $punto) {

    try   
    {
        $sql="SELECT *, DATE_FORMAT(hora, '%H:%i') as 'hora' FROM medsilumtbl
            WHERE puntoidfk = :id";
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $punto['id']);
        $s->execute();
        $mediciones = $s->fetchAll();

        /*echo "<pre>";
        var_dump($mediciones);
        echo "<br><br>";
        echo "</pre>";*/
        
    }catch (PDOException $e){
        /*$mensaje='Error al tratar de obtener información de la orden.'.$e;
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
        exit();*/
    }

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
    $pdf->Cell(30, 6, utf8_decode($punto['fecha']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(20, 6, utf8_decode('O.T.'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(0, 6, utf8_decode($orden['ot']), 1, 1, 'C', true);

    blanco($pdf, 8, 'B');
    $pdf->Cell(0, 8, utf8_decode('DATOS DE LA EMPRESA'), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Compañía'), 1, 0, 'L', true);

    blanco($pdf, 8 ,'B');
    $pdf->Cell(0, 7, utf8_decode($cliente['Razon_Social']), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Domicilio'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(0, 7, utf8_decode($cliente['Calle_Numero'].' '.$cliente['Colonia']), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Representante'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(0, 7, utf8_decode($orden['atencion']), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Planta'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(50, 7, utf8_decode(isset($cliente['Planta']) ? $cliente['Planta'] : ''), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Lugar'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(0, 7, utf8_decode($cliente['Ciudad'].' '.$cliente['Estado']), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Departamento'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(50, 7, utf8_decode($punto['departamento']), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Área'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(0, 7, utf8_decode($punto['area']), 1, 1, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Identificación'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(50, 7, utf8_decode($punto['identificacion']), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Ubicación'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(0, 7, utf8_decode($punto['ubicacion']), 1, 1, 'L', true);

    blanco($pdf, 8, 'B');
    $pdf->Cell(0, 8, utf8_decode('DATOS DEL LABORATORIO DE PRUEBAS'), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(25, 8, utf8_decode('Razón Social'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->Cell(60, 8, utf8_decode('Laboratorio del Grupo Microanalisis, S.A. de C.V.'), 1, 0, 'L', true);

    gris($pdf);
    $pdf->Cell(25, 8, utf8_decode('Acred. EMA'), 1, 0, 'L', true);

    blanco($pdf, 8);
    $pdf->MultiCell(0, 3.5, utf8_decode("Al-0102-015/12 \n Vigencia a partir del 2012-08-10"), 1, 'L', true);

    blanco($pdf, 8, 'B');
    $pdf->Cell(0, 8, utf8_decode('DATOS DEL EQUIPO DE MEDICIÓN'), 1, 1, 'C', true);

    gris($pdf);
    $pdf->Cell(25, 7, utf8_decode('Marca'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(30, 7, utf8_decode($punto['marca']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(30, 7, utf8_decode('Modelo'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(30, 7, utf8_decode($punto['modelo']), 1, 0, 'C', true);

    gris($pdf);
    $pdf->Cell(20, 7, utf8_decode('No. de Serie'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(0, 7, utf8_decode($punto['serie']), 1, 1, 'C', true);
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

    $datay1 = array();
    foreach ($mediciones as $key => $medicion) {
        $correccion = json_decode($punto['correccion'], TRUE);
        //var_dump($correccion);

        foreach ($correccion as $num => $rango) {
            if($num < count($correccion)-1){
                if($medicion['e2plano'] >= $correccion[$num]['Rango'] AND $medicion['e2plano'] < $correccion[$num+1]['Rango']){
                    $factor1 = $correccion[$num]['Correccion1'];
                    $factor2 = $correccion[$num]['Correccion2'];
                    break;
                }
            }elseif($num === count($correccion)-1){
                $factor1 = $rango['Correccion1'];
                $factor2 = $rango['Correccion2'];
                break;
            }
        }

        $medplanocorregida = (intval($medicion['e2plano']) !== 0) ? round(floatval($medicion['e2plano']) * floatval($factor1) + floatval($factor2)) : '---';
        $datay1[] = $medplanocorregida;
        $incertidumbreplano = ($medplanocorregida !== '---') ? round($medplanocorregida * 0.107632, 0) : '---';

        $reflexplano = (intval($medicion['e1plano']) !== 0) ? (round(floatval($medicion['e1plano']) * floatval($factor1) + floatval($factor2)) / $medplanocorregida) * 100 : 'No Aplica';

        $medparedcorregida = (intval($medicion['e2pared']) !== 0) ? round(floatval($medicion['e2pared']) * floatval($factor1) + floatval($factor2)) : '---';
        $incertidumbrepared = ($medparedcorregida !== '---') ? round($medparedcorregida * 0.107632, 0) : '---';

        $reflexpared = (intval($medicion['e1pared']) !== 0) ?  (round(floatval($medicion['e1pared']) * floatval($factor1) + floatval($factor2)) / $medparedcorregida) * 100 : 'No Aplica';
        
        $pdf->RowColor2(array(utf8_decode($medicion['hora']),
                        utf8_decode($medplanocorregida.' ± '.$incertidumbreplano),
                        utf8_decode($punto['nirm']),
                        utf8_decode($reflexplano),
                        utf8_decode($medparedcorregida.' ± '.$incertidumbrepared),
                        utf8_decode($reflexpared)
                    )
                );
    }

    $pdf->Ln(3);
 
    $graph = new Graph(500,250,'auto');    
    $graph->SetScale("textlin");
    $graph->SetShadow();
    $graph->img->SetMargin(30,15,15,30);
    $graph->xaxis->SetTickLabels(array('1','2','3'));
     
    $bplot1 = new BarPlot($datay1);
    $bplot1->SetShadow();

    // Setup color for gradient fill style 
    $bplot1->SetFillGradient("navy","lightsteelblue",GRAD_HOR);
         
    // Set color for the frame of each bar
    $bplot1->SetColor("navy");
    $graph->Add($bplot1);
     
     $nombreImagen = '' . uniqid() . '.png';
    // Display the graph
    $graph->Stroke($nombreImagen);

    //Aqui agrego la imagen que acabo de crear con jpgraph
    $pdf->Image($nombreImagen, 20, 182, 90, 60);

    unlink($nombreImagen);

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
}

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
    $pdf->MultiCell(0, 5, utf8_decode("      La iluminación industrial es uno de los principales factores ambientales de carácter microclimático, que tiene como principal finalidad facilitar la visualización de las cosas dentro del contexto espacial, de modo que el trabajo se pueda realizar en unas condiciones aceptables de eficacia, comodidad y seguridad."), 0, 'J');
    $pdf->Ln();
    $pdf->MultiCell(0, 5, utf8_decode("      Si se consiguen estos objetivos, las consecuencias no sólo repercuten favorablemente sobre las personas, reduciendo la fatiga, la tasa de errores y accidentes, sino que además contribuyen a aumentar la cantidad y calidad de trabajo."), 0, 'J');
    $pdf->Ln();
    $pdf->MultiCell(0, 5, $pdf->WriteHTML(utf8_decode("      En el presente reporte se establecen los niveles de iluminación en las diferentes áreas de la empresa <b>ALCOCER AGUILAR JOSÉ MANUEL, en la planta ACEROS LAMASI MATRÍZ</b>, ubicada en <b>Chetumal, Quintana Roo</b>, para control interno, a través de la medición directa de los mismos y su correlación con los niveles mínimos recomendados establecidos en la NOM-025-STPS-2008.")), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('1. JUSTIFICACIÓN'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("      Según el art. 95 del Reglamento Federal de Seguridad, Higiene y Medio Ambiente, las áreas,  planos y lugares de trabajo, deberán contar con las condiciones y niveles de iluminación adecuadas al tipo de actividad que se realice."), 0, 'J');
    $pdf->Ln();
    $pdf->MultiCell(0, 5, utf8_decode("      De acuerdo a lo anterior, se debe efectuar el reconocimiento, evaluación y control de los niveles de iluminación en el centro de trabajo,  según lo establecido en los numerales 8, 9 y 10 de la NOM-025-STPS-2008."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('2. OBJETIVO'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('2.1 General'), 0, 1, 'L');

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("      Evaluar los niveles de iluminación y establecer sus características de forma que este no sea un factor de riesgo para los trabajadores en el centro de trabajo."), 0, 'J');
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
    $pdf->MultiCell(0, 5, utf8_decode("      A continuación se describen las actividades realizadas para la evaluación de las condiciones de iluminación:"), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('3.1  Reconocimiento inicial'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("      Tal y como se establece en la norma vigente, el propósito del reconocimiento es determinar las áreas y puestos de trabajo que cuenten con una deficiente iluminación o que presenten deslumbramiento, para lo cual se deben considerar los reportes de los trabajadores y realizar un recorrido por todas las áreas del centro de trabajo en donde existan trabajadores, así como, recabar la información técnica y administrativa que permita seleccionar las áreas y puestos de trabajo por evaluar."), 0, 'J');
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
    $pdf->MultiCell(0, 5, utf8_decode("      Los puntos de medición fueron seleccionados en función de las necesidades y características de cada centro de trabajo, de tal manera que se describiera el entorno ambiental de la iluminación de una forma confiable, considerando el proceso de producción, la ubicación de las luminarias, de las áreas y puestos de trabajo y la posición de la maquinaria y equipo, según lo establece la norma de referencia, en el numeral A.2.3.1."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('3.3 Instrumentación'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(115, 5, utf8_decode("Luminómetro marca International Light, modelo ILT1400, con corrección cosenoidal y desviación máxima de su detector de +/- 5% de su respuesta espectral fotópica y con exactitud de +/- 5%,  No. de Serie ILT14000655."), 0, 'J');
    $pdf->Ln();

    $pdf->Image("../../img/luminometro.png", 140, 190, 40, 40);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('4. EVALUACIÓN'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("      La evaluación de los niveles de iluminación se llevó a cabo conforme lo establece el apéndice A de  la NOM-025-STPS-2008."), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("      A partir de los registros del reconocimiento se llevó a cabo la evaluación de los niveles de iluminación en las áreas y puestos de trabajo bajo los siguientes criterios:"), 0, 'J');
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
    $pdf->MultiCell(0, 5, utf8_decode("      Se elaboró un reporte con la información proporcionada por el cliente (Anexo 1) conforme lo marca el punto 12 de la NOM-025-STPS-2008, los datos obtenidos durante la evaluación y la siguiente información:"), 0, 'J');
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
    $pdf->Cell(0, 3, utf8_decode('Página No. 6 de 10'), 0, 1, 'R');
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
    $pdf->MultiCell(0, 5, utf8_decode("      El número de zonas a evaluar se obtuvo conforme lo dice la norma con la tabla indicada en ella y la siguiente fórmula:"), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 8);
    $pdf->Image("../../img/formula ic.png", 30, 225, 30, 15);
    $pdf->Cell(50, 5, '', 0, 0, 'R');
    $pdf->MultiCell(0, 4, utf8_decode("Donde: \n IC   :  Índice de Área \n x,y  :  dimensiones del área (largo y ancho) en metros \n h     :  Altura de la luminaria respecto al plano de trabajo,  en  metros"), 0, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("El factor de reflexión se obtiene con la siguiente fórmula"), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 8);
    $pdf->Image("../../img/formula k.png", 30, 255, 30, 15);
    $pdf->Cell(50, 5, '', 0, 0, 'R');
    $pdf->MultiCell(0, 4, utf8_decode("Donde:\nKf   :  Factor de reflexión\nE1  :  Nivel de Iluminación reflejada\nE2  :  Nivel de iluminación incidente"), 0, 'L');
    $pdf->Ln();

/**************************************************************************************************/
/********************************************* Hoja 9 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(43);
    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-2', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 7 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln();

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('5. REFERENCIAS PARA LA EVALUACIÓN'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("Con el propósito de evaluar los resultados de las mediciones practicadas a continuación se proporcionan los niveles mínimos de iluminación recomendados por la norma de referencia."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFillColor(116, 144, 119);
    $pdf->SetTextColor(255);
    $pdf->SetWidths(array(70, 60, 35));
    $pdf->SetFonts(array('B'));
    $pdf->SetFontSizes(array(9));
    $pdf->SetAligns(array('C','C','C'));
    $pdf->Row(array(utf8_decode('TAREA VISUAL DEL PUESTO DE TRABAJO'),
                utf8_decode('AREA DE TRABAJO'),
                utf8_decode('NIVELES MÍNIMOS DE ILUMINACIÓN EN LUX')
            )
        );

    $valores = array(
                    array('Tarea' => 'En exteriores: distinguir el área de tránsito, desplazarse caminando, vigilancia, movimiento de vehículo',
                        'Area' => 'Áreas generales, exteriores, patios y estacionamientos',
                        'Niveles' => '20'),
                    array('Tarea' => 'En interiores distinguir el área de tránsito, desplazarse caminando, vigilancia, movimiento de vehículos',
                        'Area' => 'Áreas generales interiores: Almacenes de poco movimiento, pasillos, escaleras, estacionamientos cubiertos, labores en minas, iluminación de emergencia.',
                        'Niveles' => '50'),
                    array('Tarea' => 'En interiores ',
                        'Area' => 'Áreas de circulación y pasillos; salas de espera; salas de descanso:;  cuartos de almacén;  plataformas; cuartos de calderas',
                        'Niveles' => '100'),
                    array('Tarea' => 'Requerimiento visual simple: Inspección visual, recuento de piezas, trabajo en banco y máquina',
                        'Area' => 'Áreas de servicios al personal: Almacenaje rudo, recepción y despacho, casetas de vigilancia, cuartos de compresores y pailería',
                        'Niveles' => '200'),
                    array('Tarea' => 'Distinción moderada de detalles: Ensamble simple, trabajo medio en banco y máquina, inspección simple, empaque y trabajo de oficina',
                        'Area' => 'Áreas de servicios al personal: Almacenaje rudo, recepción y despacho, casetas de vigilancia, cuartos de compresores y pailería',
                        'Niveles' => '300'),
                    array('Tarea' => 'Distinción clara de detalles: maquinado y acabados delicados, ensamble inspección moderadamente difícil, captura y procesamiento de información, manejo de instrumentos y equipos de laboratorio',
                        'Area' => 'Talleres de precisión, salas de cómputo, áreas de dibujo, laboratorios',
                        'Niveles' => '500'),
                    array('Tarea' => 'Distinción fina de detalles: Maquinado de precisión, ensamble e inspección de trabajos delicados, manejo de instrumentos y equipo de precisión, manejo de piezas pequeñas',
                        'Area' => 'Talleres de alta precisión: De pintura y acabado de superficies y laboratorios de control de calidad',
                        'Niveles' => '750'),
                    array('Tarea' => 'Alta exactitud en la distinción de detalles: Ensamble, proceso e inspección de piezas pequeñas y complejas y acabado con pulidos finos',
                        'Area' => 'Áreas de proceso: Ensamble e inspección de piezas complejas y acabados con pulido fino',
                        'Niveles' => '1000'),
                    array('Tarea' => 'Alto grado de especialización en la distinción de detalles ',
                        'Area' => 'Áreas de proceso de gran exactitud',
                        'Niveles' => '2000')
                    );

    $pdf->SetFonts(array(''));
    $pdf->SetAligns(array('J','J','C'));
    $pdf->SetFontSizes(array(8));
    blanco($pdf);
    foreach ($valores as $key => $value) {
        $pdf->noEnterRow(array(utf8_decode($value['Tarea']),
                utf8_decode($value['Area']),
                utf8_decode($value['Niveles'])
            )
        );
    }

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 5, utf8_decode('Tomado de la tabla 1 de la NOM-025-STPS-2008.'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->MultiCell(0, 4, utf8_decode("Las áreas evaluadas fueron comparadas con 50 lux (Pasillos), 200 lux (Requerimiento visual simple) y 300 lux (Distinción moderada de detalles)."), 0, 'L');
    $pdf->Ln();

/**************************************************************************************************/
/********************************************* Hoja 10 ********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(43);
    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-2', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 8 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln();

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 5, utf8_decode('NIVELES MÁXIMOS PERMISIBLES DEL FACTOR DE REFLEXIÓN'), 0, 1, 'C');
    $pdf->Ln();

    $pdf->SetFillColor(116, 144, 119);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(30, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, utf8_decode('CONCEPTO'), 1, 0, 'C', true);
    $pdf->Cell(50, 5, utf8_decode('NIVEL MÁXIMO PERMISIBLE'), 1, 1, 'C', true);

    blanco($pdf, 9, '');
    $pdf->Cell(30, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, utf8_decode('Paredes'), 1, 0, 'L', true);
    $pdf->Cell(50, 5, utf8_decode('60%'), 1, 1, 'L', true);

    $pdf->Cell(30, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, utf8_decode('Planos de trabajo'), 1, 0, 'L', true);
    $pdf->Cell(50, 5, utf8_decode('50%'), 1, 1, 'L', true);
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('6. RESULTADOS'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("      Los resultados que se obtuvieron con el empleo de la estrategia anteriormente descrita se indican en el listado presentado en el anexo 2."), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("      En estos listados y en los planos de identificación de las mediciones se identifica con un código de colores los valores obtenidos, de acuerdo al riesgo que presenta así:"), 0, 'J');
    $pdf->Ln();

    $pdf->Image("../../img/semaforo 5.png", 40, 123, 80, 20);
    $pdf->Ln(20);

    blanco($pdf, 6, 'B');
    $pdf->Cell(35, 3, utf8_decode('N.I.M.R.:'), 0, 0, 'R', true);
    blanco($pdf, 6, '');
    $pdf->Cell(70, 3, utf8_decode('Nivel de Iluminación Mínimo Recomendado (lux)'), 0, 1, 'L', true);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("Los informes de evaluación se presentan en el anexo 3."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('7. DESVIACIONES'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("No se presentó desviación alguna en la medición de las condiciones de iluminación"), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('8. CONCLUSIONES'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("      El estudio llevado a cabo cuyos resultados se expresan en el contenido de este informe,  son consecuencia de la aplicación de las estrategias y procedimientos derivados de las indicaciones contenidas en la regulación."), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("      El propósito de la reglamentación es el de promover que los patrones ejecuten las actividades que como mínimo a su juicio, se requieren para conservar la salud del personal."), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("      Para ello le expresa en primer término la necesidad de que realicen lo necesario para contar con la información que le permita la identificación de sus riesgos y en segundo la evaluación de la probabilidad de daño de los mismos con base en las tolerancias de las normas."), 0, 'J');
    $pdf->Ln();
    $pdf->MultiCell(0, 5, utf8_decode("      Finalmente le instruye sobre el compromiso de promover acciones programadas de control que abatan la magnitud de los riesgos a niveles que conduzcan la conservación de la salud."), 0, 'J');
    $pdf->Ln();



/**************************************************************************************************/
/********************************************* Hoja 11 ********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(43);
    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-2', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 9 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln();

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("      Bajo esa circunstancia las conclusiones y recomendaciones que se expresan en este informe, se han elaborado con base en los preceptos reglamentarios de ejecución e interpretación indicados, sin embargo, con ello al cumplirlos solo se comprueba lo expresado en los ordenamientos y no precisamente lo que pudiera requerirse para garantizar la conservación de la salud de la población laboral que se pretende proteger."), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("      La necesidad de efectuar acciones complementarias adicionales a las indicadas en la normatividad entre otras es debida en primer término por la posible no representatividad de los resultados cuando se lleva a cabo una sola medición, en segundo por la certeza en la aplicación de los valores de tolerancia adoptados para la población expuesta y en tercero por la respuesta especifica individual de cada trabajador bajo control dadas sus particulares condiciones de salud."), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("      Las conclusiones que es posible derivar para el propósito de este estudio, con las reservas que se derivan de lo anteriormente expuesto son:"), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("      Los niveles de iluminación obtenidos en los 45 sitios evaluados, en el primer y segundo ciclo de medición son adecuados, de acuerdo a las tareas y actividades que allí se realizan, dado que se encuentran por arriba de los Niveles de Iluminación Mínimos Recomendados (NIMR), establecidos en la Normatividad Mexicana."), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("      Los niveles de iluminación obtenidos en 1 de los 45 sitios evaluados, en el tercer ciclo de medición, es deficiente, de acuerdo a las tareas y actividades que allí se realizan, dado que se encuentran por debajo de los Niveles de Iluminación Mínimos Recomendados (NIMR), establecidos en la Normatividad Mexicana."), 0, 'J');
    $pdf->Ln();

    $pdf->Cell(0, 5, utf8_decode('El comportamiento general de las mediciones se muestra en el siguiente gráfico:'), 0, 1, 'C');
    $pdf->Ln();

    $pdf->Ln(40);
    $pdf->Ln();

    $pdf->MultiCell(0, 5, $pdf->WriteHTML(utf8_decode("<b>8.1 </b> El factor de reflexión obtenido se encuentra por debajo del nivel máximo recomendado, por lo que se puede concluir que no se presentan deslumbramientos bajo las condiciones en las que se realizó el estudio.")), 0, 'J');
    $pdf->Ln();

/**************************************************************************************************/
/********************************************* Hoja 12 ********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(43);
    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-2', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 10 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln(30);

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('9.  LISTADO DE ANEXOS'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(20, 5, 'Anexo 1:  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Información de reconocimiento'), 0, 'L');
    $pdf->Ln(1);

    $pdf->Cell(20, 5, 'Anexo 2:  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Listado de resultados de las evaluaciones de iluminación'), 0, 'L');
    $pdf->Ln(1);

    $pdf->Cell(20, 5, 'Anexo 3:  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Informes de evaluación'), 0, 'L');
    $pdf->Ln(1);

    $pdf->Cell(20, 5, 'Anexo 4:  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Planos'), 0, 'L');
    $pdf->Ln(1);

    $pdf->Cell(20, 5, 'Anexo 5:  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Certificados de calibración, Acreditación E.M.A., Aprobación y Registro de la S.T.P.S.'), 0, 'L');
    $pdf->Ln(30);

    $pdf->Cell(0, 5, utf8_decode('Acreditación No.: AL-0102-015/2012'), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode('Vigente a partir del 2012-08-10'), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode('Aprobación STPS: LP-STPS-001/13'), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode('Vigente a partir del 2013-04-19'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Ln(30);
    $pdf->Cell(60, 6, utf8_decode('Atentamente:'), 0, 1, 'L');

    $pdf->Cell(0, 5, '', 0, 1);
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'U', 9);
    $pdf->Cell(60, 5, utf8_decode('                                                                        '), 0, 0, 'C');
    $pdf->Cell(45, 5, '', 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(60, 4, utf8_decode('T.S.U. Omar Amador Arellano'), 0, 1, 'C');
    $pdf->Cell(60, 4, utf8_decode('Signatario autorizado por la EMA'), 0, 0, 'C');

    $pdf->Output();
    exit();

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

    function medListado($pdf, $data){
        $pdf->SetWidths(array(10,13,20,20,27,12,9,12,12,12,9,12,12,12,9,12,12));
        $pdf->SetFonts(array(''));
        $pdf->SetFontSizes(array(6));
        $pdf->SetAligns(array('C'));

        $pdf->RowColor(array($data['medicion'],
                            $data['fecha'],
                            utf8_decode($data['departamento']),
                            utf8_decode($data['area']),
                            utf8_decode($data['identificacion']),
                            utf8_decode($data[0]['ni']),
                            $data[0]['nimr'],
                            $data[0]['reflexpared'],
                            $data[0]['reflexplano'],
                            utf8_decode($data[1]['ni']),
                            $data[1]['nimr'],
                            $data[1]['reflexpared'],
                            $data[1]['reflexplano'],
                            utf8_decode($data[2]['ni']),
                            $data[2]['nimr'],
                            $data[2]['reflexpared'],
                            $data[2]['reflexplano']
                        )
                    );
    }

    function deptoListado($pdf, $data){
        blanco($pdf, 8, 'B');
        $pdf->Cell(225, 5, utf8_decode('Departamento: '.$data['departamento']), 1, 1, 'C', true);
    }

    function headerTablaListado1($pdf){
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetTextColor(0, 51, 105);
            $pdf->Cell(238, 3, utf8_decode('LISTADO DE RESULTADOS'), 0, 1, 'C');
            $pdf->Ln(2);
            $pdf->Cell(238, 3, utf8_decode('EVALUACION DE LOS NIVELES DE ILUMINACION'), 0, 1, 'C');
            $pdf->Ln(2);
        }

    function headerTablaListado2($pdf){
            gris($pdf, 'B');
            $x=$pdf->GetX();
            $y=$pdf->GetY();
            $pdf->MultiCell(10, 8.5, utf8_decode('No. Med'), 1, 'C', true);
            $pdf->SetXY($x+10,$y);
            $pdf->Cell(13, 17, utf8_decode('Fecha'), 1, 0, 'C', true);
            $pdf->Cell(20, 17, utf8_decode('Área'), 1, 0, 'C', true);
            $pdf->Cell(20, 17, utf8_decode('Ubicación'), 1, 0, 'C', true);
            $pdf->Cell(27, 17, utf8_decode('Identificación'), 1, 0, 'C', true);

            $x=$pdf->GetX();
            $y=$pdf->GetY();
            $pdf->SetWidths(array(45,12,9,12,12));
            $pdf->SetAligns(array('C'));
            $pdf->SetFonts(array('B'));
            $pdf->SetFontSizes(array(6));
            $pdf->carobsRow(array(utf8_decode('Resultados 1er Ciclo de Medición'),
                                array(utf8_decode('N.I. (Lux)'),
                                    utf8_decode('NIMR (lux)'),
                                      utf8_decode('Reflexión paredes (60%)'),
                                      utf8_decode('Reflexión plano de trabajo (50%)')
                                    )
                                )
                            );

            $pdf->SetXY($x+45,$y);
            $x=$pdf->GetX();
            $y=$pdf->GetY();
            $pdf->carobsRow(array(utf8_decode('Resultados 2do Ciclo de Medición'),
                                array(utf8_decode('N.I. (Lux)'),
                                      utf8_decode('NIMR (lux)'),
                                      utf8_decode('Reflexión paredes (60%)'),
                                      utf8_decode('Reflexión plano de trabajo (50%)')
                                    )
                                )
                            );

            $pdf->SetXY($x+45,$y);
            $pdf->carobsRow(array(utf8_decode('Resultados 3er Ciclo de Medición'),
                                array(utf8_decode('N.I. (Lux)'),
                                      utf8_decode('NIMR (lux)'),
                                      utf8_decode('Reflexión paredes (60%)'),
                                      utf8_decode('Reflexión plano de trabajo (50%)')
                                    )
                                )
                            );
            $pdf->Ln(12);
    }

    ?>