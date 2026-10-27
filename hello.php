<?php
    $message = 'PHP pada Apache Laragon berhasil berjalan.';
?>

<!doctype html>
<html lang="id">
    <head><meta charset="utf-8">
        <title>Tes Laragon</title>
    </head>
    <body>
        <h1>Hello World Laragon</h1>
        <p><?= htmlspecialchars($message) ?></p>
    </body>
</html>