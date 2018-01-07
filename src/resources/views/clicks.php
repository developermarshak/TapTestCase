<html>
<head>
    <title>Clicks</title>
    <script>
        var data = <?php echo json_encode($data); ?>
    </script>
    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
    <link href="https://cdn.fancygrid.com/fancy.min.css" rel="stylesheet">
    <script src="https://cdn.fancygrid.com/fancy.min.js"></script>
    <script src="/js/main.js"></script>
</head>
<body>
<div id="table">

</div>

<div>
    <h2>Example links:</h2>
    <ul>
        <li><a href="/click/?param1=123">/click/?param1=123</a></li>
        <li><a href="/click/?param1=asd">/click/?param1=asd</a></li>
        <li><a href="/click/?param1=1234">/click/?param1=1234</a></li>
        <li><a href="/click/?param1=4321">/click/?param1=4321</a></li>
    </ul>
</div>
</body>
</html>