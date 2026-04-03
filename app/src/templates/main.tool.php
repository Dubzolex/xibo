<div class="fx-row jc-center">
    <div class="fx-col w-600 gap-10 container mx-20" style="padding: 20px 20px 10px 20px;">
        
        <div class="fx-row gap-10 jc-between wrap">
            <div class="fx-row ai-center gap-10 wrap">
                <input type="file" id="file" name="file[]" accept=".png, .jpg, .jpeg, .mp4" multiple>
                <button onclick=upload() class="action bg-green">Upload</button>
            </div>
            <button onclick=delete() class="action bg-red">Delete</button>
        </div>

        <div class="fx-col ai-center" style="font-size: 10px">Taille max : 2 mo</div>

        <div class="fx-row jc-center">
            <div id="status"></div>
        </div>

    </div>
</div>

<div class="fx-row jc-center grow">
    <div id="list" class="fx-row w-1200 jc-evenly gap-80 wrap px-40"> 
</div>