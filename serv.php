<?php
try {
	$pdoSet = new PDO('mysql:host=localhost', 'root', '');
	$pdoSet->query('USE test;SET NAMES utf8;');
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

$socket = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr);

if (!$socket) {
    die("$errstr ($errno)\n");
}

echo "[HTTP Server waiting on port 8000] ...\n Link: http://localhost:8000/ \n";
while ($connect = stream_socket_accept($socket, -1)) {
    fwrite($connect, "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\nConnection: close\r\n\r\n");
	
	$text = file_get_contents("select.html");
	$list = explode("\n", $text);
	//echo $text;

	$i=1;
	while ($i < Count($list)) {
		echo $list[$i];
		
		if (strpos($list[$i], "@tr") !== false) {
			$tr = GetTR($pdoSet);
			echo $tr;
			fwrite($connect, $tr);
		}
		if (strpos($list[$i], "@ver") !== false) {
			$ver = GetVer($pdoSet);
			echo $ver;
			fwrite($connect, $ver);
		}
		
		if (!(strpos($list[$i], "@tr") !== false) && !(strpos($list[$i], "@ver") !== false))
			fwrite($connect, $list[$i]);
		
		$i++;
	}
	
	
    fclose($connect);
}

fclose($socket);

	
function GetVer($pdoSet) {
	$stmt = $pdoSet->query("SELECT VERSION() AS ver");
	$resultMF = $stmt->fetchAll();
	
	return $resultMF[0][0];
}
function GetTR($pdoSet) {
	$stmt = $pdoSet->query("SHOW COLUMNS FROM myarttable");
	$resultMF = $stmt->fetchAll();
	$html = "<tr>";
	for($iR=0; $iR < Count($resultMF); ++$iR) {
		$html .= "<td>".$resultMF[$iR]["Field"]."</td>";
	}
	$html .= "</tr>";
		
	$stmt=$pdoSet->query("SELECT * FROM myarttable WHERE id>14 ORDER BY id DESC");
	$resultMF = $stmt->fetchAll(PDO::FETCH_NUM);
	for ($iRow = 0; $iRow < Count($resultMF); ++$iRow) {
		$html .= "<tr>";
		for ($iCol = 0; $iCol < Count($resultMF[$iRow]); ++$iCol) {
			$html .= "<td>".$resultMF[$iRow][$iCol]."</td>";

		}
		$html .= "</tr>";
	}
	return $html;
}