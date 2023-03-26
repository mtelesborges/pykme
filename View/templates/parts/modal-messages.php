<?php
$Core = $data["Core"];
$Message = $data["Message"];
if($Message["type"] == "Alert"){
?>
<div class="modal-content">
    <h4><i class="material-icons left" style="font-size:40px">warning</i><?php echo $Core->Translator->translate("Warning");?></h4>
  <p><?php 
  if($Message["variables"]){
      
        $Msg = $Core->Translator->translate($Message["text"]);
        $replace = ["%1&", "%2&",];
        $with   = [$Message["variables"][0], $Message["variables"][1]];
        $newPhrase = str_replace($replace, $with, $Msg);
        echo $newPhrase;
        
  }else{
     echo $Core->Translator->translate($Message["text"]); 
  }

  ?>
  </p>
</div>
<div class="modal-footer">
  <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
</div>
<?php } ?>

