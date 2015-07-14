<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Orden de Trabajo <?php htmlout($orden['ot']); ?></title>
    <meta charset="utf-8" />
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]--> 
    <link rel="stylesheet" type="text/css" href="/reportes/estilo.css" />
  </head>
  <body>
    <div id="contenedor">
      <header>
        <?php 
        $ruta='/reportes/img/logoblco2.gif';
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/encabezado.inc.php'; ?>
      </header>
      <div id="cuerpoprincipal">
        <?php 
        //var_dump($orden);
        //var_dump($mcompuestas);
        //var_dump($parametros);
        ?>
        <h2>Orden de Trabajo <?php htmlout($orden['ot']); ?></h2>

        <table style="width:50%">
          <tr>
            <td style="width:50%;">RFC</td>
            <td> <?php htmlout($orden['rfc']); ?></td>
          </tr>
          <tr>
            <td>Compañía</td>
            <td> <?php htmlout($orden['razonsocial']); ?></td>
          </tr>
          <tr>
            <td>Direccion</td>
            <td> <?php htmlout(htmldecode($orden['calle'].', '.$orden['cp'].', '.$orden['estado'].', '.$orden['ciudad'].', '.$orden['colonia'])); ?></td>
          </tr>
          <tr>
            <td>Título de Concesión</td>
            <td><?php htmlout($orden['titulo']); ?></td>    
          </tr>
          <tr>
            <td>Cuerpo receptor</td>
            <td><?php htmlout($maximos['descargaen']); ?></td>    
          </tr>
          <tr>
            <td>Uso de agua</td>
            <td><?php htmlout($maximos['uso']); ?></td>    
          </tr>
          <tr>
            <td>Numero de orden</td>
            <td>
              <form method="post">
                <input type="hidden" name="otm" value="<?php htmlout($otm); ?>">
                <input type="hidden" name="id" value="<?php htmlout($orden['id']); ?>">
                <input type="text" name="numorden" value="<?php htmlout( isset($orden['numorden'])? $orden['numorden'] : ''); ?>">
                <input type="submit" name="accion" value="guardar">
              </form>
            </td>   
          </tr>
          <tr>
            <td>Número de medición</td>
            <td><?php htmlout($orden['numedicion']); ?></td>    
          </tr>
          <tr>
            <td>Horas del proceso de descarga</td>
            <td><?php htmlout($orden['tipomediciones']); ?></td>    
          </tr>
          <tr>
            <td>Fecha de emisión</td>
            <td><?php htmlout($orden['fechalta']); ?></td>
          </tr>
          <!--tr>
            <td>Dirección</td>
            <td><?php htmlout("direccion"); ?></td>    
          </tr-->
        </table>
        <br>

        <table style="width:50%">
          <tr>
            <th colspan="2">Ubicación según título</th>  
          </tr>
          <tr>
            <th colspan="2">Latitud</th>
          </tr>
          <tr>
            <td style="width:50%;">Grados</td>
            <td><?php htmlout($orden['lattgrados']); ?></td>    
            </tr>
          <tr>
            <td>Minutos</td>
            <td><?php htmlout($orden['lattmin']); ?></td>    
          </tr>
          <tr>
            <td>Segundos</td>
            <td><?php htmlout($orden['lattseg']); ?></td>    
          </tr>
          <tr>
            <th colspan="2">Longitud</th>
          </tr>
          <tr>
            <td style="width:50%;">Grados</td>
            <td><?php htmlout($orden['lontgrados']); ?></td>    
          </tr>
          <tr>
            <td>Minutos</td>
            <td><?php htmlout($orden['lontmin']); ?></td>    
          </tr>
          <tr>
            <td>Segundos</td>
            <td><?php htmlout($orden['lontseg']); ?></td>    
          </tr>
          <tr>
            <th colspan="2">Ubicación del punto de muestro</th>  
          </tr>
          <tr>
            <th colspan="2">Latitud</th>
          </tr>
          <tr>
            <td style="width:50%;">Grados</td>
            <td><?php htmlout($orden['latpgrados']); ?></td>    
          </tr>
          <tr>
            <td>Minutos</td>
            <td><?php htmlout($orden['latpmin']); ?></td>    
          </tr>
          <tr>
            <td>Segundos</td>
            <td><?php htmlout($orden['latpseg']); ?></td>    
          </tr>
          <tr>
            <th colspan="2">Longitud</th>
          </tr>
          <tr>
            <td style="width:50%;">Grados</td>
            <td><?php htmlout($orden['lonpgrados']); ?></td>    
          </tr>
          <tr>
            <td>Minutos</td>
            <td><?php htmlout($orden['lonpmin']); ?></td>    
          </tr>
          <tr>
            <td>Segundos</td>
            <td><?php htmlout($orden['lonpseg']); ?></td>    
          </tr>
        </table>
        <br>


        <?php $flujos = 0; $ft = 0;
        if(count($mcompuestas) > 1)
        { ?>
          <fieldset style="width:50%">
            <legend>MUESTRAS</legend>
            <?php/* For */ for ($i=0; $i<count($mcompuestas); $i++) :?>
              <table style="width:100%">
                <tr>
                  <th colspan="2">Muestra <?php if($i+1<count($mcompuestas)){htmlout($i+1);}else{echo "Compuesta";} ?>:</th>  
                </tr>
                <?php/* If1 */ if($i+1<count($mcompuestas)){ ?>
                  <tr>
                  <td>Signatario</td>
                  <td><?php htmlout($orden['signatarionombre'].' '.$orden['signatarioap'].' '.$orden['signatarioam']); ?></td>    
                  </tr>
                <?php/* If1 */ }; ?>
                <tr>
                  <td style="width:50%;">Fecha</td>
                  <?php
                    $fecha = $orden['fechamuestreo'];
                    if($i>0)
                    {
                        if( date('H', strtotime($mcompuestas[$i-1]['hora'])) > date('H', strtotime($mcompuestas[$i]['hora'])) )
                        {
                          $fecha = date('Y-m-d', strtotime($orden['fechamuestreo'] . ' + 1 day'));
                          $horacomparar = $mcompuestas[$i-1]['hora'];
                        }
                        if(isset($horacomparar)){
                            if( date('H', strtotime($horacomparar)) > date('H', strtotime($mcompuestas[$i]['hora'])) )
                            {
                              $fecha = date('Y-m-d', strtotime($orden['fechamuestreo'] . ' + 1 day'));
                            }
                        }
                    }
                  ?>
                  <td><?php htmlout($fecha); ?></td>
                </tr>
                <tr>
                  <td>Hora de conformación</td>
                  <td><?php htmlout($mcompuestas[$i]['hora']); ?></td>
                </tr>
                <tr>
                  <td>Fecha de recepción laboratorio</td>
                  <td><?php htmlout($mcompuestas[$i]['fechalab']); ?></td>  
                </tr>
                <tr>
                  <td>Hora de recepción laboratorio</td>
                  <td><?php htmlout($mcompuestas[$i]['horalab']); ?></td>    
                </tr>
                <tr>
                  <td>Identificación</td>
                  <td><?php htmlout($mcompuestas[$i]['identificacion']); ?></td>    
                </tr>
                <?php if($i+1<count($mcompuestas))
                { ?>
                    <tr>
                      <td>Flujo</td>
                      <td><?php htmlout($mcompuestas[$i]['flujo']); ?></td>    
                    </tr>
                    <?php 
                    if($mcompuestas[$i]['flujo'] !== "S/F")
                    {
                      $flujos += floatval($mcompuestas[$i]['flujo']);
                      $ft++;
                    }
                }
                else
                { ?>
                    <tr>
                      <td>Caudal promedio</td>
                      <td><?php htmlout( floatval(($flujos/$ft++)) ); ?></td>    
                    </tr>
                <?php } ?>
                <tr>
                  <td>Descripción</td>
                  <td><?php htmlout($mcompuestas[$i]['caracteristicas']); ?></td>    
                </tr>
                <tr>
                  <td>Observaciones</td>
                  <td><?php htmlout($mcompuestas[$i]['observaciones']); ?></td>    
                </tr>
                <!--tr>
                  <td>pH</td>
                  <td><?php htmlout($orden['pH']); ?></td>    
                </tr-->
                <!--tr>
                  <td>Coliformes</td>
                  <td><?php htmlout( (isset($parametros2[$i]['coliformes'])) ? $parametros2[$i]['coliformes'] : "" ); ?></td>    
                </tr-->
                <!--tr>
                  <td>Temperatura</td>
                  <td><?php htmlout($orden['temperatura']); ?></td>    
                </tr-->
                <!--tr>
                  <td>Grasas y Aceites</td>
                  <td><?php htmlout( (isset($parametros2[$i]['GyA'])) ? $parametros2[$i]['GyA'] : "" ); ?></td>    
                </tr-->
                <!--tr>
                  <td>Materia Flotante</td>
                  <td><?php htmlout( (strval($orden['mflotante']) == "0") ? "Ausente" : "Presente"); ?></td>    
                </tr-->
              </table>
              <?php if(count($mcompuestas) > 1) echo "<br>";?>
            <?php/* For */ endfor; ?>
          </fieldset><br>
        <?php } ?>

        <table style="width:50%">
          <tr>
            <th style="width:40%;">Párametro</th>
            <th>Resultado</th>
            <th>LC/LD</th>
          </tr>
          <?php if($parametros['hdehelminto'] !== '')
          {?>
            <tr>
              <td>Huevos de Helminto</td>
              <td><?php htmlout($parametros['hdehelminto']); ?></td>
              <td><?php htmlout($limite['hdehelminto']); ?></td>
            </tr>
          <?php
          }
          if($parametros['ssedimentables'] !== '')
          { ?>
            <tr>
              <td>Solidos sedimentables</td>
              <td><?php htmlout($parametros['ssedimentables']); ?></td>
              <td><?php htmlout($limite['ssedimentables']); ?></td>
            </tr>
          <?php
          }
          if($parametros['ssuspendidos'] !== '')
          { ?>
            <tr>
              <td>Solidos suspendidos</td>
              <td><?php htmlout($parametros['ssuspendidos']); ?></td>
              <td><?php htmlout($limite['ssuspendidos']); ?></td>
            </tr>
          <?php
          }
          if($parametros['dbo'] !== '')
          { ?>
            <tr>
              <td>DBO</td>
              <td><?php htmlout($parametros['dbo']); ?></td>
              <td><?php htmlout($limite['dbo']); ?></td>
            </tr>
          <?php
          }
          if($parametros['nkjedahl'] !== '')
          { ?>
            <tr>
              <td>Nitrógeno Kjeldahl</td>
              <td><?php htmlout($parametros['nkjedahl']); ?></td>
              <td><?php htmlout($limite['nkjedahl']); ?></td>
            </tr>
          <?php
          }
          if($parametros['nitritos'] !== '')
          { ?>
            <tr>
              <td>Nitrógeno Nitritos</td>
              <td><?php htmlout($parametros['nitritos']);?></td>
              <td><?php htmlout($limite['nitritos']);?></td>
            </tr>
          <?php
          }
          if($parametros['nitratos'] !== '')
          { ?>
            <tr>
              <td>Nitrógeno Nitratos</td>
              <td><?php htmlout($parametros['nitratos']); ?></td>
              <td><?php htmlout($limite['nitratos']); ?></td>
            </tr>
          <?php
          }
          if($parametros['nitrogeno'] !== '' AND $parametros['nitrogeno'] !== '0.00')
          { ?>
            <tr>
              <td>Nitrógeno</td>
              <td><?php htmlout($parametros['nitrogeno']); ?></td>
              <td><?php htmlout('Calculado');//htmlout($limite['nitrogeno']); ?></td>
            </tr>
          <?php
          }
          if($parametros['fosforo'] !== '')
          { ?>
            <tr>
              <td>Fosforo</td>
              <td><?php htmlout($parametros['fosforo']); ?></td>
              <td><?php htmlout($limite['fosforo']); ?></td>
            </tr>
          <?php
          }
          if($parametros['arsenico'] !== '')
          { ?>
            <tr>
              <td>Arsenico</td>
              <td><?php htmlout($parametros['arsenico']); ?></td>
              <td><?php htmlout($limite['arsenico']); ?></td>
            </tr>
          <?php
          }
          if($parametros['cadmio'] !== '')
          { ?>
            <tr>
              <td>Cadmio</td>
              <td><?php htmlout($parametros['cadmio']); ?></td>
              <td><?php htmlout($limite['cadmio']); ?></td>
            </tr>
          <?php
          }
          if($parametros['cianuros'] !== '')
          { ?>
            <tr>
              <td>Cianuros</td>
              <td><?php htmlout($parametros['cianuros']); ?></td>
              <td><?php htmlout($limite['cianuros']); ?></td>
            </tr>
          <?php
          }
          if($parametros['cobre'] !== '')
          { ?>
            <tr>
              <td>Cobre</td>
              <td><?php htmlout($parametros['cobre']); ?></td>
              <td><?php htmlout($limite['cobre']); ?></td>
            </tr>
          <?php
          }
          if($parametros['cromo'] !== '')
          { ?>
            <tr>
              <td>Cromo</td>
              <td><?php htmlout($parametros['cromo']); ?></td>
              <td><?php htmlout($limite['cromo']); ?></td>
            </tr>
          <?php
          }
          if($parametros['mercurio'] !== '')
          { ?>
            <tr>
              <td>Mercurio</td>
              <td><?php htmlout($parametros['mercurio']); ?></td>
              <td><?php htmlout($limite['mercurio']); ?></td>
            </tr>
          <?php
          }
          if($parametros['niquel'] !== '')
          { ?>
            <tr>
              <td>Niquel</td>
              <td><?php htmlout($parametros['niquel']); ?></td>
              <td><?php htmlout($limite['niquel']); ?></td>
            </tr>
          <?php
          }
          if($parametros['plomo'] !== '')
          { ?>
            <tr>
              <td>Plomo</td>
              <td><?php htmlout($parametros['plomo']); ?></td>
              <td><?php htmlout($limite['plomo']); ?></td>
            </tr>
          <?php
          }
          if($parametros['zinc'] !== '')
          { ?>
            <tr>
              <td>Zinc</td>
              <td><?php htmlout($parametros['zinc']); ?></td>
              <td><?php htmlout($limite['zinc']); ?></td>
            </tr>
          <?php } ?>
        </table>
        <br>

        <?php if(strcmp($adicionales, "") !== 0 ){ ?>
          <table style="width:50%">
          <tr>
            <th style="width:40%;">Nombre de Párametro</th>
            <th>Unidades</th>
            <th>Resultado</th>
          </tr>
          <?php foreach ($adicionales as $value): ?>
            <tr>
              <td><?php htmlout($value['nombre']); ?></td>
              <td><?php htmlout($value['unidades']); ?></td>
              <td><?php htmlout($value['resultado']); ?></td>
            </tr>
          <?php endforeach; ?>
          </table>
        <?php } ?>
      </div>  <!-- cuerpoprincipal -->
      <div id="footer">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
      </div>  <!-- footer -->
    </div> <!-- contenedor -->
  </body>
</html>