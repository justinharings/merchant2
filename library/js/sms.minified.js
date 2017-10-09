$(document).ready(function($)
{if($("div.tab.js-load-sms").length>=0&&$("div.tab.js-load-sms").attr("sms-customer-id")!='')
{loadSms($("div.tab.js-load-sms").attr("sms-customer-id"))}
$("select.sms-template-choice").on("change",function()
{var elm=$(this);if(elm.val()>0)
{$.post("/library/php/posts/mailserver/load_template_sms.php",{templateID:elm.val()}).done(function(data)
{data=$.parseJSON(data);$("textarea#sms_content").html(data.content)})}})});function sendSms()
{$("input#sms_receiver, textarea#sms_content").css("border-color","");var valid=!0;if($("input#sms_receiver").val()=="")
{$("input#sms_receiver").css("border-color","#d00000");valid=!1}
if($("textarea#sms_content").val()=="")
{$("textarea#email_content").css("border-color","#d00000");valid=!1}
if(valid==!0)
{setTimeout(function()
{$.post("/library/php/posts/mailserver/send_sms.php",{receiver:$("input#sms_receiver").val(),content:$("textarea#sms_content").val(),customerID:$("div.tab.js-load-sms").attr("sms-customer-id"),workorderID:0,orderID:$("input#orderID").val()}).done(function(data)
{loadSms($("div.tab.js-load-sms").attr("sms-customer-id"))})},1000);setTimeout(function()
{$("textarea#sms_content, select#sms_template").val("");$("input#send_sms").val($("input#send_sms").attr("original")).removeClass("no-action");$('div.content').animate({scrollTop:200},1000)},1500)}}
function loadSms(customerID)
{$("div.loaded-sms").remove();$.post("/library/php/posts/klanten/load_sms.php",{customerID:$("div.tab.js-load-sms").attr("sms-customer-id")}).done(function(data)
{data=$.parseJSON(data);for(var i=0;i<data.length;i++)
{var div=$("<div/>").addClass("form-content").addClass("loaded-sms").addClass("blue").html(data[i].content).hide().appendTo("div.tab.js-load-sms").fadeIn("fast");var html=data[i].date_added;$("<div/>").addClass("content-header").html(html).prependTo(div)}})}