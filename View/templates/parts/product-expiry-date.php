<?php
$Core = $data["Core"];
if(!empty($data["inShopsAvailabe"])){ ?>
<div>
<div class="col s12" id="expiryDate_1" style="margin-bottom:35px;">
    <div class="col m3 s12 input-field">
        <select name="expiryInShops[]" id="expiryS_1"  class="expiryInput" data-get-id="1" onChange="getExpiryVariations($(this))">
            <option disabled selected value=""><?php echo $Core->Translator->translate("Select Shop");?></option>
            <?php
            foreach($data["inShopsAvailabe"] as $shop){
            ?>
            <option value="<?php echo $shop["id"]?>"  data-icon="<?php echo $shop["logo"]?>" class="circle left"><?php echo $shop["name"];?><br/><br/><span class="grey-text">(<?php echo $shop["address"]["googleString"];?>)</span></option>   
            <?php
            }
            ?>
        </select>
        <label><?php echo $Core->Translator->translate("Shop");?></label>
    </div>
    <?php
    if(!empty($data["variationsAvailable"])){
    ?>
    <div class="col m3 s12 input-field">
        <select name="expiryVariations[]" id="expiryV_1" class="expiryInput" data-get-id="1">
            <option disabled selected><?php echo $Core->Translator->translate("Select Variation");?></option>
            <option value="0"><?php echo $Core->Translator->translate("Default");?></option>
            <?php
            foreach($data["variationsAvailable"] as $v){
            ?>
            <option value="<?php echo $v["info"]["id"]?>"><?php foreach($v["descriptions"] as $descriptions){
                    if($descriptions["default"] == 1){
                        echo $descriptions["title"];
                    }
                }?></option>
            <?php
            }
            ?>
        </select>
        <label><?php echo $Core->Translator->translate("Variations");?></label>
    </div>
    <?php
    }
    ?>
    <div class="col m3 s12 input-field">
        <input type="text" name="expiryDate[]" class="datepicker expiryInput" id="expiryD_1" data-get-id="1"/>
        <label><?php echo $Core->Translator->translate("Expiration Date");?></label>
    </div>
    <div class="col m3 s12 input-field">
        <input type="number" name="expiryAmount[]" id="expiryA_1" class="expiryInput validate" data-error="<?php echo $Core->Translator->translate("Use only numbers!");?>"  data-get-id="1"/>
        <label><?php echo $Core->Translator->translate("Product Amount");?></label>
    </div>
</div>

</div>
<div class="col s12">
    <button type="button" class="btn-flat" onclick="addExpiryDate()"><?php echo $Core->Translator->translate("Add Expiry Date");?></button>
</div>  
<?php }else{?>
<ul class="collection">
    <li class="collection-item avatar">
        <i class="material-icons circle yellow black-text">priority_high</i>
        <span class="title"><b><?php echo $Core->Translator->translate("Please select a category first.");?></b></span>
        <p><?php echo $Core->Translator->translate("Your product needs a category in order to show up on your shop.");?><br/>
        </p>
    </li>
</ul>
<?php
}
?>
<script>
function addExpiryDate(){
   var original = $("#expiryDate_1");
    
    var countCopies = $("[id^='expiryDate_']").length + 1;
    var cloned = original.clone();
    cloned.attr("id", "expiryDate_" + countCopies);
    $(cloned).find(":input.expiryInput").each(function(){
        var current = $(this);
        console.log(this);
        var currentId = current.attr("id");
        var newId = currentId.replace(/\d+/,countCopies);
        current.attr("data-get-id",countCopies);
        current.attr("id", newId);
        
    });
    original.parent().append(cloned);
    $('select').material_select();

}

function getExpiryVariations(input){
    var input = input;
    var currentshopId = input.val();
    var inputId = input.attr("data-get-id");
    
    var variations = $("input[name='productVariation[]']").map(function(){
        if($(this).prop("checked")){
            return $(this).val();
        }
    });
     variations.each(function(){
        var varId = this;
        var hasShop = false;
        $(".variationInshop_"+varId).map(function(){
            if($(this).prop("checked")){
                    var a = $(this).val();
                    a = a.replace(/'/g, '"');
                    a = JSON.parse(a);
                    var shopid = a[1];

                if(shopid == currentshopId){
                    hasShop = true;
                }
            }
        });
        
        if(hasShop == false){
           $("#expiryV_"+inputId+" option[value="+varId+"]").attr('disabled','disabled'); 
        }else{
            $("#expiryV_"+inputId+" option[value="+varId+"]").removeAttr('disabled');
        }
     });
      $("#expiryV_"+inputId).prop('selectedIndex', 0); 
      $("#expiryV_"+inputId).formSelect();  
        
}
$('select').material_select();
</script>
