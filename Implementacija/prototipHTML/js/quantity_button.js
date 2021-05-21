/* Luka Tomanovic 0410/2018
   Kosta Matijevic 0034/2018 */

$('.btn-number').click(function(e){
    e.preventDefault();
    
    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {
            
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            } 
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {
    
    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());
    
    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }

    /*ja sam dodao za izmenu iznosa dinamicki*/
    quantity_text=document.getElementById("quantityInputTextField").value;
    quantity=parseInt(quantity_text);
    var p=document.getElementById("pricePerStock");
    var text = p.textContent;
    text_priceps = text.slice(0, -1);
    var stockPrice= parseFloat(text_priceps);
    total=quantity*stockPrice;
    vtotal=parseFloat(total).toFixed(2);
    document.getElementById("totalPrice").innerHTML=vtotal.toString()+"&euro;";
    
    
});
$(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });




function setImageModal(filename,stockPrice) {
    $('#modalStockImage').attr('src', 'images/'+filename);
    $('#quantityminus').attr('disabled', true);
    document.getElementById("quantityInputTextField").value="1";
    quantity=1;
    
    total=quantity*stockPrice;
    vtotal=parseFloat(total).toFixed(2);
    vstockPrice=parseFloat(stockPrice).toFixed(2);
    document.getElementById("pricePerStock").innerHTML=vstockPrice.toString()+"&euro;";
    document.getElementById("totalPrice").innerHTML=vtotal.toString()+"&euro;";
}



function setSellModal(filename,stockPrice,max) {
    $('#modalStockImage').attr('src', 'images/'+filename);
    $('#quantityInputTextField').attr('max', max);
    document.getElementById("quantityInputTextField").value="1";
    quantity=1;

    if(max == 1){
        $('#quantityplus').attr('disabled', true);
        $('#quantityminus').attr('disabled', true);
    }
    else{
        $('#quantityplus').removeAttr('disabled');
        $('#quantityminus').attr('disabled', true);
    }
    total=quantity*stockPrice;
    vtotal=parseFloat(total).toFixed(2);
    vstockPrice=parseFloat(stockPrice).toFixed(2);
    document.getElementById("pricePerStock").innerHTML=vstockPrice.toString()+"&euro;";
    document.getElementById("totalPrice").innerHTML=vtotal.toString()+"&euro;";
}