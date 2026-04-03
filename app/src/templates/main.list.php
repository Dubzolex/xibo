<?php if (isset($data)): ?>

    <?php foreach ($data as $m): ?>
        <div class="fx-col ai-center gap-10">
            <?php $url = "../images/$screenId/$m";?>

            <?php if ($this->isImage($m)): ?>
                <img src="<?= $url ?>" alt="<?= $m ?>">

            <?php elseif ($this->isVideo($m)): ?>
                <video autoplay muted playsinline>
                    <source src="<?= $url ?>">
                </video>

            <?php endif; ?>
            <div class="fx-row ai-center gap-10">
                <input id ="<?= $m ?>" type="checkbox">
                <p><?= $m ?></p>
            </div>
        </div>
    <?php endforeach; ?>

<?php else: ?>
        <div class="fx-center">
            <em>No images found...</em>
        </div>`

<?php endif; ?>