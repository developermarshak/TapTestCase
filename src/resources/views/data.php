<table>
    <?php foreach ($data as $name => $val){ ?>
        <tr>
            <td><?php echo htmlspecialchars($name); ?>:</td>
            <td><?php echo htmlspecialchars($val); ?></td>
        </tr>
    <?php } ?>
</table>