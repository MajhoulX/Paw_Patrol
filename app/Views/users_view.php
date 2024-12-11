<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animals List</title>
</head>
<body>
    <h1>Animals List</h1>
    <?php if (empty($animals)): ?>
        <p>No animals found.</p>
    <?php else: ?>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Species</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Picture</th> <!-- Added picture column -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animals as $animal): ?>
                    <tr>
                        <td><?= esc($animal['id']) ?></td>
                        <td><?= esc($animal['species']) ?></td>
                        <td><?= esc($animal['age']) ?></td>
                        <td><?= esc($animal['sex']) ?></td>
                        <td><?= esc($animal['location']) ?></td>
                        <td><?= esc($animal['description']) ?></td>
                        <td>
                            <?php if (isset($animal['picture'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($animal['picture']) ?>" alt="Animal Picture" width="100" />
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
