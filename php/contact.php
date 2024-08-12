<?php

$array = array("firstname" => "", "name" => "", "email" => "", "phone" => "", "subject" => "", "message" => "", "firstnameError" => "", "nameError" => "", "emailError" => "", "phoneError" => "", "subjectError" => "", "messageError" => "", "isSuccess" => false);
$emailTo = "fabienne_berges@yahoo.fr";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $array["firstname"] = test_input($_POST["firstname"]);
    $array["name"] = test_input($_POST["name"]);
    $array["email"] = test_input($_POST["email"]);
    $array["phone"] = test_input($_POST["phone"]);
    $array["subject"] = test_input($_POST["subject"]);
    $array["message"] = test_input($_POST["message"]);
    $array["isSuccess"] = true;
    $emailText = "";

    if (empty($array["firstname"])) {
        $array["firstnameError"] = "Merci de m'indiquer votre prénom";
        $array["isSuccess"] = false;
    } else {
        $emailText .= "Firstname: {$array['firstname']}\n";
    }

    if (empty($array["name"])) {
        $array["nameError"] = "Votre nom est aussi nécessaire";
        $array["isSuccess"] = false;
    } else {
        $emailText .= "Name: {$array['name']}\n";
    }

    if (!isEmail($array["email"])) {
        $array["emailError"] = "Votre email n'est pas valide";
        $array["isSuccess"] = false;
    } else {
        $emailText .= "Email: {$array['email']}\n";
    }

    if (!isPhone($array["phone"])) {
        $array["phoneError"] = "Seuls les chiffres sont utilisables.";
        $array["isSuccess"] = false;
    } else {
        $emailText .= "Phone: {$array['phone']}\n";
    }
    if (empty($array["subject"])) {
        $array["subjectError"] = "Désolé, vous avez oublié d'indiquer le sujet de votre message";
        $array["isSuccess"] = false;
    }

    if (empty($array["message"])) {
        $array["messageError"] = "Vous avez oublié d'écrire votre message";
        $array["isSuccess"] = false;
    } else {
        $emailText .= "Message: {$array['message']}\n";
    }

    if ($array["isSuccess"]) {
        $headers = "From: {$array['firstname']} {$array['name']} <{$array['email']}>\r\nReply-To: {$array['email']}";
        mail($emailTo, $array["subject"], $emailText, $headers);
    }

    echo json_encode($array);
}

function isEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function isPhone($phone) {
    return preg_match("/^[0-9 ]*$/", $phone);
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>