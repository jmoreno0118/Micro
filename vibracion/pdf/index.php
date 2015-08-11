<?php
 /********** Iluminació **********/
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/jpgraph-3.5.0b1/src/jpgraph.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/jpgraph-3.5.0b1/src/jpgraph_pie.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/jpgraph-3.5.0b1/src/jpgraph_pie3d.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/jpgraph-3.5.0b1/src/jpgraph_bar.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/jpgraph-3.5.0b1/src/jpgraph_line.php';

/**************************************************************************************************/
/* Modificaciones al FPDF */
/**************************************************************************************************/

    //include ('fpdf/fpdf.php');
    include ($_SERVER['DOCUMENT_ROOT'].'/reportes/includes/fpdf/fpdf.php');

    class PDF extends FPDF
    {
        function Header()
        {
            $this->Image("../../img/logoyeslogan.gif", 35, 5, 140, 40);
            $this->SetTextColor(69, 147, 56);
            $this->SetFont('Arial', '', 12);
            $this->SetY(45);
            $this->Cell(0, 2, utf8_decode('LABORATORIO DEL GRUPO MICROANALISIS, S.A. DE C.V.'), 0, 1, 'C');
        }

        function Footer()
        {
            $this->SetY(-25);

            $this->SetTextColor(125);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(0, 3, utf8_decode('El presente informe no podrá ser alterado ni reproducido total o parcialmente sin autorización previa por escrito del Laboratorio del Grupo Microanálisis, S.A. de C.V.'), 0, 'C'); //////////// Dirección
            $this->Ln();

            $this->SetTextColor(69, 147, 56);
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(0, 3, utf8_decode('General Sóstenes Rocha No. 28 Col. Magdalena Mixhuca Del. Venustiano Carranza, México D.F. CP 15850'), 0, 1, 'C');
            $this->Ln(1);
            $this->Cell(0, 3, utf8_decode('Tel. 01 (55) 57 68 77 44                E-Mail:ventas@microanalisis.com                Web: www.microanalisis.com'), 0, 1, 'C');
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
        $sql='SELECT ordenestbl.id, ordenestbl.ot, ordenestbl.fechalta, ordenestbl.atencion, ordenestbl.atenciontel,
                    ordenestbl.signatarionombre, ordenestbl.signatarioap, ordenestbl.signatarioam,
                    ordenestbl.plantaidfk, ordenestbl.clienteidfk, ordenestbl.atencion, representantestbl.nombre as "representante"
                FROM  ordenestbl
                INNER JOIN estudiostbl ON ordenestbl.id = estudiostbl.ordenidfk
                INNER JOIN representantestbl ON representantestbl.id = ordenestbl.representanteidfk';
        if(isset($_GET['ot']) AND isset($_GET['id'])){
            $where=' WHERE estudiostbl.nombre="Iluminacion" AND ordenestbl.ot = :ot AND ordenestbl.id = :id';
            $s=$pdo->prepare($sql.$where);
            $s->bindValue(':ot', $_GET['ot']);
            $s->bindValue(':id', $_GET['id']);
            
        }else{
            $where=' WHERE estudiostbl.nombre="Vibraciones mano-brazo" AND ordenestbl.ot = :ot';
            $s=$pdo->prepare($sql.$where);
            $s->bindValue(':ot', /*$_POST['ot']*/ '2591');
        }
        $s->execute();
        $orden = $s->fetch();

        //var_dump($orden);

        $sql='SELECT vib_recstbl.*
            FROM vib_recstbl
            INNER JOIN ordenestbl ON vib_recstbl.ordenidfk = ordenestbl.id
            WHERE ordenestbl.ot = :ot';
        $s=$pdo->prepare($sql);
        if(isset($_GET['ot'])){
            $s->bindValue(':ot', $_GET['ot'] /*'2591'*/);
        }else{
            $s->bindValue(':ot', /*$_POST['ot']*/ '2591');
        }
        $s->execute();
        $recini = $s->fetch();

        //var_dump($recini);

        $sql='SELECT equipos.Marca, equipos.Modelo, equipos.Numero_Serie
            FROM equipos
            WHERE ID_Equipo = :id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $recini['eqvibracionidfk']);
        $s->execute();
        $eqvibracion = $s->fetch();

        $sql='SELECT equipos.Marca, equipos.Modelo, equipos.Numero_Serie
            FROM equipos
            WHERE ID_Equipo = :id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $recini['acelerometroidfk']);
        $s->execute();
        $acelerometro = $s->fetch();

        $sql='SELECT equipos.Marca, equipos.Modelo, equipos.Numero_Serie
            FROM equipos
            WHERE ID_Equipo = :id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $recini['calibradoridfk']);
        $s->execute();
        $calibrador = $s->fetch();

        $sql='SELECT vib_puestostbl.*
            FROM vib_puestostbl
            WHERE vibrecidfk = :id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $recini['id']);
        $s->execute();
        $puestos = $s->fetchAll();
        
        $sql='SELECT vib_producciontbl.*
            FROM vib_producciontbl
            WHERE vibrecidfk = :id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $recini['id']);
        $s->execute();
        $produccion = $s->fetchAll();

        $sql='SELECT vib_poetbl.*
            FROM vib_poetbl
            WHERE vibrecidfk = :id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $recini['id']);
        $s->execute();
        $poes = $s->fetchAll();

        $sql='SELECT vib_idstbl.*
            FROM vib_idstbl
            WHERE vibrecidfk = :id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $recini['id']);
        $s->execute();
        $ids = $s->fetchAll();


        $sql='SELECT puntostbl.*, vib_medstbl.*
            FROM  puntostbl
            INNER JOIN vib_medstbl ON puntostbl.id = vib_medstbl.puntoidfk
            INNER JOIN vib_puntorectbl ON puntostbl.id = vib_puntorectbl.puntoidfk
            INNER JOIN vib_recstbl ON vib_puntorectbl.vibrcidfk = vib_recstbl.id
            INNER JOIN ordenestbl ON vib_recstbl.ordenidfk = ordenestbl.id
            WHERE ordenestbl.ot = :ot';
        $s=$pdo->prepare($sql);
        if(isset($_GET['ot'])){
            $s->bindValue(':ot', $_GET['ot'] /*'2591'*/);
        }else{
            $s->bindValue(':ot', /*$_POST['ot']*/ '2591');
        }
        $s->execute();
        $puntos = $s->fetchAll();

        //var_dump($puntos);

        if($orden['plantaidfk'] !== NULL){
            $sql='SELECT razonsocial, planta, calle, colonia, ciudad, estado, cp, rfc
                FROM plantastbl
                WHERE id = :id';
            $s=$pdo->prepare($sql);
            $s->bindValue(':id', $orden['plantaidfk']);
            $s->execute();
            $resultado = $s->fetch();

            $cliente = array('Razon_Social' => $resultado['razonsocial'],
                            'Planta' => $resultado['planta'],
                            'Calle_Numero' => $resultado['calle'],
                            'Colonia' => $resultado['colonia'],
                            'Ciudad' => $resultado['ciudad'],
                            'Estado' => $resultado['estado'],
                            'Giro_Empresa' => '',
                            'Codigo_Postal' => $resultado['cp'],
                            'RFC' => $resultado['rfc']
                            );

            $sql='SELECT Giro_Empresa
                FROM clientestbl
                WHERE Numero_Cliente = :id';
            $s=$pdo->prepare($sql);
            $s->bindValue(':id', $orden['clienteidfk']);
            $s->execute();
            $giro = $s->fetch();

            $cliente['Giro_Empresa'] = $giro['Giro_Empresa'];

        }else{
            $sql='SELECT Razon_Social, Calle_Numero, Colonia, Ciudad, Estado, Giro_Empresa, Codigo_Postal, RFC
                FROM clientestbl
                WHERE Numero_Cliente = :id';
            $s=$pdo->prepare($sql);
            $s->bindValue(':id', $orden['clienteidfk']);
            $s->execute();
            $cliente = $s->fetch();
        }

        $cliente['atencion'] = $orden['atencion'];
        $cliente['telefono'] = $orden['atenciontel'];
        $cliente['representante'] = $orden['representante'];

        //var_dump($cliente);


    }
    catch (PDOException $e)
    {
        $mensaje='Error al tratar de obtener información de la orden.'.$e;
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
        exit();
    }




/**************************************************************************************************/
/********************************************* Hoja 0 *********************************************/
/**************************************************************************************************/
foreach ($puntos as $punto) {
    $pdf->AddPage();
    $pdf->SetMargins(25, 0, 20);
    $pdf->SetLineWidth(.2);

    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(0, 5, utf8_decode('EVALUACION DE LA EXPOSICION A VIBRACIONES'), 0, 1, 'C');
    $pdf->Cell(0, 5, utf8_decode('VIBRACIONES SEGMENTALES'), 0 ,1 ,'C');

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(0, 3, utf8_decode('AIR-F-6'), 0, 1, 'R');
    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(0, 5, utf8_decode('INFORMACIÓN GENERAL'), 0, 1, 'C');
    $pdf->Ln(2);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Orden de Trabajo'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(40, 5, utf8_decode($orden['ot']), 1, 0, 'C', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('No. de Medición'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(40, 5, utf8_decode($punto['medicion']), 1, 1, 'C', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Fecha'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(40, 5, utf8_decode($punto['fecha']), 1, 0, 'C', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Lugar'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(40, 5, utf8_decode($cliente['Ciudad'].' '.$cliente['Estado']), 1, 1, 'C', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Compañía'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(0, 5, utf8_decode($cliente['Razon_Social']), 1, 1, 'L', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Planta'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(0, 5, utf8_decode(isset($cliente['Planta']) ? $cliente['Planta'] : ''), 1, 1, 'L', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Departamento'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(40, 5, utf8_decode($punto['departamento']), 1, 0, 'C', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Area'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(40, 5, utf8_decode($punto['area']), 1, 1, 'C', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Identificación'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(40, 5, utf8_decode($punto['identificacion']), 1, 0, 'C', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Puesto'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(40, 5, utf8_decode($punto['puesto']), 1, 1, 'C', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Ubicación'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(0, 5, utf8_decode($punto['ubicacion']), 1, 1, 'L', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 8, utf8_decode('Producción/Evento'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(40, 8, utf8_decode('---Romper ladrillos/60 ciclos'), 1, 0, 'C', true);

    $x = $pdf->GetX();
    $y = $pdf->GetY();
    verdeLetraBlanca($pdf);
    $pdf->MultiCell(42.5, 4, utf8_decode('Tiempo de exposición total'), 1, 'L', true);

    $pdf->SetXY($x + 42.5, $y);
    blanco($pdf);
    $pdf->Cell(0, 8, utf8_decode('----120.00 Minutos'), 1, 1, 'L');

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Herramienta'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(0, 5, utf8_decode($punto['herramienta']), 1, 1, 'L', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Instrumento Marca'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(40, 5, utf8_decode($eqvibracion['Marca']), 1, 0, 'C', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Modelo / N.Serie'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(40, 5, utf8_decode($eqvibracion['Modelo'].' / '.$eqvibracion['Numero_Serie']), 1, 1, 'C', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Practicó el estudio'), 1, 0, 'C', true);

    blanco($pdf);
    $pdf->Cell(0, 5, utf8_decode('---Ing. Juan Carlos Sánchez Reyes'), 1, 1, 'L', true);
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(0, 5, utf8_decode('RESULTADOS'), 0, 1, 'C');
    $pdf->Ln(2);

    verdeLetraBlanca($pdf);
    $pdf->SetWidths(array(33, 33, 33, 33, 33));
    $pdf->SetFonts(array('B'));
    $pdf->SetFontSizes(array(9));
    $pdf->SetAligns(array('C','C','C','C','C'));
    $pdf->Row(array(utf8_decode('Medición 1'),utf8_decode('Medición 2'),utf8_decode('Medición 2'),utf8_decode('Promedio'),utf8_decode('Total')));

    azul($pdf);
    $pdf->Row(array(utf8_decode('Aeq1'),utf8_decode('Aeq2'),utf8_decode('Aeq3'),utf8_decode('Aeq Promedio'),utf8_decode('Aeq (8)')));

    $promedio = ($punto['med1'] + $punto['med2'] + $punto['med3'])/3;

    blanco($pdf);
    $pdf->Row(array($punto['med1'], $punto['med2'], $punto['med3'], number_format($promedio, 2), '---1'));
    $pdf->Ln();

    verdeLetraNegra($pdf);
    $pdf->Cell(55, 5, utf8_decode('Tiempo Total de Exposición (hrs)'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(0, 5, utf8_decode('---2.00 Horas'), 1, 1, 'L', true);
    $pdf->Ln();

    verdeLetraNegra($pdf);
    $pdf->Cell(55, 5, utf8_decode('Tipo de Evento'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(0, 5, utf8_decode($punto['tipoevento']), 1, 1, 'L', true);
    $pdf->Ln(2);

    verdeLetraNegra($pdf);
    $pdf->Cell(55, 5, utf8_decode('Valor Máximo Permitido'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(0, 5, utf8_decode('---0 m/seg2'), 1, 1, 'L', true);
    $pdf->Ln(2);

    verdeLetraNegra($pdf);
    $pdf->Cell(42.5, 5, utf8_decode('Conclusión'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(0, 5, utf8_decode('---El valor obtenido Aeq (8hrs), SUPERA el Valor Maximo Permitido'), 1, 1, 'C', true);
    $pdf->Ln(2);

    verdeLetraNegra($pdf);
    $pdf->Cell(42.5, 12, utf8_decode('Vigencia del reporte'), 1, 0, 'L', true);

    blanco($pdf, 7);
    $pdf->MultiCell(0, 4, utf8_decode('---La vigencia del informe de resultados es de 2 años, sujeto a que no se modifiquen las tareas, el area de trabajo, las herramientas o equipos del proceso de tal manera que se puedan incrementar las caracteristicas de las vibraciones o los ciclos de exposicion.'), 1, 'L', true);
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(60, 5, utf8_decode('Nombre y Firma del Responsable'), 0, 0, 'C');
    $pdf->Cell(0, 5, '', 0, 1);
    $pdf->Ln(15);

    $pdf->SetFont('Arial', 'U', 8);
    $pdf->Cell(60, 5, utf8_decode('                                                                        '), 0, 0, 'C');
    $pdf->Ln(4);

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(60, 4, utf8_decode('Ing. Juan Carlos Sánchez Reyes'), 0, 1, 'C');
    $pdf->Cell(60, 4, utf8_decode('Signatario autorizado'), 0, 0, 'C');
}

/**************************************************************************************************/
/********************************************* Hoja 1 *********************************************/
/**************************************************************************************************/
    foreach($puntos as $punto){
        $deptos[] = $punto['departamento'];
    }
    $deptos = array_unique($deptos); 

    $verde = array(0, 0, 0);
    $rojo = array(0, 0, 0);
    $listado = array();
    $i = 0;
    foreach ($deptos as $numdepto => $depto) {
        foreach ($puntos as $numpunto => $punto) {
            if($punto['departamento'] === $depto){
                $promedio = ($punto['med1'] + $punto['med2'] + $punto['med3'])/3;

                $listado[$i] = array('departamento' => $punto['departamento'],
                                    'medicion' => $punto['medicion'],
                                    'fecha' => $punto['fecha'],
                                    'puesto' => $punto['puesto'],
                                    'area' => $punto['area'],
                                    'herramienta' => $punto['herramienta'],
                                    'actividad' => $punto['tipoevento'],
                                    'identificacion' => $punto['identificacion'],
                                    'promedio' => $promedio,
                                    'vmp' => '---'
                                    );

                /*if($promedio >= 'vmp'){
                    $verde[$key]++;
                }else{
                    $rojo[$key]++;
                }*/
                $i++;
            }
        }
    }   

    $pdf->AddPage('L');
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(3);

    headerTablaListado1($pdf);

    verdeLetraBlanca($pdf);
    $pdf->Cell(20, 6, utf8_decode('Compañía'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(120, 6, utf8_decode($cliente['Razon_Social']), 1, 1, 'L', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(20, 6, utf8_decode('Planta'), 1, 0, 'L', true);

    blanco($pdf);
    $pdf->Cell(120, 6, utf8_decode(isset($cliente['Planta']) ? $cliente['Planta'] : ''), 1, 1, 'L', true);

    verdeLetraBlanca($pdf);
    $pdf->Cell(20, 6, utf8_decode('Lugar'), 1, 0, 'L', true);

    blanco($pdf);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->Cell(120, 6, utf8_decode($cliente['Ciudad'].', '.$cliente['Estado']), 1, 1, 'L', true);
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
                $pdf->Cell(20, 3, utf8_decode('V.M.P'), 0, 0, 'R', true);
                blanco($pdf, 6, '');
                $pdf->Cell(70, 3, utf8_decode('Valor Máximo Permisible m/seg2'), 0, 1, 'L', true);

                $y=$pdf->GetY();
                if($pag === 0){
                    $pdf->Image("../../img/semaforo 5.png", 20, 160, 70, 17);
                }else{
                    $pdf->Image("../../img/semaforo 5.png", 20, $y+2, 70, 17);
                }
            }
        }
    }

    $pdf->Rotate(0);

/**************************************************************************************************/
/********************************************* Hoja 2 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(40, 0, 30);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(20);


    $pdf->SetFont('Arial', 'B', 15);
    $pdf->MultiCell(0, 7, utf8_decode("ESTUDIO DE EVALUACION DE LA EXPOSICION A"), 0, 'C');

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->MultiCell(0, 6, utf8_decode("VIBRACIONES SEGMENTALES NOM-024-STPS-2001"), 0, 'C');
    $pdf->Ln(15);

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->MultiCell(0, 7, utf8_decode("Practicado en la empresa"), 0, 'C');
    $pdf->Ln(5);
    
    $pdf->SetFont('Arial', 'B', 25);
    $pdf->MultiCell(0, 8, utf8_decode($cliente['Razon_Social']), 0, 'C');

    $pdf->SetFont('Arial', 'B', 24);
    $pdf->MultiCell(0, 8, utf8_decode(isset($cliente['Planta']) ? 'Planta '.$cliente['Planta'] : ''), 0, 'C');
    $pdf->Ln(20);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 5, utf8_decode('Registro Federal de Contribuyentes'), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode($cliente['RFC']), 0, 1, 'L');
    $pdf->Ln(7);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 6, utf8_decode('Domicilio completo'), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode($cliente['Calle_Numero'].', CP. '.$cliente['Codigo_Postal']), 0, 1, 'L');
    $pdf->Ln(3);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 6, utf8_decode('Teléfono'), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode($cliente['telefono']), 0, 1, 'L');
    $pdf->Ln(3);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 6, utf8_decode('Actividad principal '), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode($cliente['Giro_Empresa']), 0, 1, 'L');
    $pdf->Ln(3);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 6, utf8_decode('Representante '), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode($cliente['representante']), 0, 1, 'L');
    $pdf->Ln(3);

    $pdf->SetFont('Arial', 'B', 11);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    $pdf->MultiCell(40, 6, utf8_decode('Atención'), 0, 'J');
    $pdf->SetXY($x+40,$y);
    $pdf->Cell(2, 4, utf8_decode(':'), 0, 0, 'C');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 4, utf8_decode($cliente['atencion']), 0, 1, 'L');
    $pdf->Ln(20);

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 4, utf8_decode($cliente['Ciudad'].', '.$cliente['Estado']), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('ENERO DEL 2013'), 0, 1, 'C');

/**************************************************************************************************/
/********************************************* Hoja 3 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->Ln(30);
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->MultiCell(0, 7, utf8_decode("ESTUDIO DE EVALUACION DE LA EXPOSICION A"), 0, 'C');

    $pdf->SetFont('Arial', 'B', 13);
    $pdf->MultiCell(0, 6, utf8_decode("VIBRACIONES SEGMENTALES NOM-024-STPS-2001"), 0, 'C');
    $pdf->Ln(30);

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 6, utf8_decode("CONTENIDO"), 0, 'C');
    $pdf->Ln(10);

    $pdf->Cell(0, 4, utf8_decode('INTRODUCCIÓN _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 3'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('1. OBJETIVO _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 3'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('2. ACTIVIDADES _ _ _ _ _ _ _ _ _ _ __ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 4'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('3. REFERENCIAS PARA LA EVALUACIÓN _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 7'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('4. RESULTADOS _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 8'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('5. DESVIACIONES _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 8'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('6. CONCLUSIONES _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _  8'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('7. LISTADO DE ANEXOS _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 10'), 0, 1, 'C');

/**************************************************************************************************/
/********************************************* Hoja 4 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-6', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 3 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln();

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('INTRODUCCIÓN'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, $pdf->WriteHTML(utf8_decode("El presente es el estudio de vibraciones segmentales (mano-brazo),  llevado a cabo en la empresa <b>".$cliente['Razon_Social']." ".(isset($cliente['Planta']) ? 'Planta '.$cliente['Planta'] : '')."</b>, ubicada en ".$cliente['Ciudad'].', '.$cliente['Estado'].", a solicitud de la misma, para efectos de control interno debido a que en la empresa existen operaciones de trabajo en las que se visualizan equipos que vibran y transfieren el movimiento al brazo y las manos del trabajador.")), 0, 'J');
    $pdf->Ln();
    $pdf->MultiCell(0, 5, $pdf->WriteHTML(utf8_decode("Para la medición de los estudios indicados se siguió el procedimiento publicado por:\n<b>NOM-024-STPS-2001 Vibraciones - Condiciones de seguridad e higiene en los centros de trabajo donde se generen vibraciones.</b>")), 0, 'J');
    $pdf->Ln();
    $pdf->MultiCell(0, 5, utf8_decode("Adjunto al presente se encontrarán los resultados de las pruebas practicadas en los 3 ejes de posible presencia del movimiento vibratorio, estos integrados en el Aeq (8), tal y como se establece en la norma internacional del caso."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('1. OBJETIVO'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("- Evaluar la exposición de los  trabajadores expuestos a las vibraciones segmentales con el fin de promover el control de los riesgos que se derivan de la exposición laboral."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('2. ACTIVIDADES'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('2.1 Reconocimiento:'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("2.1.1    Identificación de los lugares donde se presente la exposición a vibraciones:"), 0, 'J');
    $pdf->Ln();

/**************************************************************************************************/
/********************************************* Hoja 5 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-2', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 5 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln();

    verdeBajitoLetraNegra($pdf);
    $pdf->SetWidths(array(82.5, 82.5));
    $pdf->SetFonts(array('B'));
    $pdf->SetFontSizes(array(9));
    $pdf->SetAligns(array('C', 'C'));
    $pdf->Row(array(utf8_decode('Área'),
                    utf8_decode('Identificación de la fuente generadora de vibración')
                    )
            );

    blanco($pdf);
    $pdf->SetFonts(array(''));
    $pdf->SetAligns(array('L', 'L'));
    foreach ($ids as $value) {
        $pdf->Row(array(utf8_decode($value['area']),
                        utf8_decode($value['fuente'])
                        )
                );
    }
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("2.1.2    Descripción de los procedimientos de operación de la maquinaria, herramientas, materiales usados y equipos del proceso, así como aquellas condiciones en que pudieran alterar las características de las vibraciones:"), 0, 'J');
    $pdf->Ln();

    blanco($pdf);
    $pdf->MultiCell(0, 5, utf8_decode($recini['procedimiento']), 1, 'L');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("2.1.3    Descripción de los puestos de trabajo del POE para determinar los ciclos de exposición."), 0, 'J');
    $pdf->Ln();

    verdeBajitoLetraNegra($pdf);
    $pdf->SetWidths(array(30, 105, 30));
    $pdf->SetFonts(array('B'));
    $pdf->SetFontSizes(array(9));
    $pdf->SetAligns(array('C', 'C', 'C'));
    $pdf->Row(array(utf8_decode('Puesto'),
                    utf8_decode('Descripción de sus actividades'),
                    utf8_decode('Ciclos de exposición')
                    )
            );

    blanco($pdf);
    $pdf->SetFonts(array(''));
    $pdf->SetAligns(array('L', 'L', 'C'));
    foreach ($puestos as $value) {
        $pdf->Row(array(utf8_decode($value['nombre']),
                        utf8_decode($value['descripcion']),
                        utf8_decode($value['ciclos']),
                        )
                );
    }
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("2.1.4    Programas de mantenimiento de maquinaria y equipos generadores de vibración."), 0, 'J');
    $pdf->Ln();

    blanco($pdf);
    $pdf->MultiCell(0, 5, utf8_decode($recini['manto']), 1, 'L');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("2.1.5    Registros de producción"), 0, 'J');
    $pdf->Ln();

    verdeBajitoLetraNegra($pdf);
    $pdf->SetWidths(array(50, 60, 55));
    $pdf->SetFonts(array('B'));
    $pdf->SetFontSizes(array(9));
    $pdf->SetAligns(array('C', 'C', 'C'));
    $pdf->Row(array(utf8_decode('Departamento / Área'),
                    utf8_decode('Producción condiciones normales de operación'),
                    utf8_decode('Producción real')
                    )
            );

    blanco($pdf);
    $pdf->SetFonts(array(''));
    $pdf->SetAligns(array('L', 'L', 'L'));
    foreach ($produccion as $value) {
        $pdf->Row(array(utf8_decode($value['depto']),
                        utf8_decode($value['cnormales']),
                        utf8_decode($value['preal']),
                        )
                );
    }
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("2.1.6    Número de POE por área y por proceso de trabajo y tiempos de exposición"), 0, 'J');
    $pdf->Ln();

    verdeBajitoLetraNegra($pdf);
    $pdf->SetWidths(array(60, 50, 55));
    $pdf->SetFonts(array('B'));
    $pdf->SetFontSizes(array(9));
    $pdf->SetAligns(array('C', 'C', 'C'));
    $pdf->Row(array(utf8_decode('Área y/o proceso de trabajo'),
                    utf8_decode('Número de trabajadores'),
                    utf8_decode('Tiempo de exposición')
                    )
            );

    blanco($pdf);
    $pdf->SetFonts(array(''));
    $pdf->SetAligns(array('L', 'C', 'C'));
    foreach ($poes as $value) {
        $pdf->Row(array(utf8_decode($value['area']),
                        utf8_decode($value['numero']),
                        utf8_decode($value['expo']),
                        )
                );
    }
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("2.1.7    Identificación del tipo de exposición para determinar el método de evaluación."), 0, 'J');
    $pdf->Ln();

    blanco($pdf);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("El tipo de vibración que se evaluó fue segmentaria (mano-brazo), ya que la principal fuente generadora de vibración es la pistola rompedora de ladrillo. "), 1, 'L');
    $pdf->Ln();

/**************************************************************************************************/
/********************************************* Hoja 6 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-6', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 3 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln();

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("2.1.2    Determinación de la instrumentación y método de muestreo para vibraciones."), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("Medidor de vibraciones - Marca:".$eqvibracion['Marca'].", Modelo: ".$eqvibracion['Modelo'].", Serie: ".$eqvibracion['Numero_Serie']." \nAcelerómetro - Marca: ".$acelerometro['Marca']." Modelo: ".$acelerometro['Modelo']." No. de Serie: ".$acelerometro['Numero_Serie']." \nCalibrador - Marca: ".$calibrador['Marca'].", Modelo: ".$calibrador['Modelo'].", No. de Serie: ".$calibrador['Numero_Serie']), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(0, 5, utf8_decode("2.2    Evaluación:"), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, $pdf->WriteHTML(utf8_decode("Se efectuaron las mediciones  como lo indica  la <b>NOM-024-STPS-2001</b> y con el instrumental referido de tal manera que con ello se obtuvieron los resultados  que se indican en los informes de evaluación para cada caso.")), 0, 'J');
    $pdf->Ln();

    $pdf->MultiCell(0, 5, utf8_decode("De acuerdo a la norma de referencia el acelerómetro se ubicó en la interfase entre la mano y la superficie o herramienta que vibra. Esto para evaluar la aceleración media cuadrática a frecuencia ponderada en los tres ejes ortogonales de la exposición que se valora."), 0, 'J');
    $pdf->Ln();

    $pdf->Ln(60);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('3. REFERENCIAS  PARA LA EVALUACIÓN'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, $pdf->WriteHTML(utf8_decode("La Interpretación de los resultados se realiza tomando como referencia los valores máximos permitidos para vibraciones segméntales indicados en la tabla No. 3 de la norma <b>NOM-024-STPS 2001</b>.")), 0, 'J');
    $pdf->Ln();

    $pdf->Cell(20, 5, '');
    verdeBajitoLetraNegra($pdf);
    $pdf->SetWidths(array(60, 60));
    $pdf->SetFonts(array('B'));
    $pdf->SetFontSizes(array(9));
    $pdf->SetAligns(array('C', 'C'));
    $pdf->Row(array(utf8_decode(' Total exposición diaria permitida (Hrs.)'),
                    utf8_decode('Aceleración en m/s2'),
                    )
            );

    $valores = array(
                    array('Total' => 'de 4 a 8 horas ',
                        'Aceleracion' => '4'),
                    array('Total' => 'De 2 a menos de 4 hrs.',
                        'Aceleracion' => '6'),
                    array('Total' => 'De 1 a menos de 2 hrs.',
                        'Aceleracion' => '8'),
                    array('Total' => 'Menos de 1 hr.',
                        'Aceleracion' => '12'),
                    );

    $pdf->SetFonts(array(''));
    $pdf->SetAligns(array('L', 'C'));
    foreach ($valores as $key => $value) {
        $pdf->Cell(20, 5, '');
        blanco($pdf);
        $pdf->Row(array(utf8_decode($value['Total']),
                utf8_decode($value['Aceleracion'])
            )
        );
    }
    $pdf->Ln();

/**************************************************************************************************/
/********************************************* Hoja 6 *********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-6', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 3 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln();

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('4. RESULTADOS'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("4.1    Los resultados obtenidos se presentan en el anexo \"Listado de resultados\",  el cual contiene:"), 0, 'J');
    $pdf->MultiCell(0, 5, utf8_decode("-    Número de medición (N° Med.).
-    Fecha de medición.
-    Puesto.
-    Área.
-    Herramienta
-    Actividad.
-    Identificación.
-    Promedio de la aceleración en m/seg2,  en los tres ejes (x, y,  z), en Aeq.
-    Aeq para 8 horas.
-    Valor Máximo Permitido (VMP) en m/seg2."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("4.2    Los registros de evaluación se presentan en el anexo 2."), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('5. DESVIACIONES'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, utf8_decode("Ninguna"), 0, 'J');
    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('6. CONCLUSIONES'), 0, 1, 'L');

    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 5, $pdf->WriteHTML(utf8_decode("Como se  aprecia en los listados de resultados (<b>anexo1</b>), podemos concluir que el puesto evaluado <b>no supera</b> el Nivel Máximo Permitido, para la vibración segmental mano-brazo, por lo que <b>no existe un riesgo para el trabajador, bajo las condiciones de operación en las cuales se realizó la evaluación.</b>")), 0, 'J');
    $pdf->Ln();

/**************************************************************************************************/
/********************************************* Hoja 7 ********************************************/
/**************************************************************************************************/
    $pdf->AddPage();
    $pdf->SetMargins(20, 0, 25);
    $pdf->SetLineWidth(.1);

    $pdf->SetTextColor(100);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(0, 3, 'AIR-F-2', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, utf8_decode('Página No. 10 de 10'), 0, 1, 'R');
    $pdf->Cell(0, 3, utf8_decode("O.T. 916I - 2015"), 0, 1, 'R');
    $pdf->Ln(20);

    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, utf8_decode('7.  LISTADO DE ANEXOS'), 0, 1, 'L');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(20, 5, 'Anexo 1:  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Listado de resultados'), 0, 'L');
    $pdf->Ln(1);


    $pdf->Cell(20, 5, 'Anexo 2:  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Información de evaluación'), 0, 'L');
    $pdf->Ln(1);

    $pdf->Cell(20, 5, 'Anexo 3:  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Efectos a la salud'), 0, 'L');
    $pdf->Ln(1);

    $pdf->Cell(20, 5, 'Anexo 4:  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Planos de unicación de las mediciones'), 0, 'L');
    $pdf->Ln(1);

    $pdf->Cell(20, 5, 'Anexo 5:  ', 0, 0, 'R');
    $pdf->MultiCell(0, 5, utf8_decode('Tablas y gráficas'), 0, 'L');
    $pdf->Ln(1);

    $pdf->Cell(20, 5, 'Anexo 6:  ', 0, 0, 'R');
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
    function verdeLetraBlanca($pdf){
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(114, 144, 119);
        $pdf->SetTextColor(255);
    }

    function verdeBajitoLetraNegra($pdf){
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(160, 190, 156);
        $pdf->SetTextColor(0);
    }

    function verdeLetraNegra($pdf){
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(114, 144, 119);
        $pdf->SetTextColor(0);
    }

    function azul($pdf){
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(153, 204, 255);
        $pdf->SetTextColor(0);
    }

    function blanco($pdf, $size=8, $fuente=''){
        $pdf->SetFont('Arial', $fuente, $size);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
    }

    function headerTablaListado1($pdf){
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 3, utf8_decode('LISTADO DE RESULTADOS'), 0, 1, 'C');
        $pdf->Ln(2);
        $pdf->Cell(0, 3, utf8_decode('EVALUACION DE LAS VIBRACIONES SEGMENTALES'), 0, 1, 'C');
        $pdf->Ln(2);
    }

    function medListado($pdf, $data){
        $pdf->SetWidths(array(10, 20, 25, 35, 35, 45, 25, 20, 15));
        $pdf->SetFonts(array(''));
        $pdf->SetFontSizes(array(6));
        $pdf->SetAligns(array('C', 'C', 'L', 'L', 'L', 'L', 'L', 'C', 'C'));

        $pdf->RowColor(array($data['medicion'],
                            $data['fecha'],
                            utf8_decode($data['puesto']),
                            utf8_decode($data['area']),
                            utf8_decode($data['herramienta']),
                            utf8_decode($data['actividad']),
                            utf8_decode($data['identificacion']),
                            $data['promedio'],
                            $data['vmp']
                        )
                    );
    }

    function deptoListado($pdf, $data){
        blanco($pdf, 8, 'B');
        $pdf->Cell(230, 5, utf8_decode('Departamento: '.$data['departamento']), 1, 1, 'C', true);
    }

    function headerTablaListado2($pdf){
        verdeLetraBlanca($pdf);
        $pdf->SetWidths(array(10, 20, 25, 35, 35, 45, 25, 20, 15));
        $pdf->SetFonts(array('B'));
        $pdf->SetFontSizes(array(9));
        $pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
        $pdf->Row(array(
                        utf8_decode('No. Med'),
                        utf8_decode('Fecha'),
                        utf8_decode('Puesto'),
                        utf8_decode('Area'),
                        utf8_decode('Herramienta'),
                        utf8_decode('Actividad'),
                        utf8_decode('Identificación'),
                        utf8_decode("A(8) \n Promedio \n (m/seg2)"),
                        utf8_decode('V.M.P')));
    }

?>