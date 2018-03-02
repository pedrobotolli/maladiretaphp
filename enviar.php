<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'vendor/autoload.php';

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$emaild = $_POST['emaild'];
$nomed = $_POST['nomed'];
$assunto = $_POST['assunto'];
$mensagem = $_POST['mensagem'];
$nomeNoCorpo = false;
$provedores = ["gmail","hotmail","bol"];
$servidoresSMTP = ['tls://smtp.gmail.com','tls://smtp.live.com','tls://smtps.bol.com.br'];
$provedor = explode("@",$email);
$provedor = explode(".",$provedor[1]);
$provedor = $provedor[0];
$iProvedor = -1;
$provedorValido = false;

echo $provedor;

for($i=0 ; $i < count($provedores); $i++){
    if($provedor == $provedores[$i]){
        $iProvedor = $i;
        $provedorValido = true;
        break;
    }
}

$nomesd = explode(", ",$nomed);
$emailsd = explode(", ",$emaild);

if((count($nomesd)==count($emailsd)) && $provedorValido){
    for ($i=0 ; $i < count($nomesd); $i++){

        if($pos = strpos($mensagem, "@@nome")){
            $mensagemAlt = str_replace("@@nome",$nomesd[$i],$mensagem);
            $nomeNoCorpo = true;
        }

        echo "se voce digitou tudo certo e mesmo assim nao enviou verifique se nao e a google bloqueando: https://security.google.com/settings/security/activity?hl=en&pli=1";

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 1;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'tls://smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $email;                 // SMTP username
            $mail->Password = $senha;                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($email, $nome);
            $mail->addAddress($emailsd[$i], $nomesd[$i]);     // Add a recipient
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $assunto;
            if($nomeNoCorpo){
                $mail->Body    = utf8_encode($mensagemAlt);
                $mail->AltBody = utf8_encode($mensagemAlt);
            }else{
                $mail->Body    = utf8_encode($mensagem);
                $mail->AltBody = utf8_encode($mensagem);
            }    
            $mail->send();
            echo $mensagem;
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}else{
    if($provedorValido){ 
        echo "o numero de emails nao coincide com o de nomes, verifique e tente novamente";
    }else{
        echo "provedor de email invalido";
    }
}
?>