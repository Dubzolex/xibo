<?php if($role > 1): ?>
<aside class="fx-col scroll-y py-20 gap-20">
    <div class="fx-col gap-20 ai-center">
        <h3>Settings</h3>
    </div>
    <div class="fx-col grow gap-50 jc-evenly py-20">

        <?php if($role >= 2): ?>
            <button id="user" class="link" >Users</button>
        <?php endif; ?>

        <?php if($role >= 2): ?>
            <button id="screen" class="link" >Screens</button>
        <?php endif; ?>

        <?php if($role >= 2): ?>
            <button id="permission" class="link" >Permissions</button>
        <?php endif; ?>

        <?php if($role >= 2): ?>
            <button id="session" class="link" >Sessions</button>
        <?php endif; ?>

    </div>
</aside>
<div id="main" class="fx-col grow scroll-y gap-20 p-20"></div>
<?php endif; ?>