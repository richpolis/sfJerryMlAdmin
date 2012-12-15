<?php
class sfRichSys
{
    static public function marcarImagen($path,$imagen,$imagenMarcaAgua='',$isNew=false){
        if($imagenMarcaAgua=='') $imagenMarcaAgua='watterlogo.png';
        
        $fileImagen = $path.$imagen;
        $img = new sfImage($fileImagen);
        $imgWatterMark=new sfImage(sfConfig::get('sf_upload_dir').'/uploads/'.$imagenMarcaAgua);
        //$img->overlay($imgWatterMark, 'bottom-right');
        try{
        $proporcion=$img->getWidth()/$imgWatterMark->getWidth();
        $imgWatterMark->resize($img->getWidth(),round($imgWatterMark->getHeight()*$proporcion,0));

        $img->overlay($imgWatterMark, array(
            ($img->getWidth()-$imgWatterMark->getWidth())/2,
            ($img->getHeight()-$imgWatterMark->getHeight())/2
         ));
        }catch(Exception $e){
           echo "Error en la marca de agua ". $e->getMessage();
        }

        if(!$isNew){
            $imagen2=sfRichSys::createFilenameImage($path,$imagen);
            $img->saveAs($path.$imagen2);
            unlink($path.$imagen);
            return $imagen2;
        }else{
            $img->save();
            return $imagen;
        }
    }
    static public function crearThumbnailGaleria($path,$imagen,$New=false,$eliminarFuente=false){
        $fileImagen = $path.$imagen;
        if($New){
            chmod($fileImagen, 0666);
        }    
        //$fileNameOut=self::createFilenameImage($path.'thumbnails/',$imagen);
        $thumbFile= $path.'thumbnails/'.$imagen;
        $img = new sfImage($fileImagen,self::getTipoMime($imagen));
        $img->thumbnail(289,162,'center');
        $img->saveAs($thumbFile);
        chmod($thumbFile, 0666);
        if($eliminarFuente){
            unlink($path.$imagen);
        }
        return $imagen;
    }
    
    static public function crearThumbnailVentas($path,$imagen,$New=false,$eliminarFuente=false){
        $fileImagen = $path.$imagen;
        if($New){
            chmod($fileImagen, 0666);
        }    
        //$fileNameOut=self::createFilenameImage($path.'thumbnails/',$imagen);
        $thumbFile= $path.'thumbnails/'.$imagen;
        $img = new sfImage($fileImagen,self::getTipoMime($imagen));
        $img->thumbnail(220,220,'center');
        $img->saveAs($thumbFile);
        chmod($thumbFile, 0666);
        if($eliminarFuente){
            unlink($path.$imagen);
        }
        return $imagen;
    }
    static public function crearThumbnailTalento($path,$imagen,$New=false,$eliminarFuente=false){
        $fileImagen = $path.$imagen;
        if($New){
            chmod($fileImagen, 0666);
        }    
        //$fileNameOut=self::createFilenameImage($path.'thumbnails/',$imagen);
        $thumbFile= $path.'talentos/'.$imagen;
        $img = new sfImage($fileImagen,self::getTipoMime($imagen));
        $img->thumbnail(387,387,'center');
        
        $grayscale= new sfImageGreyscaleGD();
          
        $img=$grayscale->execute($img);
        
        $img->saveAs($thumbFile);
        chmod($thumbFile, 0666);
        if($eliminarFuente){
            unlink($path.$imagen);
        }
        
    }
    static public function crearThumbnailTalentoColor($path,$imagen,$New=false,$eliminarFuente=false){
        $fileImagen = $path.$imagen;
        if($New){
            chmod($fileImagen, 0666);
        }    
        //$fileNameOut=self::createFilenameImage($path.'thumbnails/',$imagen);
        $thumbFile= $path.'thumbnails/'.$imagen;
        $img = new sfImage($fileImagen,self::getTipoMime($imagen));
        $img->thumbnail(387,387,'center');
        
        $img->saveAs($thumbFile);
        chmod($thumbFile, 0666);
        if($eliminarFuente){
            unlink($path.$imagen);
        }
        return $imagen;
    }
    
    static public function createFilenameImage($path,$filename){
        $arregloFile=explode(".", $filename);
        $uploadDirectory=$path;
        while (file_exists($uploadDirectory . $arregloFile[0] . '.' . $arregloFile[1])) {
                 $arregloFile[0] = sha1($arregloFile[0].rand(11111, 99999));
        }
        return $arregloFile[0].'.'.$arregloFile[1];
        
    }
    static public function getTipoMime($archivo){
        $archivo=explode(".", $archivo);
        $resp="image/jpeg";
        switch ($archivo[1]){
            case "png":
                $resp="image/png";
                break;
            case "gif":
                "image/gif";
                break;
            case "jpg":
            case "jpeg":    
              $resp="image/jpeg";
              break;
            case "flv":
            $resp="flv-application/octet-stream";
              break;
            case "mpg":
              $resp="video/mpeg";
              break;
            case "mp4":
              $resp="application/mp4";
              break;  
            case "avi":    
              $resp="video/x-msvideo";
              break;
            default:    
              $resp="image/jpeg";
              break;
        }
        return $resp;
    }
    static public function getTitleAndImageVideoYoutube($link){
        ProjectConfiguration::registerZend();
        Zend_Loader::loadClass('Zend_Gdata_YouTube');
        $yt = new Zend_Gdata_YouTube();
        $yt->setMajorProtocolVersion(2);
        $link=self::getLinkLargeYoutube($link);
        $videoId=self::getVideoIdYoutube($link);


        if(!$videoId==null){
            
            $videoEntry = $yt->getVideoEntry($videoId);
            $videoThumbnails = $videoEntry->getVideoThumbnails();
            $arreglo['thumbnail']=$videoThumbnails[2]['url'];
            $arreglo['title']=$videoEntry->getVideoTitle();
            $arreglo['videoId']=$videoEntry->getVideoId();
            $arreglo['description']=$videoEntry->getVideoDescription();
            $arreglo['urlVideo']="http://www.youtube.com/watch?v=".$videoId;

            return $arreglo;
            
        }else{
            
            return self::getInfoFindVideoInWeb($link);
        }

    }
    static public function getLinkLargeYoutube($link){
        $arreglo=explode("/", $link);
        if($arreglo['2']=="youtu.be"){
           $arreglo2=explode('&',$arreglo[3]); 
           $linkArreglado="http://www.youtube.com/watch?v=".$arreglo2[0];
        }elseif($arreglo['2']=='vimeo.com' || $arreglo['2']=='www.vimeo.com'){
            $linkArreglado="http://vimeo.com/".$arreglo[3];
        }else{
            $linkArreglado=$link;
        }
        return $linkArreglado;
    }
    static public function getVideoIdYoutube($link){
        $arreglo=explode("/", $link);
        if($arreglo['2']=="www.youtube.com"){
            preg_match('/youtube\.com\/watch\?v=([A-Za-z0-9._%-]*)[&\w;=\+_\-]*/',$link,$match);
            return $match[1];
        }else{
            return null;
        }
    }
    static public function getInfoFindVideoInWeb($link){
        $arreglo=explode("/", $link);
        if($arreglo['2']=="vimeo.com" || $arreglo['2']=="www.vimeo.com"){
            $web=new sfWebBrowser();
            $linkInfo="http://vimeo.com/api/v2/video/".$arreglo[3].".json";
            $web->get($linkInfo);
            $arrayJson=$web->getResponseText();
            $videoInfo=json_decode($arrayJson);
            
            //$videoInfo=$web->getResponseText();
            
            //self::error('getInfoFindVideoInWeb.- videoInfo: ', var_dump($videoInfo));
            
            $arregloInfo['thumbnail']=$videoInfo[0]->thumbnail_small;
            $arregloInfo['title']=$videoInfo[0]->title;
            $arregloInfo['videoId']=$videoInfo[0]->id;
            $arregloInfo['description']=$videoInfo[0]->description;
            $arregloInfo['urlVideo']=$videoInfo[0]->url;
            
            //self::error('getInfoFindVideoInWeb.- arregloInfo: ', var_dump($arregloInfo));
            
            return $arregloInfo;
        }else{
            return null;
        }
    }
    
    static public function cut_string($string, $charlimit)
    {
        if(substr($string,$charlimit-1,1) != ' ')
        {
            $string = substr($string,'0',$charlimit);
            $array = explode(' ',$string);
            array_pop($array);
            $new_string = implode(' ',$array);

            return $new_string.' ...';
        }else{ 
            return substr($string,'0',$charlimit-1).' ...';
        }
    }


    
    static public function cut_string2($str, $n, $delim='...')
    {
       $len = strlen($str);
       if ($len > $n) {
            preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches);
            return rtrim($matches[1]) . $delim;
        }
        else {
            return $str;
        }
    }
    
    static public function strip_html_tags( $text )
    {
            $text = preg_replace(
                array(
                // Remove invisible content
                    '@<head[^>]*?>.*?</head>@siu',
                    '@<style[^>]*?>.*?</style>@siu',
                    '@<script[^>]*?.*?</script>@siu',
                    '@<object[^>]*?.*?</object>@siu',
                    '@<embed[^>]*?.*?</embed>@siu',
                    '@<applet[^>]*?.*?</applet>@siu',
                    '@<noframes[^>]*?.*?</noframes>@siu',
                    '@<noscript[^>]*?.*?</noscript>@siu',
                    '@<noembed[^>]*?.*?</noembed>@siu',
                // Add line breaks before and after blocks
                    '@</?((address)|(blockquote)|(center)|(del))@iu',
                    '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                    '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                    '@</?((table)|(th)|(td)|(caption))@iu',
                    '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                    '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                    '@</?((frameset)|(frame)|(iframe))@iu',
                ),
                array(
                    ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',"$0", "$0", "$0", "$0", "$0", "$0","$0", "$0",), $text );

            return $text;
     }
     
     static public function TextToUrls($text){
        $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $text);
        $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
        $ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
        $ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
        return $ret;
    }
    static public function getUrlsTwitters($twitters=null){
        if($twitters==null)
            return null;

        foreach($twitters as $key=>$value){
            $arregloTwitters[$key]=sfRichSys::TextToUrls($value->text);
        }
        
        return $arregloTwitters;
    }
    static public function twitter_time($a) {
            //get current timestampt
            $b = strtotime("now"); 
            //get timestamp when tweet created
            $c = strtotime($a);
            //get difference
            $d = $b - $c;
            //calculate different time values
            $minute = 60;
            $hour = $minute * 60;
            $day = $hour * 24;
            $week = $day * 7;

            if(is_numeric($d) && $d > 0) {
                //if less then 3 seconds
                if($d < 3) return " justo ahora";
                //if less then minute
                if($d < $minute) return "hace ". floor($d) . " segundos";
                //if less then 2 minutes
                if($d < $minute * 2) return "hace un minuto";
                //if less then hour
                if($d < $hour) return "hace ". floor($d / $minute) . " minutos";
                //if less then 2 hours
                if($d < $hour * 2) return "hace una hora";
                //if less then day
                if($d < $day) return "hace ". floor($d / $hour) . " horas";
                //if more then day, but less then 2 days
                if($d > $day && $d < $day * 2) return "ayer";
                //if less then year
                if($d < $day * 365) return "hace ". floor($d / $day) . " días";
                //else return more than a year
                return "hace mas de un año";
            }
    }
    
    static public function setDebugMensaje($mensaje){
        $fp = fopen(sfConfig::get('sf_upload_dir'). "/debug.txt","a");
        fwrite($fp, date('l jS \of F Y h:i:s A')." Mensaje: $mensaje" . PHP_EOL);
        fclose($fp);
    }
        
    static public function getStringDateTime($cadena){
        $segundos=0; $minutos=0; $horas=0; $dias=0;
        date_default_timezone_set('America/Mexico_City');
        
        $fecha= new DateTime($cadena);
        
        $minutos= intval($fecha->format('i'));
        $horas=   intval($fecha->format('H'));
        
        if($minutos!=0){
            return $fecha->format('d/M/Y g:i a');
        }elseif($horas!=0){
            return $fecha->format('d/M/Y g a');
        }else{
            return $fecha->format('d/M/Y');
        }
        
    }
    
    static public function getStringFechasInicialFinal($inicial,$final){
        //date_timezone_set($fechaInicial, timezone_open('America/Mexico_City'));
        //date_timezone_set($fechaFinal, timezone_open('America/Mexico_City'));
        date_default_timezone_set('America/Mexico_City');
        $segundos=0; $minutos=0; $horas=0; $dias=0; $meses=0; $years=0;
        $fechaInicial=new DateTime($inicial);
        $fechaFinal=new DateTime($final);
        $intervalo=$fechaInicial->diff($fechaFinal);
        
        $dias= intval($intervalo->d);
        $horas = intval($intervalo->h);
        if($dias>0){
            return $fechaInicial->format('d/M/Y g:i a') . ' a '.
                $fechaFinal->format('d/M/Y g:i a');
        }else{
            if($horas>0){
                return $fechaInicial->format('d/M/Y g:i a') . ' a '.
                    $fechaFinal->format('g:i a');
            }else{
                return $fechaInicial->format('d/M/Y') . ' Todo el dia ';
            }
        }
        
    }
    
}

/*****************************************************************/


/**
 * Handle file uploads via XMLHttpRequest
 */
       class qqUploadedFileXhr {
            /**
             * Save the file to the specified path
             * @return boolean TRUE on success
             */
            function save($path) {
                $input = fopen("php://input", "r");
                $temp = tmpfile();
                $realSize = stream_copy_to_stream($input, $temp);
                fclose($input);

                if ($realSize != $this->getSize()){
                    return false;
                }

                $target = fopen($path, "w");
                fseek($temp, 0, SEEK_SET);
                stream_copy_to_stream($temp, $target);
                fclose($target);

                return true;
            }
            function getName() {
                return $_GET['qqfile'];
            }
            function getSize() {
                if (isset($_SERVER["CONTENT_LENGTH"])){
                    return (int)$_SERVER["CONTENT_LENGTH"];
                } else {
                    throw new Exception('Getting content length is not supported.');
                }
            }
        }

        /**
         * Handle file uploads via regular form post (uses the $_FILES array)
         */
        class qqUploadedFileForm {
            /**
             * Save the file to the specified path
             * @return boolean TRUE on success
             */
            function save($path) {
                if(move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
                    return true;
                }
                return false;
            }
            function getName() {
                return $_FILES['qqfile']['name'];
            }
            function getSize() {
                return $_FILES['qqfile']['size'];
            }
        }

        class qqFileUploader {
            private $allowedExtensions = array();
            private $sizeLimit = 10485760;
            private $file;

            function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){
                $allowedExtensions = array_map("strtolower", $allowedExtensions);

                $this->allowedExtensions = $allowedExtensions;
                $this->sizeLimit = $sizeLimit;

                if (isset($_GET['qqfile'])) {
                    $this->file = new qqUploadedFileXhr();
                } elseif (isset($_FILES['qqfile'])) {
                    $this->file = new qqUploadedFileForm();
                } else {
                    $this->file = false;
                }
            }

            /**
             * Returns array('success'=>true) or array('error'=>'error message')
             */
            function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
                if (!is_writable($uploadDirectory)){
                    chmod($uploadDirectory,777);
                    
                    //return array('error' => "Server error. Upload directory isn't writable.");
                }

                if (!$this->file){
                    return array('error' => 'No files were uploaded.');
                }

                $size = $this->file->getSize();

                if ($size == 0) {
                    return array('error' => 'File is empty');
                }

                if ($size > $this->sizeLimit) {
                    return array('error' => 'File is too large');
                }

                $pathinfo = pathinfo($this->file->getName());
                $filename = $pathinfo['filename'];
                $infoarchivo=explode("-", $filename);
                if(is_array($infoarchivo)){
                    if(!isset($infoarchivo[0])){
                        $infoarchivo[0]=$filename;
                    }else{
                        $infoarchivo[0]=trim($infoarchivo[0]);
                    }
                    if(!isset($infoarchivo[1])){
                        $infoarchivo[1]='';
                    }else{
                        $infoarchivo[1]=trim($infoarchivo[1]);
                    }
                }
                
                //$filename = md5(uniqid());
                $ext = strtolower($pathinfo['extension']);

                if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
                    $these = implode(', ', $this->allowedExtensions);
                    return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
                }

                if(!$replaceOldFile){
                    /// don't overwrite previous files that were uploaded
                    do{
                        $filename =sha1($filename.rand(11111, 99999));
                    }while(file_exists($uploadDirectory . $filename . '.' . $ext));
                }
                try{
                    if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
                        return array(
                            'success'=>true,
                            'filename'=>$filename . '.' . $ext,
                            'original'=>$pathinfo['filename'],
                            'titulo'=>$infoarchivo[0],
                            'contenido'=>$infoarchivo[1],
                            );
                    } else {
                        return array('error'=> 'Could not save uploaded file.' .
                            'The upload was cancelled, or server error encountered');
                    }
                }catch(Exception $e){
                    return array('error'=>$e->getMessage());
                }

            }
        }