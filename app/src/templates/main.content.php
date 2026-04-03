<?php foreach ($data as $e): ?>
    <?php if ($e["is_visible"] == 1): 
        $statusClass = ($e['is_running'] == 1) ? "green" : "red";
        $statusText  = ($e['is_running'] == 1) ? "ON" : "OFF";
    ?>
        <div class="fx-col gap-100 jc-between h-400">
            <div class="fx-col gap-60">
                <div class="fx-col ai-center">
                    <div class="fx-row jc-between ai-center container w-1000 p-20">
                        <div class="fx-row gap-20 ai-center">
                            <h3><?= htmlspecialchars($e['label']) ?></h3>
                        </div>
                        <div class="fx-row gap-10">
                            <div>online :</div>
                            <div class="<?= $statusClass ?>">
                                <strong><?= $statusText ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fx-row jc-evenly gap-80 wrap">
                    <?php if (isset($e['images'])): ?>

                        <?php foreach ($e['images'] as $m): ?>
                            <div class="fx-col ai-center gap-10">
                                <?php $url = "../images/{$e['id']}/$m";?>

                                <?php if ($this->isImage($m)): ?>
                                    <img src="<?= $url ?>" alt="<?= $m ?>">

                                <?php elseif ($this->isVideo($m)): ?>
                                    <video autoplay muted playsinline>
                                        <source src="<?= $url ?>">
                                    </video>

                                <?php endif; ?>
                                <div><?= htmlspecialchars($m) ?></div>
                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>

            <div class="fx-col ai-center">
                <div class="fx-row w-1000">
                    <hr>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>