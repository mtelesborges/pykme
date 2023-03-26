function searchCrossSelling(name,shopId){
    var filter, ul, li, i, txtValue;
    
   ul = $("#listProductsIn_"+shopId);
   li = ul.children();
   filter = name.toUpperCase();
   console.log(li);
    for( i = 0; i < li.length; i++) {
        txtValue =  li[i].getAttribute("data-name").toUpperCase();
        if (txtValue.includes(filter)) {
          li[i].style.display = "block";
        } else {
          li[i].style.display = "none";
        }
        
    }
}
function checkTransportationAndStore(input,shopId){
    var varId = input.attr("data-variation-id");
    if(input.prop("checked")){
        $("#transportationOnlyVariationShop_"+shopId+"_"+varId).prop("checked",false);
        $(".onDelivery_"+shopId+"_"+varId).fadeIn();
        $(".inStore_"+shopId+"_"+varId).fadeIn();
    }else{
        if($("#transportationVariationShop_"+shopId+"_"+varId).prop("checked") == false){
            $(".onDelivery_"+shopId+"_"+varId).fadeOut();
        }else{
            $(".onDelivery_"+shopId+"_"+varId).fadeIn();
        }
    }
}
function checkTransportationOnly(input,shopId){
    var varId = input.attr("data-variation-id");
    if(input.prop("checked")){
        $("#transportationVariationShop_"+shopId+"_"+varId).prop("checked",false);
        $(".inStore_"+shopId+"_"+varId).fadeOut();
        $(".onDelivery_"+shopId+"_"+varId).fadeIn();
    }else{
        $("#transportationVariationShop_"+shopId+"_"+varId).prop("checked",true);
        $(".inStore_"+shopId+"_"+varId).fadeIn();
    }
}
function checkTransportOptionShop(input){
    var shopId = input.val();
    if(input.prop("checked")){
        $("#transportOptionsShop_"+shopId).fadeIn();
        $("#transportOptionInformationShop_"+shopId).hide();
        $(".onDelivery_"+shopId).fadeIn();
        $(".transportationShop_"+shopId).prop("checked",true);
        $(".transportationOnlyShop_"+shopId).prop("checked",false);
    }else{
        $("#transportOptionsShop_"+shopId).fadeOut();
        $("#transportOptionInformationShop_"+shopId).show();
        $(".onDelivery_"+shopId).fadeOut();
        $(".inStore_"+shopId).fadeIn();
    }
}
function checkTransportationVariation(input,varId){
    if(input.prop("checked")){
        $(".transportVariationInput_"+varId).prop("checked",false)
        $("#checkTransportation_"+varId).fadeOut();
        $(".transportVariation_"+varId).fadeOut();
    }else{
        $(".transportVariationInput_"+varId).prop("checked",true)
        $("#checkTransportation_"+varId).fadeIn();
        $(".transportVariation_"+varId).fadeIn();
    }
}
function checkOnlyDeliveryVariation(input, varId){
     if(input.prop("checked")){
        $(".transportOnlyVariation_"+varId).fadeOut();
    }else{
        $(".transportOnlyVariation_"+varId).fadeIn();
    } 
}
function checkOrderOptionCredit(input,shopId,variation){
    if(input.prop("checked")){
        $("#orderOption_onlyCash_shop_"+shopId+"_"+variation+"_container").prop("checked",false);
        $("#orderOption_onlyCash_shop_"+shopId+"_"+variation+"_container").fadeOut();

    }else{
        $("#orderOption_onlyCash_shop_"+shopId+"_"+variation+"_container").fadeIn();
    }
}
function checkOrderOptionCash(input,shopId,variation){
    if(input.prop("checked")){
        $("#orderOption_onlyCredit_shop_"+shopId+"_"+variation+"_container").prop("checked",false);
        $("#orderOption_onlyCredit_shop_"+shopId+"_"+variation+"_container").fadeOut();
    }else{
        $("#orderOption_onlyCredit_shop_"+shopId+"_"+variation+"_container").fadeIn();
    }

}
function productRestrictionTimeHasAPeriod(input){
    if(input.prop("checked")){
        $("#productRestrictionTimePeriod").fadeIn();
    }else{
        $("#productRestrictionTimePeriod").hide();
    }
}
function getbundleOptionRequired(input){
    if(input.prop("checked")){
        $("#hiddenbundleOptionRequired").fadeIn();
        $(".bundleOptionAmount").hide();
    }else{
        $("#hiddenbundleOptionRequired").hide();
        $(".bundleOptionAmount").fadeIn();
    }
}
function getbundleOptionAmount(input){
    if(input.prop("checked")){
       $("#hiddenbundleOptionAmount").fadeIn(); 
    }else{
        $("#hiddenbundleOptionAmount").hide(); 
    }
}

function changeLangBundle(input){
    var lang = input.val();
    $(".optionBundleInfo").hide();
    $("#optionBundleInfo_"+lang).fadeIn();
}

function selectProductOption(bundleId,inputCurrency){
    var currencyId = inputCurrency.val();
     $(".containerOptionPrice").hide();
    $("#option_price_"+currencyId+"_bundle_"+bundleId).fadeIn();
}

function optionLanguage(bundleId,selectLanguage){
    var lang = selectLanguage.val();
    
    $(".optionDescriptionContainer").hide();
    $("#optionLanguage_"+lang+"_bundle_"+bundleId).fadeIn();
}

function checkePriceConditionPeriod(input){
    if(input.prop("checked")){
        $("#priceConditionPeriodContent").fadeOut();
    }else{
        $("#priceConditionPeriodContent").fadeIn();
    }
}
function checkDistanceTimeSensitive(input){
    if(input.prop("checked")){
        $("#distanceConditionTimeSensitiveContent").fadeIn();
    }else{
        $("#distanceConditionTimeSensitiveContent").fadeOut();
    }
}
function checkDistanceConditionWeekly(input){
    if(input.prop("checked")){
        $("#distanceConditionHasPeriod").fadeOut();
    }else{
        $("#distanceConditionHasPeriod").fadeIn();
    }
}

function changeDistanceConditionCurrency(input,id){
    var currency = input.val();
    $(".distanceConditionPriceContainer").hide();
    $("#distanceConditionPrice_"+currency).fadeIn();
}
function checkGlobalInventoryPeriod(input,id = ""){
    if(input.val() == "period"){
        $("#globalInventoryHasTimePeriodContainer"+id).fadeIn();
    }else{
        $("#globalInventoryHasTimePeriodContainer"+id).hide();
    }
}
function checkShopInventoryPeriod(input,id){
     if(input.val() == "period"){
        $("#shopInventoryHasTimePeriodContainer"+id).fadeIn();
    }else{
        $("#shopInventoryHasTimePeriodContainer"+id).hide();
    }
}

function checkShopInventoryPeriodVariation(input,id,vid){
     if(input.val() == "period"){
        $("#shopInventoryHasTimePeriodContainer"+id+"_variation_"+vid).fadeIn();
    }else{
        $("#shopInventoryHasTimePeriodContainer"+id+"_variation_"+vid).hide();
    }
}

function changeInventoryConditionCurrency(input){
    var currency = input.val();
    $(".inventoryConditionPriceContainer").hide();
    $("#inventoryConditionPrice_"+currency).fadeIn();
}

function checkInventoryTimeSensitive(input){
    if(input.prop("checked")){
        $("#priceConditionInventoryTimeContent").fadeIn();
    }else{
        $("#priceConditionInventoryTimeContent").hide(); 
    }
}

function checkInventoryConditionWeekly(input){
    if(input.prop("checked")){
        $("#inventoryConditionHasPeriod").hide();
    }else{
        $("#inventoryConditionHasPeriod").fadeIn();
    }
}
function changeExpirationConditionCurrency(input){
    var currency = input.val();
    $(".expirationConditionPriceContainer").hide();
    $("#expirationConditionPrice_"+currency).fadeIn();
}
function productRestrictionDeliveryTimeHasATime(input){
    if(input.prop("checked")){
        $("#productRestrictionDeliveryTime_Time").fadeIn();
    }else{
        $("#productRestrictionDeliveryTime_Time").hide();
    }
}
function productRestrictionDeliveryTimeHasAPeriod(input){
    if(input.prop("checked")){
        $("#productRestrictionDeliveryTime_Period").fadeIn();
    }else{
        $("#productRestrictionDeliveryTime_Period").hide();
    }
}

function addProduct(){

    var shops = getShops();
    var product = new Object;
    /////////////////////////////////////////////////////////
    // BASIC INFORMATION ////////////////////////////////////
    /////////////////////////////////////////////////////////
    var defaultLanguage = $("input[name='defaultInfoLang']:checked").val();
    var descriptionInfo = $(".productName").map(function(){
        var lang = $(this).attr("data-lang");
        var isDefault = false;
        //check if default Language is declared
        if(defaultLanguage == lang){
            isDefault = true;
            if($(this).val() == ""){
                var msg = {
                    type:"Alert",
                    text:"Product name in default language can't be empty!",
                    variables:""
                }
                modalMessage(msg);
                throw new Error;
            }
        }
        if($(this).val() != ""){
            var object = {
                name:$(this).val(),
                description:$("#description_"+lang).val(),
                lang_id:lang,
                isDefault:isDefault
            };
            return object;
        }
    }).get(); 
    
    var productType = $("#productType").val();

    if(productType == "" || productType == null){
        alert("Please select product type in 'Basic Information'");
        throw new Error;
    }
    
    if(productType == 1){
        var contentBevarage = $("#contentBevarage").val();
        var contentScalaType= $("#contentScalaType").val();
        var contentCalories = $("#calories").val();
        var propeties       = $("#selectProductPropeties").val();
        var allergies       = $("#allergies").val();
        var noAllergies     = $("#noAllergicContent").prop("checked");
        
        if(contentBevarage == ""){
            var msg = {
                    type:"Alert",
                    text:"Please declare content Amount in 'Basic Information!",
                    variables:""
                }
                modalMessage(msg);
            throw new Error;
        }
        if(allergies == "" || allergies == null){
            if(noAllergies == false){
                var msg = {
                    type:"Alert",
                    text:"Please declare if product has allergic content in 'Basic Information",
                    variables:""
                }
                modalMessage(msg);
                throw new Error;
            }
        }
        var productTypeInformation = {
            type: productType,
            contentBevarage:contentBevarage,
            contentScalaType:contentScalaType,
            properties:propeties,
            noAllergies:noAllergies,
            allergies:allergies,
            contentCalories:contentCalories
            
        };
    }
    if(productType == 2){
        var contentCalories = $("#calories").val();
        var propeties       = $("#selectProductPropeties").val();
        var allergies       = $("#allergies").val();
        var noAllergies     = $("#noAllergicContent").prop("checked");
        
        if(allergies == "" || allergies == null){
            if(noAllergies == false){
                var msg = {
                    type:"Alert",
                    text:"Please declare if product has allergic content in 'Basic Information",
                    variables:""
                }
                modalMessage(msg);
                throw new Error;
            }
        }
        
        var productTypeInformation = {
            type: productType,
            properties:propeties,
            noAllergies:noAllergies,
            allergies:allergies,
            contentCalories:contentCalories
            
        };
    }
    if(productType == 3){
        var hasPrescription = $("#needPrescription").prop("checked");
        var productTypeInformation = {
            type: productType,
            hasPrescription:hasPrescription 
        };
    }
    if(productType == 4){
        var productTypeInformation = {
            type: productType,
            noAllergies:true
        };
    }
    
    
    product.BasicInformation = {
        descriptions: descriptionInfo,
        typeInformation:productTypeInformation,
    }
    
    
    
    /////////////////////////////////////////////////////////
    // SHOP CATEGORY ////////////////////////////////////////
    /////////////////////////////////////////////////////////
    
   
    var categories = $("input[name='category[]']:checked").map(function(){
        var hasShops = false;
        var catId = $(this).attr("data-category-id");
        var hasShops = $(".shopsCategory_"+catId).map(function(){
            if($(this).prop("checked")){
                hasShops = true;
                return $(this).attr("data-shop-id");
            }
        }).get();
        
        if(hasShops == false){
            var msg = {
                    type:"Alert",
                    text:"Something went wrong in 'Shop Category'",
                    variables:""
                }
                modalMessage(msg);
            throw new Error;
        }
        
        var category = {
            catId:catId,
            hasShops:hasShops
        }
        return category;
    }).get();
    
    if(categories == ""){
        var msg = {
                    type:"Alert",
                    text:"Please select a category in 'Shop Category'",
                    variables:""
                }
                modalMessage(msg);
        throw new Error;
    }
    product.ShopCategories = categories;
    
    /////////////////////////////////////////////////////////
    // PRODUCT VARIATION ////////////////////////////////////
    /////////////////////////////////////////////////////////
    var productVariations = $("input[name='productVariation[]']:checked").map(function(){
        var hasShops = false;
        var varId = $(this).val();
        var inShops = $(".variationInshop_"+varId).map(function(){
            if($(this).prop("checked")){
                hasShops = true;
                return $(this).attr("data-shop-id");
            }
        }).get();
        if(hasShops == false){
            var msg = {
                    type:"Alert",
                    text:"Something went wrong in 'Product Variation'",
                    variables:""
                }
                modalMessage(msg);
            throw new Error;
        }
        var variation = {
            varId:varId,
            inShops:inShops
        }
        return variation;
        
    }).get();
    
    product.Variations = productVariations;
    
    /////////////////////////////////////////////////////////
    // TRANSPORTATION////////////////////////////////////////
    /////////////////////////////////////////////////////////
    var hasTransportation;
    if($("#noTransportation").prop("checked")){
        hasTransportation = false;
    }else{
        hasTransportation = true;
    }
    if(hasTransportation){
        var dimensions = Object.keys(product.Variations).map(function(key){
            var varId = product.Variations[key].varId;
            var weight          = $("#weight_"+varId).val();
            var weightSystem    = $("#weightSystem_"+varId).val();
            if(weight == ""){
                var msg = {
                    type:"Alert",
                    text:"Please declare weight for variation in 'Transportation'",
                    variables:""
                }
                modalMessage(msg);
                throw new Error;
            }

            var distanceSystem  = $("#distanceSystem_"+varId).val();
            var width           = $("#width_"+varId).val();
            var height          = $("#height_"+varId).val();
            var depth           = $("#depth_"+varId).val();
            if(width =="" || height =="" || depth ==""){
                var msg = {
                    type:"Alert",
                    text:"Please declare all dimensions for variations in 'Transportation'",
                    variables:""
                }
                modalMessage(msg);
                throw new Error;
            }
            
            var obj = {
                varId:varId,
                weight:weight,
                weightSystem:weightSystem,
                width:width,
                height:height,
                depth:depth,
                distanceSystem:distanceSystem,
            };
            return obj;
        });
        
        // default product
            var weight          = $("#weight").val();
            var weightSystem    = $("#weightSystem").val();
            if(weight == ""){
               var msg = {
                    type:"Alert",
                    text:"Please declare all weight for variations in 'Transportation'",
                    variables:""
                }
                modalMessage(msg);
                throw new Error;
            }

            var distanceSystem  = $("#distanceSystem").val();
            var width           = $("#width").val();
            var height          = $("#height").val();
            var depth           = $("#depth").val();
            if(width =="" || height =="" || depth ==""){
                var msg = {
                    type:"Alert",
                    text:"Please declare all dimensions for variations in 'Transportation'",
                    variables:""
                }
                modalMessage(msg); 
                throw new Error;
            }
            
            var defaultProduct = {
                varId:"0",
                weight:weight,
                weightSystem:weightSystem,
                width:width,
                height:height,
                depth:depth,
                distanceSystem:distanceSystem,
            };
            dimensions.push(defaultProduct);

        
        
        
        // get transport options
        var transportationShops = $(".shopHasTransportation:checkbox:checked").map(function(){
            var shopId = $(this).val();
            //variations
            var variations = Object.keys(product.Variations).map(function(key){
                var varId = product.Variations[key].varId;
                var hasTransportation = $("#transportationVariationShop_"+shopId+"_"+varId).prop("checked");
                var hasTransportOnly = $("#transportationOnlyVariationShop_"+shopId+"_"+varId).prop("checked");
                var object = {
                    varId:varId,
                    hasTransportation:hasTransportation,
                    hasTransportOnly:hasTransportOnly
                }
                return object;
            });
            // default
            var varId = "0";
            var hasTransportation = $("#transportationVariationShop_"+shopId+"_0").prop("checked");
            var hasTransportOnly = $("#transportationOnlyVariationShop_"+shopId+"_0").prop("checked");
            var defaultProduct = {
                varId:varId,
                hasTransportation:hasTransportation,
                hasTransportOnly:hasTransportOnly
            }
            variations.push(defaultProduct);
            var object = {
                shopId:shopId,
                variations:variations
            }
            return object;
        }).get();
        
        var transportation = {
           dimensions:dimensions,
           transportationShops:transportationShops
        }
        
    }else{
        var transportation = false;
    }

    product.Transportation = transportation;
    
    
    /////////////////////////////////////////////////////////
    // PRODUCT OPTIONS //////////////////////////////////////
    /////////////////////////////////////////////////////////
    var allOptions = Object.keys(shops).map(function(key,index){
            var shopId = shops[key];
    var optionsDefault = $("input[name='productOptions_shop_"+shopId+"[]']:checked").map(function(){
        return $(this).val();
    }).get();
    if(product.Variations != ""){
        var optionsVariations = Object.keys(product.Variations).map(function(key, index){
            var variation = product.Variations[key];
            var options = $("input[name='productOptionsVariation_"+variation.varId+"_"+shopId+"[]']:checked").map(function(){
                return $(this).val();
            }).get();
            if(options != ""){
                var obj = {
                    varId:variation.varId,
                    options:options
                }
                return obj;
            }
        }).filter(function(value){
             if(value != ""){
                    return value;
                }
        });
        if(optionsVariations == "" && optionsDefault == ""){
        }else{
            var productOptions = {
                optionsDefault:optionsDefault,
                optionsVariations:optionsVariations
            }
        }
    }else{
        if(optionsDefault != ""){
            var productOptions = {
                optionsDefault:optionsDefault
            }
        }else{
            var productOptions = false;
        }
    }
    var obj = {
        shopId:shopId,
        options:productOptions
    }
    return obj;
    });
    product.Options = allOptions;
    /////////////////////////////////////////////////////////
    // PREPARATION TIME /////////////////////////////////////
    /////////////////////////////////////////////////////////
    var preparationTime = $("#preparationTime").val();
    var typePreparationTime = $("#typePreparationTime").val();
    if(preparationTime == ""){

        var msg = {
            type:"Alert",
            text:"Please declare time amount in 'Preparation Time'",
            variables:""
        }
        modalMessage(msg);
        throw new Error;
    }
    if(product.Variations == "" || product.Variations == null){
        product.PreparationTime = {
          preparationTime:preparationTime,
          typePreparationTime:typePreparationTime
        };
    }else{
        var preparationVariations = Object.keys(product.Variations).map(function(key, index){
            var variation = product.Variations[key];
            var varId = variation.varId;
            var time = $("#preparationTimeVariation_"+varId).val();
            var type = $("#typePreparationTimeVariation_"+varId).val();
            if(time == ""){
                var msg = {
                    type:"Alert",
                    text:"Please declare preparation time amount for all variations in 'Preparation Time'",
                    variables:""
                }
                modalMessage(msg);
                throw new Error;
            }
            var prepVar = {
                preparationTime:time,
                typePreparationTime:type,
                variationId:varId
            }
            return prepVar;
        });
        
        product.PreparationTime = {
          preparationTime:preparationTime,
          typePreparationTime:typePreparationTime,
          variations:preparationVariations
        };
    }
    
    /////////////////////////////////////////////////////////
    // PRICE & INVENTORY ////////////////////////////////////
    /////////////////////////////////////////////////////////

    // scan shops
    var pricesAndInventory = Object.keys(shops).map(function(key,index){
            var shopId = shops[key];
            var shopInfo = $("#shopPriceInventoryInfo_"+shopId);
            var shopName = shopInfo.attr("data-shop-name");
            var shopCurrency =  shopInfo.attr("data-currency-name");
            var shopCurrencyId = shopInfo.attr("data-currency-id");
            
            console.log(product);
            
            // get prices per shop
            var prices = $(".StorePrice_"+shopId).map(function(){
                var isDefault   = false;
                var currencyId  = $(this).attr("data-currency-id");
                
                var inStorePrice    = $(this).val();
                var inStoreTax      = $("#inStoreTax_"+shopId+"_"+currencyId).val();
                var storeTaxType    = $("#storeTaxType_"+shopId+"_"+currencyId).val();
                
                var deliveryPrice   = $("#deliveryPrice_"+shopId+"_"+currencyId).val();
                var deliveryTax     = $("#deliveryTax_"+shopId+"_"+currencyId).val();
                var deliveryTaxType = $("#deliveryTaxType_"+shopId+"_"+currencyId).val();
                
                if(shopCurrencyId == currencyId){
                    isDefault = true;
                    if(product.Transportation == false){
                        //In Store Price
                        if(inStorePrice == ""){
                                var msg = {
                                type:"Alert",
                                text:"Shop %1& has no 'In Store Price' for %2&",
                                variables: [shopName,shopCurrency]
                            }
                            modalMessage(msg);
                            throw new Error;
                        }
                        //In Store Tax.
                        if(inStoreTax == ""){
                            var msg = {
                                type:"Alert",
                                text:"Shop %1& has no 'In Store Tax.' for %2&",
                                variables: [shopName,shopCurrency]
                            }
                            modalMessage(msg);
                            throw new Error; 
                        }
                    }else{
                    product.Transportation.transportationShops.forEach(function(shop){
                        if(shop.shopId == shopId){
                            shop.variations.forEach(function(vari){
                                if(vari.varId == 0){
                                    if(vari.hasTransportOnly == false){
                                        //In Store Price
                                        if(inStorePrice == ""){
                                            var msg = {
                                                type:"Alert",
                                                text:"Shop %1& has no 'In Store Price' for %2&",
                                                variables: [shopName,shopCurrency]
                                            }
                                            modalMessage(msg);
                                            throw new Error;
                                        }
                                        //In Store Tax.
                                        if(inStoreTax == ""){
                                           var msg = {
                                                type:"Alert",
                                                text:"Shop %1& has no 'In Store Tax.' for %2&",
                                                variables: [shopName,shopCurrency]
                                            }
                                            modalMessage(msg);
                                            throw new Error; 
                                        }
                                    }
                                    if(vari.hasTransportation == true){
                                        // Delivery Price
                                        if(deliveryPrice == ""){
                                            var msg = {
                                                type:"Alert",
                                                text:"Shop %1& has no 'Delivery Price' for %2&",
                                                variables: [shopName,shopCurrency]
                                            }
                                            modalMessage(msg);
                                            throw new Error;
                                        }
                                        // Delivery Tax
                                        if(deliveryTax == ""){
                                            var msg = {
                                                type:"Alert",
                                                text:"Shop %1& has no 'Delivery Tax' for %2&",
                                                variables: [shopName,shopCurrency]
                                            }
                                            modalMessage(msg);
                                            throw new Error;
                                        }
                                    }
                                    
                                }
                            });
                        }
                    });
                    }
                    
                }
                
                if(inStorePrice == "" && deliveryPrice == "" ){
                
                }else{
                    var price = {
                        currencyId:currencyId,
                        shopId:shopId,
                        isDefault:isDefault,
                        inStorePrice:inStorePrice,
                        inStoreTax:inStoreTax,
                        inStoreTaxType:storeTaxType,
                        deliveryPrice:deliveryPrice,
                        deliveryTax:deliveryTax,
                        deliveryTaxType:deliveryTaxType
                    }
                    
                    return price;
                }
            }).get();
            
            // get variations
            var variationsPrices = Object.keys(product.Variations).map(function(key){
                var varId = product.Variations[key].varId;
                var pricesVar = $(".InStorePriceVariation_"+varId+"_shop_"+shopId).map(function(){
                    var currencyId = $(this).attr("data-currency-id");
                    var isDefaultCurrency = false;
                      
                    var inStorePrice    = $(this).val();
                    var inStoreTax      = $("#InStoreTaxVariation_"+varId+"_currency_"+currencyId+"_shop_"+shopId).val();
                    var storeTaxType    = $("#InStoreTaxTypeVariation_"+varId+"_currency_"+currencyId+"_shop_"+shopId).val();

                    var deliveryPrice   = $("#DeliveryPriceVariation_"+varId+"_currency_"+currencyId+"_shop_"+shopId).val();
                    var deliveryTax     = $("#DeliveryTaxVariation_"+varId+"_currency_"+currencyId+"_shop_"+shopId).val();
                    var deliveryTaxType = $("#DeliveryTaxTypeVariation_"+varId+"_currency_"+currencyId+"_shop_"+shopId).val();

                    if(currencyId == shopCurrencyId){
                        isDefaultCurrency = true;
                        if(product.Transportation == false){
                            //In Store Price
                            if(inStorePrice == ""){
                                var msg = {
                                    type:"Alert",
                                    text:"Shop %1& has no 'In Store Price' for %2&",
                                    variables: [shopName,shopCurrency]
                                }
                                modalMessage(msg);
                                throw new Error;
                            }
                            //In Store Tax.
                            if(inStoreTax == ""){
                                var msg = {
                                    type:"Alert",
                                    text:"Shop %1& has no 'In Store Tax.' for %2&",
                                    variables: [shopName,shopCurrency]
                                }
                                modalMessage(msg);
                                throw new Error; 
                            }
                        }else{
                            product.Transportation.transportationShops.forEach(function(shop){
                                if(shop.shopId == shopId){
                                    shop.variations.forEach(function(vari){
                                        if(vari.varId == varId){
                                            if(vari.hasTransportOnly == false){
                                                //In Store Price
                                                if(inStorePrice == ""){
                                                    var msg = {
                                                        type:"Alert",
                                                        text:"Variation in shop %1& has no 'In Store Price' for %2&",
                                                        variables: [shopName,shopCurrency]
                                                    }
                                                    modalMessage(msg);
                                                    throw new Error;
                                                }
                                                //In Store Tax.
                                                if(inStoreTax == ""){
                                                   var msg = {
                                                        type:"Alert",
                                                        text:"Variation in shop %1& has no 'In Store Tax.' for %2&",
                                                        variables: [shopName,shopCurrency]
                                                    }
                                                    modalMessage(msg);
                                                    throw new Error; 
                                                }
                                            }
                                            if(vari.hasTransportation == true){
                                                // Delivery Price
                                                if(deliveryPrice == ""){
                                                    var msg = {
                                                        type:"Alert",
                                                        text:"Variation in shop %1& has no 'Delivery Price' for %2&",
                                                        variables: [shopName,shopCurrency]
                                                    }
                                                    modalMessage(msg);
                                                    throw new Error;
                                                }
                                                // Delivery Tax
                                                if(deliveryTax == ""){
                                                    var msg = {
                                                        type:"Alert",
                                                        text:"Variation in shop %1& has no 'Delivery Tax' for %2&",
                                                        variables: [shopName,shopCurrency]
                                                    }
                                                    modalMessage(msg);
                                                    throw new Error;
                                                }
                                            }

                                        }
                                    });

                                }
                            });
                        }
                    }
                    if(inStorePrice == "" && deliveryPrice == "" ){
                
                    }else{
                        var price = {
                            variationId:varId,
                            currencyId:currencyId,
                            shopId:shopId,
                            isDefault:isDefaultCurrency,
                            inStorePrice:inStorePrice,
                            inStoreTax:inStoreTax,
                            inStoreTaxType:storeTaxType,
                            deliveryPrice:deliveryPrice,
                            deliveryTax:deliveryTax,
                            deliveryTaxType:deliveryTaxType
                        }

                        return price;
                    }
                }).get();
                
                // get variation inventory
                var inventoryAmount = $("#inventoryShop_"+shopId+"_variation_"+varId).val();
                var inventoryType = $("#inventoryPeriodShop_"+shopId+"_variation_"+varId).val();
                
                var inventoryPeriodAmount   = $("#inventoryTimeAmountShop_"+shopId+"_variation_"+varId).val();
                var inventoryPeriodType     = $("#inventoryTimeAmountPeriodShop_"+shopId+"_variation_"+varId).val();
                
                
                if(inventoryType == "period"){
                    var addInventoryToPrevious      = $("#addInventoryToPreviousShop_"+shopId+"_variation_"+varId).prop("checked");
                    if(inventoryPeriodAmount == "" || inventoryPeriodAmount == 0){
                        var msg = {
                            type:"Alert",
                            text:"Please declare inventory period amount in shop %1& for variation",
                            variables: [shopName]
                        }
                        modalMessage(msg);
                        throw new Error;
                    }
                    var variationInventory = {
                        inventoryAmount:inventoryAmount,
                        inventoryType:inventoryType,
                        inventoryPeriodAmount:inventoryPeriodAmount,
                        inventoryPeriodType:inventoryPeriodType,
                        addToPrevious: addInventoryToPrevious
                    }
                }else{
                    var variationInventory = {
                        inventoryAmount:inventoryAmount,
                        inventoryType:inventoryType  
                    }
                }
                var obj = {
                   variationId:varId,
                   prices:pricesVar,
                   inventory:variationInventory
                }
                return obj;
                
            });

            // get inventory per shop
            var inventoryType = $("#inventoryPeriodShop_"+shopId).val();
            var inventoryAmount = $("#inventoryShop_"+shopId).val();
            var inventoryHasPeriod = false;
            
            if(inventoryType == "period"){
                inventoryHasPeriod = true;
                var inventoryTimeAmount         = $("#inventoryTimeAmountShop_"+shopId).val();
                var inventoryTimeAmountPeriod   = $("#inventoryTimeAmountPeriodShop_"+shopId).val();
                var addInventoryToPrevious      = $("#addInventoryToPreviousShop_"+shopId).prop("checked");
                if(inventoryTimeAmount == "" || inventoryTimeAmount == 0 || inventoryTimeAmount < 0){
                    var msg = {
                        type:"Alert",
                        text:"Please declare 'Period amount' for inventory in shop %1&",
                        variables: [shopName]
                    }
                    modalMessage(msg);
                    throw new Error;
                }
                
            }
            if(inventoryAmount > 1){
                var inventory = {
                    amount: inventoryAmount,
                    type: inventoryType,
                    hasPeriod:inventoryHasPeriod,
                    periodAmount:inventoryTimeAmount,
                    period:inventoryTimeAmountPeriod,
                    addToPrevious:addInventoryToPrevious
                };
  
            }else{
                var inventory = false;
            }

            // end shop loop
            var shopPricesAndInventory = {
                shopId:shopId,
                prices:prices,
                variations:variationsPrices,
                inventory:inventory
            }
            
            return shopPricesAndInventory;
    });
    
    product.PricesAndInventory = pricesAndInventory;
    
    /////////////////////////////////////////////////////////
    // ORDER OPTIONS ////////////////////////////////////////
    /////////////////////////////////////////////////////////
    var orderOptions =  Object.keys(shops).map(function(key,index){
        var shopId = shops[key];
        var onlyCredit = $("#orderOption_onlyCredit_shop_"+shopId+"_default").prop("checked");
        var onlyCash   = $("#orderOption_onlyCash_shop_"+shopId+"_default").prop("checked");
        if(onlyCredit == true && onlyCash == true) {
            var msg = {
                type:"Alert",
                text:"Something went wrong on 'Order Options'",
                variables: ""
            }
            modalMessage(msg);
            throw new Error;
        }
        var variations = Object.keys(product.Variations).map(function(key){
                var varId = product.Variations[key].varId;
                var varInShop = product.Variations[key].inShops.find( e => e == shopId);
                
                if(varInShop == shopId){
                    var onlyCredit = $("#orderOption_onlyCredit_shop_"+shopId+"_"+varId).prop("checked");
                    var onlyCash   = $("#orderOption_onlyCash_shop_"+shopId+"_"+varId).prop("checked");
                    if(onlyCredit == true && onlyCash == true) {
                        var msg = {
                            type:"Alert",
                            text:"Something went wrong on 'Order Options'",
                            variables: ""
                        }
                        modalMessage(msg);
                        throw new Error;
                    }else{
                        if(onlyCredit == true || onlyCash == true){
                            var object = {
                                varId:varId,
                                onlyCredit:onlyCredit,
                                onlyCash:onlyCash
                            }
                            return object;
                        }else{
                            return;
                        }
                    }
                }
        });
        if(variations == "" && onlyCredit == false && onlyCash == false){
        }else{
            var obj = {
                shopId:shopId,
                onlyCredit:onlyCredit,
                onlyCash:onlyCash,
                variations:variations
            }
            return obj;
        }
    }).filter(function(v){
        if(v != ""){
            return v;
        }
    });
    
    
    product.OrderOptions = orderOptions;

    
    
    /////////////////////////////////////////////////////////
    // EXPIRY DATE //////////////////////////////////////////
    /////////////////////////////////////////////////////////
    
    var expiryDates = $("select[name='expiryInShops[]']").map(function(){
        var id = $(this).attr("data-get-id");
        console.log(id);
        var inShops = $(this).val();
        if(product.Variations != ""){
            var forVariations = $("#expiryV_"+id).val();
        }else{
            var forVariations = 0;
        }
        var expiryDate      = $("#expiryD_"+id).val();
        var expiryAmount    = $("#expiryA_"+id).val();
        
        if(expiryDate != "" || expiryAmount != ""){
            if(inShops == ""){
                var msg = {
                    type:"Alert",
                    text:"Please declare at least one shop in 'Expiry Date' in position: %1&",
                    variables: [id]
                }
                modalMessage(msg);
                throw new Error;
            }
            if(expiryDate == ""){
                var msg = {
                    type:"Alert",
                    text:"Please declare expiry date on position:  %1&",
                    variables: [id]
                }
                modalMessage(msg);
                throw new Error;
            }
            if(expiryAmount ==""){
                var msg = {
                    type:"Alert",
                    text:"Please declare expiry amount on position:  %1&",
                    variables: [id]
                }
                modalMessage(msg);
                throw new Error;
            }
            if(forVariations != 0){
                if(forVariations == "" || forVariations == null){
                    var msg = {
                        type:"Alert",
                        text:"Please declare variations in 'Expiry Date' on position: %1&",
                        variables: [id]
                    }
                    modalMessage(msg);
                    throw new Error;
                }
            }
        
        var expiryObject = {
            id:id,
            inShops:inShops,
            forVariations:forVariations,
            expiryDate:expiryDate,
            expiryAmount:expiryAmount,
        }
        return expiryObject;
        }
    }).get();
    
    product.ExpiryDates = expiryDates;
    /////////////////////////////////////////////////////////
    // PRICE CONDITIONS /////////////////////////////////////
    /////////////////////////////////////////////////////////
    product.PriceConditions = $("input[name='priceConditions[]']:checked").map(function(){
        return $(this).val();
    }).get();
    
    /////////////////////////////////////////////////////////
    // PRODUCT RESTRICTIONS /////////////////////////////////
    /////////////////////////////////////////////////////////
    product.Restrictions = $("input[name='productRestriction[]']:checked").map(function(){
        return $(this).val();
    }).get();
    product.RestrictionEquipment = $("input[name='equipmentProduct[]']:checked").map(function(){
        return $(this).val();
    }).get();
    product.RestrictionVehicles = $("input[name='vehicleProduct[]']:checked").map(function(){
        return $(this).val();
    }).get();
    
    /////////////////////////////////////////////////////////
    // CROSS SELLING ////////////////////////////////////////
    /////////////////////////////////////////////////////////
    var crossselling = $("input[name='crossselling[]']:checked").map(function(){
        var shop        = $(this).attr("data-shop-id");
        var variation   = $(this).attr("data-variation-id");
        var product     = $(this).attr("data-product-id");
        var obj = {
            shop:shop,
            variation:variation,
            product:product
        }
        return obj;
    }).get();
    product.CrossSelling = crossselling;

    /////////////////////////////////////////////////////////
    // PRODUCT IMAGES ///////////////////////////////////////
    /////////////////////////////////////////////////////////    

    var id = $("#mId").val();
    var images = [];
    var defaultImg = uploadPimages(id);
    var varImg = uploadVimages(id,product.Variations);
    if(defaultImg != false){
        images.push(defaultImg[0]);
    }
    if(varImg != false){
        images.push(varImg[0]);
    }
    product.Images = images;
    
    
    /////////////////////////////////////////////////////////
    sendProductRegistration(product);
}


function uploadPimages(id){
        var images = [];
        var form_data = new FormData();
        var totalfiles = document.getElementById('productImage').files.length;
        if(totalfiles > 0){
            for (var index = 0; index < totalfiles; index++) {
               form_data.append('images[]',document.getElementById('productImage').files[index]);
            }
            form_data.append('id',id);
            form_data.append('userType',"merchant");
            $.ajax({
                url: '/Vendors/upload-and-crop-image/upload-multiple.php', 
                type: 'post',
                data: form_data,
                dataType: 'json',
                contentType: false,
                processData: false,
                async: false, 
                success: function (response) {
                    var obj = {
                        variation: 0,// default
                        images:response
                    };
                    images.push(obj);
                },error: function (r){
                    console.log(r);
                }
            });
            return images;
        }else{
            return false;
        }
}
function uploadVimages(id,variations){
        if(variations != ""){
            var images = [];
            $(".productVariationImages").map(function(){
                var varId = $(this).attr("data-variation-id");
                if($(this).val() != ""){

                    var form_data = new FormData();
                    var totalfiles = $(this)[0].files.length;
                    if(totalfiles > 0){
                        for (var index = 0; index < totalfiles; index++) {
                           form_data.append('images[]',$(this)[0].files[index]);
                        }
                        form_data.append('id',id);
                        form_data.append('userType',"merchant");
                        $.ajax({
                            url: '/Vendors/upload-and-crop-image/upload-multiple.php', 
                            type: 'post',
                            data: form_data,
                            dataType: 'json',
                            contentType: false,
                            processData: false,
                            async: false, 
                            success: function (response) {
                                var obj = {
                                    variation: varId,
                                    images:response
                                };
                                images.push(obj);
                            },error: function (r){
                                console.log(r);
                            }
                        });
                    }else{
                        return false
                    }
                }else{
                 return false;   
                }
            });
             return images;
        }else{
            return false;
        }
}
function sendProductRegistration(product){
    $("#overallLoader").show();
    var data = {
        product:JSON.stringify(product)
    };
    $.ajax({
        url:"/merchant/registerProduct",
        type:"post",
        data:data,
        success: function (response) {
            $("body").html(response);
            console.log(response);
        },error: function (r){
            console.log(r);
        }
    });
    $("#overallLoader").hide();
}
