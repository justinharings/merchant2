$(document).ready(function()
{$("div.loader").fadeOut("fast");$(document).on("keypress",'form',function(e)
{var code=e.keyCode||e.which;if(code==13)
{e.preventDefault();return!1}});if($("div.simple-form").find("div.form-tabs").length>0)
{$("div.form-tabs").find("div:nth-child(2)").addClass("active");$("div.simple-form").find("div.tab").hide().first().show();$("div.simple-form").find("div.form-tabs").find("div").slice(1).on("click",function()
{var index=$(this).index();$("div.simple-form").find("div.form-tabs").find("div").removeClass("active");$(this).addClass("active");$("div.simple-form").find("div.tab").hide();$("div.simple-form").find("div.tab-"+index).show()})}
var url=document.location.href;if($("ul.breadcrumbs").length>0&&url.indexOf("/form-")>0)
{if($("form").find("input[type=hidden]").first().val()!=0&&$("ul.breadcrumbs").find("li").last().html().length<=2)
{var split=url.split("/form-");url=split[0];document.location.href=url}}
$("span.logout-button").on("click",function()
{$(this).removeClass("fa-power-button").addClass("fa-spinner").addClass("fa-spin");$.post("/library/php/posts/authorization/logout.php").done(function(data)
{if(data==1)
{document.location.href="/"}})});$("span.previous-button, input#return").on("click",function()
{if($(this).hasClass("fa-previous-button"))
{$(this).removeClass("fa-previous-button").addClass("fa-spinner").addClass("fa-spin")}
window.history.back()});$("input#search").focus();$("input#search").on("keypress",function(e)
{if(e.which==13)
{var str=document.location.href;var match=str.match("/search/");var url;if(match!=""&&match!=null)
{url=str.split("search/");url=url[0]}
else{url=document.location.href}
if($(this).val()=="")
{document.location.href=url}
else{document.location.href=url+'search/'+$(this).val()+'/'}}});$("span.fa-bars").on("click",function()
{if($("div.menu").css("left")=="0px")
{$("div.menu").removeAttr("style")}
else{$("div.menu").css("left","0px")}});$("ul.breadcrumbs").find("li").slice(1).each(function()
{$(this).prepend('<span class="fa fa-caret-right"></span>')});$("li.menu-item").each(function()
{var str=document.location.href;var match=str.match($(this).attr("rel"));if(match!=""&&match!=null)
{$(this).find("div.icon").addClass("active");$(this).find("div.text").addClass("active");$(this).parent().parent().prev("li").find("div.icon").addClass("active");$(this).parent().parent().prev("li").find("div.text").addClass("active");var offset=$("div.icon.active").first().offset();offset.top-=100;$("div.menu-items-holder").scrollTop(offset.top)}
if($(this).attr("rel")!=""&&typeof $(this).attr("rel")!='undefined')
{$(this).bind("click",function()
{document.location.href="/"+$("input#_language_pack").val()+"/modules"+$(this).attr("rel")})}});var cnt=0;$("table.view").each(function()
{$(this).before('<div class="table-holder n-'+cnt+'"></div>');$(this).appendTo($("div.table-holder.n-"+cnt));cnt++});$("table.view").find("tbody").find("tr").each(function()
{if($(this).attr("click")!=""&&typeof $(this).attr("click")!=="undefined")
{var link=$(this).attr("click");$(this).bind("click",function()
{document.location.href=link})}});$("div.button").each(function()
{if($(this).attr("click")!=""&&typeof $(this).attr("click")!=="undefined")
{var link=$(this).attr("click");$(this).bind("click",function()
{document.location.href=link})}});var cnt=0;$("span.add-row").on("click",function()
{cnt+=1;console.log(cnt);var tbody=$(this).closest("table").find("tbody");var clone=tbody.find("tr.new-row").clone();clone.find("input, select").each(function()
{var str=$(this).attr("id");if(str.indexOf("_+")>=0)
{var replaced=str.replace("_+","_"+cnt)
$(this).attr("id",replaced)}});clone.appendTo(tbody).removeClass("new-row").show();$("input, select, textarea").each(function()
{if($(this).parents('tr.new-row').length)
{return}
systemChanges($(this))});checkboxHandler();findProduct()});$("span.remove-row").on("click",function()
{if($(this).attr("post")!="")
{var msg="Are you sure mate?";if(confirm(msg))
{document.location.href=$(this).attr("post")}}});$(document).on("keypress",'input.product-search',function(e)
{var code=e.keyCode||e.which;var elm=$(this);if(code==13)
{e.preventDefault();elm.prev("span.fa").removeClass("fa-search").addClass("fa-circle-o-notch").addClass("fa-spin");$.post("/library/php/posts/catalogus/return_product.php",{article_code:elm.val()}).done(function(data)
{if(data=="null")
{elm.val("");elm.css("border","1px solid #d00000");elm.prev("span.fa").addClass("fa-search").removeClass("fa-circle-o-notch").removeClass("fa-spin")}
else{data=$.parseJSON(data);var tr=elm.parent().parent().parent();var productID=data.productID;productID='<input type="hidden" name="productID[]" id="productID" value="'+productID+'" />';tr.find("td.searched-p-name").html(data.name);tr.find("td.searched-p-price").html("&euro;&nbsp;"+data.price);tr.find("td.searched-p-productID").html(productID)}})}})})