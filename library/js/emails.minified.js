$(document).ready(function($)
{if($("div.tab.js-load-emails").length>=0&&$("div.tab.js-load-emails").attr("e-mail-customer-id")!='')
{loadEmails($("div.tab.js-load-emails").attr("e-mail-customer-id"))}
$("select.email-template-choice").on("change",function()
{var elm=$(this);if(elm.val()>0)
{$.post("/library/php/posts/mailserver/load_template.php",{templateID:elm.val()}).done(function(data)
{data=$.parseJSON(data);$("input#email_sender").val(data.sender);$("input#email_subject").val(data.subject);$("textarea#email_content").val(data.content)})}})});function sendEmail()
{$("input#email_customerID, input#email_sender, input#email_receiver, input#email_subject, textarea#email_content").css("border-color","");var valid=!0;if($("input#email_customerID").val()=="")
{$("input#email_customerID").css("border-color","#d00000");valid=!1}
if($("input#email_sender").val()=="")
{$("input#email_sender").css("border-color","#d00000");valid=!1}
if($("input#email_receiver").val()=="")
{$("input#email_receiver").css("border-color","#d00000");valid=!1}
if($("input#email_subject").val()=="")
{$("input#email_subject").css("border-color","#d00000");valid=!1}
if($("textarea#email_content").val()=="")
{$("textarea#email_content").css("border-color","#d00000");valid=!1}
if(valid==!0)
{setTimeout(function()
{$.post("/library/php/posts/mailserver/send.php",{customerID:$("div.tab.js-load-emails").attr("e-mail-customer-id"),orderID:$("input#email_orderID").val(),sender:$("input#email_sender").val(),receiver:$("input#email_receiver").val(),subject:$("input#email_subject").val(),content:$("textarea#email_content").val(),attachment:$("select#email_attachment").val()}).done(function(data)
{loadEmails($("input#email_customerID").val())})},1000);setTimeout(function()
{$("input#email_sender, input#email_subject, textarea#email_content, select#email_template, select#email_attachment").val("");$("input#send_email").val($("input#send_email").attr("original")).removeClass("no-action");$('div.content').animate({scrollTop:200},1000)},1500)}}
function loadEmails(customerID)
{$("div.loaded-email").remove();$.post("/library/php/posts/klanten/load_emails.php",{customerID:$("div.tab.js-load-emails").attr("e-mail-customer-id")}).done(function(data)
{data=$.parseJSON(data);for(var i=0;i<data.length;i++)
{var div=$("<div/>").addClass("form-content").addClass("loaded-email").addClass("blue").html(data[i].content).hide().appendTo("div.tab.js-load-emails").fadeIn("fast");var attachment="";if(data[i].attachment==1)
{attachment="&nbsp;&nbsp;<span class=\"fa fa-paperclip\"></span>"}
var html=data[i].date_added+"&nbsp;&nbsp;-&nbsp;&nbsp;"+data[i].receiver+attachment;$("<div/>").addClass("content-header").html(html).prependTo(div)}})}