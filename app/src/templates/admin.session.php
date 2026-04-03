<table>
    <thead>
        <th style="width: 25%;">Name</th>
        <th style="width: 25%;">Email</th>
        <th style="width: 15%;">Connected</th>
        <th style="width: 15%;">Expires</th>
        <th style="width: 10%;">Token</th>
    </thead>
    <tbody>
        <?php foreach ($data as $c): ?>
            <tr>
                <td><?= $c['name'] ?? "-" ?></td>
                <td><?= $c['email'] ?></td>
                <td class="scroll-x" style="width: 120px;"><?= $c['connected_at'] ?></td>
                <td class="scroll-x" style="width: 120px;"><?= $c['expires_at'] ?></td>
                <td class="scroll-x" style="max-width: 160px;"><?= $c['token'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


