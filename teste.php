<?php

	/**
	 * Exemplo de envio de dados por POST atraves da funcao
	 * file_get_contents()
	 *
	 * Angelito M. Goulart
	 *
	 * www.angelitomg.com
	 */

	$dados = http_build_query(array(
		'firstname' => 'John',
		'lastname' => 'Doe'
	));

	$contexto = stream_context_create(array(
	    'http' => array(
	        'method' => 'POST',
	        'content' => $dados,
	        'header' => "Content-type: application/x-www-form-urlencoded\r\n"
	        . "Content-Length: " . strlen($dados) . "\r\n",
	    )
	));

	// ALTERE A URL
	$resposta = file_get_contents('http://localhost/~Tomas/Bookstore/url.php', null, $contexto);

	print_r($resposta);

?>
