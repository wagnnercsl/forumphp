<?php
	require_once './../login/session.php';

	$admin = $_SESSION['administrador'];

	$path = "./../usuario/alterar.php?login=" . $_SESSION['usuario'];

	date_default_timezone_set('America/Sao_Paulo');

?>
<!DOCTYPE html>
<html lang="pt-br">

	<head>

		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">

		<link href="./../../assets/css/bootstrap.css" rel="stylesheet" media="all">
		<link href="./../../assets/css/all.css" rel="stylesheet" media="all">
		<link href="./../../assets/css/cabecalho_geral.css" rel="stylesheet" media="all">
		<link href="./../../assets/css/paginacao.css" rel="stylesheet" media="all">

		<title>Forum Unifin</title>

	</head>

	<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

			<a class="navbar-brand" href="./../forum/home.php?page=1"><img src="./../../assets/img/forum-icon.png" width="30" height="30" class="d-inline-block align-top" alt=""> Forum Unifin</a>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nbInicio">

					<span class="navbar-toggler-icon"></span>

				</button>

				<div class="collapse navbar-collapse" id="nbInicio">

					<ul class="nav navbar-nav">

						<li class="nav-item"><a class="nav-link" href="./../forum/home.php?page=1">Início</a></li>
						<li class="nav-item"><a class="nav-link" href="./../categoria/listar.php?page=1">Categorias</a></li>
						<?php if ($admin): ?>
							<li class="nav-item"><a class="nav-link" href="./../usuario/listar.php?page=1">Usuários</a></li>
						<?php endif;?>
					</ul>

					<ul class="nav navbar-nav ml-auto justify-content-end">

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Minha Conta</a>
							<div class="dropdown-menu perfil" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href=<?= $path ?>>Editar Perfil</a>
								<a class="dropdown-item" href="./../login/logout.php">Sair</a>
							</div>
						</li>

					</ul>

				</div>
		</nav>

