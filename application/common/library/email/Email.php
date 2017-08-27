<?php
namespace app\common\library\email;

use Closure;


class Email extends \PHPMailer
{


    public function __construct()
    {
        $comfig = config('email');
        $this->isSMTP();                                      // Set mailer to use SMTP
        $this->Host = $comfig['smtp_host'];  // Specify main and backup SMTP servers
        $this->SMTPAuth = true;                               // Enable SMTP authentication
        $this->Username = $comfig['smtp_user'];                 // SMTP username
        $this->Password = $comfig['smtp_pass'];                           // SMTP password
        //$this->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $this->Port = $comfig['smtp_port'];
        $this->setFrom($this->Username, $comfig['sender_name']);
    }

}