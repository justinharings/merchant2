$(document).ready(function($)
{$(".progress").each(function()
{var width=parseFloat($(this).attr("percentage"));var elm=$(this);if(width>100)
{width=100}
else if(width<0)
{width=0}
$(this).animate({width:width+"%"},2000,function()
{if(width<=3)
{setInterval(function()
{elm.animate({width:"5%"},500,function()
{elm.animate({width:width+"%"},500)})},1000)}})});$('.animate-number').each(function()
{var value=$(this).text();value=value.replace(",",".");var str=$(this).text();if(str.indexOf(",")>-1)
{var decimals=$(this).text();decimals=decimals.slice(-2)}
var elm=$(this);$(this).prop('Counter',0).animate({Counter:value},{duration:2000,easing:'swing',step:function(now)
{var new_value=Math.ceil(now);$(this).text(new_value);if(decimals>0)
{elm.text($(this).text()+",00")}},done:function()
{if(decimals>0)
{var str=$(this).text();str=str.substring(0,str.length-3);str=str+","+decimals;elm.text(str)}}})})});$(function()
{try
{var months=$("input#totalVisitorsMonthlyKeys").val();months=months.split(",");var unique=$("input#totalVisitorsMonthlyValues").val();unique=unique.split("|");var mySeries=new Array();for(var i=0;i<unique.length;i++)
{mySeries.push(parseFloat(unique[i]))}
unique=mySeries;var hits=$("input#totalVisitorHitsMonthlyValues").val();hits=hits.split("|");var mySeries=new Array();for(var i=0;i<hits.length;i++)
{mySeries.push(parseFloat(hits[i]))}
hits=mySeries;$('div.visitors-line-chart').highcharts({chart:{type:'line'},title:{text:''},xAxis:{categories:months,gridLineWidth:0,min:0.5,max:7.5},yAxis:{title:{text:''},labels:{enabled:!1},gridLineWidth:0,minorGridLineWidth:0},tooltip:{enabled:!1},legend:{enabled:!0},credits:{enabled:!1},plotOptions:{series:{marker:{enabled:!1}}},series:[{name:'Unieke bezoekers',data:unique,color:'#21252d',cursor:'default',enableMouseTracking:!1,fillOpacity:1},{name:'Pagina hits',data:hits,color:'#d00000',cursor:'default',enableMouseTracking:!1,fillOpacity:1}]});var months=$("input#salesMonthlyKeys").val();months=months.split(",");var d=new Date();var thisYearPrint=d.getFullYear();var thisYear=$("input#salesThisYear").val();thisYear=thisYear.split("|");var mySeries=new Array();for(var i=0;i<thisYear.length;i++)
{mySeries.push(parseFloat(thisYear[i]))}
thisYear=mySeries;var oneYear=$("input#salesOneYear").val();oneYear=oneYear.split("|");var mySeries=new Array();for(var i=0;i<oneYear.length;i++)
{mySeries.push(parseFloat(oneYear[i]))}
oneYear=mySeries;var twoYears=$("input#salesTwoYears").val();twoYears=twoYears.split("|");var mySeries=new Array();for(var i=0;i<twoYears.length;i++)
{mySeries.push(parseFloat(twoYears[i]))}
twoYears=mySeries;$('div.sales-line-chart').highcharts({chart:{type:'line'},title:{text:''},xAxis:{categories:months,gridLineWidth:0,min:0,max:11},yAxis:{title:{text:''},labels:{enabled:!1},gridLineWidth:0,minorGridLineWidth:0},tooltip:{enabled:!1},legend:{enabled:!0},credits:{enabled:!1},plotOptions:{series:{marker:{enabled:!1}}},series:[{name:(thisYearPrint-2),data:twoYears,color:'#21252d',cursor:'default',enableMouseTracking:!1,fillOpacity:1},{name:(thisYearPrint-1),data:oneYear,color:'#a6bbe8',cursor:'default',enableMouseTracking:!1,fillOpacity:1},{name:thisYearPrint,data:thisYear,color:'#d00000',cursor:'default',enableMouseTracking:!1,fillOpacity:1}]})}
catch(err)
{}})