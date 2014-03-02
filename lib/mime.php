<?php
/************************************************************************
uebimiau is a gpl'ed software developed by

 - aldoir ventura - aldoir@users.sourceforge.net
 - http://uebimiau.sourceforge.net

fell free to contact, send donations or anything to me :-)
s? paulo - brasil
*************************************************************************/
if (defined('_mime_')) return;
define('_mime_',true);

class mime {
        var $allow_scripts      = true;
        var $use_html           = true;
        var $charset            = "utf-8";
        var $timezone           = "+0900";
        var $user_folder        = "./";
        var $displayimages      = false;
        var $current_level      = array();
        // internal
        var $_msgbody           = "";
        var $_content           = array();
        var $_tnef              = "";

        /**
        open a file and read it until a double line break
        is reached.
        used to get the list of cached messages from cache
        */

        function _get_headers_from_cache($strfile) {
                if(!file_exists($strfile)) return;
                $f = fopen($strfile,"rb");
                while(!feof($f)) {
                        $result .= preg_replace("/\r?\n/","\r\n",fread($f,4096));
                        $pos = strpos($result,"\r\n\r\n");
                        if(!($pos === false)) {
                                $result = substr($result,0,$pos);
                                break;
                        }
                }
                fclose($f);
                unset($f); unset($pos); unset($strfile);
                return $result;
        }


        /**
        open a file and read it fixing possible mistakes
        on the line breaks. a single variable is returned
        */

        function _read_file($strfile) {
                if($strfile == "" || !file_exists($strfile)) return;
                $fp = fopen($strfile,"rb"); fseek($fp,0,seek_end);
                $size = ftell($fp); rewind($fp);
                $result =  preg_replace("/\r?\n/","\r\n",fread($fp,$size));
                fclose($fp);
                return $result;
        }

        /**
        save the specified $content to disk using the $filename path
        */

        function _save_file($filename,$content) {
                $tmpfile = fopen($filename,"wb");
                fwrite($tmpfile,$content);
                fclose($tmpfile);
                unset($content,$tmpfile);
        }


        /**
        recursivelly remove files and directories
        */

        function _rmdirr($location) {
                if (substr($location,-1) <> "/") $location = $location."/";
                $all=opendir($location);
                while ($file=readdir($all)) {
                        if (is_dir($location.$file) && $file <> ".." && $file <> ".") {
                                $this->_rmdirr($location.$file);
                                unset($file);
                        } elseif (!is_dir($location.$file)) {
                                unlink($location.$file);
                                unset($file);
                        }
                }
                closedir($all);
                unset($all);
                rmdir($location);
        }


        /**
        encode header strings to be compliant with mime format

        todo: i18n: implement base64 encoding according to charsets
        */

        function mime_encode_headers($string) {
                if($string == "") return;
        if(!eregi("^([[:print:]]*)$",$string))
                $string = "=?".$this->charset."?Q?".str_replace("+","_",str_replace("%","=",urlencode($string)))."?=";
                return $string;
        }


        /**
        add a body,to a container.
        some malformed messages have more than one body.
        used to display inline attachments (images) too.
        */
        function add_body($strbody) {
                if(!$this->allow_scripts) $strbody = $this->filter_scripts($strbody);
                if($this->_msgbody == "")
                        $this->_msgbody = $strbody;
                else
                        $this->_msgbody .= "\r\n<br>\r\n<br>\r\n<hr>\r\n<br>\r\n$strbody";
        }


        /**
        this function,if running under php 4.3+ will convert any string between charsets.
        if running under php < 4.3,will convert the string to php's default charset (iso-8859-1)
        */
        function convert_charset($string,$from,$to) {
//�ӽ�
return $string;
                $string = htmlentities($string,ENT_COMPAT,$from);

                if( function_exists('html_entity_decode') ) { //php 4.3+(����ȯ)
                        return html_entity_decode($string,ENT_COMPAT,$to);
                } else {
                        return $this->unhtmlentities($string);
                }//end if else
        }

        /**
        decode headers strings. inverse of mime_encode_headers()
        */

        function decode_mime_string($subject) {
                $string = $subject;

                if(($pos = strpos($string,"=?")) === false) return $string;

                $newresult='';
                while(!($pos === false)) {

                        $newresult .= substr($string,0,$pos);
                        $string = substr($string,$pos+2,strlen($string));
                        $intpos = strpos($string,"?");
                        $charset = substr($string,0,$intpos);
                        $enctype = strtolower(substr($string,$intpos+1,1));
                        $string = substr($string,$intpos+3,strlen($string));
                        $endpos = strpos($string,"?=");
                        $mystring = substr($string,0,$endpos);
                        $string = substr($string,$endpos+2,strlen($string));
                        if($enctype == "q") $mystring = quoted_printable_decode(ereg_replace("_"," ",$mystring));
                        else if ($enctype == "b") $mystring = base64_decode($mystring);

                        if(strcasecmp($charset,$this->charset) <> 0)
                        $mystring = $this->convert_charset($mystring,$charset,$this->charset);

                        $newresult .= $mystring;
                        $pos = strpos($string,"=?");

                }//end while
                $result = $newresult.$string;
                if(ereg("koi8",$subject)) $result = convert_cyr_string($result,"k","w");
                return $result;

        }

        /**
        split headers into an array,where the key is the same found in the header.

        subject: hi

                will be converted in

        $decodedheaders["subject"] = "hi";

        some headers are broken into multiples lines,prefixed with a tab (\t)
        */
        function decode_header($header) {
                $headers = explode("\r\n",$header);
                $decodedheaders = array();
                for($i=0;$i<count($headers);$i++) {
                        $thisheader = trim($headers[$i]);
                        if(!empty($thisheader))
                                if(!ereg("^[A-Z0-9a-z_-]+:",$thisheader))
                                        $decodedheaders[$lasthead] .= " ".$thisheader;
                                else {
                                        $dbpoint = strpos($thisheader,":");
                                        $headname = strtolower(substr($thisheader,0,$dbpoint));
                                        $headvalue = trim(substr($thisheader,$dbpoint+1));
                                        if(array_key_exists($headname,$decodedheaders)) $decodedheaders[$headname] .= "; $headvalue";
                                        else $decodedheaders[$headname] = $headvalue;
                                        $lasthead = $headname;
                                }
                }

                return $decodedheaders;
        }


        /**
        try to extract all names in a specified field (from,to,cc)
        in order to guess what is the format (the rfc support 3),it will
        try different ways to get an array with name and email
        */

        function get_names($strmail) {
                $arfrom = array();
                $strmail = stripslashes(ereg_replace("(\t|\r|\n)","",$strmail));

                if(trim($strmail) == "") return $arfrom;

                $armail = array();
                $counter = 0;  $inthechar = 0;
                $chartosplit = ",;"; $protectchar = "\""; $temp = "";
                $lt = "<"; $gt = ">";
                $closed = 1;

                for($i=0;$i<strlen($strmail);$i++) {
                        $thischar = $strmail[$i];
                        if($thischar == $lt && $closed) $closed = 0;
                        if($thischar == $gt && !$closed) $closed = 1;
                        if($thischar == $protectchar) $inthechar = ($inthechar)?0:1;
                        if(!(strpos($chartosplit,$thischar) === false) && !$inthechar && $closed) {
                                $armail[] = $temp; $temp = "";
                        } else
                                $temp .= $thischar;
                }

                if(trim($temp) != "")
                        $armail[] = trim($temp);

                for($i=0;$i<count($armail);$i++) {
                        $thispart = trim(eregi_replace("^\"(.*)\"$","\\1",trim($armail[$i])));
                        if($thispart != "") {
                                if (eregi("(.*)<(.*)>",$thispart,$regs)) {
                                        $email = trim($regs[2]);
                                        $name = trim($regs[1]);
                                } else {
                                        if (eregi("([-a-z0-9_$+.]+@[-a-z0-9_.]+[-a-z0-9_]+)((.*))",$thispart,$regs)) {
                                                $email = $regs[1];
                                                $name = $regs[2];
                                        } else
                                                $email = $thispart;
                                }

                                $email = preg_replace("/<(.*)\\>/","\\1",$email);
                                $name = preg_replace("/\"(.*)\"/","\\1",trim($name));
                                $name = preg_replace("/\((.*)\)/","\\1",$name);

                                if ($name == "") $name = $email;
                                if ($email == "") $email = $name;
                                $arfrom[$i]["name"] = $this->decode_mime_string($name);
                                $arfrom[$i]["mail"] = $email;
                                unset($name);unset($email);
                        }
                }
                return $arfrom;
        }


        /**
        compile a body for multipart/alternative format.
        guess the format we want and add it to the bod container
        */

        function build_alternative_body($ctype,$body) {

                $boundary = $this->get_boundary($ctype);
                $parts = $this->split_parts($boundary,$body);

                $thispart = ($this->use_html)?$parts[1]:$parts[0];

                foreach($parts as $index => $value) {
                        $email = $this->fetch_structure($value);

                        $parts[$index] = $email;
                        $parts[$index]["headers"] = $headers = $this->decode_header($email["header"]);
                        unset($email);
                        $ctype = split(";",$headers["content-type"]); $ctype = strtolower($ctype[0]);

                        $parts[$index]["type"] = $ctype;
                        if($this->use_html && $ctype == "text/html") {
                                $part = $parts[$index];
                                break;
                        } elseif (!$this->use_html && $ctype == "text/plain") {
                                $part = $parts[$index];
                                break;
                        }

                }

                if(!isset($part)) $part = $parts[0];
                unset($parts);


                $body = $this->compile_body($part["body"],$part["headers"]["content-transfer-encoding"],$part["headers"]["content-type"]);

                if(!$this->use_html && $part["type"] != "text/plain") $body = $this->html2text($body);
                if(!$this->use_html) $body = $this->build_text_body($body);
                $this->add_body($body);
        }

        /**
        recursively compile the parts of multipart/* emails.
        'complex' means multipart/signed|mixed|related|report and other
        types that can be added in the future
        */

        function build_complex_body($ctype,$body) {
                global $sid,$lid,$ix,$folder;

                $rtype = trim(substr($ctype,strpos($ctype,"type=")+5,strlen($ctype)));

                if(strpos($rtype,";") != 0)
                        $rtype = substr($rtype,0,strpos($rtype,";"));
                if(substr($rtype,0,1) == "\"" && substr($rtype,-1) == "\"")
                        $rtype = substr($rtype,1,strlen($rtype)-2);


                $boundary = $this->get_boundary($ctype);
                $part = $this->split_parts($boundary,$body);

                for($i=0;$i<count($part);$i++) {

                        $email = $this->fetch_structure($part[$i]);

                        $header = $email["header"];
                        $body = $email["body"];
                        $headers = $this->decode_header($header);

                        $ctype = $headers["content-type"];
                        $cid = $headers["content-id"];

                        $actype = split(";",$headers["content-type"]);
                        $types = split("/",$actype[0]); $rctype = strtolower($actype[0]);

                        $is_download = (ereg("name=",$headers["content-disposition"].$headers["content-type"]) || $headers["content-id"] != "" || $rctype == "message/rfc822");

                        if($rctype == "multipart/alternative") {

                                $this->build_alternative_body($ctype,$body);

                        } elseif($rctype == "text/plain" && !$is_download) {

                                $body = $this->compile_body($body,$headers["content-transfer-encoding"],$headers["content-type"]);
                                $this->add_body($this->build_text_body($body));

                        } elseif($rctype == "text/html" &&  !$is_download) {

                                $body = $this->compile_body($body,$headers["content-transfer-encoding"],$headers["content-type"]);

                                if(!$this->use_html) $body = $this->build_text_body($this->html2text($body));
                                $this->add_body($body);

                        } elseif($rctype == "application/ms-tnef") {

                                $body = $this->compile_body($body,$headers["content-transfer-encoding"],$headers["content-type"]);
                                $this->extract_tnef($body,$boundary,$i);

                        } elseif($is_download) {

                                $thisattach     = $this->build_attach($header,$body,$boundary,$i);
                                $tree           = array_merge($this->current_level,array($thisattach["index"]));
                                $thisfile       = "download.php?sid=$sid&tid=$tid&lid=$lid&folder=".urlencode($folder)."&ix=".$ix."&attach=".join(",",$tree);
                                $filename       = $thisattach["filename"];
                                $cid = preg_replace("/<(.*)\\>/","\\1",$cid);

                                if($cid != "") {
                                        $cid = "cid:$cid";
                                        $this->_msgbody = preg_replace("/".quotemeta($cid)."/i",$thisfile,$this->_msgbody);

                                } elseif($this->displayimages) {
                                        $ext = substr($thisattach["name"],-4);
                                        $allowed_ext = array(".gif",".jpg");
                                        if(in_array($ext,$allowed_ext)) {
                                                $this->add_body("<img src=\"$thisfile\">");
                                        }
                                }

                        } else
                                $this->process_message($header,$body);

                }
        }


        /**
        format a plain text string into a html formated string
        */

        function build_text_body($body) {
                $body = preg_replace("/(\r\n|\n|\r)/","<br />\\1",$this->make_link_clickable(htmlspecialchars($body)));
                return "<font face=\"courier new\" size=2>$body</font>";
        }

        /**
        decode quoted-printable strings
        */
        function decode_qp($str) {
                return quoted_printable_decode(preg_replace("/=\r?\n/","",$str));
        }


        /**
        convert url and emails into clickable links
        */

        function make_link_clickable($str){

                $str = eregi_replace("([[:space:]])((f|ht)tps?:\/\/[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)","\\1<a class=autolink href=\"\\2\" target=\"_blank\">\\2</a>",$str); //http
                $str = eregi_replace("([[:space:]])(www\.[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)","\\1<a class=autolink href=\"http://\\2\" target=\"_blank\">\\2</a>",$str); // www.
                $str = eregi_replace("([[:space:]])([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})","\\1<a class=autolink href=\"mailto:\\2\">\\2</a>",$str); // mail

                $str = eregi_replace("^((f|ht)tp:\/\/[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)","<a href=\"\\1\" target=\"_blank\">\\1</a>",$str); //http
                $str = eregi_replace("^(www\.[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)","<a class=autolink href=\"http://\\1\" target=\"_blank\">\\1</a>",$str); // www.
                $str = eregi_replace("^([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})","<a class=autolink href=\"mailto:\\1\">\\1</a>",$str); // mail

                return $str;
        }


        /**
        guess the type of the part and call the apropriated
        method
        */

        function process_message($header,$body) {
                $mail_info = $this->get_mail_info($header);

                $ctype = $mail_info["content-type"];
                $ctenc = $mail_info["content-transfer-encoding"];

                if($ctype == "") $ctype = "text/plain";

                $type = $ctype;

                $ctype = split(";",$ctype);
                $types = split("/",$ctype[0]);

                $maintype = trim(strtolower($types[0]));
                $subtype = trim(strtolower($types[1]));

                switch($maintype) {
                case "text":
                        $body = $this->compile_body($body,$ctenc,$mail_info["content-type"]);
                        switch($subtype) {
                        case "html":
                                if(!$this->use_html) $body = $this->build_text_body($this->html2text($body));
                                $msgbody = $body;
                                break;
                        default:
                                $this->extract_uuencoded($body);
                                $msgbody = $this->build_text_body($body);
                                break;
                        }
                        $this->add_body($msgbody);
                        break;
                case "multipart":
                        if(ereg($subtype,"signed,mixed,related,report"))
                                $subtype = "complex";

                        switch($subtype) {
                        case "alternative":
                                $msgbody = $this->build_alternative_body($ctype[1],$body);
                                break;
                        case "complex":
                                $msgbody = $this->build_complex_body($type,$body);
                                break;
                        default:
                                $thisattach = $this->build_attach($header,$body,"",0);
                        }
                        break;
                default:
                        $thisattach = $this->build_attach($header,$body,"",0);
                }
        }

        /**
        compile the attachment,saving it to cache and
        add it to the $attachments array if needed
        */

        function build_attach($header,$body,$boundary,$part) {

                global $mail,$temporary_directory,$userfolder;

                $headers = $this->decode_header($header);
                $cdisp = $headers["content-disposition"];
                $ctype = $headers["content-type"];

                preg_match("/filename ?= ?(.+)/i",$cdisp,$matches);
                $filename = preg_replace("/\"(.*)\"/","\\1",trim($matches[1]));
                if(!$filename) {
                        preg_match("/name ?= ?(.+)/i",$ctype,$matches);
                        $filename = preg_replace("/\"(.*)\"/","\\1",trim($matches[1]));
                }

                $tenc = $headers["content-transfer-encoding"];

                preg_match("/[a-z0-9]+/",$cdisp,$matches);
                $content_disposition    = $matches[0];

                preg_match("/[a-z0-9\/-]+/",$ctype,$matches);
                $content_type   = $matches[0];

                $tmp                    = explode("/",$content_type);
                $main_type              = $tmp[0];
                $sub_type               = $tmp[1];

                $is_embebed = ($headers["content-id"] != "")?1:0;

                $body = $this->compile_body($body,$tenc,$ctype);


                if($filename == "" && $main_type == "message") {
                        $attachheader = $this->fetch_structure($body);
                        $attachheader = $this->decode_header($attachheader["header"]);
                        $filename = $attachheader["subject"].".eml";
                        unset($attachheader);
                } elseif($filename == "") {
                        $filename = uniqid("").".tmp";
                }

                $filename = preg_replace("/[.]{2,}/",".",preg_replace("'(/|\\\\)+'","_",trim($this->decode_mime_string($filename))));

                $nindex                                 = count($this->_content["attachments"]);
                $temp_array["name"]                     = trim($filename);
                $temp_array["size"]                     = strlen($body);
                $temp_array["temp"]                     = $is_embebed;
                $temp_array["content-type"]             = strtolower(trim($content_type));
                $temp_array["content-disposition"]      = strtolower(trim($content_disposition));
                $temp_array["boundary"]                 = $boundary;
                $temp_array["part"]                     = $part;
                $temp_array["filename"]                 = $this->user_folder."_attachments/".md5($temp_array["boundary"])."_".$temp_array["name"];
//todo::
                $temp_array["filename"]=$this->user_folder."/".md5($temp_array["boundary"])."_".$temp_array["name"];
                $temp_array["type"]                     = "mime";
                $temp_array["index"]                    = $nindex;

                $this->_save_file($temp_array["filename"],$body);
                $this->_content["attachments"][$nindex] = $temp_array;
                return $temp_array;
        }


        /**
        compile a string following the encoded method
        */

        function compile_body($body,$enctype,$ctype) {

                $enctype = explode(" ",$enctype);
                $enctype = $enctype[0];
                if(strtolower($enctype) == "base64")
                        $body = base64_decode($body);
                elseif(strtolower($enctype) == "quoted-printable")
                        $body = $this->decode_qp($body);

                if(ereg("koi8",$ctype))
                        $body = convert_cyr_string($body,"k","w");
                else
                        if(preg_match("/charset ?= ?\"?([a-z0-9_-]+)\"?/i",$ctype,$regs)) {
                                if(strcasecmp($regs[1],$this->charset) <> 0)
                                $body = $this->convert_charset($body,$regs[1],$this->charset);
                        }

                return $body;

        }

        /**
        todo: remove this function

        function download_attach($header,&$body,$bound="",$part=0,$down=1,$type,$tnef) {
                if ($type == "uue") {
                        $this->get_uuencoded($body,$bound,$down,"down");
                }else {
                        if ($bound != "") {
                                $parts = $this->split_parts($bound,$body);
                                // split the especified part of mail,body and headers
                                $email = $this->fetch_structure($parts[$part]);
                                $header = $email["header"];
                                $body = $email["body"];
                                unset($email);
                        }
                        if($type == "tnef" && is_numeric($tnef))
                                $this->get_tnef($header,$body,$tnef,$down,"down");
                        else
                                $this->build_attach($header,$body,"",0,$mode="down",$down);
                }
        }

        */

        /**
        guess the attachment format and call the specific method
        */

        function save_attach($header,&$body,$filename,$type="mime",$tnef="-1",$bound) {
                switch($type) {
                case "uue":
                        $this->get_uuencoded($body,$bound,0,$mode="save",$filename);
                        break;
                case "tnef":
                        $this->get_tnef($header,$body,$tnef,0,$mode="save",$filename);
                        break;
                default:
                        $this->build_attach($header,$body,"",0,$mode="save",0,$filename);
                }
        }


        /**
        guess all needed information about this mail
        */

        function get_mail_info($header) {

                $myarray = array();
                $headers = $this->decode_header($header);

                $myarray["message-id"] = (array_key_exists("message-id",$headers))?ereg_replace("<(.*)>","\\1",trim($headers["message-id"])):null;
                $myarray["content-type"] = (array_key_exists("content-type",$headers))?$headers["content-type"]:null;
                $myarray["priority"] = (array_key_exists("x-priority",$headers))?$headers["x-priority"][0]:null;
                $myarray["flags"] = (array_key_exists("x-um-flags",$headers))?$headers["x-um-flags"]:null;
                $myarray["content-transfer-encoding"] = (array_key_exists("content-transfer-encoding",$headers))?str_replace("gm","-",$headers["content-transfer-encoding"]):null;

                $received       = ereg_replace("  "," ",$headers["received"]);
                $user_date      = ereg_replace("  "," ",$headers["date"]);

                if(eregi("([0-9]{1,2}[ ]+[a-z]{3}[ ]+[0-9]{4}[ ]+[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2})[ ]?((\+|-)[0-9]{4})?",$received,$regs)) {
                        //eg. tue,4 sep 2001 16:22:31 -0000
                        $mydate = $regs[1];
                        $mytimezone = $regs[2];
                        if(empty($mytimezone))
                                if(eregi("((\\+|-)[0-9]{4})",$user_date,$regs)) $mytimezone = $regs[1];
                                else $mytimezone = $this->timezone;
                } elseif(eregi("(([a-z]{3})[ ]+([0-9]{1,2})[ ]+([0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2})[ ]+([0-9]{4}))",$received,$regs)) {
                        //eg. tue sep 4 16:26:17 2001 (cubic circle's style)
                        $mydate = $regs[3]." ".$regs[2]." ".$regs[5]." ".$regs[4];
                        if(eregi("((\\+|-)[0-9]{4})",$user_date,$regs)) $mytimezone = $regs[1];
                        else $mytimezone = $this->timezone;
                } elseif(eregi("([0-9]{1,2}[ ]+[a-z]{3}[ ]+[0-9]{4}[ ]+[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2})[ ]?((\+|-)[0-9]{4})?",$user_date,$regs)) {
                        //eg. tue,4 sep 2001 16:22:31 -0000 (from date header)
                        $mydate = $regs[1];
                        $mytimezone = $regs[2];
                        if(empty($mytimezone))
                                if(eregi("((\\+|-)[0-9]{4})",$user_date,$regs)) $mytimezone = $regs[1];
                                else $mytimezone = $this->timezone;
                } else {
                        $mydate         = date("d m y h:i");
                        $mytimezone     = $this->timezone;
                }

                $myarray["date"] = $this->build_mime_date($mydate,$mytimezone);
                $myarray["subject"] = $this->decode_mime_string($headers["subject"]);
                $myarray["from"] = $this->get_names($headers["from"]);
                $myarray["to"] = $this->get_names($headers["to"]);
                $myarray["cc"] = $this->get_names($headers["cc"]);
                $myarray["reply-to"] = $this->get_names($headers["reply-to"]);
                $myarray["status"] = $headers["status"];
                $myarray["read"] = $headers["x-um-status"];

                return $myarray;

        }


        /**
        convert a timestamp value into a rfc-compliant date
        */

        function build_mime_date($mydate,$timezone = "+0000") {
                if(!ereg("((\\+|-)[0-9]{4})",$timezone)) $timezone = "+0000";
                if(!$intdate = strtotime($mydate)) return time();
                if(preg_match("/(\\+|-)+([0-9]{2})([0-9]{2})/",$timezone,$regs)) $datetimezone = ($regs[1].$regs[2]*3600)+($regs[1].$regs[3]*60);
                else $datetimezone = 0;
                if(preg_match("/(\\+|-)+([0-9]{2})([0-9]{2})/",$this->timezone,$regs)) $usertimezone = ($regs[1].$regs[2]*3600)+($regs[1].$regs[3]*60);
                else $usertimezone = 0;
                $diff = $datetimezone-$usertimezone;
                return ($intdate+$diff);
        }


        /**
        main method called by script,start the decoding process
        */
        function decode($email) {

                $email = $this->fetch_structure($email);//���&�ٵ� ������
                $this->_msgbody = "";
                $body = $email["body"];
                $header = $email["header"];

                $mail_info = $this->get_mail_info($header);
                $this->process_message($header,$body);
                $this->_content["headers"] = $header;
                $this->_content["date"] = $mail_info["date"];
                $this->_content["subject"] = $mail_info["subject"];
                $this->_content["message-id"] = $mail_info["message-id"];
                $this->_content["from"] = $mail_info["from"];
                $this->_content["to"] = $mail_info["to"];
                $this->_content["cc"] = $mail_info["cc"];
                $this->_content["reply-to"] = $mail_info["reply-to"];
                $this->_content["body"] = $this->_msgbody;
                $this->_content["read"] = $mail_info["read"];
                $this->_content["priority"] = $mail_info["priority"];
                $this->_content["flags"] = $mail_info["flags"];

                return $this->_content;
        }

        /**
        split an email by its boundary
        */

        function split_parts($boundary,$body) {
                $startpos = strpos($body,$boundary)+strlen($boundary)+2;
                $lenbody = strpos($body,"\r\n$boundary--") - $startpos;
                $body = substr($body,$startpos,$lenbody);
                return explode($boundary."\r\n",$body);
        }

        /**
        split header and body into an array
        */

        function fetch_structure($email) {
                $aremail = array();
                $separador = "\r\n\r\n";
                $header = trim(substr($email,0,strpos($email,$separador)));
                $bodypos = strlen($header)+strlen($separador);
                $body = substr($email,$bodypos,strlen($email)-$bodypos);
                $aremail["header"] = $header; $aremail["body"] = $body;
                return $aremail;
        }

        /**
        guess the boundary from header
        */

        function get_boundary($ctype){
                if(preg_match('/boundary[ ]?=[ ]?(["]?.*)/i',$ctype,$regs)) {
                        $boundary = preg_replace('/^\"(.*)\"$/',"\\1",$regs[1]);
                        return trim("--$boundary");
                }
        }

        /**
        aux method for filter_scripts
        */

        function _filter_tag($str) {
                $matches = array(
                                        "'(%[0-9a-za-z]{2})+'e",//unicode
                                        "'(\bon\w+)'i",//events
                                        "'(href)( *= *[\"\"]?\w+script *:[^\"\' >]+)'i" //links
                                        );
                $replaces = array("chr(hexdec('\\1'))","\\1_filtered","\\1_filtered\\2");
                return stripslashes(preg_replace($matches,$replaces,$str));
        }

        /**
        filter any javascript: used if $allow_scripts is off
        */

        function filter_scripts($str) {
                return preg_replace(
                                        array("'(<\/?\w+[^>]*>)'e","'<script[^>]*?>.*?</script[^>]*?>'si"),
                                        array("\$this->_filter_tag('\\1')",""),$str);
        }

        /**
        oposite of htmlentities.
        */
        function unhtmlentities ($string) {
                $trans_tbl = get_html_translation_table (HTML_ENTITIES);
                $trans_tbl = array_flip ($trans_tbl);
                return strtr ($string,$trans_tbl);
        }

        /**
        format a html message to be displayed as text if allow_html is off
        */
        function html2text($str) {
                return $this->unhtmlentities(preg_replace(
                                array(  "'<(script|style)[^>]*?>.*?</(script|style)[^>]*?>'si",
                                                "'(\r|\n)'",
                                                "'<br[^>]*?>'i",
                                                "'<p[^>]*?>'i",
                                                "'<\/?\w+[^>]*>'e"
                                                ),
                                array(  "",
                                                "",
                                                "\r\n",
                                                "\r\n\r\n",
                                                ""),
                                $str));
        }

        /**
        decode uuencoded attachments
        */
        function uudecode($data) {
                $b64chars='abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz0123456789+/a';
                $uudchars='`!"#$%&\'()*+,-./0123456789:;<=>?@abcdefghijklmnopqrstuvwxyz[\]^_ ';
                $lines = preg_split('/\r?\n/',$data);
                $encode = "";
                foreach ($lines as $line) {
                        if($line != '') {
                                $count   = (ord($line[0])-32)%64;
                                $count   = ceil(($count*4)/3);
                                $encode .= substr(ltrim($line),1,$count);
                        }
                }
                $encode = strtr($encode,$uudchars,$b64chars);
                while(strlen($encode) % 4) {
                        $encode .= '=';
                }
                return base64_decode($encode);
        }

        /**
        guess all uuencoded in the body
        */

        function extract_uuencoded(&$body) {
                $regex = "/(begin ([0-7]{3}) (.+))\r?\n(.+)\r?\nend/us";
        preg_match_all($regex,$body,$matches);
        for ($i = 0; $i < count($matches[3]); $i++) {

                        $boundary       = $matches[1][$i];
                        $fileperm       = $matches[2][$i];
                        $filename       = $matches[3][$i];
                        $stream         = $this->uudecode($matches[4][$i]);

                        $temp_array["index"]= count($this->_content["attachments"]);
                        $temp_array["name"] = $filename;
                        $temp_array["size"] = strlen($stream);
                        $temp_array["content-type"] = "application/unknown";
                        $temp_array["content-disposition"] = "attachment";
                        $temp_array["boundary"] = $boundary;
                        $temp_array["part"] = 0;
                        $temp_array["type"] = "uue";
                        $temp_array["filename"] = $this->user_folder."_attachments/".md5($temp_array["boundary"])."_".$temp_array["name"];
//todo::
                        $temp_array["filename"] = $this->user_folder."/".md5($temp_array["boundary"])."_".$temp_array["name"];

                        $this->_save_file($temp_array["filename"],$stream);
                        $this->_content["attachments"][] = $temp_array;
                }
                $body = preg_replace($regex,"",$body);
        }


        /**
        extract all attachmentes contained in a ms-tnef attachment
        */

        function extract_tnef(&$body,$boundary,$part) {
                $tnefobj = $this->_tnef->decode($body);

                for($i=0;$i<count($tnefobj);$i++) {
                        $content                                = $tnefobj[$i]["stream"];
                        $temp_array["index"]                    = count($this->_content["attachments"]);
                        $temp_array["name"]                     = $tnefobj[$i]["name"];
                        $temp_array["size"]                     = $tnefobj[$i]["size"];
                        $temp_array["content-type"]             = $tnefobj[$i]["type0"]."/".$tnefobj[$i]["type1"];
                        $temp_array["content-disposition"]      = "attachment";
                        $temp_array["boundary"]                 = $boundary;
                        $temp_array["part"]                     = $part;
                        $temp_array["type"]                     = "tnef";
                        $temp_array["tnef"]                     = $i;
                        $temp_array["filename"]                 = $this->user_folder."_attachments/".md5($temp_array["boundary"])."_".$temp_array["name"];
//todo::
                        $temp_array["filename"]=$this->user_folder."/".md5($temp_array["boundary"])."_".$temp_array["name"];

                        $this->_save_file($temp_array["filename"],$content);
                        $this->_content["attachments"][]        = $temp_array;
                }

        }

        /**
        used for imap servers wich uses inbox. as prefix for folder names
        */

        function fix_prefix($folder,$add = 0) {
                if(             $this->mail_protocol == "imap" &&
                                !preg_match("/^inbox$/i",$folder) &&
                                $this->mail_prefix &&
                                !preg_match("/^_/",$folder)) {

                        if($add) return $this->mail_prefix.$folder;
                        else return preg_replace("/^".quotemeta($this->mail_prefix)."/","",$folder);

                } else return $folder;
        }

        //todo::
        function utf8ToUnicodeEntities ($source){
                // array used to figure what number to decrement from character order value
                // according to number of characters used to map unicode to ascii by utf-8

                $decrement[4] = 240;
                $decrement[3] = 224;
                $decrement[2] = 192;
                $decrement[1] = 0;

                // the number of bits to shift each charNum by
                $shift[1][0] = 0;
                $shift[2][0] = 6;
                $shift[2][1] = 0;
                $shift[3][0] = 12;
                $shift[3][1] = 6;
                $shift[3][2] = 0;

               $pos = 0;
                $len = strlen ($source);
                $encodedString = '';
                while ($pos < $len) {
                        $asciiPos = ord (substr ($source, $pos, 1));
                        if (($asciiPos >= 240) && ($asciiPos <= 255)) {
                                // 4 chars representing one unicode character
                                $thisLetter = substr ($source, $pos, 4);
                                $pos += 4;
                        }
                        else if (($asciiPos >= 224) && ($asciiPos <= 239)) {
                                // 3 chars representing one unicode character
                                $thisLetter = substr ($source, $pos, 3);
                                $pos += 3;
                        }
                        else if (($asciiPos >= 192) && ($asciiPos <= 223)) {
                                // 2 chars representing one unicode character
                                $thisLetter = substr ($source, $pos, 2);
                                $pos += 2;
                        }
                        else {
                                // 1 char (lower ascii)
                                $thisLetter = substr ($source, $pos, 1);
                                $pos += 1;
                        }

                        // process the string representing the letter to a unicode entity
                        $thisLen = strlen ($thisLetter);
                        $thisPos = 0;
                        $decimalCode = 0;
                        while ($thisPos < $thisLen) {
                                $thisCharOrd = ord (substr ($thisLetter, $thisPos, 1));
                                if ($thisPos == 0) {
                                        $charNum = intval ($thisCharOrd - $decrement[$thisLen]);
                                        $decimalCode += ($charNum << $shift[$thisLen][$thisPos]);
                                }else {
                                        $charNum = intval ($thisCharOrd - 128);
                                        $decimalCode += ($charNum << $shift[$thisLen][$thisPos]);
                                }//end if else

                                $thisPos++;
                        }

                        if ($thisLen == 1)
                                $encodedLetter = "&#". str_pad($decimalCode, 3, "0", STR_PAD_LEFT) . ';';
                        else
                                $encodedLetter = "&#". str_pad($decimalCode, 5, "0", STR_PAD_LEFT) . ';';

                        $encodedString .= $encodedLetter;
                }

                return $encodedString;
        }
}
?>
