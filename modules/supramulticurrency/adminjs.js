
$(document).on('change',"select#sbm_id_currency", function(){
    var selected = $("option:selected", this).attr("sign");
    $("div#product-prices").find(".input-group-addon").text(selected);
    $("div#product-prices").find("#selected_currency_sign").text(selected);
});


$( document ).ready(function() {
    $("#link-Prices").click(function(){
        if($('select#sbm_id_currency').length){
            ;
        }
        else {
            if($("div#product-prices").length){
                $("div#product-prices").prepend(form_data);           
                setStartUpValues();
                return;
            }
            else { 
                console.log("That was caused by a slow page loading. lets give it change to load it");
                callPricesTabAgain();
            }
       }
         
    });
});

function callPricesTabAgain(){
    setTimeout(function(){
      $("#link-Prices").trigger("click");
   },800);
  
}


function setStartUpValues() {
    if(typeof currency_sign === "undefined")
        return false;
    $("div#product-prices").find(".input-group-addon").text(currency_sign);
    $("div#product-prices").find("#selected_currency_sign").text(currency_sign);
    $("input#wholesale_price").val(product_sale_price);
    $("input#priceTEReal").val(product_price);
    $("input#priceTE").val(product_price);
    $("input#priceTE").trigger("keyup");   
    return;
}

