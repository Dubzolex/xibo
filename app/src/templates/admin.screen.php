<table>
    <thead>
        <th style="width: 20%;">Label</th>
        <th style="width: 20%;">Description</th>
        <th style="width: 20%;">Format</th>
        <?php if($role >= 3): ?>
            <th style="width: 10%;">Visible</th>
            <th style="width: 10%;">Running</th>
            <th style="width: 10%;">Updating</th>
        <?php endif; ?>
    </thead>
    <tbody class="scroll-y">
        <?php foreach ($data as $e): ?>
            <?php 
                $visible = $e['is_visible'] == 1 ? 'checked' : '';
                $running = $e['is_running'] == 1 ? 'checked' : '';
                $update = $e['is_updating'] == 1 ? 'checked' : '';
            ?>
            <tr>
                <td ondblclick="makeEdit('screen', this, <?= $e['id'] ?>, 'label')"><?= $e['label'] ?></td>
                <td ondblclick="makeEdit('screen', this, <?= $e['id'] ?>, 'description')"><?= $e['description'] ?? "" ?></td>
                <td ondblclick="makeEdit('screen', this, <?= $e['id'] ?>, 'format')"><?= $e['format'] ?? "" ?></td>

                <?php if($role >= 3): ?>
                
                    <td>
                        <label class="switch">
                            <input type="checkbox" <?= $visible ?>
                                onchange="makeToggle('screen', this, <?= $e['id'] ?>, 'visible')">
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" <?= $running ?>
                                onchange="makeToggle('screen', this, <?= $e['id'] ?>, 'running')">
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" <?= $update ?>
                                onchange="makeToggle('screen', this, <?= $e['id'] ?>, 'updating')">
                            <span class="slider round"></span>
                        </label>
                    </td>

                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>