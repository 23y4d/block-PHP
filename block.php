<?php

/*
 * @ package   securtiy/block
 * @ author    Zeyad Besiso <zeyad.besiso@gmail.com>
 * @link       https://github.com/23y4d/block-PHP
 */

date_default_timezone_set("Asia/Gaza");


namespace securtiy\block;

class block {



        const HTACCESS = ".htaccess";



        private $ip,
                $url,
                $agent,
                $time,
                $email,
                $whitelist,$readfile;




private $write ="\n# @package   htaccess securtiy\n#  @author:Zeyad Besiso <zeyad.besiso@gmail.com>\n
RewriteEngine On\n ErrorDocument 403 /403.php\n
##################################################################\n
RewriteRule ^css/?$      d.php [L,QSA]\nRewriteRule ^js/?$       d.php [L,QSA]\n
RewriteRule ^fonts/?$    d.php [L,QSA]\nRewriteRule ^customer/?$ d.php [L,QSA]\n
RewriteRule ^images/?$   d.php [L,QSA]\nRewriteRule ^file/?$     d.php [L,QSA]\n
######################## START BANS ###############################\n";




        const EXIST = '@author:Zeyad Besiso <zeyad.besiso@gmail.com>';

        /**
         * Constructor
         *
         * Sets up the object with the passed arguments
         *
         * @param string $email email who you want send info
         * @param array  $whitelist  IP  person you don't need blocked
    	 *
         * @return void
         */
            function __construct($email=null,$whitelist=array()){

                 $this->email     = $email;
                 $this->whitelist = $whitelist;
                 $this->url       = htmlspecialchars($_SERVER['REQUEST_URI'],ENT_QUOTES);
                 $this->agent     = str_replace(array("\n", "\r"), '',$_SERVER['HTTP_USER_AGENT']);
                 $this->time      = date("Y-m-d H:i:s");
                 $this->readfile  = file_get_contents(".htaccess");

                 try {
                        $this->openHtaccess();
                        $this->getUserIP();
                        $this->blockedUser();
                    }catch(Exception $why) {
                        	echo $why->getMessage();
                 }
            }



        public function openHtaccess() {

            if(!file_exists(self::HTACCESS)){
                $f = fopen(self::HTACCESS, "a+");
                $r = fwrite($f,$this->write);
                fclose($f);
            }elseif(strpos($this->readfile,self::EXIST)==0){
                    return file_put_contents(self::HTACCESS,$this->write,FILE_APPEND);
                }else{
                    if(strpos($this->readfile,self::EXIST)==1){
                        return false;
                    }
                }
       }


        private function getUserIP(){
                if (!empty($_SERVER['HTTP_CLIENT_IP'])){
                  $ip=$_SERVER['HTTP_CLIENT_IP'];
                }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
                }else{
                $ip=$_SERVER['REMOTE_ADDR'];
                }
              $this->ip = $ip;
        }



        private function blockedUser(){
            if (empty($_SERVER['HTTP_REFERER'])) {
                  if(in_array($this->ip,$this->whitelist)) {
                    header('location:../index.php');
                    exit;
                  }else{
                            $ban =  "\n# The IP below was banned on {$this->time} for trying to access {$this->url}\n";
                            $ban .= "# Agent: {$this->agent}\n";
                            $ban .= "Deny from {$this->ip} \n# By  https://twitter.com/ZEY4D_\n";
                            file_put_contents(self::HTACCESS,$ban,FILE_APPEND);
                    if (!empty($this->email)) {
                          $Subject  = "Website Auto Ban:";
                          $message  = "hello Mr,\n this person below was banned on for trying to access soruce website\n
                          His information:\n";
                          $message .= "IP Address:{$this->ip}\n";
                          $message .= "Date/Time:{$this->time}\n";
                          $message .= "User Agent:{$this->agent}\n";
                          $message .= "tring access to URL:{$this->url}\n";
                          $message .= "------------------------------------\n";
                          $message .= "this massage send by shad0w , zeyad besiso \n";
                          $send = mail($this->email,$Subject.$this->ip,$message);
                       }
                    }
               }
        }
}
