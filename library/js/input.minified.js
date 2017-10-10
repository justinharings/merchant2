$(document).ready(function()
{$("input#delete-item").on("click",function()
{var elm=$(this);setTimeout(function()
{var msg="Are you sure mate?";if(confirm(msg))
{var input='<input type="hidden" name="delete" id="delete" value="1" />';elm.closest("form").append(input);elm.closest("form").submit()}
else{setTimeout(function()
{elm.val(elm.attr("original")).removeClass("no-action")},100)}},500)});$(document).on("keyup",".calc-main-price, .calc-main-quantity",function(e)
{var unique_id=$(this).closest("tr").attr("id");var quantity=$("input#quantity_"+unique_id);var price=$("input#price_"+unique_id);var total=$("input#total_"+unique_id);var price_ex=$("input#excl_"+unique_id);var taxrate=$("input#taxrate_"+unique_id);var val=$(this).val();if(val=="")
{$(this).val(1)}
else{val=val.replace(",",".");$(this).val(val)}
var calced=(quantity.val()*price.val());calced=calced.toFixed(2)
total.val(calced);calced=(taxrate.val()/100)+1;calced=(price.val()/calced);calced=calced.toFixed(2)
price_ex.val(calced)});$("select#authorization").find("option").on("click",function(e)
{if($(this).attr("selected")=="selected")
{$(this).removeAttr("selected")}
else{$(this).attr("selected","selected")}
var elms=$("select#authorization").find("option:selected");setTimeout(function()
{elms.each(function()
{$(this).attr("selected","selected")})},100)});$("input, select, textarea").each(function()
{if($(this).parents('tr.new-row').length)
{return}
systemChanges($(this))});checkboxHandler();$(".show-load").on("click",function()
{$(this).attr("original",$(this).val()).val("Bezig...").addClass("no-action")});$(".no-action").on("click",function(event)
{event.preventDefault()});function isFloatOrInteger(n)
{n=n.replace(",",".");if((parseInt(n)==n)||(parseFloat(n)==n))
{return!0}
return!1}
$(".validate-form").on("click",function(event)
{event.preventDefault();var valid=!0;$(this).closest("form").find("input, select, textarea").css("border","").each(function()
{if($(this).attr("id")=="workorder_unique_code"&&$(this).val()==1)
{var check=$("#key_number").val();var current=$("#key_number_current").val();$.post("/library/php/posts/werkorders/unique_code.php",{check:check,current:current}).done(function(data)
{if(data>0)
{$("#key_number").css("border-color","#d00000");valid=!1}})}
if($(this).attr("validation-type")=="int"&&$.parseJSON($(this).attr("validation-required"))==!1&&$(this).val()=="")
{$(this).val(0)}
if($(this).parents('tr.new-row').length)
{return}
if($(this).attr("validation-type")=="image"&&$(this).val()==""&&($(this).attr("validation-required")!=""&&$(this).attr("validation-required")!==undefined))
{if($.parseJSON($(this).attr("validation-required"))==!0)
{$(this).css("border-color","#d00000");valid=!1}}
if($(this).attr("validation-type")=="text"&&$(this).val()==""&&$.parseJSON($(this).attr("validation-required"))==!0)
{$(this).css("border-color","#d00000");valid=!1}
if($(this).attr("validation-type")=="int"&&!isFloatOrInteger($(this).val()))
{$(this).css("border-color","#d00000");valid=!1}
if(typeof $(this).attr("unique-article")!=="undefined")
{if($(this).val()!=$(this).attr("unique-article"))
{var elm=$(this);$.post("/library/php/posts/catalogus/unique_plu.php",{article_code:elm.val()}).done(function(data)
{data=$.parseJSON(data);if(data[0]!="")
{elm.css("border-color","#d00000");valid=!1}})}}
var re=/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;if($(this).attr("validation-type")=="email"&&re.test($(this).val())==!1)
{if($(this).val()==""&&($(this).attr("validation-required")!=""&&$(this).attr("validation-required")!==undefined))
{if($.parseJSON($(this).attr("validation-required"))==!0)
{$(this).css("border-color","#d00000");valid=!1}}
else if($(this).val()!="")
{$(this).css("border-color","#d00000");valid=!1}}
if($(this).attr("validation-type")=="password")
{var elm=$(this);var elm2=$("input#"+elm.attr("id")+"_repeat");if(!elm2.length)
{console.log("Second password field missing.");document.location.href="/"+$("input#_language_pack").val()+"/modules/errors/503/";valid=!1}
var first=elm.val();var second=elm2.val();if(elm.attr("new-password")==0)
{if(first==""&&second=="")
{return}}
if(first=="")
{elm.css("border-color","#d00000");valid=!1}
if(second=="")
{elm2.css("border-color","#d00000");valid=!1}
if(first!=""&&second!="")
{if(first!=second)
{elm2.css("border-color","#d00000");valid=!1}
else{re=/^\w+$/;if(!re.test(first))
{elm.css("border-color","#d00000");valid=!1}
if(first.length<5)
{elm.css("border-color","#d00000");valid=!1}}}}
if($(this).attr("validation-type")=="image"&&$(this).val()!="")
{var elm=$(this);var max_width=elm.attr("image-width");var max_height=elm.attr("image-height");var filter_ext=elm.attr("image-extension");var input=document.getElementById(elm.attr("id"));var file=input.files[0];var img=new Image();var re=/(?:\.([^.]+))?$/;var extension=elm.val();extension=re.exec(extension)[1];if((filter_ext!=""&&filter_ext!==undefined)&&filter_ext!=extension)
{elm.css("border-color","#d00000");valid=!1}
img.onload=function()
{var sizes={width:this.width,height:this.height};if(this.width>max_width||this.height>max_height)
{elm.css("border-color","#d00000");valid=!1}
URL.revokeObjectURL(this.src)}
var objectURL=URL.createObjectURL(file);img.src=objectURL}
if($(this).attr("max-characters")!==undefined&&$(this).val()!="")
{var max=parseInt($(this).attr("max-characters"));if(isNaN(max))
{$(this).css("border-color","#d00000");valid=!1}
else{if($(this).val().length>max)
{$(this).css("border-color","#d00000");valid=!1}}}});setTimeout(function()
{if(valid==!1)
{$(".validate-form").val($(".validate-form").attr("original")).removeClass("no-action")}
else{$(".validate-form").closest("form").find('input[type=checkbox]').each(function()
{$(this).attr("value",$(this).is(":checked")?"1":"0");$(this).attr("checked","checked")});$(".validate-form").closest("form").submit()}},500)})});function checkboxHandler()
{$("div.checkbox-holder").unbind("click");$("div.checkbox-holder").on("click",function()
{var disabled=$(this).next("input[type='checkbox']").first().attr("disabled");var checked=$(this).next("input[type='checkbox']").first().attr("checked");if(typeof disabled===typeof undefined)
{if(typeof checked!==typeof undefined)
{$(this).next("input[type='checkbox']").first().removeAttr("checked");$(this).removeClass("active");$(this).find("div.slider").removeClass("right").addClass("left");$(this).find("div.text").html("OFF")}
else{var name=$(this).next("input[type='checkbox']").first().attr("name");var id=$(this).next("input[type='checkbox']").first().attr("id");if($("*[name*='thumb[]']").length>1)
{$("*[name*='thumb[]']").each(function()
{if($(this).attr("id")!=id)
{$(this).prev("div.checkbox-holder.active").trigger("click")}})}
$(this).next("input[type='checkbox']").first().attr("checked","checked");$(this).addClass("active");$(this).find("div.slider").removeClass("left").addClass("right");$(this).find("div.text").html("ON")}}})}
function systemChanges(elm)
{if(elm.attr("system-changes")!=""&&elm.attr("system-changes")!==undefined)
{return!1}
if(elm.is('[holder]'))
{$('<span class="input-holder">'+elm.attr("holder")+'</span>').insertBefore(elm)}
if(elm.is('[holder-eg]'))
{elm.prev("span").append('<span>'+elm.attr("holder-eg")+'</span>')}
if(elm.is('[icon]'))
{$('<div class="icon-input-holder"></div>').insertBefore(elm).append('<span class="icon fa '+elm.attr("icon")+'"></span>').append(elm).css("width",elm.css("width"))}
if(elm.is('[icon-img]'))
{$('<div class="icon-input-holder large-padding"></div>').insertBefore(elm).append('<img src="'+elm.attr("icon-img")+'" />').append(elm).css("width",elm.css("width"))}
if(elm.attr("type")=="checkbox")
{var floating="left";var active="";var text="OFF";var margin="";var text_elm=!0;if(elm.attr("checked")=="checked")
{floating="right";active="active";text="ON"}
if(elm.hasClass("margin"))
{margin="margin"}
if(elm.hasClass("double-margin"))
{margin="double-margin"}
if(elm.hasClass("no-text"))
{text_elm=!1}
if(text_elm==!0)
{text_elm='<div class="text">'+text+'</div>'}
else{text_elm=""}
$('<div class="checkbox-holder '+active+' '+margin+'"><div class="slider '+floating+'"></div>'+text_elm+'</div>').insertBefore(elm);elm.hide()}
if(elm.hasClass("select-option"))
{elm.hide();if(elm.prev().hasClass("input-holder"))
{elm.prev().hide()}}
setTimeout(function()
{$(".datepicker").datepicker({dateFormat:"dd-mm-yy"});if(elm.find("option[activate]").length>0)
{elm.bind("change",function()
{$(".select-option").each(function()
{$(this).hide();if($(this).prev().hasClass("input-holder"))
{$(this).prev().hide()}});if(elm.val()!="")
{var str=$(this).find("option:selected").attr("activate");var res=str.split(",");res.forEach(function(entry)
{$("#"+entry).show();if($("#"+entry).prev().first().hasClass("input-holder"))
{$("#"+entry).prev().first().show()}})}})}
if(elm.is("select"))
{elm.trigger("change")}},100);elm.attr("system-changes","1")}
function findProduct()
{var productID=$("input[name='find-product']");productID.bind("keypress",function(e)
{if(e.keyCode==13)
{var elm=$(this);elm.prev("span.fa").removeClass("fa-chain").addClass("fa-circle-o-notch").addClass("fa-spin");$.post("/library/php/posts/catalogus/return_product.php",{productID:elm.val()}).done(function(data)
{data=$.parseJSON(data);if(data==null)
{elm.css("border-color","#d00000")}
elm.prev("span.fa").removeClass("fa-circle-o-notch").removeClass("fa-spin").addClass("fa-chain")})}})}