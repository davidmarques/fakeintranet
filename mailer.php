<?php
$current_time = time();
$date=date('Y-m-d H:i:s', $current_time);


function custom_mailer($to_address,$from_address,$bcc,$subject,$message){
	$headers = "From: $from_address\r\nX-Mailer: php\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	if($bcc!=""){
		$headers .= "Bcc: $bcc\r\n";
	}
	$returnst=false;


	if(mail("$to_address", "$subject", "$message", "$headers")){
		$returnst=true;
	}
	return($returnst);

}


$toreturn=Array();
$success=false;
$tag="";
if(isset($_GET['tag'])){

	$tag=$_GET['tag'];

}


if(isset($_GET['action'])){

	$action=$_GET['action'];

	switch($action){
		case "contact":
		if(sendcontact()){
			$success=true;		
		}
		break;
		case "newsletter":
		if(sendnews()){
			$success=true;
		}
		break;
		case "track":
			custom_mailer("davidmarquesbv@gmail.com","no-reply@octocm.com","","Página visitada em ".$date,"Visitaram o site em ".$date."<br>".$tag);
		break;
		case "locale":
			custom_mailer("davidmarquesbv@gmail.com","no-reply@octocm.com","","Página visitada em ".$date,"Local Obtido em ".$date."<br><a href='https://maps.google.com/maps?q=loc:".$tag."'>https://maps.google.com/maps?q=loc:".$tag."</a>");
		break;
	}
	$toreturn['success']=$success;
}
if($action!="track" && $action!=""){
echo json_encode($toreturn);
}




function sendcontact(){
	if( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message']) && $_POST['name'] != "" && $_POST['email'] != "" && $_POST['subject'] != "" && $_POST['message'] != ""){
		$message="Contato enviado pelo site<br> Nome: ".$_POST['name']."<br> Email: ".$_POST['email']."<br> Mensagem:<hr>".$_POST['message'];

		if(custom_mailer("davidmarquesbv@gmail.com","no-reply@segproptect.com.br","","Contato pelo site - ".$_POST['subject'],$message)){
			custom_mailer($_POST['email'],"no-reply@segproptect.com.br","","Obrigado por entrar em contato 😃","<h1>Obrigado, ".$_POST['name']."! </h1>Você é importante para nós. Entraremos em contato assim que possível.");
			return(true);
		}else{
			return(false);
		}
	}else{
		return(false);
	}
}



function sendnews(){
	if( isset($_POST['email']) && $_POST['email']!="" ){

		if(custom_mailer("davidmarquesbv@gmail.com","no-reply@segproptect.com.br","","Newsletter preenchido - ".$_POST['email'],"Assinou newsletter")){
			custom_mailer($_POST['email'],"no-reply@segproptect.com.br","","Newsletter SegProtect 😃","<h1>Obrigado!</h1>Você agora está incluído em nossa newsletter.");
			return(true);
		}else{
			return(false);
		}
	}else{
		return(false);	
	}
}
