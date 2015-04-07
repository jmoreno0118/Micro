<?php
 /********** Norma 001 **********/
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';

/**************************************************************************************************/
/* Modificaciones al FPDF */
/**************************************************************************************************/

    //include ('fpdf/fpdf.php');
    include ($_SERVER['DOCUMENT_ROOT'].'/reportes/includes/fpdf/fpdf.php');

    class PDF extends FPDF
    {
        function Header()
        {
            $this->Image("logolaboratorio3.gif", 20, 15, 165, 33);
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
/* Búsqueda de ordenes de la norma 001 */
/**************************************************************************************************/
    if(isset($_POST['accion']) AND ($_POST['accion']=='buscar' OR $_POST['accion']=='informe') OR (isset($_GET['ot']) AND isset($_GET['id'])))
    {
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
        try   
        {
            if(isset($_GET['ot']) AND isset($_GET['id'])){
                $sql='SELECT ordenestbl.id, ordenestbl.ot, clientestbl.Razon_Social, clientestbl.Calle_Numero, clientestbl.Colonia, clientestbl.Ciudad, 
                    clientestbl.Estado, clientestbl.Nombre_Usuario,  ordenestbl.signatario, ordenestbl.ot, ordenestbl.fechalta, clientestbl.Giro_Empresa,
                    clientestbl.Codigo_Postal, muestreosaguatbl.responsable, muestreosaguatbl.fechamuestreo
                    FROM clientestbl
                    INNER JOIN ordenestbl ON clientestbl.Numero_Cliente = ordenestbl.clienteidfk
                    INNER JOIN generalesaguatbl ON ordenestbl.id = generalesaguatbl.ordenaguaidfk
                    INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
                    INNER JOIN estudiostbl ON ordenestbl.id = estudiostbl.ordenidfk
                    WHERE estudiostbl.nombre="NOM 001" AND ordenestbl.ot = :ot AND ordenestbl.id = :id';
                $s=$pdo->prepare($sql);
                $s->bindValue(':ot', $_GET['ot']);
                $s->bindValue(':id', $_GET['id']);
            $s->execute();
            }else{
                $sql='SELECT ordenestbl.id, ordenestbl.ot, clientestbl.Razon_Social, clientestbl.Calle_Numero, clientestbl.Colonia, clientestbl.Ciudad, 
                    clientestbl.Estado, clientestbl.Nombre_Usuario,  ordenestbl.signatario, ordenestbl.ot, ordenestbl.fechalta, clientestbl.Giro_Empresa,
                    clientestbl.Codigo_Postal, muestreosaguatbl.responsable, muestreosaguatbl.fechamuestreo
                    FROM clientestbl
                    INNER JOIN ordenestbl ON clientestbl.Numero_Cliente = ordenestbl.clienteidfk
                    INNER JOIN generalesaguatbl ON ordenestbl.id = generalesaguatbl.ordenaguaidfk
                    INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
                    INNER JOIN estudiostbl ON ordenestbl.id = estudiostbl.ordenidfk
                    WHERE estudiostbl.nombre="NOM 001" AND ordenestbl.ot = :ot ';
                $s=$pdo->prepare($sql);
                $s->bindValue(':ot', $_POST['ot']);
                $s->execute();
            }
            $orden = $s->fetch();
        }
        catch (PDOException $e)
        {
        $mensaje='Error al tratar de obtener información de la orden.';
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

        $pdf->Ln(26);
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
        $mensaje='Hubo un error extrayendo la orden.'.$e;
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
        $pdf->Cell(0, 5, utf8_decode($orden['Razon_Social']), 0 ,1); ////////////////// Nombre de empresa
        $pdf->Ln(1);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 5, utf8_decode($orden['Calle_Numero']),0 ,1); //////////// Dirección
        $pdf->Ln(1);
        $pdf->Cell(0, 5, utf8_decode("Col. ".$orden['Colonia'].", ".$orden['Ciudad'].", ".$orden['Estado']), 0, 1); //////////// Dirección
        $pdf->Ln();
        $pdf->Ln();

        $pdf->Cell(0, 5, utf8_decode("At'n.: ".$orden['Nombre_Usuario']."."), 0, 1, 'R'); //////////////////////////////// Atn
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
        $mensaje='Hubo un error extrayendo la orden.'.$e;
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
        exit();
        }

        $ident = "";
        foreach ($identificaciones as $key => $value) {
            $ident .= '"'.$value['identificacion'].'", '; 
        }
        $ident = rtrim($ident, ", ");

        $pdf->SetFont('Arial', '', 11);
        if(count($identificaciones) > 1){
            $pdf->MultiCell(0, 5, utf8_decode('Con relación a las determinaciones analíticas practicadas a las muestras de agua identificadas como: '.$ident.', tomadas por '.$orden['responsable'].' el día '. date('d', strtotime($orden['fechalta']))." de ".$meses[date('n', strtotime($orden['fechalta']))-1]. " del ".date('Y',strtotime($orden['fechalta'])).', nos permitimos informarle lo siguiente:'), 0, 'J');
        }else{
            $pdf->MultiCell(0, 5, utf8_decode('Con relación a las determinaciones analíticas practicadas a la muestra de agua identificada como: '.$ident.', tomada por '.$orden['responsable'].' el día '. date('d', strtotime($orden['fechalta']))." de ".$meses[date('n', strtotime($orden['fechalta']))-1]. " del ".date('Y',strtotime($orden['fechalta'])).', nos permitimos informarle lo siguiente:'), 0, 'J');
        }
        $pdf->Ln();

        $pdf->MultiCell(0, 5, utf8_decode('La muestra fué analizada por el Laboratorio del Grupo Microanálisis,  S.A. de C.V.,  el cual cuenta con acreditación ante la Entidad Mexicana de Acreditación (EMA).'), 0, 'J');
        $pdf->Ln();

        $pdf->MultiCell(0, 5, utf8_decode('Los métodos de muestreo y análisis, están referenciados en la Normatividad Nacional, los cuales son indicados en los resultados de laboratorio para cada sustancia.'), 0, 'J');
        $pdf->Ln();

        $pdf->MultiCell(0, 5, utf8_decode('El presente informe está integrado por informe de resultados, resultados del laboratorio, hojas de campo y cadena de custodia.'), 0, 'J');
        $pdf->Ln();

        $pdf->MultiCell(0, 5, utf8_decode('Agradecemos su interés en nuestros servicios y esperamos poder atenderle en futuras ocasiones.'), 0,  'J');
        $pdf->Ln(4);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, utf8_decode('Acreditación EMA No. AG-016-008/12.  Vigencia: A partir del 09 de Agosto de 2012.'), 0,  'J');
        $pdf->Ln(4);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 5, utf8_decode('Atentamente.'));
        $pdf->Ln();
        $pdf->Ln(20);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 5, utf8_decode('Víctor Manuel Hernández Soria.'));
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 5, utf8_decode('Signatario Autorizado por la E.M.A.'));
        $pdf->Ln();
        $pdf->Ln(15);

        try   
        {
         $sql='SELECT generalesaguatbl.numedicion, muestreosaguatbl.fechamuestreo, muestreosaguatbl.identificacion, generalesaguatbl.lugarmuestreo,
              generalesaguatbl.descriproceso, generalesaguatbl.materiasusadas, generalesaguatbl.tratamiento, generalesaguatbl.Caracdescarga,
              generalesaguatbl.receptor, generalesaguatbl.estrategia, generalesaguatbl.observaciones, muestreosaguatbl.temperatura,
              muestreosaguatbl.pH, muestreosaguatbl.conductividad, muestreosaguatbl.cloro, muestreosaguatbl.mflotante, muestreosaguatbl.olor,
              muestreosaguatbl.color, muestreosaguatbl.turbiedad, muestreosaguatbl.GyAvisual, muestreosaguatbl.burbujas, muestreosaguatbl.id as "muestreoaguaid",
              generalesaguatbl.nom01maximosidfk, muestreosaguatbl.identificacion, generalesaguatbl.tipomediciones, muestreosaguatbl.caltermometro,
              generalesaguatbl.id as "generalaguaid"
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
         $mensaje='Hubo un error extrayendo la orden.'.$e;
         include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
         exit();
        }

        foreach ($muestras as $muestra) {
            $cantidad = 1; if($muestra['tipomediciones'] == '8'){$cantidad = 4;}elseif($muestra['tipomediciones'] === '24'){$cantidad = 6;}
            
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
                   FROM nom01maximostbl
                   WHERE id = :id';
             $s = $pdo->prepare($sql);
             $s->bindValue(':id', $muestra['nom01maximosidfk']);
             $s->execute();
             $maximos = $s->fetch();
            }
            catch (PDOException $e)
            {
             $mensaje='Hubo un error extrayendo la orden.'.$e;
             include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
             exit();
            }

    //--------------------------------------------------------------------------------------------------------------------
    //Obtener parametros2 y mcompuesta------------------------------------------------------------------------------------
    //--------------------------------------------------------------------------------------------------------------------
            try   
            {
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

              $sql="SELECT DATE_FORMAT(mcompuestastbl.hora, '%H:%i') as 'hora', mcompuestastbl.flujo, mcompuestastbl.volumen, mcompuestastbl.observaciones,
                    mcompuestastbl.caracteristicas
                    FROM laboratoriotbl
                    INNER JOIN mcompuestastbl ON laboratoriotbl.mcompuestaidfk = mcompuestastbl.id
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
            }
            catch (PDOException $e)
            {
             $mensaje='Hubo un error extrayendo la orden.'.$e;
             include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
             exit();
            }
    //--------------------------------------------------------------------------------------------------------------------
    //Obtener adicionales-------------------------------------------------------------------------------------------------
    //--------------------------------------------------------------------------------------------------------------------
            try   
                {
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
                                       "resultado" => $linea["resultado"]);
                 }
                }
                catch (PDOException $e)
                {
                 $mensaje='Hubo un error extrayendo la orden.'.$e;
                 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
                 exit();
                }

    //--------------------------------------------------------------------------------------------------------------------
    //Obtener croquis----------------------------------------------------------------------------------------
    //--------------------------------------------------------------------------------------------------------------------
            try   
            {
             $sql='SELECT *
                   FROM croquistbl
                   WHERE generalaguaidfk = :id';
             $s=$pdo->prepare($sql);
             $s->bindValue(':id', $muestra['generalaguaid']);
             $s->execute();
             $croquis = $s->fetch();
             //var_dump($croquis);
            }
            catch (PDOException $e)
            {
             $mensaje='Hubo un error extrayendo la orden.'.$e;
             include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
             exit();
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
                $pdf->Cell(50, 5, utf8_decode('N° de muestra'), 1, 0, 'C');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(50, 5, utf8_decode($muestra['numedicion']), 1, 0, 'R');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(20, 5, utf8_decode('Fecha'), 1, 0, 'C');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($muestra['fechamuestreo']), 1, 1, 'R');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Compañía'), 1, 0, 'C');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(0, 5, utf8_decode($orden['Razon_Social']), 1, 1, 'R');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Giro de la empresa'), 1, 0, 'C');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($orden['Giro_Empresa']), 1, 1, 'R');

                $pdf->SetWidths(array(50,115));
                $pdf->SetFonts(array('B',''));
                $pdf->SetFontSizes(array(9));
                $pdf->SetAligns(array('C','R'));
                $pdf->Row(array(utf8_decode('Dirección'),utf8_decode($orden['Calle_Numero']."\nCol. ".$orden['Colonia']." C.P. ".$orden['Codigo_Postal']." ".$orden['Ciudad']." ".$orden["Estado"])));
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 5, utf8_decode('Datos del muestreo'), 1, 1, 'C', true);

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Identificación de la muestra'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($muestra['identificacion']), 1, 1, 'R');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Lugar de muestreo'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($muestra['lugarmuestreo']), 1, 1, 'R');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Descripción del proceso'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($muestra['descriproceso']), 1, 1, 'R');

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->MultiCell(50, 4, utf8_decode('Materias primas usadas en el proceso de descarga'), 1, 'L');

                $pdf->SetXY($x + 50, $y);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 8, utf8_decode($muestra['materiasusadas']), 1, 1, 'R');

                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->MultiCell(50, 4, utf8_decode('Tratamiento del agua antes de la descarga'), 1, 'L');

                $pdf->SetXY($x + 50, $y);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 8, utf8_decode($muestra['tratamiento']), 1, 1, 'R');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Características de la descarga'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($muestra['Caracdescarga']), 1, 1, 'R');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Tipo de receptor de la descarga'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode($muestra['receptor']), 1, 1, 'R');

                $pdf->SetWidths(array(50,115));
                $pdf->SetFonts(array('B',''));
                $pdf->SetFontSizes(array(9));
                $pdf->SetAligns(array('L','R'));
                $pdf->Row(array(utf8_decode('Estrategia de muestreo'),utf8_decode($muestra['estrategia'])));
                //$pdf->MultiCell(0, 4, utf8_decode($muestra['estrategia']."adfhddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd"), 1, 'J');

                $pdf->SetWidths(array(50,115));
                $pdf->SetFonts(array('B',''));
                $pdf->SetFontSizes(array(9));
                $pdf->SetAligns(array('L','R'));
                $pdf->Row(array(utf8_decode('Observaciones de campo'),utf8_decode($muestra['observaciones'])));
                //$pdf->MultiCell(0, 4, utf8_decode($muestra['observaciones']."adfhdddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd"), 1, 'J');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(50, 5, utf8_decode('Conservación de muestra'), 1, 0, 'L');

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 5, utf8_decode('Refrigeración < 4 °C'), 1, 1, 'R');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(0, 5, utf8_decode('Parámetros de Campo'), 1, 1, 'C', true);
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(40, 8, utf8_decode('Parámetros'), 1, 0, 'C', true);
                $pdf->Cell(20, 8, utf8_decode('Unidades'), 1, 0, 'C', true);
                $pdf->Cell(25, 8, utf8_decode('Medición'), 1, 0, 'C', true);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell(40, 8, utf8_decode('Incertidumbre Estándar'), 1, 'C', true);
                $pdf->SetXY($x + 40, $y);
                $pdf->MultiCell(40, 4, utf8_decode('Limites Máximos Permisibles'), 1, 'C', true);

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(40, 5, utf8_decode('Temperatura'), 1, 0, 'C');
                $pdf->Cell(20, 5, utf8_decode('°C'), 1, 0, 'C');
                $pdf->Cell(25, 5, utf8_decode(($muestra['temperatura'] - $muestra['caltermometro'])), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('±'.($muestra['temperatura'] * 1.645 * 0.02866)), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('40'), 1, 1, 'C');

                $pdf->Cell(40, 5, utf8_decode('pH'), 1, 0, 'C');
                $pdf->Cell(20, 5, utf8_decode('U de pH'), 1, 0, 'C');
                $pdf->Cell(25, 5, utf8_decode($muestra['pH']), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('±'.($muestra['pH'] * 1.645 * 0.0037)), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('de 5 a 10'), 1, 1, 'C');
                
                $pdf->Cell(40, 5, utf8_decode('Conductividad'), 1, 0, 'C');
                $pdf->Cell(20, 5, utf8_decode('ms/m'), 1, 0, 'C');
                $pdf->Cell(25, 5, utf8_decode($muestra['conductividad']), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('±'.($muestra['conductividad'] * 1.645 * 0.00964)), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 1, 'C');
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
                $pdf->Cell(40, 5, utf8_decode(($muestra['mflotante'] === '1')? 'Presente' : 'Ausente'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('Ausente'), 1, 1, 'C');

                $pdf->Cell(45, 5, utf8_decode('Olor'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode(($muestra['olor'] === '1')? 'Sí' : 'No'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 1, 'C');

                $pdf->Cell(45, 5, utf8_decode('Color visual'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode(($muestra['color'] === '1')? 'Sí' : 'No'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 1, 'C');

                $pdf->Cell(45, 5, utf8_decode('Turbiedad visual'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode(($muestra['turbiedad'] === '1')? 'Sí' : 'No'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 1, 'C');

                $pdf->Cell(45, 5, utf8_decode('Grasas y aceites visual'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode(($muestra['GyAvisual'] === '1')? 'Sí' : 'No'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 1, 'C');

                $pdf->Cell(45, 5, utf8_decode('Burbujas y espuma visual'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode(($muestra['burbujas'] === '1')? 'Sí' : 'No'), 1, 0, 'C');
                $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 1, 'C');
                $pdf->Ln();


                if(count($parametros2)<=0){
                    $mensaje='Faltan llenar datos de las mediciones.';
                    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
                    exit();
                }
                $promcoliformes = 0;
                for ($i=0; $i < $cantidad; $i++) {
                    $promcoliformes += $parametros2[$i]['coliformes'];
                }
                $promcoliformes /= $cantidad;

                if($cantidad === 1){
                    parametrosPDF($pdf, $muestra, $parametros, $maximos, $cantidad, $parametros2, '', $promcoliformes);
                }else{
                    observacionesPDF($pdf, $mcompuestas, $cantidad);
                }

            /**************************************************************************************************/
            /********************************************* Hoja 3 *********************************************/
            /**************************************************************************************************/
                if($cantidad === 1){
                    if($adicionales === ''){
                        hojaNueva($pdf, $orden, '3', '3');
                        croquisPDF($pdf, $cantidad, $croquis);
                    }else{
                        hojaNueva($pdf, $orden, '3', '4');
                        adicionalesPDF($pdf, $adicionales);

                        hojaNueva($pdf, $orden, '4', '4');
                        croquisPDF($pdf, $cantidad, $croquis);
                    }
                }else{
                    if($adicionales === ''){
                        hojaNueva($pdf, $orden, '3', '4');
                    }else{
                        hojaNueva($pdf, $orden, '3', '5');
                    }
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
                    for ($i=0; $i < $cantidad; $i++) {
                      $pdf->Cell(35, 5, $parametros2[$i]['GyA'], 1, 0, 'C');
                      $gyatotal += floatval($parametros2[$i]['GyA']);

                      $pdf->Cell(40, 5, $mcompuestas[$i]['flujo'], 1, 0, 'C');
                      $flujototal += floatval($mcompuestas[$i]['flujo']);

                      $concentracion = "S/F";
                      if($mcompuestas[$i]['flujo'] !== "S/F"){
                        $concentracion = $mcompuestas[$i]['flujo'] * $parametros2[$i]['GyA'];
                        $totalconcentracion += $concentracion;
                      }

                      $pdf->Cell(50, 5, utf8_decode($concentracion), 1, 1, 'C');
                    }

                    $pdf->SetFont('Arial', '', 10);
                    $pdf->SetXY($x + 35 + 40 + 50, $y);
                    $promedio = ($totalconcentracion === 0)? $gyatotal/$cantidad : $totalconcentracion/$flujototal;
                    $pdf->MultiCell(40, ($cantidad === 4) ? 4 : 6, utf8_decode("\n\n".$promedio."\n\n\n"), 1, 'C');

                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(35, 5, '', 0, 0, 'C');
                    $pdf->Cell(40, 5, utf8_decode($flujototal), 1, 0, 'C', true);
                    $pdf->Cell(50, 5, utf8_decode($totalconcentracion), 1, 1, 'C', true);
                    $pdf->Ln();

                    parametrosPDF($pdf, $muestra, $parametros, $maximos, $cantidad, $parametros2, $promedio, $promcoliformes);
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
                    $pdf->MultiCell(30, 4, utf8_decode("\nVolumen de alicuota de cada muestra simple \n (Vx) \n Vx = (Vms) (% Mtx) / 100\n\n"), 1, 'C', true);

                    $pdf->SetFont('Arial', '', 8);

                    $flujototal = 0;
                    $totalporalicuota = 0;
                    $totalvolalicuota = 0;
                    
                    for ($i=0; $i < $cantidad; $i++) {
                        $flujototal += floatval($mcompuestas[$i]['flujo']);
                    }

                    for ($i=0; $i < $cantidad; $i++) { 
                      $pdf->Cell(15, 5, utf8_decode($i+1), 1, 0, 'C');
                      $pdf->Cell(20, 5, utf8_decode($mcompuestas[$i]['hora']), 1, 0, 'C');
                      $pdf->Cell(30, 5, $mcompuestas[$i]['flujo'], 1, 0, 'C');

                      $poralicuota = "S/F";
                      if($mcompuestas[$i]['flujo'] !== "S/F"){
                        $poralicuota = ($mcompuestas[$i]['flujo'] * 100)/$flujototal;
                        $totalporalicuota += $poralicuota;
                      }

                      $pdf->Cell(35, 5, utf8_decode($poralicuota), 1, 0, 'C');
                      $pdf->Cell(35, 5, utf8_decode($mcompuestas[$i]['volumen']), 1, 0, 'C');

                      $volalicuota = "S/F";
                      if($mcompuestas[$i]['flujo'] !== "S/F"){
                        $volalicuota = ($mcompuestas[$i]['volumen'] * $poralicuota)/100;
                        $totalvolalicuota += $volalicuota;
                      }
                      $pdf->Cell(30, 5, utf8_decode($volalicuota), 1, 1, 'C');
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
                    $pdf->Cell(35, 8, utf8_decode($totalporalicuota), 1, 0, 'C');
                    $pdf->SetXY($x + 35, $y);
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();
                    $pdf->MultiCell(35, 4, utf8_decode('Volumen total de la muestra compuesta'), 1, 'C');
                    $pdf->SetXY($x + 35, $y);
                    $pdf->Cell(30, 8, utf8_decode($totalvolalicuota), 1, 1, 'C');
                    $pdf->Ln(4);

                    croquisPDF($pdf, $cantidad, $croquis);
                }
        }
        $pdf->Output();
        exit();
    }

/**************************************************************************************************/
/* Acción por defualt, llevar a búsqueda de ordenes */
/**************************************************************************************************/
    //include 'formabuscaorden.html.php';
    //exit();
    $mensaje='Hubo un error.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
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
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 5, utf8_decode('Caracteristicas y observaciones por toma.'), 1, 1, 'C', true);

        for ($i=0; $i < $cantidad; $i++) { 
            $pdf->SetWidths(array(50,115));
            $pdf->SetAligns(array('C','J'));
            $pdf->SetFonts(array('B',''));
            $pdf->SetFontSizes(array(9,9));
            $pdf->carobsRow(array(utf8_decode('Toma '. ($i+1) .' ('.$mcompuestas[$i]['hora'].')'),array(utf8_decode($mcompuestas[$i]['observaciones']),utf8_decode($mcompuestas[$i]['caracteristicas']))));
        }
        $pdf->carobsRow(array(utf8_decode('Toma Compuesta('.$mcompuestas[$cantidad]['hora'].')'),array(utf8_decode($mcompuestas[$cantidad]['observaciones']),utf8_decode($mcompuestas[$cantidad]['caracteristicas']))));
    }

/**************************************************************************************************/
/* Función para tabla de parametros */
/**************************************************************************************************/
//Recibe el objeto de pdf, los array de muestra, parametros y maximos
    function parametrosPDF($pdf, $muestra, $parametros, $maximos, $cantidad, $parametros2, $gya, $coliformes){
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

        $metodos = array("GyA" => "NMX-AA-005-SCFI-2013",
                        "ssedimentables" => "NMX-AA-004-2013",
                        "ssuspendidos" => "NMX-AA-034-SCFI-2001",
                        "dbo" => "NMX-AA-028-SCFI-2001",
                        "nkjedahl" => "NMX-AA-026-SCFI-2010",
                        "nitritos" => "NMX-AA-099-SCFI-2006",
                        "nitratos" => "NMX-AA-082-1986",
                        "nitrogeno" => "Calculado",
                        "fosforo" => "NMX-AA-029-SCFI-2001",
                        "arsenico" => "EPA 6010C",
                        "cadmio" => "EPA 6010C",
                        "cianuros" => "NMX-AA-058-SCFI-2001",
                        "cobre" => "EPA 6010C",
                        "cromo" => "EPA 6010C",
                        "mercurio" => "NMX-AA-051-SCFI-2001",
                        "niquel" => "EPA 6010C",
                        "plomo" => "EPA 6010C",
                        "zinc" => "EPA 6010C",
                        "coliformes" => "NMX-AA-042-1987",
                        "hdehelminto" => "NMX-AA-113-SCFI-2012" 
                    );
        
        $pdf->SetFont('Arial', '', 9);
        foreach ($params as $key => $value) {
            $pdf->Cell(45, 5, utf8_decode($key), 1, 0, 'L');

            if($value == "coliformes"):
                $pdf->Cell(25, 5, utf8_decode('NMP/100ml'), 1, 0, 'C');
            elseif($value == "hdehelminto"):
                $pdf->Cell(25, 5, utf8_decode('Huevos /L'), 1, 0, 'C');
            else:
                $pdf->Cell(25, 5, utf8_decode('mg/L'), 1, 0, 'C');
            endif;

            if($value == "GyA"):
                if($cantidad === 1){
                    if(in_array($value, $formulario2)){
                        if(doubleval($parametros2[0]['GyA']) > doubleval($maximos[$value])){
                            $pdf->SetFont('Arial', 'B', 9);
                        }
                    }
                    $pdf->Cell(25, 5, utf8_decode(number_format(doubleval($parametros2[0]['GyA']), 5)), 1, 0, 'C');
                }else{
                    if(in_array($value, $formulario2)){
                        if($gya > doubleval($maximos[$value])){
                            $pdf->SetFont('Arial', 'B', 9);
                        }
                    }
                    $pdf->Cell(25, 5, utf8_decode(number_format($gya, 5)), 1, 0, 'C');
                }
            elseif($value == "coliformes"):
                if($cantidad === 1){
                    if(in_array($value, $formulario2)){
                        if(doubleval($parametros2[0]['coliformes']) > doubleval($maximos[$value])){
                            $pdf->SetFont('Arial', 'B', 9);
                        }
                    }
                    $pdf->Cell(25, 5, utf8_decode(number_format(doubleval($parametros2[0]['coliformes']), 5)), 1, 0, 'C');
                }else{
                    if(in_array($value, $formulario2)){
                        if(doubleval($coliformes) > doubleval($maximos[$value])){
                            $pdf->SetFont('Arial', 'B', 9);
                        }
                    }                    
                    $pdf->Cell(25, 5, utf8_decode(number_format($coliformes, 5)), 1, 0, 'C');
                }
            else:
                if(in_array($value, $formulario2)){
                        if(doubleval($parametros[$value]) > doubleval($maximos[$value])){
                            $pdf->SetFont('Arial', 'B', 9);
                        }
                    }
                $pdf->Cell(25, 5, utf8_decode((in_array($value, $formulario)) ? $parametros[$value] : ""), 1, 0, 'C');
            endif;

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(30, 5, utf8_decode((in_array($value, $formulario2)) ? $maximos[$value] : "No Aplica"), 1, 0, 'C');
            $pdf->Cell(40, 5, utf8_decode($metodos[$value]), 1, 1, 'C');
        }
        $pdf->Ln(1);
        $pdf->SetFont('Arial', 'BU', 8);
        $pdf->MultiCell(0, 3, utf8_decode('Valores que superan el LMP'), 0, 'C');
        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(0, 3, utf8_decode('** Los LMP son de acuerdo a los límites indicados por la CNA'), 0, 'C');
        $pdf->Ln(2);
        $pdf->Cell(10, 3, utf8_decode('NOTA:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(0, 3, utf8_decode('De acuerdo a la NOM-008-SCFI-1993 "Sistema general de unidades de medidas" se indica que el decimal debe ser una coma, esta regla está de acuerdo con la recomendaciones de la organización Internacional de Normalización (ISO).'), 0, 'J');
    }

/**************************************************************************************************/
/* Función para tabla de adicionales */
/**************************************************************************************************/
//Recibe el objeto de pdf y el array de adicionales
    function adicionalesPDF($pdf, $adicionales){
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 5, utf8_decode('Informe de Análisis Adicionales'), 1, 1, 'C', true);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(55, 10, utf8_decode('Parámetros'), 1, 0, 'C', true);
        $pdf->Cell(30, 10, utf8_decode('Unidades'), 1, 0, 'C', true);
        $pdf->Cell(40, 10, utf8_decode('Resultado'), 1, 0, 'C', true);
        $pdf->MultiCell(40, 5, utf8_decode('Limites Máximos Permisibles (LMP)'), 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);
        foreach ($adicionales as $value) {
            $pdf->Cell(55, 5, utf8_decode($value['nombre']), 1, 0, 'L');
            $pdf->Cell(30, 5, utf8_decode($value['unidades']), 1, 0, 'C');
            $pdf->Cell(40, 5, utf8_decode($value['resultado']), 1, 0, 'C');
            $pdf->Cell(40, 5, utf8_decode('No Aplica'), 1, 1, 'C');
        }

        $pdf->Ln(1);
        $pdf->SetFont('Arial', 'BU', 8);
        $pdf->MultiCell(0, 3, utf8_decode('Valores que superan el LMP'), 0, 'C');
        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(0, 3, utf8_decode('** Los LMP son de acuerdo a los límites indicados por la CNA'), 0, 'C');
        $pdf->Ln(2);
        $pdf->Cell(10, 3, utf8_decode('NOTA:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(0, 3, utf8_decode("De acuerdo a la NOM-008-SCFI-1993 \"Sistema general de unidades de medidas\" se indica que el decimal debe ser una coma, esta regla está de acuerdo con la recomendaciones de la organización Internacional de Normalización (ISO)."), 0, 'J');
        $pdf->Ln();
    }

/**************************************************************************************************/
/* Función para dibujar el croquis */
/**************************************************************************************************/
//Recibe el objeto de pdf, el valor de cantidad y la imagen del croquis
    function croquisPDF($pdf, $cantidad, $croquis){
        //var_dump($croquis);
        $imagen = $_SERVER['DOCUMENT_ROOT'].'/reportes/nom001/croquis/'.$croquis['nombrearchivado'];
        $pdf->Cell(0, 5, utf8_decode('Croquis del lugar donde se tomó la muestra'), 1, 1, 'C', true);
        if($croquis!==false){
            if($cantidad === 1){
                $pdf->Image($imagen, 20, 74, 165, 70);
            }elseif($cantidad === 4){
                $pdf->Image($imagen, 20, 143, 165, 70);
            }elseif($cantidad === 6){
                $pdf->Image($imagen, 20, 153, 165, 70);
            }
        }
        $pdf->Cell(0, 70, '', 1, 1, 'C');
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
        $pdf->Cell(60, 4, utf8_decode('Tec. Leopoldo Sánchez Bautista'), 0, 0, 'C');
        $pdf->SetXY($x + 105, $y);
        $pdf->MultiCell(60, 4, utf8_decode("Víctor Manuel Hernández Soria. \n Signatario Autorizado por la E.M.A."), 0, 'C');
    }


    
    ?>