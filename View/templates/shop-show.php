<?php
$Core = $view["Core"];
$data = $view["Data"];
$shop = $data["Shop"];
$mode = "Delivery";

?>
<div class="row">
    <div class="col s12" style="padding:0;">
        <div class="gridShopShow">
            <div class="col s12 l4" id="introShopShow">
                <div class="col s12 center-align">
                    <img src="<?php echo $shop["info"]["logo"] ?>" id="logoShopShow"/>
                </div>
                <div class="col s12 center-align">
                    <h4 id="shopNameh4"><?php echo $shop["info"]["name"] ?></h4>
                    <?php
                    if (!empty($shop["info"]["description"])) {
                        ?>
                        <p class="grey-text"><?php echo $shop["info"]["description"] ?></p>
                        <?php
                    }
                    ?>
                    <p class="pykmegreen">
                        <a class="waves-effect waves-light btn"><i class="material-icons">place</i></a>
                        <a class="waves-effect waves-light btn"><i class="material-icons">schedule</i></a>
                        <a class="waves-effect waves-light btn"><i class="material-icons">info</i></a>
                    </p>
                </div>
            </div>
            <div class="col s12 l8" id="mapShop"></div>
        </div>
        <div class="col s12" id="filterPlaceholder">
            <form class="col s12" id="filterHeader" method="get">
                <div class="nav-wrapper">
                    <div class="input-field col m2 s12">
                        <input id="search" name="search" type="search" style="height:3rem;"
                           placeholder="<?php echo $Core->Translator->translate("Search"); ?>"
                           value="<?php echo $data["params"]["search"] ?>"
                        >
                        <label class="label-icon" id="searchProducts" for="search"><i
                                    class="material-icons small pykmegreen" style="margin-top:-7px;">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                    <div class="input-field col m2 s12">
                        <select id="searchProperties" name="searchProperties[]" multiple>
                            <option disabled
                                    selected><?php echo $Core->Translator->translate("Select Properties"); ?></option>
                            <?php $propeties = array();
                            foreach ($shop["Categories"] as $c) {
                                foreach ($c["products2"] as $p) {
                                    foreach ($p["default"]["Properties"] as $prop) {
                                        if (!in_array($prop["name"], $propeties)) {
                                            $propeties[] = $prop["name"];
                                        }
                                    }
                                }
                            }
                            ?>
                            <?php
                            foreach ($propeties as $property) { ?>
                                <option value="<?php echo $property ?>" <?php if (in_array($property, $data["params"]["searchProperties"] ?? [])) { ?> selected <?php } ?> ><?php echo $Core->Translator->translate($property); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="input-field col m2 s12">
                        <select id="excludeAllergies" name="excludeAllergies[]" multiple>
                            <option disabled
                                    selected><?php echo $Core->Translator->translate("Exclude Allergies"); ?></option>
                            <?php $allergies = array();
                            foreach ($shop["Categories"] as $c) {
                                foreach ($c["products2"] as $p) {
                                    foreach ($p["default"]["Allergies"] as $all) {

                                        if (!in_array($all["name"], $allergies)) {
                                            $allergies[] = $all["name"];
                                        }
                                    }
                                }
                            }
                            foreach ($allergies as $allergy) { ?>
                                <option value="<?php echo $allergy ?>" <?php if(in_array($allergy, $data["params"]["excludeAllergies"] ?? [])) { ?> selected <?php } ?> ><?php echo $Core->Translator->translate($allergy); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="input-field col m2 s12">
                        <select id="tranportationType" name="tranportationType">
                            <?php
                            if (!$shop["info"]["noDelivery"]) {
                                ?>
                                <option value="delivery" <?php if ($data["params"]["tranportationType"] == "delivery") { ?> selected <?php } ?>><?php echo $Core->Translator->translate("Delivery"); ?></option>
                                <?php
                            }
                            ?>
                            <option value="takeaway" <?php if ($data["params"]["tranportationType"] == "takeaway") { ?> selected <?php } ?>><?php echo $Core->Translator->translate("Take-Away"); ?></option>
                        </select>
                    </div>
                    <div class="input-field col m2 s12">
                        <input type="text" class="timepicker" id="searchTime"
                               placeholder="<?php echo $Core->Translator->translate("ASAP"); ?>"/>
                        <label for="searchTime"
                               class="active"><?php echo $Core->Translator->translate("Time"); ?></label>
                    </div>
                    <div class="input-field col m2 s12">
                        <input type="text" class="datepicker" id="searchDate"
                               placeholder="<?php echo $Core->Translator->translate("Today"); ?>"/>
                        <label for="searchDate""
                        class="active"><?php echo $Core->Translator->translate("Date"); ?></label>
                    </div>
                    <div style="margin-bottom: 1rem" class="col m2 s12">
                        <button class="btn" type="submit">Pesquisar</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col s12 l8 xl9">
            <div class="col l3 xl2 hide-on-med-and-down fullHeight" id="sectionMenuCategories">
                <div id="menuCategories">
                    <h6 class="grey-text"><?php echo $Core->Translator->translate("Categories"); ?></h6>
                    <ul class="table-of-contents">
                        <?php
                        foreach ($shop["Categories"] as $c) {
                            if (!empty($c["products"])) {
                                ?>
                                <li>
                                    <a href="#category_<?php echo $c["info"]["id"] ?>"><?php echo $c["description"]["title"] ?></a>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div class="col s12 l9 xl10 fullHeight" id="sectionProducts" style="height:300vh">

                <div id="product-loop">
                    <?php include "parts/product-loop.php"; ?>
                </div>

            </div>
        </div>
        <div class="col l4 xl3 hide-on-med-and-down fullHeight" id="sidebarShop" style="padding: 10rem 0; height: calc(100% - 70px); overflow-y: auto;">
            <div class="col s12 sidebarShop">
                <h6>
                    <i class="material-icons left">directions_walk</i><?php echo $Core->Translator->translate("Pickers"); ?>
                </h6>
            </div>
            <div class="col s12 sidebarShop">
                <div class="row">
                    <form class="col s12" style="padding: 0" id="order">
                        <div class="row" style="margin-bottom: 0">
                            <div class="input-field col s12" style="padding: 0">
                                <input id="street" name="street" type="text" class="validate" required>
                                <label for="street">Street</label>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 0">
                            <div class="input-field col s12" style="padding: 0">
                                <input id="neighborhood" name="neighborhood" type="text" class="validate" required>
                                <label for="neighborhood">Neighborhood</label>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 0">
                            <div class="input-field col s6" style="padding: 0">
                                <input id="zipCode" name="zipCode" type="text" class="validate" required>
                                <label for="zipCode">Zip code</label>
                            </div>
                            <div class="input-field col s6" style="padding: 0">
                                <input id="city" name="city" type="text" class="validate" required>
                                <label for="city">City</label>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 0">
                            <div class="input-field col s12" style="padding: 0">
                                <input id="complement" name="complement" type="text" class="validate" required>
                                <label for="complement">Complement</label>
                            </div>
                        </div>
                        <div class="row">
                            <p style="margin-bottom: 0;">
                                <input class="with-gap" name="group1" type="radio" id="delivery" checked required/>
                                <label for="delivery">Delivery</label>
                            </p>
                            <p>
                                <input class="with-gap" name="group1" type="radio" id="takeaway" required/>
                                <label for="takeaway">Takeaway</label>
                            </p>
                        </div>
                        <div class="row select-container-driver">
                            <div class="input-field col s12">
                                <select id="driver">
                                    <option value="" disabled selected>Choose your option</option>
                                </select>
                                <label>Select a picker</label>
                            </div>
                        </div>
                    </form>
                </div>
                <h6><i class="material-icons left">shopping_cart</i><span style="display: flex; justify-content: space-between"><?php echo $Core->Translator->translate("Cart"); ?> <span id="price"></span></span></h6>
                <ul id="cartItems">
                    <li style="display: flex; align-items: flex-end; justify-content: space-between; width: 100%">
                        <div style="display: flex; align-items: flex-end">
                            <div style="width: 2rem; margin-right: 1em;">
                                <input name="cart-item" value="1" type="number" min="1">
                            </div>
                            <span>Cart item</span>
                        </div>
                        <button class="btn"><i class="material-icons">delete</i></button>
                    </li>
                </ul>
                <div>
                    <span style="float: left; margin-left: 0; display: none;" class="new badge gren" data-badge-caption="<?php echo $Core->Translator->translate("The driver has accepted the order and is on his way to the store."); ?>" id="orderAccepted"></span>
                    <span style="float: left; margin-left: 0; display: none;" class="new badge red" data-badge-caption="<?php echo $Core->Translator->translate("The driver rejected the order, try with another one."); ?>" id="orderRejected"></span>
                </div>
                <div id="loading" style="display: none; align-items: center">
                    <div class="preloader-wrapper small active" >
                        <div class="spinner-layer spinner-green-only">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                                <div class="circle"></div>
                            </div><div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>
                    <span style="padding-left: 1em; width: calc(100% - 50px);"><?php echo $Core->Translator->translate("Waiting for the driver to accept the order, this can take up to 5 minutes."); ?></span>
                </div>
                <div>
                    <button class="btn" onclick="submitCart()" id="submitCart" style="margin-top: 2rem;" disabled><i class="material-icons">delete</i><?php echo $Core->Translator->translate("Create order"); ?></button>
                </div>
            </div>
        </div>
    </div>

</div>
<script>

    /*$("#tranportationType").change(function () {
        getProducts();
    });
    $("#search").keyup(function () {
        getProducts();
    });
    $("#searchProperties").change(function () {
        getProducts();
    });
    $("#excludeAllergies").change(function () {
        getProducts();
    });*/

    function getProducts() {
        var data = {
            search: $("#search").val(),
            tranportationType: $("#tranportationType").val(),
            searchProperties: $("#searchProperties").val(),
            excludeAllergies: $("#excludeAllergies").val(),
            searchTime: $("#searchTime").val(),
            searchDate: $("#searchDate").val()
        }
        $.ajax({
            url: "/shop/getProducts/<?php echo $Core->FrontController->Router->Parameters ?>",
            data: data,
            type: 'POST',
            async: false,
            success: function (data) {
                $("#product-loop").html(data);
            },
            error: function (e) {
                alert("<?php echo $Core->Translator->translate("Error, please contact support@pykme.com")?>");
            }
        });
    }


    $(document).ready(function () {
        $('select').material_select();
    });


    function initMap() {
        var stylesArray =
            [
                {
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f5f5f5"
                        }
                    ]
                },
                {
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "color": "#f5f5f5"
                        }
                    ]
                },
                {
                    "featureType": "administrative.land_parcel",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#bdbdbd"
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#eeeeee"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#757575"
                        }
                    ]
                },
                {
                    "featureType": "poi.business",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#e5e5e5"
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#9e9e9e"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#cccccc"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#dadada"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit.line",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#e5e5e5"
                        }
                    ]
                },
                {
                    "featureType": "transit.station",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#eeeeee"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#c9c9c9"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#9e9e9e"
                        }
                    ]
                }
            ];

        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer({
            preserveViewport: true,
            suppressMarkers: true,
            polylineOptions: {strokeColor: "#17c600", strokeWeight: 5}
        });


        const map = new google.maps.Map(document.getElementById("mapShop"), {
            zoom: 12,
            center: {lat: <?php echo $Core->Tracker->lat ?? 0 ?>, lng: <?php echo $Core->Tracker->lng ?? 0 ?> },
            styles: stylesArray,
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // directions settings
        var waypts = [];
        waypts.push({
            location: new google.maps.LatLng(<?php echo $shop["info"]["lat"] ?? 0;?>,<?php echo $shop["info"]["lng"] ?? 0;?>),
            stopover: true
        });
        directionsRenderer.setMap(map);


        // markers
        var bounds = new google.maps.LatLngBounds();


        //shop marker
        var contentString =
            '<div class="col s6 center-align">' +
            '<img src="<?php echo $shop["info"]["logo"]?>" id="logoShopShow"/>' +
            '</div>' +
            '<div class="col s6 center-align">' +
            '<h4><?php echo $shop["info"]["name"]?></h4>' +
            <?php
            if(!empty($shop["info"]["description"])){
            ?>
            '<p class="grey-text"><?php echo $shop["info"]["description"]?></p>' +
            <?php
            }
            ?>
            '<p class="pykmegreen"> ' +
            '<a class="waves-effect waves-light btn"><i class="material-icons">place</i></a> ' +
            '<a class="waves-effect waves-light btn"><i class="material-icons">schedule</i></a> ' +
            '<a class="waves-effect waves-light btn"><i class="material-icons">info</i></a> ' +
            '</p>' +
            '</div>';

        const infowindowShop = new google.maps.InfoWindow({
            content: contentString,
        });


        var shopLatlng = new google.maps.LatLng(<?php echo $shop["info"]["lat"];?>,<?php echo $shop["info"]["lng"];?>);
        var markerShop = new google.maps.Marker({
            position: shopLatlng,
            icon: "/View/img/icon_shop.png",
            title: "<?php echo $shop["info"]["name"];?>"
        });
        markerShop.setMap(map);
        markerShop.addListener("click", () => {
            infowindowShop.open(map, markerShop);
        });

        bounds.extend(markerShop.getPosition());


        // user
        var contentString =
            '<div class="col s12 center-align">' +
            '<h4><?php echo $Core->Translator->translate("Your Position")?></h4>' +
            '<button type="button" class="btn"><?php echo $Core->Translator->translate("Change Position")?></button>' +
            '</div>';

        const infowindowUser = new google.maps.InfoWindow({
            content: contentString,
        });

        var userLatlng = new google.maps.LatLng(<?php echo $Core->Tracker->lat ?? 0?>,<?php echo $Core->Tracker->lng ?? 0 ?>);
        var markerUser = new google.maps.Marker({
            position: userLatlng,
            icon: "/View/img/icon_user.png",
            title: "<?php echo $Core->Translator->translate("Your position");;?>"
        });
        markerUser.setMap(map);
        markerUser.addListener("click", () => {
            infowindowUser.open(map, markerUser);
        });
        bounds.extend(markerUser.getPosition());


        /// loop

        var contentString =
            '<div class="col s12 center-align">' +
            '<h4><?php echo $Core->Translator->translate("Picker")?></h4>' +
            '<div class="col s12 input-field">' +
            '<input id="pricePicker_1" type="number" step="any" value="2" disabled/>' +
            '<label for="pricePicker_1" class="active"><?php echo $Core->Translator->translate("Price per km in");?></label>' +
            '</div>' +
            '<p id="estimatePricePicker_1"></p>' +
            '<p id="estimateTimePicker_1"></p>' +
            '</div>';

        const infowindowPicker = new google.maps.InfoWindow({
            content: contentString,
        });

        var pickerLatlng = new google.maps.LatLng(47.21529006083242, 9.520538249935669);
        var markerPicker = new google.maps.Marker({
            position: pickerLatlng,
            icon: "/View/img/icon_pickers.png",
            title: "<?php echo $Core->Translator->translate("Picker");?>"
        });
        markerPicker.setMap(map);
        bounds.extend(markerPicker.getPosition());
        markerPicker.addListener('click', () => getRoute(markerPicker.getPosition(), 1/*picker id */));
        markerPicker.addListener("click", () => {
            infowindowPicker.open(map, markerPicker);
        });


        // end loop
        map.fitBounds(bounds);

        function getRoute(position, pickerid) {
            directionsService.route({
                origin: markerUser.position,
                destination: position,
                travelMode: 'DRIVING',
                waypoints: waypts
            }, (result, status) => {
                if (status !== 'OK') return alert(`Error: ${status}`);
                directionsRenderer.setDirections(result);
                var totalDistance = 0;
                var totalDuration = 0;
                var legs = result.routes[0].legs;
                for (var i = 0; i < legs.length; ++i) {
                    totalDistance += legs[i].distance.value;
                    totalDuration += legs[i].duration.value;
                }
                var perDistance = $("#pricePicker_" + pickerid).val();
                var distance = totalDistance / 1000;
                var price = distance * perDistance;
                $("#estimatePricePicker_" + pickerid).html(price);
                $("#estimateTimePicker_" + pickerid).html(Math.round(totalDuration / 60) + " Min.");
            });
        }

    }

    /*$('.timepicker').timepicker({
        default: 'now', // Set default time: 'now', '1:30AM', '16:30'
        fromnow: 0,       // set default time to * milliseconds from now (using with default = 'now')
        twelvehour: false, // Use AM/PM or 24-hour format
        donetext: '<?php echo $Core->Translator->translate("OK");?>', // text for done-button
        cleartext: '<?php echo $Core->Translator->translate("Clear");?>', // text for clear-button
        canceltext: '<?php echo $Core->Translator->translate("Cancel");?>', // Text for cancel-button,
        container: undefined, // ex. 'body' will append picker to body
        autoclose: false, // automatic close timepicker
        ampmclickable: true, // make AM PM clickable
    });

    $('.datepicker').datepicker({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year,
        today: '<?php echo $Core->Translator->translate("Today");?>',
        min: new Date(),
        clear: '<?php echo $Core->Translator->translate("Clear");?>',
        close: '<?php echo $Core->Translator->translate("Ok");?>',
        closeOnSelect: false, // Close upon selecting a date,
        container: undefined, // ex. 'body' will append picker to body
    });*/

    function addToCart(id, title, price, option) {

        const CART_KEY = 'cart'

        const shopName = '<?php echo $shop["info"]["name"]; ?>';

        let cart = localStorage.getItem(CART_KEY);

        cart = cart ? JSON.parse(cart) : {};

        cart[shopName] ??= [];

        const hasItemInCart = cart[shopName].some(item => item.id === id);

        if (!hasItemInCart) {
            cart[shopName].push({
                id,
                quantity: title ? 1 : 0,
                title,
                unit_price: +price,
                price: +price,
                index: cart[shopName].length,
                options: option ? [option] : []
            });
        } else {
            const cartItem = cart[shopName].find(item => item.id === id);
            const cartItemIndex = cart[shopName].findIndex(item => item.id === id);

            if (title) {
                cartItem.quantity += 1;
                cartItem.price = cartItem.unit_price * cartItem.quantity;
            }

            cartItem.options = cartItem?.options?.filter(item => item.id !== option?.id);

            if (option?.checked === true) {
                cartItem.options.push(option);
            }

            cart[shopName][cartItemIndex] = cartItem;
        }

        localStorage.setItem(CART_KEY, JSON.stringify(cart));

        renderCart(cart[shopName]);
    }

    function renderCart(cart) {
        const el = document.getElementById('cartItems');

        updatePrice(cart)

        el.innerHTML = '';

        cart.filter(item => item.title).forEach((item, index) => {
            const li = document.createElement('li');
            li.style.display = "flex";
            li.style.alignItems = "flex-end";
            li.style.justifyContent = "space-between";
            li.style.width = "100%";
            li.id = `cartItem-${index}`

            li.innerHTML = `
                <div style="display: flex; align-items: flex-end" data-cart-item>
                    <div style="width: 2rem; margin-right: 1em;">
                        <input data-cart-item-quantity data-cart-item-amount="${item.price}" value="${item.quantity}" type="number" min="1" onchange="addQuantity(this, ${index})">
                    </div>
                    <span data-cart-item-id="${item.id}">${item.title}</span>
                </div>
                <button class="btn" onclick="removeOfCart(${index})"><i class="material-icons">delete</i></button>
            `;
            el.appendChild(li);
        })
    }

    function addQuantity(el, index) {
        const CART_KEY = 'cart'
        let cart = localStorage.getItem(CART_KEY);
        const shopName = '<?php echo $shop["info"]["name"]; ?>';
        cart = cart ? JSON.parse(cart) : {};
        cart[shopName] ??= [];
        const quantity = +el.value;
        cart[shopName].at(index).quantity = quantity;
        cart[shopName].at(index).price = +cart[shopName].at(index).unit_price * quantity;
        el.setAttribute('data-cart-item-amount', +cart[shopName].at(index).unit_price * quantity);
        updatePrice(cart[shopName])
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
    }

    function updatePrice(cart) {
        const price = document.getElementById('price');
        price.textContent = cart.filter(item => item.title)
            .map(item => {
                const itemPrice = (item.unit_price ?? 0) * (item.quantity ?? 0);
                const optionsPrice = item.options?.map(option => option.price)?.reduce((a, b) => a + b, 0);
                return (itemPrice ?? 0) + (optionsPrice ?? 0);
            }).reduce((a, b) => a + b, 0).toFixed(2);
    }

    function removeOfCart(index) {
        document.getElementById(`cartItem-${index}`)?.remove();
        const CART_KEY = 'cart'
        const shopName = '<?php echo $shop["info"]["name"]; ?>';
        let cart = localStorage.getItem(CART_KEY);
        cart = cart ? JSON.parse(cart) : {};
        cart[shopName] ??= [];
        cart[shopName].splice(index, 1);
        updatePrice(cart[shopName])
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
    }

    (function initCart() {
        const CART_KEY = 'cart'
        let cart = localStorage.getItem(CART_KEY);
        const shopName = '<?php echo $shop["info"]["name"]; ?>';
        cart = cart ? JSON.parse(cart) : {};
        cart[shopName] ??= [];
        renderCart(cart[shopName]);
    })();

    function getPickers() {
        const lat = <?php echo $Core->Tracker->lat ?? 0 ?>;
        const lng = <?php echo $Core->Tracker->lng ?? 0 ?>;
        const url = `/api/avaiableDrivers/?lat=${lat}&lng=${lng}`;
        fetch(url)
            .then(async data => {
                const drivers = await data.json();
                const select = document.getElementById("driver");
                drivers.forEach(driver => {
                    // Create a new option element
                    const option = document.createElement("option");
                    option.value = `${driver.driver_id},${driver.vehicle_id}`;
                    option.text = `($${driver.price}) ${driver.driver_name}`;

                    // Append the option to the select element
                    select.appendChild(option);
                });
                $("#driver").material_select();
            });
    }

    getPickers();

    const fields = {};

    fields['transportationType'] = 'DELIVERY';

    document.getElementById("takeaway").addEventListener('change', function(_event) {
        _event.stopPropagation();
        const select = document.querySelector(".select-container-driver");
        const display = _event.target.checked;
        if (display) {
            fields['transportationType'] = 'TAKEWAY';
            select.style.display = 'none';
        }
    })

    document.getElementById("delivery").addEventListener('change', function(_event) {
        _event.stopPropagation();
        const select = document.querySelector(".select-container-driver");
        const display = _event.target.checked;
        if (display) {
            fields['transportationType'] = 'DELIVERY';
            select.style.display = 'block';
        }
    });

    const shopId = "<?php echo $shop['info']['id'] ?>";
    const lat = <?php echo $Core->Tracker->lat ?? 0 ?>;
    const lng = <?php echo $Core->Tracker->lng ?? 0 ?>;

    fields['shopId'] = shopId;
    fields['geo'] = {
        lat, lng
    }

    const _submitCart = document.getElementById('submitCart');
    const _loading = document.getElementById('loading');
    const _orderAccepted = document.getElementById('orderAccepted');
    const _orderRejected = document.getElementById('orderRejected');

    const driver = $("#driver");
    driver.material_select();
    fields['driverId'] = undefined;
    fields['vehicleId'] = undefined;
    driver.on('change', function (e) {
        const values = e.target.value?.split(',')
        fields['driverId'] = values[0];
        fields['vehicleId'] = values[1];
        const invalidFields = Object.values(fields).filter(field => !field).length;
        invalidFields ?  _submitCart.setAttribute('disabled', '') : _submitCart.removeAttribute('disabled');
    });

    document
        .getElementById('order')
        .querySelectorAll('input')
        .forEach(input => {
            const name = input.getAttribute('name');
            if(input.hasAttribute('required') && !name.includes('group')) {
                fields[name] = undefined;
                input.addEventListener('change', _event => {
                    _event.stopPropagation();
                    const value = input.value;
                    fields[name] = value;
                    const invalidFields = Object.values(fields).filter(field => !field).length;
                    invalidFields ?  _submitCart.setAttribute('disabled', '') : _submitCart.removeAttribute('disabled');
                });
            }
        });

    function submitCart() {
        const cart = [];
        document
            .querySelectorAll('[data-cart-item]')
            .forEach(item => {
                const quantity = item.querySelector('[data-cart-item-quantity]').value;
                const amount =  item.querySelector('[data-cart-item-amount]').getAttribute('data-cart-item-amount');
                const _id = item.querySelector('[data-cart-item-id]').getAttribute('data-cart-item-id');
                cart.push({quantity, amount, id: _id});
            });

        _submitCart.setAttribute('disabled', '');

        fetch('/api/createOrder/', {
            method: 'POST',
            body: JSON.stringify({cart, ...fields})
        })
            .then(async response => {
                const data = await response.json();
                _loading.style.display = 'flex';
                const { order_id } = data;
                let intervalCounter = 0;
                const interval = setInterval(() => {
                    intervalCounter++;
                    if (intervalCounter === 50) {
                        _orderRejected.style.display = 'block';
                        clearInterval(interval);
                        return;
                    }

                    fetch(`/api/findOrder/?id=${order_id}`)
                        .then(async response => {
                            const data = await response.json();

                            if (data.status === "DRIVER_ACCEPTED") {
                                _loading.style.display = 'none';
                                _orderAccepted.style.display = 'block';
                                clearInterval(interval);
                            }

                            if (data.status === "DRIVER_REJECTED") {
                                _loading.style.display = 'none';
                                _orderRejected.style.display = 'block';
                                _submitCart.removeAttribute('disabled');
                                clearInterval(interval);
                            }
                        })
                }, 10000);
            })
            .catch( _ => {
                _submitCart.removeAttribute('disabled');
            });
    }

    const options = document.querySelectorAll('[data-option]');
    options.forEach(option => {
        option.addEventListener('change', (event) => {
            event.stopPropagation();
            let id = event.target.id;
            const price = +event.target.getAttribute('data-option-price');
            id = id.replace('option_', '');
            const [optionId, productId] = id.split('_');
            addToCart(+productId, null, null, { id: +optionId, checked: event.target.checked, price });
        });
    });

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC00WRtBgVw_2E2zJM0EwR9uiyW6uZ03bM&callback=initMap" async></script>