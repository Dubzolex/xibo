<aside class="fx-col scroll-y py-20 gap-20">
    <div class="fx-col gap-20 ai-center">
        <h3>Screen</h3>
        <a class="link" href="https://forms.office.com/Pages/ResponsePage.aspx?id=CDbAgGRfu0CccJOUq-YBHPcNcvYrXmxAjd-Pb3F9CwhUQ0IwT0E1TFFHTkRLSUY2TzlZTDMyREhGTS4u&r26868e63daf642288200ae1c132507da=%22Affichage%20Dynamique%22&r508386bf771f4868a87c7d26a929eaf5=%22Controller%20un%20%C3%A9cran%22">add</a>
    </div>
    
    <div class="fx-col grow gap-50 jc-evenly py-20">
        <?php if (!empty($data)): ?>

            <?php foreach ($data as $e): ?>
                <button id="<?= $e['id'] ?>" class="link">
                    <?= htmlspecialchars($e['label']) ?>
                </button>
            <?php endforeach; ?>

        <?php else: ?>

            <div class="fx-col ai-center">
                <em>No screen available.</em>
            </div>
            
        <?php endif; ?>
    </div>
</aside>

<main class="fx-col grow scroll-y gap-50 p-20" id="main"></main>