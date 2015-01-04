<?php
    require 'PhpGrowler.php';
    require 'GrowlConnection.php';

    use Khill\PhpGrowler\PhpGrowler;
    use Khill\PhpGrowler\GrowlConnection;

    $conn = new GrowlConnection('Test App', '192.168.0.50');
    $conn->addNotification(['success', 'info', 'warning', 'danger'])
         ->register();

    $growl = new PhpGrowler($conn);
?>

<!doctype html>
<html>
<head></head>
<body>
    <form action="index.php" method="GET">
        <table>
            <tr>
                <td>Title:</td>
                <td><input type="text" name="title" value="<?php echo $_GET['title']; ?>"></td>
            </tr>
            <tr>
                <td>Message:</td>
                <td><input type="text" name="message" value="<?php echo $_GET['message']; ?>"></td>
            </tr>
            <tr>
                <td>Type:</td>
                <td>
                    <select name="type">
                        <?php foreach ($conn->getNotifications() as $type) {
                            echo sprintf('<option value="%s">%s</option>', $type['name'], $type['name']);
                        } ?>
                    </select>
                </td>
            </tr>
        </table>
        <button type="submit">Send</button>
    </form>
<?php
    $growl->notify(
        $_GET['type'],
        '['.strtoupper($_GET['type']).'] '.$_GET['title'],
        $_GET['message']
    );
?>
</body>
</html>