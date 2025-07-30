<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once APPPATH . 'vendor/autoload.php';

class MY_Email extends CI_Email
{

	public $email = '';
	public $from = 'support@grahaone.com';
	public $from_name = 'Grahaone';

	public function __construct()
	{

		// Instantiation and passing `true` enables exceptions
		$this->mail = new PHPMailer(true);

		//Server settings
		$this->mail->SMTPDebug = 0;                                       // Enable verbose debug output
		$this->mail->isSMTP();                                            // Set mailer to use SMTP
		// $this->mail->Host       = 'mail.cahayagemilangmakmur.com';  // Specify main and backup SMTP servers
		$this->mail->Host       = 'smtp.hostinger.com';  // Specify main and backup SMTP servers
		$this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		// $this->mail->Username   = 'support@cahayagemilangmakmur.com';                     // SMTP username
		$this->mail->Username   = 'info@wirawan.web.id';                     // SMTP username
		// $this->mail->Password   = 'V!e,eBgODyvy';                               // SMTP password
		$this->mail->Password   = '7^FxlNSn';                               // SMTP password
		$this->mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
		$this->mail->Port       = 465;                                    // TCP port to connect to

		$this->from($this->from, $this->from_name);
		/*try {
		    //Server settings
		    $mail->SMTPDebug = 2;                                       // Enable verbose debug output
		    $mail->isSMTP();                                            // Set mailer to use SMTP
		    $mail->Host       = 'mail.asialabel.id';  // Specify main and backup SMTP servers
		    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		    $mail->Username   = 'hello@nestque.com';                     // SMTP username
		    $mail->Password   = 'label2019';                               // SMTP password
		    $mail->SMTPSecure = 'SSL';                                  // Enable TLS encryption, `ssl` also accepted
		    $mail->Port       = 587;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom('hello@nestque.com', 'Mailer');
		    $mail->addAddress('ccp_04@yahoo.com', 'Asefur Mukti');     // Add a recipient
		    // $mail->addAddress('hello@nestque.com');               // Name is optional
		    $mail->addReplyTo('hello@nestque.com', 'Information');
		    // $mail->addCC('cc@example.com');
		    // $mail->addBCC('bcc@example.com');

		    // Attachments
		    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		    // Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = 'Here is the subject '.date('H:i:s');
		    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		    $mail->send();
		    echo 'Message has been sent';
		} catch (Exception $e) {
		    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}*/
	}

	public function from($from, $name = '', $return_path = NULL)
	{
		$this->from = $from;
		$this->from_name = $name;
	}

	public function to($email, $name = '')
	{
		if (is_array($email)) {
			foreach ($email as $mail => $name) {
				$this->mail->addAddress($mail, $name);
			}
		} else {
			$this->mail->addAddress($email, $name);     // Add a recipient
		}
	}

	public function subject($subject)
	{
		$this->mail->Subject = $subject;
	}

	public function message($message)
	{
		$this->mail->Body = $message;
	}

	public function send($auto_clear = true)
	{

		//Recipients
		$this->mail->setFrom($this->from, $this->from_name);
		$this->mail->addReplyTo($this->from, $this->from_name);

		// Content
		$this->mail->isHTML(true);                                  // Set email format to HTML
		// $this->mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		// $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$this->mail->send();
	}
}
