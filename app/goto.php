<?php
//if session does not exist and cookie does, copy cookie to session
if (!isset($_SESSION['currentDataUuid']) && isset($_COOKIE['persistent_uuid'])) {
    $_SESSION['currentDataUuid'] = $_COOKIE['persistent_uuid'];
}

/// if session uuid exists
if (isset($_SESSION['currentDataUuid']) && !empty($_SESSION['currentDataUuid'])) {
    $uuidFromSession = htmlspecialchars($_SESSION['currentDataUuid']);
?>
    <form method="post">
        <input type="text" name="uuid" value="<?php echo $uuidFromSession; ?>" size="40" readonly>
        <br>
        <input type="submit" name="goto_uuid" value="Go to My ID">
        <br>
        <br>
        <input type="submit" name="forget_uuid" value="Forget My ID">
    </form>
<?php
} else {
?>
    <form method="post">
        <input type="submit" name="generate_uuid" value="Generate UUID">
        <br>
        <br>
        <input type="text" name="uuid" size="40">
        <br>
        <input type="submit" name="goto_uuid" value="Go to Above ID">
    </form>
<?php
}
