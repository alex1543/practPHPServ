<?php

$socket = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr);

if (!$socket) {
    die("$errstr ($errno)\n");
}

while ($connect = stream_socket_accept($socket, -1)) {
    fwrite($connect, "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\nConnection: close\r\n\r\n");
	
	$text = file_get_contents("select.html");
	$list = explode("\n", $text);
	//echo $text;

	$i=1;
	while ($i < Count($list)) {
		echo $list[$i];
		
		if (strpos($list[$i], "@tr") !== false) {
try {
	$pdoSet = new PDO('mysql:host=localhost', 'root', '');
	$pdoSet->query('SET NAMES utf8;');
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}			
			$stmt=$pdoSet->query($sql);
			$resultMF = $stmt->fetchAll();
for ($iRow = 0; $iRow < Count($resultMF); ++$iRow) {

}
		}
		
		fwrite($connect, $list[$i]);
		
		$i++;
	}
	
	
    fclose($connect);
}

fclose($socket);