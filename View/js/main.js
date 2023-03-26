$( document ).ready(function(){
		$('select').material_select();
		$('.modal').modal();
	 	$('.button-collapse').sideNav();
		$('.tooltipped').tooltip({delay: 50});
});


function parseDate(input, format) {
  format = format || 'yyyy-mm-dd'; // default format
  var parts = input.match(/(\d+)/g), 
      i = 0, fmt = {};
  // extract date-part indexes from the format
  format.replace(/(yyyy|dd|mm)/g, function(part) { fmt[part] = i++; });

  return new Date(parts[fmt['yyyy']], parts[fmt['mm']]-1, parts[fmt['dd']]);
}
function modalMessage(message = null){
    $(".modal").modal();
    $('#modalMessage').modal('open');
    $.ajax({
        url:"/message",
        type:"post",
        data:message,
        success: function (response) {
            $("#modalMessage").html(response);
        },error: function (r){
        }
    })
}

function searchAddress(){
    /* const input = document.getElementById("autocomplete-input").value;
    if(input !== ""){
       window.location.href = "/search/address/"+input; 
    }*/
    navigator.geolocation.getCurrentPosition((position) => {
        let lat = position.coords.latitude;
        let lng = position.coords.longitude;
        window.location.href = `/search/address/?lat=${lat}&lng=${lng}`;
    });
}