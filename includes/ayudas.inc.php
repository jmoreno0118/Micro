<?php
  function html($texto)
  {
    return htmlspecialchars($texto,ENT_QUOTES,'UTF-8');
  }

  function htmlout($texto)
  {
    echo html($texto);
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
?>