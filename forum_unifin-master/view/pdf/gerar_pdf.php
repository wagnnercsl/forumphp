<?php

require_once "./../login/session.php";
require_once "./../../database/Database.php";
require_once "./../../model/Tema.php";
require_once "./../../model/Comentario.php";
require_once "./../../model/Pdf.php";
require_once "./../../assets/geradorpdf/pdf_mc_table.php";

if (isset($_GET['temaComentario']))
{
    $pdf = new Pdf();
    $pdf->geraPdfComentarios($_GET['temaComentario']);
}
else if (isset($_GET['tema']))
{
    $pdf = new Pdf();
    $pdf->geraPdfTemas();
}
else
{
    header("Location: ./../forum/home.php?page=1");
}

