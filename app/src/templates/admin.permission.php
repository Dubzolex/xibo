<div class="fx-row jc-center px-20">
    <div class="fx-row container w-600 px-20 py-20 jc-between gap-20 wrap">
        <div class="fx-row gap-20 wrap jc-between">

            <select id="select-user">
                <option value="">User</option>
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u['id'] ?>"><?= $u['email'] ?></option>
                <?php endforeach; ?>  
            </select>

            <select id="select-screen">
                <option value="">Screen</option>
                <?php foreach ($screens as $u): ?>
                    <option value="<?= $u['id'] ?>"><?= $u['label'] ?></option>
                <?php endforeach; ?>
            </select>

        </div>
        <button class="action bg-green" onclick=addP()>Add</button>
    </div>
</div>
<div class="fx-row jc-center">
    <div class="fx-col  w-1200">
        <table>
            <thead>
                <th style="width: 35%;">User</th>
                <th style="width: 35%;">Screen</th>
                <th style="width: 20%;">Manage</th>
            </thead>
            <tbody>
                <?php foreach ($data as $p): ?>
                        <tr>
                        <td><?= $p['email'] ?></td>
                        <td><?= $p['label'] ?></td>
                        <td>
                            <button class="action bg-red" onclick=deleteP(<?= $p['id'] ?>)>Delete</button>
                        </td>
                <?php endforeach; ?>  
            </tbody>
        </table>
    </div>
</div>