<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Editor Grid Example</title>

    <!-- ** CSS ** -->
    <!-- base library -->
    <link rel="stylesheet" type="text/css" href="../css/ext-all.css" />

    <!-- overrides to base library -->

    <!-- page specific -->
    <link rel="stylesheet" type="text/css" href="../css/ex.css" />
    <link rel="stylesheet" type="text/css" href="../css/grid.css" />

    <style type="text/css">

    </style>

    <!-- ** Javascript ** -->
    <!-- ExtJS library: base/adapter -->
    <script type="text/javascript" src="../js/ext-base.js"></script>

    <!-- ExtJS library: all widgets -->
    <script type="text/javascript" src="../js/ext-all.js"></script>

    <!-- overrides to base library -->

    <!-- extensions -->
    <script type="text/javascript" src="../js/CheckColumn.js"></script>

    <!-- page specific -->
    <script type="text/javascript" src="../js/examples.js"></script>
    <script type="text/javascript" src="../js/edit-grid.js"></script>

</head>
<body>

    
    <!-- the custom editor for the 'Light' column references the id="light" -->
    <select name="light" id="light" style="display: none;">
    	<option value="Shade">Shade</option>
    	<option value="Mostly Shady">Mostly Shady</option>
    	<option value="Sun or Shade">Sun or Shade</option>
    	<option value="Mostly Sunny">Mostly Sunny</option>
    	<option value="Sunny">Sunny</option>
    </select>
    
    <div id="editor-grid"></div>
</body>
</html>