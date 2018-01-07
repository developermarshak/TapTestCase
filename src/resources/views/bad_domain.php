<html>
<head>
    <title>Bad domains</title>
    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
    <script src="/js/bad_domain.js"></script>
</head>
<body>
<h1 style="text-align: center;">Bad domains</h1>
<table>
    <thead>
        <tr>
            <td>
                ID
            </td>
            <td>
                Domain
            </td>
        </tr>
    </thead>
    <tbody>
<?php foreach ($data as $row){ ?>
    <tr data-id="<?php echo $row['id'] ?>">
        <td>
            <?php echo $row['id'] ?>
        </td>
        <td>
            <?php echo $row['name'] ?>
        </td>
        <td>
            <button type="button" class="delete-btn">Delete</button>
        </td>
    </tr>
<?php } ?>
    </tbody>
</table>
<br>
<form id="formAddDomain">
    <label for="domain">Domain:</label>
    <input id="domain" name="domain"> <br/>
    <input type="submit" value="Send">
</form>
</body>
</html>