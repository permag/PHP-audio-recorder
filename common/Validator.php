<?php
    namespace permag\common;

    /**
     * Validator for user inputs
     */
    class Validator {
            private $errorNumber = null;
            private static $instance;
            private static $kMinPasswordLength = 8;
            private static $kMaxPasswordLength = 15;
            
            // Valideringsfel
            const WRONG_EMAIL_FORMAT = 'WRONG_EMAIL_FORMAT';
            const WRONG_USERNAME_FORMAT = 'WRONG_USERNAME_FORMAT';
            const WRONG_PASSWORD_FORMAT = 'WRONG_PASSWORD_FORMAT';
            const WRONG_SSN_FORMAT = 'WRONG_SSN_FORMAT';
            const WRONG_DATE_FORMAT = 'WRONG_DATE_FORMAT';
            
            public static function GetInstance() {
                if (!self::$instance) {
                    self::$instance = new Validator();
                } 
                return  self::$instance;
            }
            
            public function GetValidationError() {
                return $this->errorNumber;
            }
            
            public function ValidateEmail($email) {
    			$pattern = "/^([a-z0-9\\+_\\-]+)(\\.[a-z0-9\\+_\\-]+)*@([a-z0-9\\-]+\\.)+[a-z]{2,6}$/ix";

    			if (preg_match($pattern, $email) && strlen($email) <= 40) {
    				return true;
    			} else {
    				$this->errorNumber = self::WRONG_EMAIL_FORMAT;
    				return false;
    			}
    		}
            
            public function ValidateUsername($username) {
    			if (preg_match('/^[a-zA-Z0-9]{5,18}$/', $username)) {
    				return true;
    			} else {
    				$this->errorNumber = self::WRONG_USERNAME_FORMAT;
    				return false;
    			}
    		}
            
    		public function ValidatePassword($password) {
    			if (preg_match('/^[a-zA-Z0-9]{6,}$/', $password)) {
    				return true;
    			} else {
    				$this->errorNumber = self::WRONG_PASSWORD_FORMAT;
    				return false;
    			}
            }
            
            
            public function ValidateSSN($ssn) {
                $pattern[]="^[12]{1}[90]{1}[0-9]{6}-[0-9]{4}$";    //XXXXXXXX-XXXX
                $pattern[]="^[12]{1}[90]{1}[0-9]{6}[0-9]{4}$";     //XXXXXXXXXXXX
                $pattern[]="^[10]{1}[90]{1}[0-9]{6}-[0-9]{4}$";    //XXXXXX-XXXX
                $pattern[]="^[10]{1}[90]{1}[0-9]{6}[0-9]{4}$";     //XXXXXXXXXX

                foreach ($pattern as $val) {
                    if (!preg_match($val, $ssn)){
                        $this->errorNumber = self::WRONG_SSN_FORMAT;
                        return false;
                    }
                }
                
                $testWithLuhn = $this->TestWithLuhn($ssn);    // Testa numret med luhns algoritm
                
                if ($testWithLuhn == false) {
                    $this->errorNumber = self::WRONG_SSN_FORMAT;
                    return false;
                }
                
                return true;
            }
            
            public function TestWithLuhn($number) {
                
                $number=preg_replace('/\D/', '', $number);    // ta bort eventuella bindestreck

                $nr_length = strlen($number);       // längden på strängen
                $parity = $nr_length % 2;           // pariteten
                
                $sum = 0;
                
                // Loopa igenom samtliga siffror och räkna ut den korrekta summan
                for ($i = 0; $i < $nr_length; $i++){
                    $digit = $number[$i];
                    
                    // Multiplicera varannat tal med 2
                    if ($i % 2 == $parity) {
                        $digit*=2;
                        
                        // Om talet har två siffror lägg ihop dem
                        if ($digit > 9) {
                            $digit-=9;
                        }
                    }
                    
                    $sum+=$digit;
                }
                
                // Om summan är delbar med 10 är allt okej och true returneras
                if ($sum % 10 == 0) {
                    return true;
                } else {
                    return false;
                }
            }
        
            public function ValidateDate($date) {
                 if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date) || preg_match("/^[0-9]{2}-[0-9]{2}-[0-9]{2}$/", $date) || preg_match("/^[0-9]{6}$/", $date)) {
                                    $date = str_replace('-', '', $date);
                                    if (strlen($date) > 6) {
                                            $date = substr($date, -6);
                                    }
                                    return $date;
                            }
                            else {
                                $this->errorNumber = self::WRONG_DATE_FORMAT;
                                return false;
                            }
            
            }
            
            // Tar bort samtliga taggar förutom undantag i parameter två.
            public function RemoveScripts($string, $allow) { // ('text', '<tag1><tag2>')
                return strip_tags($string, $allow);

            }
            
            // Tar bort samtliga taggar
            public function RemoveHtmlAndScripts($string) {
                return strip_tags($string);
            }
    }


    function Test() {
            $valid = Validator::GetInstance();
            
            // korrekt epost
            if ($valid->ValidateEmail('test@test.se') == true) { 
            	echo 'OK. Korrekt epost <br />';
            }
            // fel epost
            if ($valid->ValidateEmail('test_test.se') == false) {
            	echo 'OK. Felaktig epost <br />';
            }

            // korrekt username
            if ($valid->ValidateUsername('Username222') == true) {
            	echo 'OK. Korrekt username <br />';
            }
            // fel username, för kort
            if ($valid->ValidateUsername('Um22') == false) {
            	echo 'OK. Felaktigt username <br />';
            }
            
            //korrekt datum
            if($valid->ValidateDate('1999-10-01') == true) {
                echo 'OK. Korrekt datum <br />';
            }
            //korrekt datum
            if($valid->ValidateDate('99-10-01') == true) {
                echo 'OK. Korrekt datum <br />';
            }
             //korrekt datum
            if($valid->ValidateDate('991001') == true) {
                echo 'OK. Korrekt datum <br />';
            }
            
            //felaktigt datum
            if($valid->ValidateDate('testing') == false) {
                echo 'OK.  Felaktigt datum <br />';
            }
            
            // RemoveScripts
            if (strpos($check = $valid->RemoveScripts('<h1>Heeeej</h1><h2>Och hej!</h2>', '<h1>'),'<h2>') === false) {
            	echo ('OK. Korrekt tagg borttagen.<br />');
            }

            // RemoveHtmlAndScripts
            if (strpos($valid->RemoveHtmlAndScripts('<h1>Hej</h1>') ,'<h1>') === false) {
            	echo 'OK. Tagg borttagen.';
            }
    }

   // Test();
