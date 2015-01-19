<?php require('php/functions.php'); ?>

<html>

<head>
    <link rel="stylesheet" href="style/main.css">
    <script src="script/jquery-1.11.2.js"></script>
    <script src="script/functions.js"></script>
    
</head>

<body>
    
    <h1>Autosuggest</h1>
    
    <input type="text" name="autosuggest" id="autosuggest" onkeyup="autoSuggest()" />
    
    <div id="autosuggest-container"></div>
    
</body>

</html>