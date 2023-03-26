<?php
$Core = $data["Core"];
$langs = $Core->Translator->getLanguages();
?>
<h4><?php echo $Core->Translator->translate("Create Product Options");?></h4>
        <div class="col s12" id="load_createProductOptions">
            <?php
            if(empty($data["productOptions"])){?>
            <ul class="collection">
            <li class="collection-item avatar">
                <i class="material-icons circle yellow black-text">priority_high</i>
                <span class="title"><b><?php echo $Core->Translator->translate("Please create a bundle first.");?></b></span>
                <p><?php echo $Core->Translator->translate("Below you can create a bundle. A bundle is a group of options that will be attached to your product. If you have only one option you still have to create a bundle.");?>
                </p>
            </li>
            </ul>    
            <?php    
            }else{
            ?>
            <h5 class="h5divider"><?php echo $Core->Translator->translate("Create Option");?></h5>
            <ul class="collapsible"  data-collapsible="accordion">
            <?php foreach($data["productOptions"] as $bundle){?>
                <li>
                    <div class="collapsible-header">
                        <span style="width:100%;">
                        <?php echo $bundle["descriptions"]["title"]?>
                        <?php if($bundle["info"]["required"] == 1){?><i class="material-icons right tooltipped" data-position="bottom" data-delay="5" data-tooltip="<?php echo $Core->Translator->translate("Is required. Customer has to choose one or more options in this bundle to purchase product.");?>">verified</i> <?php }?>
                        <?php if($bundle["info"]["hasAmount"] == 1){?><i class="material-icons right tooltipped" data-position="bottom" data-delay="5" data-tooltip="<?php echo str_replace("%",$bundle["info"]["maxAmount"],$Core->Translator->translate("Is limited. Customer can only choose % option(s)"));?>">pan_tool</i> <?php }?>
                        </span>
                    </div>
                    <div class="collapsible-body">
                        <div style="display:grid" id="load_optionsBody_<?php echo $bundle["info"]["id"]?>">
                        <?php 
                        $hasData["Core"] = $Core;
                        $hasData["bundle"] = $bundle;
                        $hasData["currency"] = $data["currency"];
                        $hasData["foodAllergies"] = $data["foodAllergies"];
                        $Core->FrontController->partialRender("product-get-options-by-bundle.php",$hasData);?>
                        </div>
                    </div>
                </li>
            <?php } ?>
            </ul>
            <?php } ?>
        </div>




<div class="col s12">
 <ul class="collapsible"  data-collapsible="accordion">
     <li>
         <div class="collapsible-header">
             <h5 class="title"><i class="material-icons left">add_circle_outline</i><?php echo $Core->Translator->translate("Create Bundle");?></h5>
         </div>
         <div class="collapsible-body">
        <div style="display:grid">
            
            <div class="col s12 input-field">
                <select id="productOptionLanguage" onchange="changeLangBundle($(this))">
                    <?php
                    foreach($langs as $lang){
                    ?>
                    <option value="<?php echo $lang["id"]?>" <?php if($lang["code"] == $Core->Translator->lang){ echo "selected";}?>><?php echo $Core->Translator->translate($lang["name"])?> (<?php echo $lang["local_name"];?>)</option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            
            <?php
            foreach($langs as $lang){?>
            <div class="optionBundleInfo" id="optionBundleInfo_<?php echo $lang["id"]?>" <?php if($lang["code"] == $Core->Translator->lang){?>style="display:block"<?php }?>>
                <div class="col s12">
                    <input name="optionBundledefaultLang" class="with-gap" value="<?php echo $lang["id"]?>" <?php if($lang["code"] == $Core->Translator->lang){?>checked="checked"<?php }?> type="radio" id="optionBundledefaultLang_<?php echo $lang["id"]?>"/>
                    <label for="optionBundledefaultLang_<?php echo $lang["id"]?>"><?php echo $Core->Translator->translate("Default Language");?> <i class="material-icons tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?php echo $Core->Translator->translate("Show in this language if selected language by the customer is not translated");?>">help_outline</i></label>
                </div>
                <div class="input-field col s12">
                    <label for="optionsBundleName_<?php echo $lang["id"]?>"><?php echo $Core->Translator->translate("Options Bundle Name");?> (<?php echo $Core->Translator->translate($lang["name"]);?>)</label>
                    <input data-language="<?php echo $lang["id"]?>" type="text" id="optionsBundleName_<?php echo $lang["id"]?>" class="optionBundleName"/>
                </div>
                <div class="input-field col s12">
                    <label for="optionsBundleDescription_<?php echo $lang["id"]?>"><?php echo $Core->Translator->translate("Option Bundle Description");?> (<?php echo $Core->Translator->translate($lang["name"]);?>)</label>
                    <textarea id="optionsBundleDescription_<?php echo $lang["id"]?>" class="materialize-textarea optionBundleDescription"></textarea>
                </div>
            </div>
            <?php
            }
            ?>
            <div class="col s12">
                <input type="checkbox" id="bundleOptionRequired" onclick="getbundleOptionRequired($(this))"/>
                <label for="bundleOptionRequired"><?php echo $Core->Translator->translate("Check this with customer is required to choose one or more of the options in this bundle to be able to buy the product.");?></label>
                <div class="col s12 ishidden" id="hiddenbundleOptionRequired">
                    <div class="col m6 s12 input-field">
                        <input id="requiredMin" type="number" class="validate"/>
                        <label><?php echo $Core->Translator->translate("Min. Amount");?> (<?php echo $Core->Translator->translate("Required")?>)</label>
                    </div>
                    <div class="col m6 s12 input-field">
                        <input id="requiredMax" type="number" class="validate"/>
                        <label><?php echo $Core->Translator->translate("Max. Amount");?> (<?php echo $Core->Translator->translate("Optional")?>)</label>
                    </div>
                </div>
            </div>
            <div class="col s12 bundleOptionAmount">
                <input type="checkbox" id="bundleOptionAmount" onclick="getbundleOptionAmount($(this))"/>
                <label for="bundleOptionAmount"><?php echo $Core->Translator->translate("Check this with customer can only choose a specifc amount of options in this bundle.");?></label>
                <div class="col s12 ishidden" id="hiddenbundleOptionAmount">
                    <div class="col m6 s12 input-field">
                        <input id="bundleMax" type="number" class="validate"/>
                        <label><?php echo $Core->Translator->translate("Max. Amount");?> (<?php echo $Core->Translator->translate("Optional")?>)</label>
                    </div>
                </div>
            </div>
            <div class="col s12" id="buttonCreateBundle">
                <button class="btn-flat" type="button" onclick="addOptionBundle()"><?php echo $Core->Translator->translate("Create Bundle");?></button>
            </div>
        </div>
         </div>
     </li>
 </ul>
</div>
<script>
 $('.collapsible').collapsible();
</script>