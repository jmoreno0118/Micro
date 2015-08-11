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
            $this->SetTextColor(69, 147, 56);
            $this->SetFont('Arial', '', 12);
            $this->SetY(45);
            $this->Cell(0, 2, utf8_decode('LABORATORIO DEL GRUPO MICROANALISIS, S.A. DE C.V.'), 0, 1, 'C');
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
                        $a2=explode(' ', $e);
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
    }

    $pdf = new PDF();

/**************************************************************************************************/
/* Búsqueda de ordenes de la norma 001 */
/**************************************************************************************************/
    if(isset($_POST['accion']) AND ($_POST['accion']=='buscar' OR $_POST['accion']=='informe2') OR (isset($_GET['ot']) AND isset($_GET['id'])))
    {
        $errores = 0;
        $error = '';
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
        try   
        {
            $sql='SELECT ordenestbl.id, ordenestbl.ot, ordenestbl.fechalta, muestreosaguatbl.id as "muestreoaguaid",
                        ordenestbl.signatarionombre, ordenestbl.signatarioap, ordenestbl.signatarioam,
                        muestreosaguatbl.fechamuestreo, ordenestbl.plantaidfk, ordenestbl.clienteidfk, ordenestbl.atencion,
                        ordenacreditaciontbl.nombre,  ordenacreditaciontbl.fecha
                    FROM  ordenestbl
                    INNER JOIN generalesaguatbl ON ordenestbl.id = generalesaguatbl.ordenaguaidfk
                    INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
                    INNER JOIN estudiostbl ON ordenestbl.id = estudiostbl.ordenidfk
                    INNER JOIN ordenacreditaciontbl ON ordenestbl.id = ordenacreditaciontbl.ordenidfk';
            if(isset($_GET['ot']) AND isset($_GET['id'])){
                $where=' WHERE estudiostbl.nombre="NOM 001" AND ordenestbl.ot = :ot AND ordenestbl.id = :id';
                $s=$pdo->prepare($sql.$where);
                $s->bindValue(':ot', $_GET['ot']);
                $s->bindValue(':id', $_GET['id']);
                $s->execute();
                $orden = $s->fetch();
            }else{
                $where=' WHERE estudiostbl.nombre="NOM 001" AND ordenestbl.ot = :ot';
                $s=$pdo->prepare($sql.$where);
                $s->bindValue(':ot', $_POST['ot']);
                $s->execute();
                $orden = $s->fetch();
            }
        }
        catch (PDOException $e)
        {
            $mensaje='Error al tratar de obtener información de la orden. ';
            include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
            exit();
        }

        try{
            $sql='SELECT nombre, ap, am
                FROM responsables
                WHERE muestreoaguaidfk = :id';
            $s=$pdo->prepare($sql);
            $s->bindValue(':id', $orden['muestreoaguaid']);
            $s->execute();
            $responsables = $s->fetchAll();
        }
        catch (PDOException $e)
        {
            $mensaje='Error, no se encontraron responsables de la orden. ';
            include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
            exit();
        }

        $sql='SELECT fechamuestreo, fechamuestreofin
            FROM muestreosaguatbl
            WHERE id = :id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $orden['muestreoaguaid']);
        $s->execute();
        $fechasmuestreo = $s->fetchAll();

        try{
            if($orden['plantaidfk'] !== NULL){
                $sql='SELECT plantastbl.razonsocial, plantastbl.calle, plantastbl.colonia, plantastbl.ciudad, 
                    plantastbl.estado, plantastbl.cp
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
                                'Giro_Empresa' => '',
                                'Codigo_Postal' => $resultado['cp']);

                $sql='SELECT clientestbl.Giro_Empresa
                    FROM clientestbl
                    WHERE clientestbl.Numero_Cliente = :id';
                $s=$pdo->prepare($sql);
                $s->bindValue(':id', $orden['clienteidfk']);
                $s->execute();
                $giro = $s->fetch();

                $cliente['Giro_Empresa'] = $giro['Giro_Empresa'];

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
        }
        catch (PDOException $e)
        {
            $mensaje='Error en la información de la planta. ';
            include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
            exit();
        }

        if(!$orden){
            $mensaje='Error al tratar de obtener información de la orden.';
            include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
            exit();
        }

/**************************************************************************************************/
/********************************************* Hoja 0 *********************************************/
/**************************************************************************************************/
        $pdf->AddPage();
        $pdf->SetMargins(20, 0, 25);

        $pdf->SetTextColor(100);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 3, 'AIR-F-11', 0, 1, 'R');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(0, 3, utf8_decode('Página No. 1 de 1.'), 0, 1, 'R');
        $pdf->Cell(0, 3, utf8_decode("O.T. - ".$orden['ot']." - ".date('Y',strtotime($orden['fechalta']))."."), 0, 1, 'R'); //////////////////////////////// O.T.
        $pdf->Ln();
        $pdf->Ln();

        try   
        {
        $sql='SELECT max(fechareporte) as "Fecha"
            FROM clientestbl
            INNER JOIN ordenestbl ON clientestbl.Numero_Cliente = ordenestbl.clienteidfk
            INNER JOIN generalesaguatbl ON ordenestbl.id = generalesaguatbl.ordenaguaidfk
            INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
            INNER JOIN parametrostbl ON muestreosaguatbl.id = parametrostbl.muestreoaguaidfk
            INNER JOIN estudiostbl ON ordenestbl.id = estudiostbl.ordenidfk
            WHERE estudiostbl.nombre="NOM 001" AND ordenestbl.ot = :ot ';
        $s=$pdo->prepare($sql);
        $s->bindValue(':ot', isset($_GET['ot']) ? $_GET['ot'] : $_POST['ot']);
        $s->execute();
        $fecha = $s->fetch();
        }
        catch (PDOException $e)
        {
            $mensaje='Hubo un error extrayendo la fecha del reporte. ';
            include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
            exit();
        }

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 5, utf8_decode('México D.F.'), 0, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(0, 5, utf8_decode(date('Y', strtotime($fecha['Fecha']))."-".$meses[date('n', strtotime($fecha['Fecha']))-1]. "-".date('d',strtotime($fecha['Fecha'])) .'.'), 0, 1, 'R'); //////////////////////////////// Fecha

        //$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        //echo date('d', strtotime($orden['fechalta']))."-".$meses[date('n', strtotime($orden['fechalta']))-1]. "-".date('Y',strtotime($orden['fechalta'])) ;

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 5, utf8_decode(htmldecode($cliente['Razon_Social'])), 0 ,1); ////////////////// Nombre de empresa
        $pdf->Ln(1);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 5, utf8_decode(htmldecode($cliente['Calle_Numero'])),0 ,1); //////////// Dirección
        $pdf->Ln(1);
        $pdf->Cell(0, 5, utf8_decode("Col. ".htmldecode($cliente['Colonia']).", ".htmldecode($cliente['Ciudad']).", ".htmldecode($cliente['Estado'])), 0, 1); //////////// Dirección
        $pdf->Ln();
        $pdf->Ln();

        $pdf->Cell(0, 5, utf8_decode("At'n.: ".htmldecode($orden['atencion'])."."), 0, 1, 'R'); //////////////////////////////// Atn
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 5, utf8_decode('Asunto: Informe del Análisis de Aguas.'), 0, 1);
        $pdf->Ln();

        try   
        {
            $sql='SELECT muestreosaguatbl.identificacion
                FROM clientestbl
                INNER JOIN ordenestbl ON clientestbl.Numero_Cliente = ordenestbl.clienteidfk
                INNER JOIN generalesaguatbl ON ordenestbl.id = generalesaguatbl.ordenaguaidfk
                INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
                INNER JOIN estudiostbl ON ordenestbl.id = estudiostbl.ordenidfk
                WHERE estudiostbl.nombre="NOM 001" AND ordenestbl.ot = :ot ';
            $s=$pdo->prepare($sql);
            $s->bindValue(':ot', isset($_GET['ot']) ? $_GET['ot'] : $_POST['ot']);
            $s->execute();
            $identificaciones = $s->fetchAll();
        }
        catch (PDOException $e)
        {
            $mensaje='Hubo un error extrayendo la identificación de las mediciones. ';
            include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
            exit();
        }

        $fechasinicio = array();
        $fechasfin = array();
        foreach ($fechasmuestreo as $key => $value) {
            $fechasinicio[] = $value['fechamuestreo'];
            if(!is_null($value['fechamuestreofin']))
                $fechasfin[] = $value['fechamuestreofin'];
        }

        $ident = "";
        foreach ($identificaciones as $key => $value) {
            $ident .= '"'.$value['identificacion'].'", '; 
        }
        $ident = rtrim($ident, ", ");

        $responsable = '';
        foreach ($responsables as $value) {
            $responsable = $value['nombre'].' '.$value['ap'].' '.$value['am'].', ';
        }
        $responsable = rtrim($responsable, ', ');

        $responsable = '';
        foreach ($responsables as $value) {
            $responsable = $value['nombre'].' '.$value['ap'].' '.$value['am'].', ';
        }
        $responsable = rtrim($responsable, ', ');

        $pdf->SetFont('Arial', '', 11);


        if(count($fechasfin) > 0){
            $fecha = ' desde el día '. date('d', strtotime(min($fechasinicio)))." de ".$meses[date('n', strtotime(min($fechasinicio)))-1]. " del ".date('Y',strtotime(min($fechasinicio))). " a el día ". date('d', strtotime(max($fechasfin)))." de ".$meses[date('n', strtotime(max($fechasfin)))-1]. " del ".date('Y',strtotime(max($fechasfin)));
        }else{
            $fecha = ' el día '. date('d', strtotime(min($fechasinicio)))." de ".$meses[date('n', strtotime(min($fechasinicio)))-1]. " del ".date('Y',strtotime(min($fechasinicio)));
        }
        
        if(count($identificaciones) > 1){
            $pdf->MultiCell(0, 5, utf8_decode('Con relación a las determinaciones analíticas practicadas a las muestras de agua identificadas como: '.$ident.', tomadas por '.$responsable.$fecha.', nos permitimos informarle lo siguiente:'), 0, 'J');
        }else{
            $pdf->MultiCell(0, 5, utf8_decode('Con relación a las determinaciones analíticas practicadas a la muestra de agua identificada como: '.$ident.', tomada por '.$responsable.$fecha.', nos permitimos informarle lo siguiente:'), 0, 'J');
        }
        $pdf->Ln();

        $pdf->MultiCell(0, 5, utf8_decode('La muestra fue analizada por el Laboratorio del Grupo Microanálisis,  S.A. de C.V.,  el cual cuenta con acreditación ante la Entidad Mexicana de Acreditación (EMA).'), 0, 'J');
        $pdf->Ln();

        $pdf->MultiCell(0, 5, utf8_decode('Los métodos de muestreo y análisis, están referenciados en la Normatividad Nacional, los cuales son indicados en los resultados de laboratorio para cada sustancia.'), 0, 'J');
        $pdf->Ln();

        $pdf->MultiCell(0, 5, utf8_decode('El presente informe está integrado por informe de resultados, resultados del laboratorio, hojas de campo y cadena de custodia.'), 0, 'J');
        $pdf->Ln();

        $pdf->MultiCell(0, 5, utf8_decode('Agradecemos su interés en nuestros servicios y esperamos poder atenderle en futuras ocasiones.'), 0,  'J');
        $pdf->Ln(4);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, utf8_decode('Acreditación EMA No. '.$orden['nombre'].'.  Vigencia: A partir del '.date('d', strtotime($orden['fecha']))." de ".$meses[date('n', strtotime($orden['fecha']))-1]. " del ".date('Y', strtotime($orden['fecha'])).'.'), 0,  'J');
        $pdf->Ln(4);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 5, utf8_decode('Atentamente.'));
        $pdf->Ln();
        $pdf->Ln(20);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 5, utf8_decode($orden['signatarionombre'].' '.$orden['signatarioap'].' '.$orden['signatarioam']));
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 5, utf8_decode('Signatario Autorizado por la E.M.A.'));
        $pdf->Ln();
        $pdf->Ln(15);

        try   
        {
            $sql='SELECT generalesaguatbl.id, generalesaguatbl.numedicion
                FROM  generalesaguatbl
                INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
                WHERE  generalesaguatbl.ordenaguaidfk = :id;';
            $s=$pdo->prepare($sql);
            $s->bindValue(':id', $orden['id']);
            $s->execute();
            $muestras = $s->fetchAll();
        }
        catch (PDOException $e)
        {

            $mensaje='Hubo un error extrayendo las mediciones. ';
            include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
            exit();
        }

        foreach ($muestras as $muestreo) {
            try   
            {
                $sql='SELECT generalesaguatbl.numedicion, muestreosaguatbl.fechamuestreo, muestreosaguatbl.identificacion, generalesaguatbl.lugarmuestreo,
                    generalesaguatbl.descriproceso, generalesaguatbl.materiasusadas, generalesaguatbl.tratamiento, generalesaguatbl.Caracdescarga,
                    generalesaguatbl.estrategia, generalesaguatbl.observaciones, muestreosaguatbl.temperatura,
                    muestreosaguatbl.pH, muestreosaguatbl.conductividad, muestreosaguatbl.mflotante,  muestreosaguatbl.id as "muestreoaguaid",
                    generalesaguatbl.nom01maximosidfk, muestreosaguatbl.identificacion, generalesaguatbl.tipomediciones, muestreosaguatbl.caltermometro,
                    generalesaguatbl.id as "generalaguaid"
                    FROM  generalesaguatbl
                    INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
                    WHERE  generalesaguatbl.id = :id;';
                $s=$pdo->prepare($sql);
                $s->bindValue(':id', $muestreo['id']);
                $s->execute();
                $muestra = $s->fetch();
            }
            catch (PDOException $e)
            {

                $mensaje='Hubo un error extrayendo los datos generales de la medición '.$muestreo['numedicion'].' '."\n";
                include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
                exit();
            }

            $cantidad = 1;
            if($muestra['tipomediciones'] === '4'){
                $cantidad = 2;
            }else if($muestra['tipomediciones'] === '8'){
                $cantidad = 4;
            }else if($muestra['tipomediciones'] === '12'){
                $cantidad = 6;
            }
            
    //--------------------------------------------------------------------------------------------------------------------
    //Obtener parametros y maximos----------------------------------------------------------------------------------------
    //--------------------------------------------------------------------------------------------------------------------
            try   
            {
                $sql='SELECT *
                    FROM parametrostbl
                    WHERE muestreoaguaidfk = :id';
                $s=$pdo->prepare($sql);
                $s->bindValue(':id', $muestra['muestreoaguaid']);
                $s->execute();
                $parametros = $s->fetch();

                $sql='SELECT *
                    FROM metodosparametrostbl
                    WHERE parametrosidfk = :id';
                $s=$pdo->prepare($sql);
                $s->bindValue(':id', $parametros['id']);
                $s->execute();
                $metodos = $s->fetch();

                $sql='SELECT *
                    FROM nom01maximostbl
                    WHERE id = :id';
                $s = $pdo->prepare($sql);
                $s->bindValue(':id', $muestra['nom01maximosidfk']);
                $s->execute();
                $maximos = $s->fetch();

                $sql='SELECT *
                        FROM parametros2tbl
                        WHERE parametroidfk = :id';
                $s=$pdo->prepare($sql);
                $s->bindValue(':id', $parametros['id']);
                $s->execute();
                $parametros2 = "";
                foreach ($s as $linea) {
                    $parametros2[]=array("GyA" => $linea["GyA"],
                                        "coliformes" => $linea["coliformes"]);
                }

                $sql='SELECT *
                    FROM adicionalestbl
                    WHERE parametroidfk = :id';
                $s=$pdo->prepare($sql);
                $s->bindValue(':id',$parametros['id']);
                $s->execute();
                $adicionales = '';
                foreach ($s as $linea) {
                    $adicionales[]=array("nombre" => $linea["nombre"],
                                        "unidades" => $linea["unidades"],
                                        "resultado" => $linea["resultado"],
                                        "metodo" => $linea["metodo"]);
                }
            }
            catch (PDOException $e)
            {
                $errores++;
                $error .= 'Hubo un error extrayendo los párametros de la medición '.$muestreo['numedicion'].' '."\n";
                /*$mensaje='Hubo un error extrayendo los párametros de la medición '.$muestreo['numedicion'].$e;
                include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
                exit();*/
            }

    //--------------------------------------------------------------------------------------------------------------------
    //Obtener parametros2 y mcompuesta------------------------------------------------------------------------------------
    //--------------------------------------------------------------------------------------------------------------------
            try   
            {
                $sql="SELECT DATE_FORMAT(mcompuestastbl.hora, '%H:%i') as 'hora', mcompuestastbl.flujo, mcompuestastbl.volumen, mcompuestastbl.observaciones,
                        mcompuestastbl.caracteristicas
                        FROM mcompuestastbl
                        WHERE mcompuestastbl.muestreoaguaidfk = :id";
                $s=$pdo->prepare($sql); 
                $s->bindValue(':id', $muestra['muestreoaguaid']);
                $s->execute();
                $mcompuestas = "";
                foreach($s as $linea){
                    $mcompuestas[] = array("hora" => $linea["hora"],
                                            "flujo" => $linea["flujo"],
                                            "volumen" => $linea["volumen"],
                                            "observaciones" => $linea["observaciones"],
                                            "caracteristicas" => $linea["caracteristicas"]);
                }
                //var_dump($mcompuestas);
            }
            catch (PDOException $e)
            {
                $errores++;
                $error .= 'Hubo un error extrayendo las compuestas de la medicion '.$muestreo['numedicion'].' '."\n";
                /*$mensaje='Hubo un error extrayendo las compuestas de la medicion '.$muestreo['numedicion'].$e;
                include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
                exit();*/
            }
            //var_dump($mcompuestas);

    //--------------------------------------------------------------------------------------------------------------------
    //Obtener croquis----------------------------------------------------------------------------------------
    //--------------------------------------------------------------------------------------------------------------------
            try   
            {
                $sql='SELECT *
                FROM documentos001tbl
                WHERE generalaguaidfk = :id AND tipo = "Croquis"';
                $s=$pdo->prepare($sql);
                $s->bindValue(':id', $muestra['generalaguaid']);
                $s->execute();
                $croquis = $s->fetch();
                if(!$croquis){
                    $errores++;
                    $error .= 'Hubo un error extrayendo el croquis de la medicion '.$muestreo['numedicion']."\n";
                }
                //var_dump($croquis);
            }
            catch (PDOException $e)
            {
                $errores++;
                $error .= 'Hubo un error extrayendo el croquis de la medicion '.$muestreo['numedicion'].' '."\n";
                /*$mensaje='Hubo un error extrayendo el croquis de la medicion '.$muestreo['numedicion'].$e;
                include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
                exit();*/
            }

            /**************************************************************************************************/
            /********************************************* Hoja 1 *********************************************/
            /**************************************************************************************************/
                if($cantidad === 1){
                    if($adicionales === ''){
                        hojaNueva($pdf, $orden, '1', '3');
                    }else{
                        hojaNueva($pdf, $orden, '1', '4');
                    }
                }else{
                    if($adicionales === ''){
                        hojaNueva($pdf, $orden, '1', '4');
                    }else{
                        hojaNueva($pdf, $orden, '1', '5');
                    }
                }

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(0, 5, utf8_decode('Datos generales'), 1, 1, 'C', true);

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('N° de muestra'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(50, 5, utf8_decode($muestra['numedicion']), 1, 0, 'L');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(20, 5, utf8_decode('Fecha'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($muestra['fechamuestreo']), 1, 1, 'L');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Compañía'), 1, 0, 'L');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->MultiCell(0, 5, utf8_decode($cliente['Razon_Social']), 1, 'L');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Giro de la empresa'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($cliente['Giro_Empresa']), 1, 1, 'L');

                $pdf->SetWidths(array(50,115));
                $pdf->SetFonts(array('B',''));
                $pdf->SetFontSizes(array(9));
                $pdf->SetAligns(array('L','L'));
                $pdf->Row(array(utf8_decode('Dirección'),utf8_decode(htmldecode($cliente['Calle_Numero'])."\nCol. ".htmldecode($cliente['Colonia'])." C.P. ".htmldecode($cliente['Codigo_Postal'])." ".htmldecode($cliente['Ciudad'])." ".htmldecode($cliente["Estado"]))));
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 5, utf8_decode('Datos del muestreo'), 1, 1, 'C', true);

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Identificación de la muestra'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($muestra['identificacion']), 1, 1, 'L');

                /*$pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Lugar de muestreo'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($muestra['lugarmuestreo']), 1, 1, 'R');*/

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Descripción del proceso'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($muestra['descriproceso']), 1, 1, 'L');

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->MultiCell(50, 4, utf8_decode('Materias primas usadas en el proceso de descarga'), 1, 'L');

                $pdf->SetXY($x + 50, $y);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 8, utf8_decode($muestra['materiasusadas']), 1, 1, 'L');

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->MultiCell(50, 4, utf8_decode('Tratamiento del agua antes de la descarga'), 1, 'L');

                $pdf->SetXY($x + 50, $y);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 8, utf8_decode($muestra['tratamiento']), 1, 1, 'L');

                /*$pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Características de la descarga'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($muestra['Caracdescarga']), 1, 1, 'R');*/

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Tipo de receptor de la descarga'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($maximos['descargaen']), 1, 1, 'L');

                $pdf->SetWidths(array(50,115));
                $pdf->SetFonts(array('B',''));
                $pdf->SetFontSizes(array(9));
                $pdf->SetAligns(array('L','L'));
                $pdf->Row(array(utf8_decode('Estrategia de muestreo'),utf8_decode($muestra['estrategia'])));
                //$pdf->MultiCell(0, 4, utf8_decode($muestra['estrategia']."adfhddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd"), 1, 'J');

                $pdf->Row(array(utf8_decode('Observaciones de campo'),utf8_decode($muestra['observaciones'])));
                //$pdf->MultiCell(0, 4, utf8_decode($muestra['observaciones']."adfhdddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd"), 1, 'J');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Conservación de muestra'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode('Refrigeración < 4 °C'), 1, 1, 'L');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(0, 5, utf8_decode('Parámetros de Campo'), 1, 1, 'C', true);
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(30, 8, utf8_decode('Parámetros'), 1, 0, 'C', true);
                $pdf->Cell(20, 8, utf8_decode('Unidades'), 1, 0, 'C', true);
                $pdf->Cell(20, 8, utf8_decode('Medición'), 1, 0, 'C', true);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(25, 4, utf8_decode('Incertidumbre Estándar'), 1, 'C', true);
                $pdf->SetXY($x + 25, $y);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(30, 4, utf8_decode('Limites Máximos Permisibles'), 1, 'C', true);
                $pdf->SetXY($x + 30, $y);
                $pdf->Cell(40, 8, utf8_decode('Método'), 1, 1, 'C', true);

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(30, 5, utf8_decode('Temperatura'), 1, 0, 'C');
                $pdf->Cell(20, 5, utf8_decode('°C'), 1, 0, 'C');

                $pdf->Cell(20, 5, utf8_decode(($muestra['temperatura'] + $muestra['caltermometro'])), 1, 0, 'C');
                $pdf->Cell(25, 5, utf8_decode('±      ').(number_format(($muestra['temperatura'] * 1.645 * 0.02866), 2, '.', '')), 1, 0, 'C');
                $pdf->Cell(30, 5, utf8_decode('40'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('NMX-AA-007-SCFI-2013'), 1, 1, 'C');

                $pdf->Cell(30, 5, utf8_decode('pH'), 1, 0, 'C');
                $pdf->Cell(20, 5, utf8_decode('U de pH'), 1, 0, 'C');
                $pdf->Cell(20, 5, utf8_decode($muestra['pH']), 1, 0, 'C');
                $pdf->Cell(25, 5, utf8_decode('±      ').(number_format(($muestra['pH'] * 1.645 * 0.0037), 2, '.', '')), 1, 0, 'C');
                $pdf->Cell(30, 5, utf8_decode('de 5 a 10'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('NMX-AA-008-SCFI-2011'), 1, 1, 'C');
                
                $pdf->Cell(30, 5, utf8_decode('Conductividad'), 1, 0, 'C');
                $pdf->Cell(20, 5, utf8_decode('ms/m'), 1, 0, 'C');
                $pdf->Cell(20, 5, utf8_decode($muestra['conductividad']), 1, 0, 'C');
                $pdf->Cell(25, 5, utf8_decode('±      ').(number_format(($muestra['conductividad'] * 1.645 * 0.00964), 2, '.', '')), 1, 0, 'C');
                $pdf->Cell(30, 5, utf8_decode('No Aplica'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('NMX-AA-093-SCFI-2000'), 1, 1, 'C');
                $pdf->Ln(3);

                $pdf->SetFont('Arial', 'B', 6);
                $pdf->MultiCell(0, 3, utf8_decode('"El término a adicionar o substraer del resultado en cada caso, define el valor de la incertidumbre expandida, fué obtenido experimentalmente con la aplicación de los procedimientos estándar de operación correspondientes, así como el procedimiento de cálculo de incertidumbre, por lo que pudiera diferir del que se alcance en la matríz real.   En consecuencia, esa expresión deberá ser interpretada con las reservas del caso."'), 0, 'J');
                $pdf->Ln();
                $pdf->MultiCell(0, 3, utf8_decode('"El valor de Temperatura reportado, es el resultado de la corrección de la lectura directa en campo, por un factor que se deriva de la comparación del termómetro de uso contra el de referencia trazable."'), 0, 'J');

            /**************************************************************************************************/
            /********************************************* Hoja 2 *********************************************/
            /**************************************************************************************************/
                if($cantidad === 1){
                    if($adicionales === ''){
                        hojaNueva($pdf, $orden, '2', '3');
                    }else{
                        hojaNueva($pdf, $orden, '2', '4');
                    }
                }else{
                    if($adicionales === ''){
                        hojaNueva($pdf, $orden, '2', '4');
                    }else{
                        hojaNueva($pdf, $orden, '2', '5');
                    }
                }

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(0, 5, utf8_decode('Parámetros de Campo'), 1, 1, 'C', true);

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(45, 5, utf8_decode('Parámetros'), 1, 0, 'C', true);
                $pdf->Cell(40, 5, utf8_decode('Unidades'), 1, 0, 'C', true);
                $pdf->Cell(40, 5, utf8_decode('Medición'), 1, 0, 'C', true);
                $pdf->Cell(40, 5, utf8_decode('Limites Máximos'), 1, 1, 'C', true);

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(45, 5, utf8_decode('Materia flotante visual'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode(( strval($muestra['mflotante']) === '1')? 'Presente' : 'Ausente'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('Ausente'), 1, 1, 'C');
                $pdf->Ln();

                //var_dump($parametros2);
                if(count($parametros2)<=0 OR $parametros2 === ""){
                    $mensaje='Faltan llenar datos de las mediciones.';
                    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
                    exit();
                }
                $promcoliformes = '';
                $nocoliformes = 0;
                for ($i=0; $i < $cantidad; $i++) {
                  if(strcmp($parametros2[$i]['coliformes'], "") === 0 ){
                    $nocoliformes++;
                  }
                }

                if($nocoliformes !== $cantidad){
                    $promcoliformes = 1;
                    $raiz = 0;
                    for ($i=0; $i < $cantidad; $i++) {
                        if( strcmp($parametros2[$i]['coliformes'], "") !== 0 AND strcmp($parametros2[$i]['coliformes'], "0") !== 0  ){
                            $dato[1] = $parametros2[$i]['coliformes'];
                            if( strpos($parametros2[$i]['coliformes'], "<") !== FALSE){
                                $dato = explode("<", $parametros2[$i]['coliformes']);
                            }
                            $promcoliformes = $promcoliformes * $dato[1];
                            $raiz++;
                        }
                    }
                    $promcoliformes = pow($promcoliformes, (1/$raiz) );
                }

                if($cantidad === 1){
                    parametrosPDF($pdf, $muestra, $parametros, $maximos, $cantidad, $parametros2, 0, $promcoliformes, $metodos);
                }else{
                    observacionesPDF($pdf, $mcompuestas, $cantidad);
                }

            /**************************************************************************************************/
            /********************************************* Hoja 3 *********************************************/
            /**************************************************************************************************/
                if($cantidad === 1){
                    if($adicionales === ''){
                        hojaNueva($pdf, $orden, '3', '3');
                        croquisPDF($pdf, $cantidad, $croquis, $orden, $responsables);
                    }else{
                        hojaNueva($pdf, $orden, '3', '4');
                        adicionalesPDF($pdf, $adicionales);

                        hojaNueva($pdf, $orden, '4', '4');
                        croquisPDF($pdf, $cantidad, $croquis, $orden, $responsables);
                    }
                }else{
                    if($adicionales === ''){
                        hojaNueva($pdf, $orden, '3', '4');
                    }else{
                        hojaNueva($pdf, $orden, '3', '5');
                    }

                    $promedio = '';
                    $nogya = 0;
                    for ($i=0; $i < $cantidad; $i++) {
                      if(strcmp($parametros2[$i]['GyA'], "") === 0 ){
                        $nogya++;
                      }
                    }

                    if($nogya !== $cantidad){
                        $pdf->SetFont('Arial', 'B', 9);
                        $x = $pdf->GetX();
                        $y = $pdf->GetY();
                        $pdf->MultiCell(35, 5, utf8_decode("Concentración de grasas y aceites\n(mg/L)"), 1, 'C', true);
                        $pdf->SetXY($x + 35, $y);
                        $x = $pdf->GetX();
                        $y = $pdf->GetY();
                        $pdf->MultiCell(40, 15, utf8_decode("Flujo al tiempo X (L/s)"), 1, 'C', true);
                        $pdf->SetXY($x + 40, $y);
                        $x = $pdf->GetX();
                        $y = $pdf->GetY();
                        $pdf->MultiCell(50, 5, utf8_decode("Concentración de grasas y aceites por flujo\n(mg/s)"), 1, 'C', true);
                        $pdf->SetXY($x + 50, $y);
                        $pdf->MultiCell(40, 5, utf8_decode("Promedio ponderado de grasas y aceites\n(mg/L)"), 1, 'C', true);

                        $pdf->SetFont('Arial', '', 9);
                        $x = $pdf->GetX();
                        $y = $pdf->GetY();
                        $flujototal = 0;
                        $gyatotal = 0;
                        $totalconcentracion = 0;
                        //var_dump($parametros2);
                        for ($i=0; $i < $cantidad; $i++) {
                          $gya = (strcmp($parametros2[$i]['GyA'], "") !== 0 )? $parametros2[$i]['GyA'] : 0; 
                          if(strpos($gya, '<') !== false){
                                $gya = explode('<', $gya);
                                $gya = $gya[1];
                            }
                          $pdf->Cell(35, 5, $parametros2[$i]['GyA'], 1, 0, 'C');
                          $pdf->Cell(40, 5, number_format(doubleval($mcompuestas[$i]['flujo']*1000), 5), 1, 0, 'C');

                          $concentracion = "S/F";
                          if($mcompuestas[$i]['flujo'] !== "S/F"){
                            //Las GyA sin flujo no deben ser tomadas en cuenta en el calculo
                            $gyatotal += floatval($gya);
                            $flujototal += floatval($mcompuestas[$i]['flujo']*1000);
                            $concentracion = ($mcompuestas[$i]['flujo'] * 1000) * $gya;
                            $totalconcentracion += $concentracion;
                          }
                          $pdf->Cell(50, 5, utf8_decode(number_format(doubleval($concentracion), 5)), 1, 1, 'C');
                        }

                        $pdf->SetFont('Arial', '', 10);
                        $pdf->SetXY($x + 35 + 40 + 50, $y);
                        $promedio = ($totalconcentracion === 0)? $gyatotal/$cantidad : $totalconcentracion/$flujototal;
                        if(strpos($promedio,'.') !== false){
                            $promedio = number_format(doubleval($promedio), 5);
                        }
                        $promedio1 = $promedio;
                        if(bccomp($promedio, floatval(12))==0){
                            $promedio1 = '< '.$promedio;
                        }
                        $pdf->MultiCell(40, $cantidad, utf8_decode("\n\n".$promedio1."\n\n\n"), 1, 'C');

                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->Cell(35, 5, '', 0, 0, 'C');
                        $pdf->Cell(40, 5, utf8_decode(number_format(doubleval($flujototal), 5)), 1, 0, 'C', true);
                        $pdf->Cell(50, 5, utf8_decode(number_format(doubleval($totalconcentracion), 5)), 1, 1, 'C', true);
                        $pdf->Ln();
                    }
                    
                    parametrosPDF($pdf, $muestra, $parametros, $maximos, $cantidad, $parametros2, $promedio, $promcoliformes, $metodos);
                }

            /**************************************************************************************************/
            /********************************************* Hoja 4 *********************************************/
            /**************************************************************************************************/
                if($cantidad !== 1){
                    if($adicionales !== ''){
                        hojaNueva($pdf, $orden, '4', '5');
                        adicionalesPDF($pdf, $adicionales);
                    }
                }
                

            /**************************************************************************************************/
            /********************************************* Hoja 5 *********************************************/
            /**************************************************************************************************/
                if($cantidad !== 1){
                    if($adicionales === ''){
                        hojaNueva($pdf, $orden, '4', '4');
                    }else{
                        hojaNueva($pdf, $orden, '5', '5');
                    }

                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(0, 5, utf8_decode('Cálculo de la muestra compuesta.'), 1, 1, 'C', true);

                    $pdf->SetFont('Arial', 'B', 8);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->MultiCell(15, 4, utf8_decode("\n\n\nMuestra Simple\n\n\n\n"), 1, 'C', true);
                    $pdf->SetXY($x + 15, $y);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->MultiCell(20, 4, utf8_decode("\n\n\nTiempo Hora (X)\n\n\n\n"), 1, 'C', true);
                    $pdf->SetXY($x + 20, $y);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->MultiCell(30, 4, utf8_decode("\n\n\nFlujo al tiempo X (Qtx) m3/s\n\n\n\n"), 1, 'C', true);
                    $pdf->SetXY($x + 30, $y);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->MultiCell(35, 4, utf8_decode("\n\n% de la alicuota de la muestra simple al tiempo X(%Mtx) \n % Mtx=(Qtx) (100) / Qt\n\n\n"), 1, 'C', true);
                    $pdf->SetXY($x + 35, $y);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->MultiCell(35, 4, utf8_decode("\n\nVolumen de la muestra simple \n (V ms) \n (ml)\n\n\n"), 1, 'C', true);
                    $pdf->SetXY($x + 35, $y);
                    $pdf->MultiCell(30, 4, utf8_decode("\nVolumen de alicuota de cada muestra simple \n (Vx) \n Vx = ( (Vms) (% Mtx) / 100 ) * 1.15\n\n"), 1, 'C', true);

                    $pdf->SetFont('Arial', '', 8);

                    $flujototal = 0;
                    $totalporalicuota = 0;
                    $totalvolalicuota = 0;
                    
                    for ($i=0; $i < $cantidad; $i++) {
                        if($mcompuestas[$i]['flujo'] !== "S/F"){
                            $flujototal += floatval($mcompuestas[$i]['flujo']);
                        }
                    }

                    for ($i=0; $i < $cantidad; $i++) { 
                      $pdf->Cell(15, 5, utf8_decode($i+1), 1, 0, 'C');
                      $pdf->Cell(20, 5, utf8_decode($mcompuestas[$i]['hora']), 1, 0, 'C');
                      $pdf->Cell(30, 5, $mcompuestas[$i]['flujo'], 1, 0, 'C');

                      $imprimirtotalporalicuota = "S/F";
                      if($mcompuestas[$i]['flujo'] !== "S/F"){
                        $poralicuota = ($mcompuestas[$i]['flujo'] * 100)/$flujototal;
                        $totalporalicuota += $poralicuota;
                        $imprimirtotalporalicuota = number_format(doubleval($poralicuota), 2);
                      }
                      $pdf->Cell(35, 5, utf8_decode($imprimirtotalporalicuota), 1, 0, 'C');
                      $pdf->Cell(35, 5, utf8_decode($mcompuestas[$i]['volumen']), 1, 0, 'C');

                      $imprimirvolalicuota = "S/F";
                      if($mcompuestas[$i]['flujo'] !== "S/F"){
                        $volalicuota = ($mcompuestas[$i]['volumen'] * $poralicuota * 1.15)/100;
                        $totalvolalicuota += $volalicuota;
                        $imprimirvolalicuota = number_format(doubleval($volalicuota), 2);
                      }
                      $pdf->Cell(30, 5, utf8_decode($imprimirvolalicuota), 1, 1, 'C');
                    }

                    $pdf->SetFont('Arial', 'B', 8);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->Cell(15, 4, utf8_decode(''), 0, 0, 'C');
                    $pdf->SetXY($x + 15, $y);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->MultiCell(20, 4, utf8_decode('Flujo Total (Qt)'), 1, 'C');
                    $pdf->SetXY($x + 20, $y);
                    $pdf->Cell(30, 8, utf8_decode($flujototal), 1, 0, 'C');
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    if(strpos($totalporalicuota,'.') !== false){
                        $totalporalicuota = number_format(doubleval($totalporalicuota), 5);
                    }
                    $pdf->Cell(35, 8, utf8_decode($totalporalicuota), 1, 0, 'C');
                    $pdf->SetXY($x + 35, $y);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->MultiCell(35, 4, utf8_decode('Volumen total de la muestra compuesta'), 1, 'C');
                    $pdf->SetXY($x + 35, $y);
                    if(strpos($totalvolalicuota,'.') !== false){
                        $totalvolalicuota = number_format(doubleval($totalvolalicuota), 5);
                    }
                    $pdf->Cell(30, 8, utf8_decode($totalvolalicuota), 1, 1, 'C');
                    $pdf->Ln(4);

                    croquisPDF($pdf, $cantidad, $croquis, $orden, $responsables);
                }
        }

        if($errores > 0){
            $mensaje = $error;
            include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
            exit();
        }
        $pdf->Output();
        exit();
    }

/**************************************************************************************************/
/* Acción por defualt, llevar a búsqueda de ordenes */
/**************************************************************************************************/
    //include 'formabuscaorden.html.php';
    //exit();
    $mensaje='No se accedió adecuadamente al informe.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();

/**************************************************************************************************/
/* Función para añadir nueva hoja */
/**************************************************************************************************/
//Recibe el objeto pdf, el objeto orden, número de página y el número de páginas totales
    function hojaNueva($pdf, $orden, $pagina, $paginas){
      $pdf->AddPage();
      $pdf->SetMargins(20, 0, 25);

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


/**************************************************************************************************/
/* Función para tabla de observaciones por toma */
/**************************************************************************************************/
//Recibe el objeto de pdf, el array de mcompuestas y el valor de cantidad
    function observacionesPDF($pdf, $mcompuestas, $cantidad){
        //var_dump($cantidad);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 5, utf8_decode('Caracteristicas y observaciones por toma.'), 1, 1, 'C', true);
        //var_dump($mcompuestas);
        for ($i=0; $i < $cantidad; $i++) { 
            $pdf->SetWidths(array(50,115));
            $pdf->SetAligns(array('C','J'));
            $pdf->SetFonts(array('B',''));
            $pdf->SetFontSizes(array(9,9));
            $pdf->carobsRow(array(utf8_decode('Toma '. ($i+1) .' ('.$mcompuestas[$i]['hora'].')'),array(utf8_decode('Caracteristicas: '.$mcompuestas[$i]['caracteristicas']),utf8_decode('Observaciones: '.$mcompuestas[$i]['observaciones']))));
        }
        $pdf->carobsRow(array(utf8_decode('Toma Compuesta'),array(utf8_decode('Caracteristicas: '.$mcompuestas[$cantidad]['caracteristicas']),utf8_decode('Observaciones: '.$mcompuestas[$cantidad]['observaciones']))));
    }

/**************************************************************************************************/
/* Función para tabla de parametros */
/**************************************************************************************************/
//Recibe el objeto de pdf, los array de muestra, parametros y maximos
    function parametrosPDF($pdf, $muestra, $parametros, $maximos, $cantidad, $parametros2, $gya, $coliformes, $metodos){
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 5, utf8_decode('Informe de Análisis'), 1, 1, 'C', true);

        $pdf->Cell(45, 8, utf8_decode('Parámetros'), 1, 0, 'C', true);
        $pdf->Cell(25, 8, utf8_decode('Unidades'), 1, 0, 'C', true);
        $pdf->Cell(25, 8, utf8_decode('Resultado'), 1, 0, 'C', true);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(30, 4, utf8_decode('Limites Máximos Permisibles (LMP)'), 1, 'C', true);
        $pdf->SetXY($x + 30, $y);
        $pdf->Cell(40, 8, utf8_decode('Método'), 1, 1, 'C', true);

        $params = array("Grasas y Aceites" => "GyA",
                        "Sólidos Sedimentables" => "ssedimentables",
                        "Sólidos Suspendidos Totales" => "ssuspendidos",
                        "DBO" => "dbo",
                        "Nitrógeno Kjeldahl" => "nkjedahl",
                        "Nitrógeno de Nitritos" => "nitritos",
                        "Nitrógeno de Nitratos" => "nitratos",
                        "Nitrógeno Total" => "nitrogeno",
                        "Fósforo Total" => "fosforo",
                        "Arsénico" => "arsenico",
                        "Cadmio" => "cadmio",
                        "Cianuros" => "cianuros",
                        "Cobre" => "cobre",
                        "Cromo Total" => "cromo",
                        "Mercurio" => "mercurio",
                        "Níquel" => "niquel",
                        "Plomo" => "plomo",
                        "Zinc" => "zinc",
                        "Coliformes Fecales" => "coliformes",
                        "Huevos de Helminto" => "hdehelminto"
                    );

        $formulario = array("fechareporte","ssedimentables","ssuspendidos","dbo","nkjedahl",
                            "nitritos","nitratos","nitrogeno","fosforo","arsenico","cadmio","cianuros",
                            "cobre","cromo","mercurio","niquel","plomo","zinc", "hdehelminto");

        $formulario2 = array("GyA","coliformes","ssedimentables","ssuspendidos","dbo",
                             "nitrogeno","fosforo","arsenico","cadmio","cianuros","cobre","cromo","mercurio",
                             "niquel","plomo","zinc","hdehelminto");
        
        $pdf->SetFont('Arial', '', 9);
        foreach ($params as $key => $value) {
            if($value !== "GyA" AND $value !== "coliformes"){
                if($parametros[$value] === '' OR $parametros[$value] === '0.00'){
                    continue;
                }
            }
            if(($value == "GyA" and $gya === '') OR ($value == "coliformes" and $coliformes === '') ){
                continue;
            }

            $pdf->Cell(45, 6, utf8_decode($key), 1, 0, 'L');

            if($value == "coliformes"):
                $pdf->Cell(25, 6, utf8_decode('NMP/100ml'), 1, 0, 'C');
            elseif($value == "hdehelminto"):
                $pdf->Cell(25, 6, utf8_decode('Huevos /L'), 1, 0, 'C');
            else:
                $pdf->Cell(25, 6, utf8_decode('mg/L'), 1, 0, 'C');
            endif;

            if($value == "GyA"):
                if($cantidad === 1){
                    if(in_array($value, $formulario2)){
                        if(doubleval($parametros2[0]['GyA']) > doubleval($maximos[$value])){
                            $pdf->SetFont('Arial', 'B', 9);
                        }
                    }
                    $pdf->Cell(25, 6, utf8_decode(number_format(doubleval($parametros2[0]['GyA']), 5)), 1, 0, 'C');
                }else{
                    if(in_array($value, $formulario2)){
                        if($gya > doubleval($maximos[$value])){
                            $pdf->SetFont('Arial', 'B', 9);
                        }
                    }
                    if(bccomp($gya, floatval(12))==0){
                            $gya = '< '.$gya;
                        }
                    $pdf->Cell(25, 6, utf8_decode($gya), 1, 0, 'C');
                }
            elseif($value == "coliformes"):
                if($cantidad === 1){
                    if(in_array($value, $formulario2)){
                        if(doubleval($parametros2[0]['coliformes']) > doubleval($maximos[$value])){
                            $pdf->SetFont('Arial', 'B', 9);
                        }
                    }
                    if( strpos($parametros2[0]['coliformes'], "<") !== FALSE){
                        $pdf->Cell(25, 6, utf8_decode($parametros2[0]['coliformes']), 1, 0, 'C');
                    }else{
                        $pdf->Cell(25, 6, utf8_decode(number_format(doubleval($parametros2[0]['coliformes']), 5)), 1, 0, 'C');
                    }
                }else{
                    if(in_array($value, $formulario2)){
                        if(doubleval($coliformes) > doubleval($maximos[$value])){
                            $pdf->SetFont('Arial', 'B', 9);
                        }
                    }
                    $pdf->Cell(25, 6, utf8_decode(number_format(doubleval($coliformes), 5)), 1, 0, 'C');
                }
            else:
                if(in_array($value, $formulario2)){
                    if( strcmp($maximos[$value], "N.A.") !== 0){
                        $param = $parametros[$value];
                        if(strpos($parametros[$value], '<') !== false){
                            $param = explode('<', $parametros[$value]);
                            $param = $param[1];
                        }
                        if(doubleval($param) > doubleval($maximos[$value])){
                            $pdf->SetFont('Arial', 'B', 9);
                        }
                    }
                }
                $pdf->Cell(25, 6, utf8_decode((in_array($value, $formulario)) ? $parametros[$value] : ""), 1, 0, 'C');
            endif;

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 6, utf8_decode((in_array($value, $formulario2)) ? $maximos[$value] : "No Aplica"), 1, 0, 'C');
            $pdf->Cell(40, 6, utf8_decode($metodos[$value]), 1, 1, 'C');
        }
        $pdf->Ln();
    }

/**************************************************************************************************/
/* Función para tabla de adicionales */
/**************************************************************************************************/
//Recibe el objeto de pdf y el array de adicionales
    function adicionalesPDF($pdf, $adicionales){

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 5, utf8_decode('Informe de Análisis Adicionales'), 1, 1, 'C', true);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(45, 8, utf8_decode('Parámetros'), 1, 0, 'C', true);
        $pdf->Cell(25, 8, utf8_decode('Unidades'), 1, 0, 'C', true);
        $pdf->Cell(25, 8, utf8_decode('Resultado'), 1, 0, 'C', true);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(30, 4, utf8_decode('Limites Máximos Permisibles (LMP)'), 1, 'C', true);
        $pdf->SetXY($x + 30, $y);
        $pdf->Cell(40, 8, utf8_decode('Método'), 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);
        foreach ($adicionales as $value) {
            $pdf->Cell(45, 5, utf8_decode($value['nombre']), 1, 0, 'L');
            $pdf->Cell(25, 5, utf8_decode($value['unidades']), 1, 0, 'C');
            $pdf->Cell(25, 5, utf8_decode($value['resultado']), 1, 0, 'C');
            $pdf->Cell(30, 5, utf8_decode('No Aplica'), 1, 0, 'C');
            $pdf->Cell(40, 5, utf8_decode($value['metodo']), 1, 1, 'C');
        }
        $pdf->Ln();
    }

/**************************************************************************************************/
/* Función para dibujar el croquis */
/**************************************************************************************************/
//Recibe el objeto de pdf, el valor de cantidad y la imagen del croquis
    function croquisPDF($pdf, $cantidad, $croquis, $orden, $responsables){
        //var_dump($croquis);
        $imagen = $_SERVER['DOCUMENT_ROOT'].'/reportes/nom001/documentos/'.$croquis['nombrearchivado'];
        $pdf->Cell(0, 5, utf8_decode('Croquis del lugar donde se tomó la muestra '.$croquis['nombrearchivado']), 1, 1, 'C', true);
        if($croquis!==false){
            if($cantidad === 1){
                $pdf->Image($imagen, 20, 85, 165, 75);
            }elseif($cantidad === 2){
                $pdf->Image($imagen, 20, 144, 165, 75);
            }elseif($cantidad === 4){
                $pdf->Image($imagen, 20, 154, 165, 75);
            }elseif($cantidad === 6){
                $pdf->Image($imagen, 20, 164, 165, 75);
            }
        }
        $pdf->Cell(0, 75, '', 1, 1, 'C');
        $pdf->Ln(3);

        $pdf->Cell(60, 5, utf8_decode('Responsable del muestreo'), 0, 0, 'C');
        $pdf->Cell(45, 5, '', 0, 0, 'C');
        $pdf->Cell(60, 5, utf8_decode('Responsable del estudio'), 0, 1, 'C');

        $pdf->Cell(0, 5, '', 0, 1);
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'U', 8);
        $pdf->Cell(60, 5, utf8_decode('                                                                        '), 0, 0, 'C');
        $pdf->Cell(45, 5, '', 0, 0, 'C');
        $pdf->Cell(60, 5, utf8_decode('                                                                        '), 0, 0, 'C');
        $pdf->Ln(4);

        $pdf->SetFont('Arial', 'B', 8);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(60, 4, utf8_decode($responsables[0]['nombre'].' '.$responsables[0]['ap'].' '.$responsables[0]['am']), 0, 0, 'C');
        $pdf->SetXY($x + 105, $y);
        $pdf->MultiCell(60, 4, utf8_decode($orden['signatarionombre'].' '.$orden['signatarioap'].' '.$orden['signatarioam']), 0, 'C');
    }


    
    ?>