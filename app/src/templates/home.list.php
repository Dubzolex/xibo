


<aside class="fx-col scroll-y py-20 gap-20">
    <div class="fx-col gap-20 ai-center">
        <h3>Screen</h3>
        <a class="link" href="https://forms.office.com/Pages/ResponsePage.aspx?id=CDbAgGRfu0CccJOUq-YBHPcNcvYrXmxAjd-Pb3F9CwhUQ0IwT0E1TFFHTkRLSUY2TzlZTDMyREhGTS4u&r26868e63daf642288200ae1c132507da=%22Affichage%20Dynamique%22&r508386bf771f4868a87c7d26a929eaf5=%22Controller%20un%20%C3%A9cran%22" _blanck>add</a>
    </div>
    <div id="list-screen" class="fx-col grow gap-50 jc-evenly py-20">
        <div class="fx-col ai-center"><em>No screen available.</em></div>
    </div>
</aside>
<main class="fx-col grow scroll-y gap-40 p-20" id="main">
    <div class="fx-row jc-center"></div>
    <div class="fx-row jc-center grow">
        <div id="list" class="fx-row w-1200 jc-evenly gap-80 wrap px-40"> 
    </div>
</main>


























































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
                                <?php 
                                    $url = "../images/{$e['id']}/$m";
                                    if ($this->isImage($m)): 
                                ?>
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