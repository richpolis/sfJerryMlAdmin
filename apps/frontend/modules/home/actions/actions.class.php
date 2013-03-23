<?php

/**
 * home actions.
 *
 * @package    JerryML
 * @subpackage home
 * @author     Dioner911
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class homeActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->getUser()->setCalendarUsuario(0, "", 3);
        //acciones en vista
        if ($this->getUser()->hayModoActivo()) {
            if ($this->getUser()->getModoPrecotizacion()) {
                $this->getUser()->setModoPrecotizacion(false);
            } elseif ($this->getUser()->getModoCotizacion()) {
                $this->getUser()->setModoCotizacion(false);
            } elseif ($this->getUser()->getModoContrato()) {
                $this->getUser()->setModoContrato(false);
            } elseif ($this->getUser()->getModoPago()) {
                $this->getUser()->setModoPago(false);
            } elseif ($this->getUser()->getModoPagoTalento()) {
                $this->getUser()->setModoPagoTalento(false);
            } else {
                $this->getUser()->setSeleccionarTalentos(false);
                $this->getUser()->setTalentosCotizacion(array());
                $this->getUser()->setTalentosPrecotizacion(array());
                $this->getUser()->setTalentos(array());
                $this->getUser()->setCotizacion(0);
                $this->getUser()->setPrecotizacion(0);
            }

            if (count($this->getUser()->getTalentos()) > 0) {
                $talentos = Doctrine_Core::getTable('Talentos')->getTalentosPorArreglo($this->getUser()->getTalentos());
                foreach ($talentos as $talento) {
                    if (count($this->getUser()->getEventos($talento->getId()))) {
                        $eventos = Doctrine_Core::getTable('KsWCEvent')->getEventosPorArreglo($this->getUser()->getEventos($talento->getId()));
                        foreach ($eventos as $evento) {
                            $evento->delete();
                        }
                        $this->getUser()->setEventos(array(), $talento->getId());
                    }
                }
            }

            $ruta = $this->getUser()->getCancelarRegresarA();
            $this->redirect($ruta);
        }
        $this->precotizaciones = Doctrine_Core::getTable('Precotizaciones')->getUltimasPrecotizaciones();
        $this->cotizaciones = Doctrine_Core::getTable('Cotizaciones')->getCotizacionesPendientesDePago();
        $this->eventos = Doctrine_Core::getTable('EventosUsuarios')->getProximosEventosUsuarios();
    }

    public function executeReportes(sfWebRequest $request) {
        if ($request->hasParameter('tipo')) {
            $this->form = $this->getFormulario($request);
            if ($request->isXmlHttpRequest()) {
                return $this->renderPartial('form', array('form' => $this->form, "tipo" => $request->getParameter('tipo')));
            }
        }
    }

    public function getFormulario(sfWebRequest $request) {
        if ($request->hasParameter('tipo')) {
            switch ($request->getParameter('tipo')) {
                case "jerryml":
                case "jerryml-por-concepto":
                    $form = new ReportesPagosJerrmlForm();
                    break;
                case "talentos":
                case "talentos-por-concepto":
                    $form = new ReportesPagosTalentosForm();
                    break;
                case "clientes":
                    $form = new ReportesPagosClientesForm();
                    break;
            }
            return $form;
        } else {
            return null;
        }
    }

    public function executeEjecutarReportes(sfWebRequest $request) {
        if ($request->isMethod('post') && $request->hasParameter('tipo')) {
            $this->form = $this->getFormulario($request);
            $this->tipo = $request->getParameter('tipo');
            $valores = $request->getParameter('reportes_pago');
            $this->form->bind($valores);
            if ($this->form->isValid()) {
                $this->ejecutarReporte($valores, $this->tipo);
            } else {
                $this->setTemplate("reportes");
            }
        } else {
            $this->tipo = "jerryml";
            $this->form = new ReportesPagosJerrmlForm();
            $this->setTemplate("reportes");
        }
        $this->desde = $valores['desde']['year'] . "-" . $valores['desde']['month'] . "-" . $valores['desde']['day'];
        $this->hasta = $valores['hasta']['year'] . "-" . $valores['hasta']['month'] . "-" . $valores['hasta']['day'];
    }

    public function ejecutarReporte($valores, $tipo) {
        switch ($tipo) {
            case "jerryml":
                $this->cotizaciones = Doctrine_Core::getTable('Cotizaciones')->getHistorialPagosJerryMl($valores);
                break;
            case "jerryml-por-concepto":
                $this->dccs = Doctrine_Core::getTable('DetallesCotizacionConceptos')->getHistorialPagosJerryMl($valores);
                break;
            case "talentos":
                $this->talentos = Doctrine_Core::getTable('Talentos')->getHistorialPagosTalentos($valores);
                break;
            case "talentos-por-concepto":
                $this->talentos = Doctrine_Core::getTable('Talentos')->getHistorialPagosTalentosConceptos($valores);
                break;
            case "clientes":
                $this->clientes = Doctrine_Core::getTable('Clientes')->getHistorialPagosClientes($valores);
                break;
        }
    }

    public function executeBatchExportar(sfWebRequest $request) {
        $objPHPExcel = new sfPhpExcel();

        $registros = Doctrine_Core::getTable('Newsletter')->getBaseDeDatos(true);

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Nombre');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Email');

        $cont = 2;
        foreach ($registros as $registro) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $cont, $registro->getNombre());
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $cont, $registro->getEmail());
            $cont++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Lista de correos');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save(sfConfig::get('sf_upload_dir') . '/newsletters/downloads/listadecorreos.xlsx');

        $this->getUser()->setFlash('notice', 'El archivo fue generado.');

        $this->redirect('@newsletter');
    }

    public function executeGenerarPdf(sfWebRequest $request) {
        if ($request->hasParameter('html')) {
            ProjectConfiguration::registerMPDF();
            $html = $request->getPostParameter('html');

            $mpdf = new mPDF('es_ES', 'A4-L', '12px', '', 15, 15, 16, 16, 9, 9, 'L');
            $mpdf->useOnlyCoreFonts = true;

            // load a stylesheet
            //$stylesheet = file_get_contents(sfConfig::get('sf_web_dir').'/css/mypdf.css');
            //$mpdf->WriteHTML($stylesheet,1); // el parámetro le dice que sólo es css y no contenido html
            $mpdf->WriteHTML($html, 2);

            $mpdf->Output('reporte.pdf', 'D');
            throw new sfStopException();
        } else {
            throw new sfStopException();
        }
    }

    public function executeGenerarExcel(sfWebRequest $request) {
        if ($request->hasParameter('html')) {
            $this->html = $request->getParameter('html');
            $this->tipo = $request->getParameter('tipo');
            //$this->getResponse()->setContentType('application/msexcel');
            $archivo = $this->getPartial('generarExcel', array("html" => $this->html));
            $this->getResponse()->setHttpHeader("Content-Type", "application/vnd.ms-excel name='excel'");
            $this->getResponse()->setHttpHeader("Content-Disposition", "attachment; filename=\"$this->tipo\".xls");
            $this->getResponse()->setHttpHeader("Pragma", "no-cache");
            $this->getResponse()->setHttpHeader("Expires", "0");
            //$this->getResponse()->setHttpHeader("Content-Length", strlen($archivo));
            sfConfig::set('sf_web_debug', false);
            return $this->renderText($archivo);
        }
    }

    public function executeEnviarCotizacion(sfWebRequest $request) {
        $sCorreos="";
        $this->form = new EnviarCotizacionForm();
        if ($request->hasParameter('cotizacion')) {
            $idCotizacion = $request->getParameter('cotizacion');
        } elseif ($request->hasParameter('cotizacion_id')) {
            $idCotizacion = $request->getParameter('cotizacion_id');
        }
        $this->enviado = false;
        $this->cotizacion = Doctrine_Core::getTable('Cotizaciones')->getCotizacionConClienteTalentoDetallesForId($idCotizacion);
        if (!$request->hasParameter('enviar_cotizacion')) {
            $this->form->setDefault('cliente', $this->cotizacion->getClientes());
            $this->form->setDefault('contacto', $this->cotizacion->getContactos());
            $this->form->setDefault('email', $this->cotizacion->getContactos()->getEmail());
            $this->form->setDefault('subject', '');
            if ($this->cotizacion->getManagerId() > 0) {
                $this->form->setDefault('adicional_id', $this->cotizacion->getManagerId());
            }
            $this->form->setDefault('message', $this->cotizacion->getRenderMensaje());
            $this->form->setDefault('cotizacion_id', $this->cotizacion->getId());
        }
        $this->mensaje_ok = "";

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('enviar_cotizacion'));
            if ($this->form->isValid()) {
                $mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
                $this->Datos = $this->getArregloEnviarCotizacion($request->getParameter('enviar_cotizacion'));
                $asunto = $this->Datos[5]['value'];
                //Creamos el mensaje
                $user = $this->getUser()->getGuardUser();
                //$usuario=array();
                $usuario['Nombre'] = sprintf("%s %s", $user->getFirstName(), $user->getLastName());
                //$nombreUsuario=sprintf("%s %s",$user->getFirstName(),$user->getLastName());
                $usuario['Email'] = $user->getEmailAddress();

                if (intval($this->Datos[3]['value']) > 0) {
                    $usuarioAdicional = Doctrine_Core::getTable("sfGuardUser")->find($this->Datos[3]['value']);
                    $emailAdicional = $usuarioAdicional->getEmailAddress();
                } else {
                    $emailAdicional = "";
                }

                if (strlen($this->Datos[4]['value']) > 0) {
                    $sCorreos=$this->Datos[4]['value'];
                    $aCorreos=explode(",",$sCorreos);
                }
                
                //unset($this->Datos[4]);

                $message = Swift_Message::newInstance($asunto)
                        ->setFrom(array($usuario['Email'] => $usuario['Nombre']))
                        ->setTo(array($this->Datos[2]['value']))
                        //->setTo(array('lizzy@jerryml.com','cancinolizzy@yahoo.com','ventas@jerryml.com'))
                        ->setBody("Cotizacion Jerry ML")
                        ->attach(Swift_Attachment::fromPath(sfConfig::get('sf_upload_dir') . '/cotizaciones/' . $this->cotizacion->getId() . '/' . $this->cotizacion->getPdf()))
                        ->addPart($this->getMensajeFormateadoEnviarCotizacion($this->Datos), 'text/html');

                if (strlen($emailAdicional) > 0 && strlen($sCorreos)==0) {
                    $message->setCc(array($emailAdicional));
                }else{
                    $aCorreos[]=$emailAdicional;
                    $message->setCc($aCorreos);
                }

                
                try {
                    //Enviamos el email
                    $this->enviado = $mailer->send($message, $fail);
                    //$this->redirect('cotizaciones/show?id='.$this->cotizacion->getId());
                } catch (Exception $e) {
                    echo "Error al enviar mensaje " . $e->getMessage();
                }

                if ($this->enviado) {
                    $this->form = new EnviarCotizacionForm();
                    $this->mensaje_ok = "El correo fue enviado, Status en mediacion";
                    if ($this->cotizacion->getStatus() <= CotizacionesTable::$MEDIACION) {
                        $this->cotizacion->setStatus(CotizacionesTable::$MEDIACION); //Enviado a cliente
                        $this->cotizacion->save();
                    }
                } else {
                    $this->mensaje_ok = "Sucedio el siguiente error: " . var_dump($fail);
                }
            }
        }
        //$this->list_contactos=SfroxcasConfigPeer::retrieveByPK(1);
        if ($request->isXmlHttpRequest()) {
            //return $this->renderText('ok');
            return $this->renderPartial('form_enviar_cotizacion', array('form' => $this->form, 'cotizacion' => $this->cotizacion, "mensaje_ok" => $this->mensaje_ok, 'enviado' => $this->enviado));
        }

        //$this->setLayout('simple_layout');
        //$this->contactos=Doctrine_Core::getTable('Configuracion')->getSeccion('contactos');
    }

    public function executeEnviarPrecotizacion(sfWebRequest $request) {
        $sCorreos="";
        $this->form = new EnviarCotizacionForm();
        if ($request->hasParameter('precotizacion')) {
            $idPrecotizacion = $request->getParameter('precotizacion');
        } elseif ($request->hasParameter('cotizacion_id')) {
            $idPrecotizacion = $request->getParameter('cotizacion_id');
        }
        $this->precotizacion = Doctrine_Core::getTable('Precotizaciones')->getPrecotizacionConClienteTalentoDetallesForId($idPrecotizacion);
        if (!$request->hasParameter('enviar_cotizacion')) {
            $this->form->setDefault('cliente', $this->precotizacion->getClientes());
            $this->form->setDefault('contacto', $this->precotizacion->getContactos());
            $this->form->setDefault('email', $this->precotizacion->getContactos()->getEmail());
            $this->form->setDefault('adicional_id', 0);
            $this->form->setDefault('subject', '');
            $this->form->setDefault('message', $this->precotizacion->getRenderMensaje());
            $this->form->setDefault('cotizacion_id', $this->precotizacion->getId());
        }
        $this->mensaje_ok = "";

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('enviar_cotizacion'));
            if ($this->form->isValid()) {
                $mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
                $this->Datos = $this->getArregloEnviarCotizacion($request->getParameter('enviar_cotizacion'));
                $asunto = $this->Datos[5]['value'];

                if (intval($this->Datos[3]['value']) > 0) {
                    $usuarioAdicional = Doctrine_Core::getTable("sfGuardUser")->find($this->Datos[3]['value']);
                    $emailAdicional = $usuarioAdicional->getEmailAddress();
                } else {
                    $emailAdicional = "";
                }

                if (strlen($this->Datos[4]['value']) > 0) {
                    $sCorreos=$this->Datos[4]['value'];
                    $aCorreos=explode(",",$sCorreos);
                }
                
                //unset($this->Datos[4]);

                //Creamos el mensaje
                $user = $this->getUser()->getGuardUser();
                //$usuario=array();
                $usuario['Nombre'] = sprintf("%s %s", $user->getFirstName(), $user->getLastName());
                //$nombreUsuario=sprintf("%s %s",$user->getFirstName(),$user->getLastName());
                $usuario['Email'] = $user->getEmailAddress();
                $message = Swift_Message::newInstance($asunto)
                        ->setFrom(array($usuario['Email'] => $usuario['Nombre']))
                        ->setTo(array($this->Datos[2]['value']))
                        //->setTo(array('lizzy@jerryml.com','cancinolizzy@yahoo.com','ventas@jerryml.com'))
                        ->setBody("Precotizacion Jerry ML")
                        ->addPart($this->getMensajeFormateadoEnviarCotizacion($this->Datos), 'text/html')
                        ->attach(Swift_Attachment::fromPath(sfConfig::get('sf_upload_dir') . '/precotizaciones/' . $this->precotizacion->getId() . '/' . $this->precotizacion->getPdf()));

                if (strlen($emailAdicional) > 0 && strlen($sCorreos)==0) {
                    $message->setCc(array($emailAdicional));
                }else{
                    $aCorreos[]=$emailAdicional;
                    $message->setCc($aCorreos);
                }

                try {
                    //Enviamos el email
                    $this->enviado = $mailer->send($message, $fail);
                } catch (Exception $e) {
                    echo "Error al enviar mensaje " . $e->getMessage();
                }

                if ($this->enviado) {
                    $this->form = new EnviarCotizacionForm();
                    $this->mensaje_ok = "El correo fue enviado, Status en mediacion";
                    if ($this->precotizacion->getStatus() <= PrecotizacionesTable::$MEDIACION) {
                        $this->precotizacion->setStatus(PrecotizacionesTable::$MEDIACION); //Enviado a cliente
                        $this->precotizacion->save();
                    }
                } else {
                    $this->mensaje_ok = "Sucedio el siguiente error: " . var_dump($fail);
                }
            }
        }
        //$this->list_contactos=SfroxcasConfigPeer::retrieveByPK(1);
        if ($request->isXmlHttpRequest()) {
            //return $this->renderText('ok');
            return $this->renderPartial('form_enviar_precotizacion', array('form' => $this->form, 'precotizacion' => $this->precotizacion, "mensaje_ok" => $this->mensaje_ok));
        }

        //$this->contactos=Doctrine_Core::getTable('Configuracion')->getSeccion('contactos');
    }

    public function executeContacto(sfWebRequest $request) {
        $this->form = new ContactForm();
        $this->mensaje_ok = "";

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('contact'));
            if ($this->form->isValid()) {
                $mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
                $this->Datos = $this->getArregloMensaje($request->getParameter('contact'));
                $asunto = $this->Datos[2]['value'];
                //Creamos el mensaje
                $message = Swift_Message::newInstance($asunto)
                        ->setFrom(array($this->Datos[0]['value'] => $this->Datos[1]['value']))
                        //->setTo(array('richpolis@gmail.com'))
                        ->setTo(array('lizzy@jerryml.com', 'cancinolizzy@yahoo.com', 'ventas@jerryml.com'))
                        ->setBody("Mensaje desde JerryML.com")
                        ->addPart($this->getMensajeFormateado($this->Datos), 'text/html');

                try {
                    //Enviamos el email
                    $mailer->send($message);
                } catch (Exception $e) {
                    echo "Error al enviar mensaje " . $e->getMessage();
                }

                $this->form = new ContactForm();
                $this->mensaje_ok = "Gracias, nos comunicaremos a la brevedad posible";
            }
        }
        //$this->list_contactos=SfroxcasConfigPeer::retrieveByPK(1);
        if ($request->isXmlHttpRequest()) {
            //return $this->renderText('ok');
            return $this->renderPartial('home/form_contacto', array('form' => $this->form, "mensaje_ok" => $this->mensaje_ok));
        }

        $this->contactos = Doctrine_Core::getTable('Configuracion')->getSeccion('contactos');
    }

    public function executeRenderImagen(sfWebRequest $request) {
        $imagen = $request->getParameter('imagen') . '.' . $request->getParameter('sf_format');
        $fileImagen = sfConfig::get('sf_upload_dir') . '/' . $request->getParameter('path') . '/' . $imagen;

        //chmod($fileImagen, 0666);
        $img = new sfImage($fileImagen, sfRichSys::getTipoMime($imagen));

        $response = $this->getResponse();

        $response->setContentType($img->getMIMEType());

        if ($img->getWidth() < $request->getParameter('width')) {
            $img->resize(1000, null);
        }

        $img->thumbnail($request->getParameter('width'), $request->getParameter('height'), 'center');

        $response->setContent($img);

        return sfView::NONE;
    }

    public function executeRenderGrayscaleImagen(sfWebRequest $request) {
        $imagen = $request->getParameter('imagen') . '.' . $request->getParameter('sf_format');
        $fileImagen = sfConfig::get('sf_upload_dir') . '/' . $request->getParameter('path') . '/' . $imagen;

        //chmod($fileImagen, 0666);
        $img = new sfImage($fileImagen, sfRichSys::getTipoMime($imagen));

        $response = $this->getResponse();

        $response->setContentType($img->getMIMEType());

        if ($img->getWidth() < $request->getParameter('width')) {
            $img->resize(1000, null);
        }

        $img->thumbnail($request->getParameter('width'), $request->getParameter('height'), 'center');

        $grayscale = new sfImageGreyscaleGD();

        $img = $grayscale->execute($img);

        $response->setContent($img);

        return sfView::NONE;
    }

    /*
     * background-color: #ffcc00;
      color: white;
     *
     */

    protected function getMensajeFormateado($fields) {
        $msg = '<font face="Lucida Grande,Corbel,Arial,sans-serif" color="#565656"><table border=0 cellpadding="4" cellspacing="5" width="500">
		<tr>
                    <td colspan="2" bgcolor="#181818" valign="middle" align="center">
                        <h2 style="margin:0;padding:8px;color:white;font-size: 24px;">
                            JerryML.com
                        </h2>
                    </td>
		</tr>
		<tr>
                    <td colspan="2">&nbsp;</td>
		</tr>';

        for ($i = 0; $i < count($fields); $i++) {

            if (isset($fields[$i]['field']) && mb_strlen(trim($fields[$i]['field']), "utf-8") > 0) {

                $fields[$i]['field'] = htmlspecialchars($fields[$i]['field']);

                $msg.= '<tr><td valign="top" bgcolor="#eeeeee"><small>' . $fields[$i]['label'] . ':&nbsp;&nbsp;&nbsp;</small></td><td>';

                if ($fields[$i]['type'] == 'textArea') {
                    $msg.=nl2br($fields[$i]['value']);
                } else if ($fields[$i]['type'] == 'checkBox') {
                    $msg.='Yes';
                    /* }
                      else if($fields[$i]['items']){
                      $msg.= $fields[$i]['items']['field']; */
                }else
                    $msg.= $fields[$i]['value'];

                $msg.='</td></tr>';
            }
        }

        $msg .= '</table></font>';
        return $msg;
    }

    protected function getArregloMensaje($Datos) {
        $arreglo = array();

        $arreglo[0]['type'] = 'Email';
        $arreglo[1]['type'] = 'Text';
        $arreglo[2]['type'] = 'Text';
        $arreglo[3]['type'] = 'Text';
        $arreglo[4]['type'] = 'Text';
        $arreglo[5]['type'] = 'textArea';

        $arreglo[0]['field'] = 'Email';
        $arreglo[1]['field'] = 'Nombre';
        $arreglo[2]['field'] = 'Asunto';
        $arreglo[3]['field'] = 'Telefono';
        $arreglo[4]['field'] = 'País';
        $arreglo[5]['field'] = 'Mensaje';

        $arreglo[0]['label'] = 'Email';
        $arreglo[1]['label'] = 'Nombre';
        $arreglo[2]['label'] = 'Asunto';
        $arreglo[3]['label'] = 'Telefono';
        $arreglo[4]['label'] = 'País';
        $arreglo[5]['label'] = 'Mensaje';

        $arreglo[0]['items'] = 'Email';
        $arreglo[1]['items'] = 'Nombre';
        $arreglo[2]['items'] = 'Asunto';
        $arreglo[3]['items'] = 'Telefono';
        $arreglo[4]['items'] = 'País';
        $arreglo[5]['items'] = 'Mensaje';

        $arreglo[0]['value'] = $Datos['email'];
        $arreglo[1]['value'] = $Datos['name'];
        $arreglo[2]['value'] = $Datos['subject'];
        $arreglo[3]['value'] = $Datos['telefon'];
        $arreglo[4]['value'] = $Datos['pais'];
        $arreglo[5]['value'] = $Datos['message'];

        return $arreglo;
    }

    protected function getMensajeFormateadoEnviarCotizacion($fields) {
        $msg = '<font face="Lucida Grande,Corbel,Arial,sans-serif" color="#565656"><table border=0 cellpadding="4" cellspacing="5" width="500">
		<tr>
                    <td colspan="2" bgcolor="#005b7d" valign="middle" align="center">
                        <h2 style="margin:0;padding:8px;color:white;font-size: 24px;">
                            Jerry ML
                        </h2>
                    </td>
		</tr>
		<tr>
                    <td colspan="2">&nbsp;</td>
		</tr>';

        for ($i = 0; $i < count($fields); $i++) {
            if($i!=4){
            if (isset($fields[$i]['field']) && mb_strlen(trim($fields[$i]['field']), "utf-8") > 0) {

                $fields[$i]['field'] = htmlspecialchars($fields[$i]['field']);

                $msg.= '<tr><td>';

                if ($fields[$i]['type'] == 'textArea') {
                    $msg.=nl2br($fields[$i]['value']);
                } else if ($fields[$i]['type'] == 'checkBox') {
                    $msg.='Yes';
                    /* }
                      else if($fields[$i]['items']){
                      $msg.= $fields[$i]['items']['field']; */
                }else
                    $msg.= $fields[$i]['value'];

                $msg.='</td></tr>';
            }
            }
        }

        $msg .= '</table></font>';
        return $msg;
    }

    protected function getArregloEnviarCotizacion($Datos) {
        $arreglo = array();

        $arreglo[0]['type'] = 'Text';
        $arreglo[1]['type'] = 'Text';
        $arreglo[2]['type'] = 'Email';
        $arreglo[3]['type'] = 'Text';
        $arreglo[4]['type'] = 'Text';
        $arreglo[5]['type'] = 'Text';
        $arreglo[6]['type'] = 'textArea';

        $arreglo[0]['field'] = 'Cliente';
        $arreglo[1]['field'] = 'Contacto';
        $arreglo[2]['field'] = 'Email';
        $arreglo[3]['field'] = 'CopiarA';
        $arreglo[4]['field'] = 'CC';
        $arreglo[5]['field'] = 'Asunto';
        $arreglo[6]['field'] = 'Mensaje';

        $arreglo[0]['label'] = 'Cliente';
        $arreglo[1]['label'] = 'Contacto';
        $arreglo[2]['label'] = 'Email';
        $arreglo[3]['label'] = 'Copiar a';
        $arreglo[4]['label'] = 'CC';
        $arreglo[5]['label'] = 'Asunto';
        $arreglo[6]['label'] = 'Mensaje';

        $arreglo[0]['items'] = 'Cliente';
        $arreglo[1]['items'] = 'Contacto';
        $arreglo[2]['items'] = 'Email';
        $arreglo[3]['items'] = 'CopiarA';
        $arreglo[4]['items'] = 'CC';
        $arreglo[5]['items'] = 'Asunto';
        $arreglo[6]['items'] = 'Mensaje';

        $arreglo[0]['value'] = $Datos['cliente'];
        $arreglo[1]['value'] = $Datos['contacto'];
        $arreglo[2]['value'] = $Datos['email'];
        $arreglo[3]['value'] = $Datos['adicional_id'];
        $arreglo[4]['value'] = $Datos['extras'];
        $arreglo[5]['value'] = $Datos['subject'];
        $arreglo[6]['value'] = $Datos['message'];

        return $arreglo;
    }

    public function executeViewNewsletter(sfWebRequest $request) {
        $this->list_registros = Doctrine_Core::getTable('Newsletter')->getBaseDeDatos(true);
        $this->template = Doctrine_Core::getTable('TemplatesNewsletters')->getTemplateConPubliacionesForSlug($request->getParameter('slug'));
        $this->setLayout(false);
        $this->vista_previa = true;
    }

    public function executeEnviarNewsletter(sfWebRequest $request) {
        $this->list_registros = Doctrine_Core::getTable('Newsletter')->getBaseDeDatos(true);
        $this->template = Doctrine_Core::getTable('TemplatesNewsletters')->getTemplateConPubliacionesForSlug($request->getParameter('slug'));
        $this->setLayout(false);
        $this->vista_previa = false;
        if ($request->isMethod('post')) {
            // Create the Transport
            //$transport = Swift_SmtpTransport::newInstance('smtp.com', 25)
            //->setUsername('newsletter@jerryml.com')
            //->setPassword('jerryml36') 
            //;
            //$transport = Swift_SmtpTransport::newInstance('smtp.jerrymlhost.com', 25);

            $mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
            //$mailer = Swift_Mailer::newInstance($transport);

            $asunto = 'Newsletter Jerry ML';
            //Creamos el mensaje
            $html = $this->getPartial('home/viewNewsletter', array('list_registros' => $this->list_registros, 'template' => $this->template, 'vista_previa' => $this->vista_previa));
            $cuantos = 0;
            foreach ($this->list_registros as $registro) {
                //$mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
                //if($registro->getEmail()=="richpolis@gmail.com" || $registro->getEmail()=="richpolis@hotmail.com" || $registro->getEmail()=="phrenesis@gmail.com"){
                $message = Swift_Message::newInstance($asunto)
                        ->setFrom(array('newsletter@jerryml.com' => 'Newsletter Jerry ML'))
                        ->setTo(array($registro->getEmail() => $registro->getNombre()))
                        //->setTo(array('lizzy@jerryml.com','cancinolizzy@yahoo.com','ventas@jerryml.com'))
                        ->setBody('Mensaje de Newsletter Jerry ML')
                        ->addPart($html, 'text/html');
                //->addPart('<table><tr><td style="color: white; background-color: black;">hola, esto es una prueba</td></tr></table>', 'text/html');    
                try {
                    //Enviamos el email
                    $status = $mailer->send($message);
                } catch (Exception $e) {
                    echo "Error al enviar mensaje " . $e->getMessage();
                }
                if ($status)
                    $cuantos++;
                //}
            }
            if ($cuantos > 0) {
                $enviados = new MovimientosNewsletters();
                $enviados->setCuantosEnviados($cuantos);
                $enviados->setFechaEnviados(date('y-m-d', time()));
                $enviados->save();
            }
            if ($request->isXmlHttpRequest()) {
                return $this->renderText('Se enviaron ' . $cuantos . ' correos');
            }
        } else {
            if ($request->isXmlHttpRequest()) {
                return $this->renderText('Accion desconocida');
            }
        }
        $this->setTemplate('viewNewsletter');
    }

}
