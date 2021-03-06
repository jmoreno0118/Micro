<?php
  function html($texto)
  {
    return htmlspecialchars($texto,ENT_QUOTES,'UTF-8');
  }

  function htmlout($texto)
  {
    echo html($texto);
  }
  function htmldecode($texto){
    return html_entity_decode($texto, ENT_COMPAT, 'UTF-8');
  }
  // otra referencia puede ser http://michelf.com/projects/php-markdown/
  function markdown2html($texto)
  {
   $texto=html($texto);
   
   // coloca negrillas
   $texto=preg_replace('/__(.+?)__/s','<strong>$1</strong>',$texto);
   $texto=preg_replace('/\*\*(.+?)\*\*/s','<strong>$1</strong>',$texto);
   // coloca italicas (enfasis)
   $texto=preg_replace('/_([^_]+)_/','<em>$1</em>',$texto);
   $texto=preg_replace('/\*([^\*]+)\*/','<em>$1</em>',$texto);
   //convierte de windows a unix
   $texto=str_replace("\r\n","\n",$texto);
   //convierte de macintoch a unix
   $texto=str_replace("\r","\n",$texto);
   //genera el parrafo
   $texto='<p>'.str_replace("\n\n",'</p><p>',$texto).'</p>';
   // genera line breaks
   $texto=str_replace("\n",'<br>',$texto);
   // genera el codigo para [texto](dereccion URL)
   $texto=preg_replace('/\[([^\]]+)]\(([-a-z0-9._~:\/?#@!$&\'()*+,;=%]+)\)/i',
          '<a href="$2">$1></a>',$texto);
   return $texto;   
  }
  
  function markdownout($texto)
  {
   echo markdown2html($texto);
  }

  function rastrea($texto)
  {
    $texto=htmlout($texto);
	exit();
  }

  function crearForma($label, $nombre, $valor, $atts, $tipo, $options, $extra = ''){
    if($tipo !== 'hidden')
      echo '<label for="'.$nombre.'">'.$label.': </label>';

    switch ($tipo) {
      
      case 'text':
        if(!isset($atts['name']))
          $atts['name'] = $nombre;
        if(!isset($atts['id']))
          $atts['id'] = $nombre;
        echo '<input type="text" value="'.$valor.'"';
        imprimeAtts($atts);
        echo '>';
        break;

      case 'hidden':
        echo '<input type="hidden" name="'.$nombre.'" id="'.$nombre.'" value="'.$valor.'"';
        imprimeAtts($atts);
        echo '>';
        break;

      case 'textarea':
        echo '<br><textarea style="resize: none;" rows=5 cols=50 name="'.$nombre.'" id="'.$nombre.'"';
        imprimeAtts($atts);
        echo '>'.$valor.'</textarea>';
        break;

      case 'select':
        echo '<select name="'.$nombre.'" id="'.$nombre.'"';
        imprimeAtts($atts);
        echo '>';
        $selected = strval($valor) === ''? 'selected' : '';
        $disabled = '';
        if(isset($extra['disabled']) AND $extra['disabled'] === 'false'){
          $disabled = 'disabled';
        }
        echo '<option value="" '.$disabled.' '.$selected.'>Seleccionar</option>';
        foreach ($options as $value => $texto){
          $selected = (strval($valor) === strval($value)) ? 'selected' : '';
          echo '<option value="'.$value.'" '.$selected.'>'.$texto.'</option>';
        }
        echo '</select>';
        break;

      case 'select2':
        echo '<select name="'.$nombre.'" id="'.$nombre.'"';
        imprimeAtts($atts);
        echo '>';
        $selected = strval($valor) === ''? 'selected' : '';
        $disabled = '';
        if(isset($extra['disabled']) AND $extra['disabled'] === 'false'){
          $disabled = 'disabled';
        }
        echo '<option value="" '.$disabled.' '.$selected.'>Seleccionar</option>';
        foreach ($options as $value => $texto){
          $selected = (strval($valor) === strval($texto)) ? 'selected' : '';
          echo '<option value="'.$texto.'" '.$selected.'>'.$texto.'</option>';
        }
        echo '</select>';
        break;

      //Varios check seleccionados, value = valor
      case 'check':
        if($valor === '')
          $valor = array();
        foreach ($options as $value => $texto){
          echo '<div>';

          $comp = $value;
          if($extra['comp'] === 'texto'){
            $comp = $texto;
          }

          $val = $value;
          if($extra['value'] === 'texto'){
            $val = $texto;
          }

          $multi = '';
          if($extra['multi'] === 1){
            $multi = '[]';
            $selected = in_array($comp, $valor) ? 'checked' : '';
          }else{
            $selected = (strval($valor) === strval($comp)) ? 'checked' : '';
          }

          echo '<input type="checkbox" name="'.$nombre.$multi.'" id="'.$nombre.'" value="'.$val.'"';
          imprimeAtts($atts);
          echo $selected.'>'.$texto;
          echo '</div>';
        }
        break;
    }
  }


  function imprimeAtts($atts){
    if($atts !== ''):
      foreach ($atts as $key => $value):
        if(!is_numeric($key)):
          echo ' '.$key.'='.$value.' ';
        else:
          echo ' '.$value.' ';
        endif;
      endforeach;
    endif;
  }

  function fijarAccionUrl($accion){
    $_SESSION['accion'] = $accion;
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $script   = $_SERVER['SCRIPT_NAME'];
    $params   = $_SERVER['QUERY_STRING'];
    $currentUrl = $protocol . '://' . $host . $script . '?' . $params;
    $_SESSION['url'] = $currentUrl;
  }

/**************************************************************************************************/
/* Función para obtener la acreditacion de la orden */
/**************************************************************************************************/
  function getCorreccion($idequipo){
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try{

      $sql='SELECT correccion
          FROM calibracionestbl
          WHERE equipoidfk=:id';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id', $idequipo);
      $s->execute();

      if(!$correccion = $s->fetch()){

        //var_dump($correccion);
        //exit();

        return "";
        exit();

        $mensaje='Hubo un error getCorreccion';
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
        exit();
      }

      return $correccion['correccion'];
    }catch (PDOException $e){
      //return "";
      $mensaje='Hubo un error getCorreccion '.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
    }
  }

/**************************************************************************************************/
/* Función para obtener la acreditacion de la orden */
/**************************************************************************************************/
function getEquipos($tipo, $ot){
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
        $sql='SELECT equipos.ID_Equipo, Descripcion, Marca, Modelo, Numero_Inventario
          FROM  equipos
          INNER JOIN bitacora_uso ON equipos.ID_Equipo = bitacora_uso.ID_Equipo
          INNER JOIN bitacora_personal ON bitacora_uso.ID_Personal = bitacora_personal.ID_Personal
          WHERE bitacora_personal.Numero_Orden_Muestreo IN (SELECT ot FROM ordenestbl WHERE id = :ot)
          AND equipos.Tipo = "'.$tipo.'"';
        $s=$pdo->prepare($sql);
        $s->bindValue(':ot', $ot);
        $s->execute();

        $equipos = '';
        $error = '';
        if($eqbd = $s->fetchAll()){
            foreach ($eqbd as $value){
              $sql='SELECT *
                FROM  calibracionestbl
                WHERE equipoidfk=:id';
              $s=$pdo->prepare($sql);
              $s->bindValue(':id', $value['ID_Equipo']);
              $s->execute();

              if(!$s->fetch()){
                $error.=$value['Numero_Inventario'].',';
              }

              $equipos[$value['ID_Equipo']] = $value['Descripcion'].' '.$value['Marca'].' '.$value['Modelo'].' '.$value['Numero_Inventario'];
            }
        }
        
        /*if(strcmp($error, '') !== 0){
          $mensaje = 'Les hace falta capturar correccion a los equipos: '.rtrim($error, ',');
          include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
          exit();
        }*/

      return $equipos;
    }catch (PDOException $e){
      return "";
    }
}


/**************************************************************************************************/
/* Función para obtener la acreditacion de la orden */
/**************************************************************************************************/
  function setObservacion($pdo, $estudio, $ot, $observacion){
    try
    {
        $sql= 'SELECT nombre, apellido FROM usuariostbl
                WHERE usuario = :usuario';
        $s = $pdo->prepare($sql);
        $s->bindValue(':usuario', $_SESSION['usuario']);
        $s->execute();
        $e = $s->fetch();

        $sql='INSERT INTO  observacionestbl SET
          ordenesidfk = :id,
          observacion = :observacion,
          fecha = CURDATE(),
          supervisor = :supervisor,
          estudio = :estudio';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $ot);
        $s->bindValue(':observacion', $observacion);
        $s->bindValue(':supervisor', $e['nombre'].' '.$e['apellido']);
        $s->bindValue(':estudio', $estudio);
        $s->execute();
    }
    catch (PDOException $e)
    {
      $mensaje='Hubo un error getCorreccion '.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
    }
  }
?>