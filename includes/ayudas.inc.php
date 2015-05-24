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

  function crearForma($label, $nombre, $valor, $atts, $tipo, $options){
    echo '<label for="'.$nombre.'">'.$label.': </label>';
    switch ($tipo) {
      case 'text':
        echo '<input type="text" name="'.$nombre.'" id="'.$nombre.'" value="'.$valor.'"';
        if($atts !== ''):
          foreach ($atts as $key => $value):
            if(!is_numeric($key)):
              echo ' '.$key.'='.$value.' ';
            else:
              echo ' '.$value.' ';
            endif;
          endforeach;
        endif;
        echo '>';
        break;

      case 'textarea':
        echo '<br><textarea style="resize: none;" rows=5 cols=50 name="'.$nombre.'" id="'.$nombre.'"';
        if($atts !== ''):
          foreach ($atts as $key => $value):
            if(!is_numeric($key)):
              echo ' '.$key.'='.$value.' ';
            else:
              echo ' '.$value.' ';
            endif;
          endforeach;
        endif;
        echo '>'.$valor.'</textarea>';
        break;

      case 'select':
        echo '<select name="'.$nombre.'" id="'.$nombre.'">';
        $selected = strval($valor) === ''? 'selected' : '';
            echo '<option value="" disabled '.$selected.'>Seleccionar</option>';
            foreach ($options as $value => $texto){
              $selected = (strval($valor) === strval($value)) ? 'selected' : '';
              echo '<option value="'.$value.'" '.$selected.'>'.$texto.'</option>';
            }
        echo '</select>';
        break;

      case 'select2':
        echo '<select name="'.$nombre.'" id="'.$nombre.'">';
        $selected = strval($valor) === ''? 'selected' : '';
            echo '<option value="" disabled '.$selected.'>Seleccionar</option>';
            foreach ($options as $value => $texto){
              $selected = (strval($valor) === strval($texto)) ? 'selected' : '';
              echo '<option value="'.$texto.'" '.$selected.'>'.$texto.'</option>';
            }
        echo '</select>';
        break;
    }
  }

?>