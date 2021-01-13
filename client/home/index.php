<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../login/');
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link href="../../server/css/pages/home.css" rel="stylesheet" type="text/css"/>
        <link href="../../server/assets/icon.png" rel="icon" type="image/png"/>
        <title>
            Home
        </title>
    </head>
</html>
<body>
	<script type="text/javascript" src="../../server/js/test.js"></script>
    <header>
    	<h1>
    		Home Page
    	</h1>
    	<p>
    		You will do tests in this page
    	</p>
    </header>
    <main>
    	<p>
    		LOL
    	</p>
    </main>
    <footer>
    	LOL
    </footer>
</body>
