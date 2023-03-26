<?php
$Core = $data["Core"];
?>

<div class="row">
<form action="/merchant/editshop" method="post" id="editBasic">
	<input name="edit" value="Basic" type="hidden"/>
	<input name="shopId" value="<?php echo $data["shop"]["id"]?>" type="hidden"/>
	<h5><?php echo $Core->Translator->translate("Basic Information");?></h5>
            
                <div class="input-field col s12">
                    <select name="category" id="shopCategory">
                        <?php
                        foreach ($data["allCategories"] as $category){
                        ?>
                          <option value="<?php echo $category["id"]?>" <?php if($category["id"] == $data["shop"]["category_id"]){echo "selected";}?>><?php echo $Core->Translator->translate($category["name"]); ?></option>
                        <?php
                        }
                        ?>
                    	</select>
                    <label><?php echo $Core->Translator->translate("Shop Category");?></label>
                </div>
            <?php


                foreach($data["allCategories"] as $category){
					
					
                ?>
                <div class="input-field col s12 subcategories" id="category-<?php echo $category["id"]?>" <?php if($category["id"] != $data["shop"]["category_id"]){?>style="display:none"<?php }?>>
                
                    <select name="subCategory[]" multiple id="subcategory-<?php echo $category['id']?>">
                        <?php
                        foreach ($data["allSubcategories"] as $subCat){
							
                            if($subCat["category_id"] == $category["id"]){
								
								$key = in_array($subCat["id"],array_column($data["hasSubcategories"],"sub_category_id"));
								echo $key;
								if($subCat["id"] == $key){
									$selected = true;
									
									
								}else{
									$selected = false;
								}
                        ?>
                            <option value="<?php echo $subCat["id"]?>" <?php if($selected == true){ echo"selected";}?>><?php echo $Core->Translator->translate($subCat["name"]); ?></option>
                        <?php
                        }};
                        ?>
                    </select>
                    <label><?php echo $Core->Translator->translate("Shop Subcategory");?></label>
               
                </div>
                 <?php
                };
                ?>
		<div class="input-field col s12">
                    <input name="shopName"  type="text" value="<?php echo $data["shop"]["name"];?>"/>
                    <label for="shopName"  class="active"><?php echo $Core->Translator->translate("Shop Name"); ?></label>
		</div>
		
		<div class="input-field col s12">
                    <input name="shopDescription"  type="text" value="<?php echo $data["shop"]["description"];?>"/>
                    <label for="shopDescription"  class="active"><?php echo $Core->Translator->translate("Shop Description"); ?></label>
		</div>
            
                <div class="col s12">
                    <div class="file-field input-field">
                      <div class="btn">
                            <span><?php echo $Core->Translator->translate("Logo"); ?></span>
                            <input type="file" name="upload_image" id="upload_image" value="<?php echo $data["shop"]["logo"];?>"/>
                      </div>
                      <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" id="path_image" name="path_image" value="<?php echo $data["shop"]["logo"];?>">
                           <div id="uploaded_image">
						  	<img src="<?php echo $data["shop"]["logo"];?>"/>
						  </div>
                      </div>
                    </div>
		</div>
		<div class="input-field col s12">
                        <input name="shopAddress"  type="text" id="addressInput_<?php echo $data["shop"]["id"]?>"  placeholder="<?php echo $Core->Translator->translate("Enter your Address here"); ?>" value="<?php echo $data["address"]["googleString"];?>"/>
			<label for="shopAddress"  class="active"><?php echo $Core->Translator->translate("Address"); ?></label>
		</div>
                <div id="address" class="col s12" >
                    <div class="col s12 m6">
                            <div id="map"></div>
                    </div>
                    <div class="col s12 m6">
                            <div class="input-field">
                                    <input type="text" id="street_number" name="street_number" readonly="readonly"  placeholder="" value="<?php echo $data["address"]["streetNumber"];?>"/>
                                    <label for="street_number"  class="active"><?php echo $Core->Translator->translate("Street Number"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="route" name="route" readonly="readonly" placeholder="" value="<?php echo $data["address"]["street"];?>"/>
                                    <label for="route"  class="active"><?php echo $Core->Translator->translate("Street Name"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="locality" name="neighbourhood" readonly="readonly"  placeholder="" value="<?php echo $data["address"]["neighbourhood"];?>"/>
                                    <label for="locality"  class="active"><?php echo $Core->Translator->translate("Neighbourhood"); ?></label>
                            </div>
							<div class="input-field">
                                    <input type="text" id="administrative_area_level_2" readonly="readonly" name="city"   placeholder="" value="<?php echo $data["address"]["city"];?>"/>
                                    <label for="administrative_area_level_2" class="active"><?php echo $Core->Translator->translate("City"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="administrative_area_level_1"  readonly="readonly" name="state" placeholder="" value="<?php echo $data["address"]["state"];?>"/>
                                    <label for="administrative_area_level_1" class="active"><?php echo $Core->Translator->translate("State"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input  type="text" id="postal_code" name="postal_code" readonly="readonly" placeholder="" value="<?php echo $data["address"]["postalCode"];?>"/>
                                    <label for="postal_code" class="active"><?php echo $Core->Translator->translate("Zip code"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="country" readonly="readonly" name="country" placeholder="" value="<?php echo $data["address"]["country"];?>"/>
                                    <label for="country" class="active"><?php echo $Core->Translator->translate("Country"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="lat" name="lat" readonly="readonly" placeholder="" value="<?php echo $data["address"]["lat"];?>"/>
                                    <label for="lat" class="active"><?php echo $Core->Translator->translate("Latitude"); ?></label>
                            </div>
                            <div class="input-field">
                                    <input type="text" id="lng" name="lng" readonly="readonly" placeholder="" value="<?php echo $data["address"]["lng"];?>"/>
                                    <label for="street_number" class="active"><?php echo $Core->Translator->translate("Longitude"); ?></label>
                            </div>
							<div class="input-field">
                                    <input type="text" id="timezone" name="timezone" readonly="readonly" placeholder="" value="<?php echo $data["address"]["timezone"];?>"/>
                                    <label class="active"><?php echo $Core->Translator->translate("Timezone"); ?></label>
                            </div>
							
							<input type="hidden" id="addressObject" name="addressObject" value="<?php echo htmlspecialchars($data["address"]["object"]);?>"/>

                    </div>
                </div>
	<div class="col s12">
	<button type="submit" class="btn"><i class="material-icons left">forward</i><?php echo $Core->Translator->translate("Update Basic Information");?></button>
	</div>
</form>
</div>



<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC00WRtBgVw_2E2zJM0EwR9uiyW6uZ03bM&callback=initAutocomplete&libraries=places" async defer></script>
<script>

let placeSearch;
let autocomplete;
const componentForm = {
  street_number: "short_name",
  route: "long_name",
  locality: "long_name",
  administrative_area_level_2 : "long_name",
  administrative_area_level_1: "long_name",
  country: "long_name",
  postal_code: "short_name",
};


function initAutocomplete() {

  const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: <?php echo $data["shop"]["lat"]?>, lng: <?php echo $data["shop"]["lng"]?> },
    zoom: 15,
  });
  // Create the autocomplete object, restricting the search predictions to
  // geographical location types.
  autocomplete = new google.maps.places.Autocomplete(
    document.getElementById("addressInput_<?php echo $data["shop"]["id"]?>"),
    { types: ["address"] }
  );

	autocomplete.bindTo("bounds", map);
	 const infowindow = new google.maps.InfoWindow();
  const infowindowContent = document.getElementById("infowindow-content");
  infowindow.setContent(infowindowContent);
  const marker = new google.maps.Marker({
    map,
    anchorPoint: new google.maps.Point(0, -29),
  });

  // Avoid paying for data that you don't need by restricting the set of
  // place fields that are returned to just the address components.
  autocomplete.setFields(["address_component", "geometry"]);
  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete.addListener("place_changed", () => {
	
    infowindow.close();
    marker.setVisible(false);
    const place = autocomplete.getPlace();

    if (!place.geometry) {
      // User entered the name of a Place that was not suggested and
      // pressed the Enter key, or the Place Details request failed.
      window.alert("No details available for input: '" + place.name + "'");
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
      map.setZoom(17);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);
    }
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);
	  
	  
  // Get the place details from the autocomplete object.


  for (const component in componentForm) {
    document.getElementById(component).value = "";
    document.getElementById(component).disabled = false;
  }

  // Get each component of the address from the place details,
  // and then fill-in the corresponding field on the form.
  for (const component of place.address_components) {
    const addressType = component.types[0];

    if (componentForm[addressType]) {
      const val = component[componentForm[addressType]];
      document.getElementById(addressType).value = val;
    }
  }
	document.getElementById("lat").value = place.geometry.location.lat();
	document.getElementById("lng").value = place.geometry.location.lng();
	  var timezone;
	  var lat = place.geometry.location.lat();
	  var lng = place.geometry.location.lng();
	
	$.ajax({
	   url:"https://maps.googleapis.com/maps/api/timezone/json?location="+lat+","+lng+"&timestamp="+(Math.round((new Date().getTime())/1000)).toString()+"&key=AIzaSyC00WRtBgVw_2E2zJM0EwR9uiyW6uZ03bM",
		})
		.done(function(response){
		   if(response.timeZoneId != null){
			 document.getElementById("timezone").value = response.timeZoneId;
			  // validator.element("#addressInput");
		   }
	});
	document.getElementById("addressObject").value = JSON.stringify(place);
 	$("#address").fadeIn();
 	

  });

}


</script>
<script>
$('select').material_select();
$("ul.select-dropdown > li > span").on("click",function(){
     var valToSearch = this.innerHTML;
     var options = $("select.initialized ").children();
     
     options.each(function(){
         if(this.innerHTML == valToSearch){
             var value = this.value;
			 $(".subcategories").fadeOut();
             $("#category-"+value).fadeIn();
             setTimeout( function(){
						validator.element("#shopCategory");
			 },500);
         }
     });
	 	setTimeout(function(){
		 			for (let i = 1; i < 12; i++) {
					 validator.element("#subcategory-"+i);
					}
		 },500);
 });
</script>
<script>

$('#uploadimageModal').modal({dismissible: false});

    var $image_crop = $('#image_demo').croppie({
        enforceBoundary: false,
        viewport: {
          width:250,
          height:250,
          type:'square' //circle
        },
        boundary:{
          width:300,
          height:300
        }
    });

  $('#upload_image').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('open');
  });

  $('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type: 'canvas',
      size: { width: 500, height: 500 }
    }).then(function(response){
      $.ajax({
        url:"/Vendors/upload-and-crop-image/upload.php",
        type: "POST",
        data:{
            "image": response,
            "id": <?php echo $_SESSION["merchant"]["merchantId"];?>,
            "userType": "merchant"
        },
        success:function(data)
        {
          $('#uploadimageModal').modal('close');
          $('#path_image').val(data);
          $("#uploaded_image").empty();
          var img = $("<img class='preview_logo'>");
          img.attr("src",data);
          img.appendTo("#uploaded_image");
        }
      });
    })
  });

 
</script>
<script>
$.validator.addMethod('checkSubCategory', function(value, element) {

		var cat = document.getElementById("shopCategory").value;

		var input = $("#subcategory-"+cat).val();

		if(input == ''){
		   return false;
		   }else{
			return true;
		   }
});
$.validator.addMethod('fullAddress', function(value, element) {
		var input = document.getElementById("street_number").value;
	
		if(input == ''){
		   return false;
		   }else{
			return true;
		   }
});
$.validator.addMethod('checkCategory', function(value, element) {
		var input = document.getElementById("shopCategory").value;
		if(input == 'none'){
		   return false;
		   }else{
			return true;
		   }
});
var validator = $("#editBasic").validate({
	    ignore: [],
        rules: {
            shopAddress:{
				fullAddress: true
			},
			shopName:{
				required:true
			},
			category:{
				checkCategory:true
			},
			"subCategory[]":{
				checkCategory:true,
				checkSubCategory:true
			},
			path_image:{
				required:true
			}
			
        },
        //For custom messages
        messages: {
			path_image:{
				required:"<?php echo $Core->Translator->translate("Please select a Logo");?>"
			},
           shopAddress: {
			   fullAddress:"<?php echo $Core->Translator->translate("Enter Street Number");?>"
		   },
			shopName:{
				required:"<?php echo $Core->Translator->translate("Enter Shop Name");?>"
			},
			category:{
				checkCategory:"<?php echo $Core->Translator->translate("Select Category");?>"
			},
			"subCategory[]":{
				checkSubCategory:"<?php echo $Core->Translator->translate("Select Subcategory");?>"
			}
        },
	    errorClass: "invalid form-error",
        errorElement : 'div',
        errorPlacement: function(error, element) {
          var placement = $(element).data('error');
          if (placement) {
            $(placement).append(error)
          } else {
            error.insertAfter(element);
          }
        }
     });
console.log(validator);
</script>
<style>
.pac-container { z-index: 100000; }
</style>
