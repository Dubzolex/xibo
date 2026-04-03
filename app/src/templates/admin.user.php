<div class="fx-row jc-center px-20">
    <div class="fx-row container w-600 p-20 jc-between tools gap-20 ai-center">
        <div class="fx-row gap-20 wrap">
            <input id="email" type="email" placeholder="email">
        </div>
        <button class="action bg-green" id="add-user">Add</button>
    </div>
</div>
<div class="fx-row jc-center grow">
    <div class="fx-col w-1200">
         <table>
            <thead>
                <th style="width: 15%;">Name</th>
                <th style="width: 25%;">Email</th>
                <th style="width: 12%;">Role</th>
                <th style="width: 20%;">Updated</th>
                <?php if($role >= 3): ?>
                    <th style="width: 12%;">Password</th>
                    <th style="width: 20%;">Changed</th>
                <?php endif; ?>
            </thead>
            <tbody>
                <?php foreach ($data as $u): ?>
                    <tr>
                        <?php if($role == 2): ?>
                            <td><?= $u['name'] ?></td>
                            <td><?= $u['email'] ?></td>
                            <td><?= $u['role'] ?></td>
                        <?php endif; ?>

                        <?php if($role == 3): ?>
                            <td ondblclick=makeEdit('user', this, <?= $u['id'] ?>, 'email')><?= $u['name'] ?></td>
                            <td ondblclick=makeEdit('user', this, <?= $u['id'] ?>, 'email')><?= $u['email'] ?></td>
                            <td>
                                <select onchange="makeSelect('user', this, <?= $u['id'] ?>, 'role')" style="width: 80px;">
                                    <option value="1" <?= $u['role_id'] == 1 ? 'selected' : '' ?> >Viewer</option>
                                    <option value="2" <?= $u['role_id'] == 2 ? 'selected' : '' ?> >Editor</option>
                                    <option value="3" <?= $u['role_id'] == 3 ? 'selected' : '' ?> >Manager</option>
                                    <option value="4" <?= $u['role_id'] == 4 ? 'selected' : '' ?> >Admin</option>
                                </select>
                                
                            </td>
                            <td><?= $u['updated_at'] ?? "-" ?></td>
                                <td>
                                    <button class="action bg-red" onclick="resetU(<?= $u['id'] ?>)">Reset</button>
                                </td>
                            <td><?= $u['updated_at'] ?? "-" ?></td>
                        <?php endif; ?>
                    </tr>
                         
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>