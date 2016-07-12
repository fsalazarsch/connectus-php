<?php
/* Set e-mail recipient */
$myemail = "felipe@connectus.cl";

/* Check all form inputs using check_input function */
$name = check_input($_POST['inputName'], "Your Name");
$email = check_input($_POST['inputEmail'], "Your E-mail Address");
$phone = check_input($_POST['inputPhone'], "Your Phone Number");
$message = check_input($_POST['inputMessage'], "Your Message");

/* If e-mail is not valid show error message */
if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email))
{
show_error("Correo electrónico no válido");
}
/* Let's prepare the message for the e-mail */

$subject = "Contacto web Connectus";

$message = "

Alguien te ha enviado un mensaje usando tu formulario de contacto:

Nombre: $name
Correo electronico: $email
Teléfono: $phone

Mensaje:
$message

";

/* Send the message using mail() function */
mail($myemail, $subject, $message);

/* Redirect visitor to the thank you page */
header('Location: http://www.connectus.cl/confirmacion.htm');
exit();

/* Functions we used */
function check_input($data, $problem='')
{
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
if ($problem && strlen($data) == 0)
{
show_error($problem);
}
return $data;
}

function show_error($myError)
{
?>
<html>
<body>

<p>Por favor corrige el siguiente error:</p>
<strong><?php echo $myError; ?></strong>
<p>Vuelve atrás con el botón del navegador y llena nuevamente el formulario</p>

</body>
</html>
<?php
exit();
}
?>