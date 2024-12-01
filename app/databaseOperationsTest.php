function doesUuidExist($uuid, $databaseConnector)
{
    $pdo = $databaseConnector->getConnection();

    $sql = 'SELECT COUNT(*) as count FROM opendata WHERE id = :uuid;';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['count'] > 0;
}
