/**
 * Version: 1.0 Alpha-1 
 * Build Date: 13-Nov-2007
 * Copyright (c) 2006-2007, Coolite Inc. (http://www.coolite.com/). All rights reserved.
 * License: Licensed under The MIT License. See license.txt and http://www.datejs.com/license/. 
 * Website: http://www.datejs.com/ or http://www.coolite.com/datejs/
 */
TimeSpan=function(days,hours,minutes,seconds,milliseconds){this.days=0;this.hours=0;this.minutes=0;this.seconds=0;this.milliseconds=0;if(arguments.length==5){this.days=days;this.hours=hours;this.minutes=minutes;this.seconds=seconds;this.milliseconds=milliseconds;}
else if(arguments.length==1&&typeof days=="number"){var orient=(days<0)?-1:+1;this.milliseconds=Math.abs(days);this.days=Math.floor(this.milliseconds/(24*60*60*1000))*orient;this.milliseconds=this.milliseconds%(24*60*60*1000);this.hours=Math.floor(this.milliseconds/(60*60*1000))*orient;this.milliseconds=this.milliseconds%(60*60*1000);this.minutes=Math.floor(this.milliseconds/(60*1000))*orient;this.milliseconds=this.milliseconds%(60*1000);this.seconds=Math.floor(this.milliseconds/1000)*orient;this.milliseconds=this.milliseconds%1000;this.milliseconds=this.milliseconds*orient;return this;}
else{return null;}};TimeSpan.prototype.compare=function(timeSpan){var t1=new Date(1970,1,1,this.hours(),this.minutes(),this.seconds()),t2;if(timeSpan===null){t2=new Date(1970,1,1,0,0,0);}
else{t2=new Date(1970,1,1,timeSpan.hours(),timeSpan.minutes(),timeSpan.seconds());}
return(t1>t2)?1:(t1<t2)?-1:0;};TimeSpan.prototype.add=function(timeSpan){return(timeSpan===null)?this:this.addSeconds(timeSpan.getTotalMilliseconds()/1000);};TimeSpan.prototype.subtract=function(timeSpan){return(timeSpan===null)?this:this.addSeconds(-timeSpan.getTotalMilliseconds()/1000);};TimeSpan.prototype.addDays=function(n){return new TimeSpan(this.getTotalMilliseconds()+(n*24*60*60*1000));};TimeSpan.prototype.addHours=function(n){return new TimeSpan(this.getTotalMilliseconds()+(n*60*60*1000));};TimeSpan.prototype.addMinutes=function(n){return new TimeSpan(this.getTotalMilliseconds()+(n*60*1000));};TimeSpan.prototype.addSeconds=function(n){return new TimeSpan(this.getTotalMilliseconds()+(n*1000));};TimeSpan.prototype.addMilliseconds=function(n){return new TimeSpan(this.getTotalMilliseconds()+n);};TimeSpan.prototype.getTotalMilliseconds=function(){return(this.days()*(24*60*60*1000))+(this.hours()*(60*60*1000))+(this.minutes()*(60*1000))+(this.seconds()*(1000));};TimeSpan.prototype.get12HourHour=function(){return((h=this.hours()%12)?h:12);};TimeSpan.prototype.getDesignator=function(){return(this.hours()<12)?Date.CultureInfo.amDesignator:Date.CultureInfo.pmDesignator;};TimeSpan.prototype.toString=function(format){function _toString(){if(this.days()!==null&&this.days()>0){return this.days()+"."+this.hours()+":"+p(this.minutes())+":"+p(this.seconds());}
else{return this.hours()+":"+p(this.minutes())+":"+p(this.seconds());}}
function p(s){return(s.toString().length<2)?"0"+s:s;}
var self=this;return format?format.replace(/d|dd|HH|H|hh|h|mm|m|ss|s|tt|t/g,function(format){switch(format){case"d":return self.days();case"dd":return p(self.days());case"H":return self.hours();case"HH":return p(self.hours());case"h":return self.get12HourHour();case"hh":return p(self.get12HourHour());case"m":return self.minutes();case"mm":return p(self.minutes());case"s":return self.seconds();case"ss":return p(self.seconds());case"t":return((this.hours()<12)?Date.CultureInfo.amDesignator:Date.CultureInfo.pmDesignator).substring(0,1);case"tt":return(this.hours()<12)?Date.CultureInfo.amDesignator:Date.CultureInfo.pmDesignator;}}):this._toString();};var TimePeriod=function(years,months,days,hours,minutes,seconds,milliseconds){this.years=0;this.months=0;this.days=0;this.hours=0;this.minutes=0;this.seconds=0;this.milliseconds=0;if(arguments.length==2&&arguments[0]instanceof Date&&arguments[1]instanceof Date){var date1=years.clone();var date2=months.clone();var temp=date1.clone();var orient=(date1>date2)?-1:+1;this.years=date2.getFullYear()-date1.getFullYear();temp.addYears(this.years);if(orient==+1){if(temp>date2){if(this.years!==0){this.years--;}}}else{if(temp<date2){if(this.years!==0){this.years++;}}}
date1.addYears(this.years);if(orient==+1){while(date1<date2&&date1.clone().addDays(date1.getDaysInMonth())<date2){date1.addMonths(1);this.months++;}}
else{while(date1>date2&&date1.clone().addDays(-date1.getDaysInMonth())>date2){date1.addMonths(-1);this.months--;}}
var diff=date2-date1;if(diff!==0){var ts=new TimeSpan(diff);this.days=ts.days;this.hours=ts.hours;this.minutes=ts.minutes;this.seconds=ts.seconds;this.milliseconds=ts.milliseconds;}
return this;}};

/*! timer.jquery 0.4.2 2015-08-04*/!function(a){function b(){p=setInterval(d,v.updateFrequency),t=!0}function c(){clearInterval(p),t=!1}function d(){s=g()-q,e(),u&&s%u===0&&(v.callback(),v.repeat||(u=v.duration=null),v.countdown&&(v.countdown=!1))}function e(){var a=s;v.countdown&&u>0&&(a=u-s),r[w](i(a)),r.data("seconds",a)}function f(){r.on("focus",function(){l()}),r.on("blur",function(){var a,b=r[w]();b.indexOf("sec")>0?s=Number(b.replace(/\ssec/g,"")):b.indexOf("min")>0?(b=b.replace(/\smin/g,""),a=b.split(":"),s=Number(60*a[0])+Number(a[1])):b.match(/\d{1,2}:\d{2}:\d{2}/)&&(a=b.split(":"),s=Number(3600*a[0])+Number(60*a[1])+Number(a[2])),m()})}function g(){return Math.round((new Date).getTime()/1e3)}function h(a){var b,c=0,d=Math.floor(a/60);return a>=3600&&(c=Math.floor(a/3600)),a>=3600&&(d=Math.floor(a%3600/60)),10>d&&c>0&&(d="0"+d),b=a%60,10>b&&(d>0||c>0)&&(b="0"+b),{hours:c,minutes:d,seconds:b}}function i(a){var b="",c=h(a);if(v.format){var d=[{identifier:"%h",value:c.hours,pad:!1},{identifier:"%m",value:c.minutes,pad:!1},{identifier:"%s",value:c.seconds,pad:!1},{identifier:"%H",value:parseInt(c.hours),pad:!0},{identifier:"%M",value:parseInt(c.minutes),pad:!0},{identifier:"%S",value:parseInt(c.seconds),pad:!0}];b=v.format,d.forEach(function(a){b=b.replace(new RegExp(a.identifier.replace(/([.*+?^=!:${}()|\[\]\/\\])/g,"\\$1"),"g"),a.pad&&a.value<10?"0"+a.value:a.value)})}else b=c.hours?c.hours+":"+c.minutes+":"+c.seconds:c.minutes?c.minutes+":"+c.seconds+" min":c.seconds+" sec";return b}function j(a){if(!isNaN(Number(a)))return a;var b=a.match(/\d{1,2}h/),c=a.match(/\d{1,2}m/),d=a.match(/\d{1,2}s/),e=0;return a=a.toLowerCase(),b&&(e+=3600*Number(b[0].replace("h",""))),c&&(e+=60*Number(c[0].replace("m",""))),d&&(e+=Number(d[0].replace("s",""))),e}function k(){t||(e(),b(),r.data("state",y))}function l(){t&&(c(),r.data("state",z))}function m(){t||(q=g()-s,b(),r.data("state",y))}function n(){q=g(),s=0,r.data("seconds",s),r.data("state",x),u=v.duration}function o(){c(),r.data("plugin_"+B,null),r.data("seconds",null),r.data("state",null),r[w]("")}var p,q,r,s=0,t=!1,u=null,v={seconds:0,editable:!1,restart:!1,duration:null,callback:function(){alert("Time up!"),c()},repeat:!1,countdown:!1,format:null,updateFrequency:1e3},w="html",x="stopped",y="running",z="paused",A=function(b,c){var d;v=a.extend(v,c),r=a(b),s=v.seconds,q=g()-s,r.data("seconds",s),r.data("state",x),d=r.prop("tagName").toLowerCase(),("input"===d||"textarea"===d)&&(w="val"),v.duration&&(u=v.duration=j(v.duration)),v.editable&&f()};A.prototype={start:function(){k()},pause:function(){l()},resume:function(){m()},reset:function(){n()},remove:function(){o()}};var B="timer";a.fn[B]=function(b){return b=b||"start",this.each(function(){a.data(this,"plugin_"+B)instanceof A||a.data(this,"plugin_"+B,new A(this,b));var c=a.data(this,"plugin_"+B);"string"==typeof b&&"function"==typeof c[b]&&c[b].call(c),"object"==typeof b&&c.start.call(c)})}}(jQuery);
/* Tooltipster v3.2.6 */;(function(e,t,n){function s(t,n){this.bodyOverflowX;this.callbacks={hide:[],show:[]};this.checkInterval=null;this.Content;this.$el=e(t);this.$elProxy;this.elProxyPosition;this.enabled=true;this.options=e.extend({},i,n);this.mouseIsOverProxy=false;this.namespace="tooltipster-"+Math.round(Math.random()*1e5);this.Status="hidden";this.timerHide=null;this.timerShow=null;this.$tooltip;this.options.iconTheme=this.options.iconTheme.replace(".","");this.options.theme=this.options.theme.replace(".","");this._init()}function o(t,n){var r=true;e.each(t,function(e,i){if(typeof n[e]==="undefined"||t[e]!==n[e]){r=false;return false}});return r}function f(){return!a&&u}function l(){var e=n.body||n.documentElement,t=e.style,r="transition";if(typeof t[r]=="string"){return true}v=["Moz","Webkit","Khtml","O","ms"],r=r.charAt(0).toUpperCase()+r.substr(1);for(var i=0;i<v.length;i++){if(typeof t[v[i]+r]=="string"){return true}}return false}var r="tooltipster",i={animation:"fade",arrow:true,arrowColor:"",autoClose:true,content:null,contentAsHTML:false,contentCloning:true,debug:true,delay:200,minWidth:0,maxWidth:null,functionInit:function(e,t){},functionBefore:function(e,t){t()},functionReady:function(e,t){},functionAfter:function(e){},icon:"(?)",iconCloning:true,iconDesktop:false,iconTouch:false,iconTheme:"tooltipster-icon",interactive:false,interactiveTolerance:350,multiple:false,offsetX:0,offsetY:0,onlyOne:false,position:"top",positionTracker:false,speed:350,timer:0,theme:"tooltipster-default",touchDevices:true,trigger:"hover",updateAnimation:true};s.prototype={_init:function(){var t=this;if(n.querySelector){if(t.options.content!==null){t._content_set(t.options.content)}else{var r=t.$el.attr("title");if(typeof r==="undefined")r=null;t._content_set(r)}var i=t.options.functionInit.call(t.$el,t.$el,t.Content);if(typeof i!=="undefined")t._content_set(i);t.$el.removeAttr("title").addClass("tooltipstered");if(!u&&t.options.iconDesktop||u&&t.options.iconTouch){if(typeof t.options.icon==="string"){t.$elProxy=e('<span class="'+t.options.iconTheme+'"></span>');t.$elProxy.text(t.options.icon)}else{if(t.options.iconCloning)t.$elProxy=t.options.icon.clone(true);else t.$elProxy=t.options.icon}t.$elProxy.insertAfter(t.$el)}else{t.$elProxy=t.$el}if(t.options.trigger=="hover"){t.$elProxy.on("mouseenter."+t.namespace,function(){if(!f()||t.options.touchDevices){t.mouseIsOverProxy=true;t._show()}}).on("mouseleave."+t.namespace,function(){if(!f()||t.options.touchDevices){t.mouseIsOverProxy=false}});if(u&&t.options.touchDevices){t.$elProxy.on("touchstart."+t.namespace,function(){t._showNow()})}}else if(t.options.trigger=="click"){t.$elProxy.on("click."+t.namespace,function(){if(!f()||t.options.touchDevices){t._show()}})}}},_show:function(){var e=this;if(e.Status!="shown"&&e.Status!="appearing"){if(e.options.delay){e.timerShow=setTimeout(function(){if(e.options.trigger=="click"||e.options.trigger=="hover"&&e.mouseIsOverProxy){e._showNow()}},e.options.delay)}else e._showNow()}},_showNow:function(n){var r=this;r.options.functionBefore.call(r.$el,r.$el,function(){if(r.enabled&&r.Content!==null){if(n)r.callbacks.show.push(n);r.callbacks.hide=[];clearTimeout(r.timerShow);r.timerShow=null;clearTimeout(r.timerHide);r.timerHide=null;if(r.options.onlyOne){e(".tooltipstered").not(r.$el).each(function(t,n){var r=e(n),i=r.data("tooltipster-ns");e.each(i,function(e,t){var n=r.data(t),i=n.status(),s=n.option("autoClose");if(i!=="hidden"&&i!=="disappearing"&&s){n.hide()}})})}var i=function(){r.Status="shown";e.each(r.callbacks.show,function(e,t){t.call(r.$el)});r.callbacks.show=[]};if(r.Status!=="hidden"){var s=0;if(r.Status==="disappearing"){r.Status="appearing";if(l()){r.$tooltip.clearQueue().removeClass("tooltipster-dying").addClass("tooltipster-"+r.options.animation+"-show");if(r.options.speed>0)r.$tooltip.delay(r.options.speed);r.$tooltip.queue(i)}else{r.$tooltip.stop().fadeIn(i)}}else if(r.Status==="shown"){i()}}else{r.Status="appearing";var s=r.options.speed;r.bodyOverflowX=e("body").css("overflow-x");e("body").css("overflow-x","hidden");var o="tooltipster-"+r.options.animation,a="-webkit-transition-duration: "+r.options.speed+"ms; -webkit-animation-duration: "+r.options.speed+"ms; -moz-transition-duration: "+r.options.speed+"ms; -moz-animation-duration: "+r.options.speed+"ms; -o-transition-duration: "+r.options.speed+"ms; -o-animation-duration: "+r.options.speed+"ms; -ms-transition-duration: "+r.options.speed+"ms; -ms-animation-duration: "+r.options.speed+"ms; transition-duration: "+r.options.speed+"ms; animation-duration: "+r.options.speed+"ms;",f=r.options.minWidth?"min-width:"+Math.round(r.options.minWidth)+"px;":"",c=r.options.maxWidth?"max-width:"+Math.round(r.options.maxWidth)+"px;":"",h=r.options.interactive?"pointer-events: auto;":"";r.$tooltip=e('<div class="tooltipster-base '+r.options.theme+'" style="'+f+" "+c+" "+h+" "+a+'"><div class="tooltipster-content"></div></div>');if(l())r.$tooltip.addClass(o);r._content_insert();r.$tooltip.appendTo("body");r.reposition();r.options.functionReady.call(r.$el,r.$el,r.$tooltip);if(l()){r.$tooltip.addClass(o+"-show");if(r.options.speed>0)r.$tooltip.delay(r.options.speed);r.$tooltip.queue(i)}else{r.$tooltip.css("display","none").fadeIn(r.options.speed,i)}r._interval_set();e(t).on("scroll."+r.namespace+" resize."+r.namespace,function(){r.reposition()});if(r.options.autoClose){e("body").off("."+r.namespace);if(r.options.trigger=="hover"){if(u){setTimeout(function(){e("body").on("touchstart."+r.namespace,function(){r.hide()})},0)}if(r.options.interactive){if(u){r.$tooltip.on("touchstart."+r.namespace,function(e){e.stopPropagation()})}var p=null;r.$elProxy.add(r.$tooltip).on("mouseleave."+r.namespace+"-autoClose",function(){clearTimeout(p);p=setTimeout(function(){r.hide()},r.options.interactiveTolerance)}).on("mouseenter."+r.namespace+"-autoClose",function(){clearTimeout(p)})}else{r.$elProxy.on("mouseleave."+r.namespace+"-autoClose",function(){r.hide()})}}else if(r.options.trigger=="click"){setTimeout(function(){e("body").on("click."+r.namespace+" touchstart."+r.namespace,function(){r.hide()})},0);if(r.options.interactive){r.$tooltip.on("click."+r.namespace+" touchstart."+r.namespace,function(e){e.stopPropagation()})}}}}if(r.options.timer>0){r.timerHide=setTimeout(function(){r.timerHide=null;r.hide()},r.options.timer+s)}}})},_interval_set:function(){var t=this;t.checkInterval=setInterval(function(){if(e("body").find(t.$el).length===0||e("body").find(t.$elProxy).length===0||t.Status=="hidden"||e("body").find(t.$tooltip).length===0){if(t.Status=="shown"||t.Status=="appearing")t.hide();t._interval_cancel()}else{if(t.options.positionTracker){var n=t._repositionInfo(t.$elProxy),r=false;if(o(n.dimension,t.elProxyPosition.dimension)){if(t.$elProxy.css("position")==="fixed"){if(o(n.position,t.elProxyPosition.position))r=true}else{if(o(n.offset,t.elProxyPosition.offset))r=true}}if(!r){t.reposition()}}}},200)},_interval_cancel:function(){clearInterval(this.checkInterval);this.checkInterval=null},_content_set:function(e){if(typeof e==="object"&&e!==null&&this.options.contentCloning){e=e.clone(true)}this.Content=e},_content_insert:function(){var e=this,t=this.$tooltip.find(".tooltipster-content");if(typeof e.Content==="string"&&!e.options.contentAsHTML){t.text(e.Content)}else{t.empty().append(e.Content)}},_update:function(e){var t=this;t._content_set(e);if(t.Content!==null){if(t.Status!=="hidden"){t._content_insert();t.reposition();if(t.options.updateAnimation){if(l()){t.$tooltip.css({width:"","-webkit-transition":"all "+t.options.speed+"ms, width 0ms, height 0ms, left 0ms, top 0ms","-moz-transition":"all "+t.options.speed+"ms, width 0ms, height 0ms, left 0ms, top 0ms","-o-transition":"all "+t.options.speed+"ms, width 0ms, height 0ms, left 0ms, top 0ms","-ms-transition":"all "+t.options.speed+"ms, width 0ms, height 0ms, left 0ms, top 0ms",transition:"all "+t.options.speed+"ms, width 0ms, height 0ms, left 0ms, top 0ms"}).addClass("tooltipster-content-changing");setTimeout(function(){if(t.Status!="hidden"){t.$tooltip.removeClass("tooltipster-content-changing");setTimeout(function(){if(t.Status!=="hidden"){t.$tooltip.css({"-webkit-transition":t.options.speed+"ms","-moz-transition":t.options.speed+"ms","-o-transition":t.options.speed+"ms","-ms-transition":t.options.speed+"ms",transition:t.options.speed+"ms"})}},t.options.speed)}},t.options.speed)}else{t.$tooltip.fadeTo(t.options.speed,.5,function(){if(t.Status!="hidden"){t.$tooltip.fadeTo(t.options.speed,1)}})}}}}else{t.hide()}},_repositionInfo:function(e){return{dimension:{height:e.outerHeight(false),width:e.outerWidth(false)},offset:e.offset(),position:{left:parseInt(e.css("left")),top:parseInt(e.css("top"))}}},hide:function(n){var r=this;if(n)r.callbacks.hide.push(n);r.callbacks.show=[];clearTimeout(r.timerShow);r.timerShow=null;clearTimeout(r.timerHide);r.timerHide=null;var i=function(){e.each(r.callbacks.hide,function(e,t){t.call(r.$el)});r.callbacks.hide=[]};if(r.Status=="shown"||r.Status=="appearing"){r.Status="disappearing";var s=function(){r.Status="hidden";if(typeof r.Content=="object"&&r.Content!==null){r.Content.detach()}r.$tooltip.remove();r.$tooltip=null;e(t).off("."+r.namespace);e("body").off("."+r.namespace).css("overflow-x",r.bodyOverflowX);e("body").off("."+r.namespace);r.$elProxy.off("."+r.namespace+"-autoClose");r.options.functionAfter.call(r.$el,r.$el);i()};if(l()){r.$tooltip.clearQueue().removeClass("tooltipster-"+r.options.animation+"-show").addClass("tooltipster-dying");if(r.options.speed>0)r.$tooltip.delay(r.options.speed);r.$tooltip.queue(s)}else{r.$tooltip.stop().fadeOut(r.options.speed,s)}}else if(r.Status=="hidden"){i()}return r},show:function(e){this._showNow(e);return this},update:function(e){return this.content(e)},content:function(e){if(typeof e==="undefined"){return this.Content}else{this._update(e);return this}},reposition:function(){var n=this;if(e("body").find(n.$tooltip).length!==0){n.$tooltip.css("width","");n.elProxyPosition=n._repositionInfo(n.$elProxy);var r=null,i=e(t).width(),s=n.elProxyPosition,o=n.$tooltip.outerWidth(false),u=n.$tooltip.innerWidth()+1,a=n.$tooltip.outerHeight(false);if(n.$elProxy.is("area")){var f=n.$elProxy.attr("shape"),l=n.$elProxy.parent().attr("name"),c=e('img[usemap="#'+l+'"]'),h=c.offset().left,p=c.offset().top,d=n.$elProxy.attr("coords")!==undefined?n.$elProxy.attr("coords").split(","):undefined;if(f=="circle"){var v=parseInt(d[0]),m=parseInt(d[1]),g=parseInt(d[2]);s.dimension.height=g*2;s.dimension.width=g*2;s.offset.top=p+m-g;s.offset.left=h+v-g}else if(f=="rect"){var v=parseInt(d[0]),m=parseInt(d[1]),y=parseInt(d[2]),b=parseInt(d[3]);s.dimension.height=b-m;s.dimension.width=y-v;s.offset.top=p+m;s.offset.left=h+v}else if(f=="poly"){var w=[],E=[],S=0,x=0,T=0,N=0,C="even";for(var k=0;k<d.length;k++){var L=parseInt(d[k]);if(C=="even"){if(L>T){T=L;if(k===0){S=T}}if(L<S){S=L}C="odd"}else{if(L>N){N=L;if(k==1){x=N}}if(L<x){x=L}C="even"}}s.dimension.height=N-x;s.dimension.width=T-S;s.offset.top=p+x;s.offset.left=h+S}else{s.dimension.height=c.outerHeight(false);s.dimension.width=c.outerWidth(false);s.offset.top=p;s.offset.left=h}}var A=0,O=0,M=0,_=parseInt(n.options.offsetY),D=parseInt(n.options.offsetX),P=n.options.position;function H(){var n=e(t).scrollLeft();if(A-n<0){r=A-n;A=n}if(A+o-n>i){r=A-(i+n-o);A=i+n-o}}function B(n,r){if(s.offset.top-e(t).scrollTop()-a-_-12<0&&r.indexOf("top")>-1){P=n}if(s.offset.top+s.dimension.height+a+12+_>e(t).scrollTop()+e(t).height()&&r.indexOf("bottom")>-1){P=n;M=s.offset.top-a-_-12}}if(P=="top"){var j=s.offset.left+o-(s.offset.left+s.dimension.width);A=s.offset.left+D-j/2;M=s.offset.top-a-_-12;H();B("bottom","top")}if(P=="top-left"){A=s.offset.left+D;M=s.offset.top-a-_-12;H();B("bottom-left","top-left")}if(P=="top-right"){A=s.offset.left+s.dimension.width+D-o;M=s.offset.top-a-_-12;H();B("bottom-right","top-right")}if(P=="bottom"){var j=s.offset.left+o-(s.offset.left+s.dimension.width);A=s.offset.left-j/2+D;M=s.offset.top+s.dimension.height+_+12;H();B("top","bottom")}if(P=="bottom-left"){A=s.offset.left+D;M=s.offset.top+s.dimension.height+_+12;H();B("top-left","bottom-left")}if(P=="bottom-right"){A=s.offset.left+s.dimension.width+D-o;M=s.offset.top+s.dimension.height+_+12;H();B("top-right","bottom-right")}if(P=="left"){A=s.offset.left-D-o-12;O=s.offset.left+D+s.dimension.width+12;var F=s.offset.top+a-(s.offset.top+s.dimension.height);M=s.offset.top-F/2-_;if(A<0&&O+o>i){var I=parseFloat(n.$tooltip.css("border-width"))*2,q=o+A-I;n.$tooltip.css("width",q+"px");a=n.$tooltip.outerHeight(false);A=s.offset.left-D-q-12-I;F=s.offset.top+a-(s.offset.top+s.dimension.height);M=s.offset.top-F/2-_}else if(A<0){A=s.offset.left+D+s.dimension.width+12;r="left"}}if(P=="right"){A=s.offset.left+D+s.dimension.width+12;O=s.offset.left-D-o-12;var F=s.offset.top+a-(s.offset.top+s.dimension.height);M=s.offset.top-F/2-_;if(A+o>i&&O<0){var I=parseFloat(n.$tooltip.css("border-width"))*2,q=i-A-I;n.$tooltip.css("width",q+"px");a=n.$tooltip.outerHeight(false);F=s.offset.top+a-(s.offset.top+s.dimension.height);M=s.offset.top-F/2-_}else if(A+o>i){A=s.offset.left-D-o-12;r="right"}}if(n.options.arrow){var R="tooltipster-arrow-"+P;if(n.options.arrowColor.length<1){var U=n.$tooltip.css("background-color")}else{var U=n.options.arrowColor}if(!r){r=""}else if(r=="left"){R="tooltipster-arrow-right";r=""}else if(r=="right"){R="tooltipster-arrow-left";r=""}else{r="left:"+Math.round(r)+"px;"}if(P=="top"||P=="top-left"||P=="top-right"){var z=parseFloat(n.$tooltip.css("border-bottom-width")),W=n.$tooltip.css("border-bottom-color")}else if(P=="bottom"||P=="bottom-left"||P=="bottom-right"){var z=parseFloat(n.$tooltip.css("border-top-width")),W=n.$tooltip.css("border-top-color")}else if(P=="left"){var z=parseFloat(n.$tooltip.css("border-right-width")),W=n.$tooltip.css("border-right-color")}else if(P=="right"){var z=parseFloat(n.$tooltip.css("border-left-width")),W=n.$tooltip.css("border-left-color")}else{var z=parseFloat(n.$tooltip.css("border-bottom-width")),W=n.$tooltip.css("border-bottom-color")}if(z>1){z++}var X="";if(z!==0){var V="",J="border-color: "+W+";";if(R.indexOf("bottom")!==-1){V="margin-top: -"+Math.round(z)+"px;"}else if(R.indexOf("top")!==-1){V="margin-bottom: -"+Math.round(z)+"px;"}else if(R.indexOf("left")!==-1){V="margin-right: -"+Math.round(z)+"px;"}else if(R.indexOf("right")!==-1){V="margin-left: -"+Math.round(z)+"px;"}X='<span class="tooltipster-arrow-border" style="'+V+" "+J+';"></span>'}n.$tooltip.find(".tooltipster-arrow").remove();var K='<div class="'+R+' tooltipster-arrow" style="'+r+'">'+X+'<span style="border-color:'+U+';"></span></div>';n.$tooltip.append(K)}n.$tooltip.css({top:Math.round(M)+"px",left:Math.round(A)+"px"})}return n},enable:function(){this.enabled=true;return this},disable:function(){this.hide();this.enabled=false;return this},destroy:function(){var t=this;t.hide();if(t.$el[0]!==t.$elProxy[0])t.$elProxy.remove();t.$el.removeData(t.namespace).off("."+t.namespace);var n=t.$el.data("tooltipster-ns");if(n.length===1){var r=typeof t.Content==="string"?t.Content:e("<div></div>").append(t.Content).html();t.$el.removeClass("tooltipstered").attr("title",r).removeData(t.namespace).removeData("tooltipster-ns").off("."+t.namespace)}else{n=e.grep(n,function(e,n){return e!==t.namespace});t.$el.data("tooltipster-ns",n)}return t},elementIcon:function(){return this.$el[0]!==this.$elProxy[0]?this.$elProxy[0]:undefined},elementTooltip:function(){return this.$tooltip?this.$tooltip[0]:undefined},option:function(e,t){if(typeof t=="undefined")return this.options[e];else{this.options[e]=t;return this}},status:function(){return this.Status}};e.fn[r]=function(){var t=arguments;if(this.length===0){if(typeof t[0]==="string"){var n=true;switch(t[0]){case"setDefaults":e.extend(i,t[1]);break;default:n=false;break}if(n)return true;else return this}else{return this}}else{if(typeof t[0]==="string"){var r="#*$~&";this.each(function(){var n=e(this).data("tooltipster-ns"),i=n?e(this).data(n[0]):null;if(i){if(typeof i[t[0]]==="function"){var s=i[t[0]](t[1],t[2])}else{throw new Error('Unknown method .tooltipster("'+t[0]+'")')}if(s!==i){r=s;return false}}else{throw new Error("You called Tooltipster's \""+t[0]+'" method on an uninitialized element')}});return r!=="#*$~&"?r:this}else{var o=[],u=t[0]&&typeof t[0].multiple!=="undefined",a=u&&t[0].multiple||!u&&i.multiple,f=t[0]&&typeof t[0].debug!=="undefined",l=f&&t[0].debug||!f&&i.debug;this.each(function(){var n=false,r=e(this).data("tooltipster-ns"),i=null;if(!r){n=true}else if(a){n=true}else if(l){console.log('Tooltipster: one or more tooltips are already attached to this element: ignoring. Use the "multiple" option to attach more tooltips.')}if(n){i=new s(this,t[0]);if(!r)r=[];r.push(i.namespace);e(this).data("tooltipster-ns",r);e(this).data(i.namespace,i)}o.push(i)});if(a)return o;else return this}}};var u=!!("ontouchstart"in t);var a=false;e("body").one("mousemove",function(){a=true})})(jQuery,window,document);

var Vue = require('vue');
Vue.config.debug = true;
require('sugar');

module.exports = {
    changeDate: function (date) {
        var date = Date.create(date).format('{yyyy}-{MM}-{dd}');
        console.log('date is: ' + date);
        store.setDate(date);
    },
    today: function () {
        var date = Date.create('today').format('{yyyy}-{MM}-{dd}');
        store.setDate(date);
    },
    goToDate: function (number) {
        var date = Date.create(store.state.date.typed).addDays(number).format('{yyyy}-{MM}-{dd}');
        store.setDate(date);
    }
};

var ExercisesRepository = {

    /**
     *
     * @param exercise
     * @returns {{name: *, description: *, priority: *, step_number: *, default_quantity: *, target: *, program_id: *, series_id: *, default_unit_id: *}}
     */
    setData: function (exercise) {
        var data = {
            name: exercise.name,
            description: exercise.description,
            priority: exercise.priority,
            step_number: exercise.stepNumber,
            default_quantity: exercise.defaultQuantity,
            target: exercise.target,
            program_id: exercise.program.id,
            series_id: exercise.series.id,
            stretch: HelpersRepository.convertBooleanToInteger(exercise.stretch)
        };

        if (exercise.defaultUnit.data) {
            data.default_unit_id = exercise.defaultUnit.data.id;
        }
        else {
            data.default_unit_id = exercise.defaultUnit.id;
        }

        return data;
    }
};
module.exports = {

    /**
     *
     * @param minutes
     * @returns {*}
     */
    formatDuration: function (minutes) {
        if (!minutes && minutes != 0) {
            return '-';
        }

        var hours = Math.floor(minutes / 60);
        if (hours < 10) {
            hours = '0' + hours;
        }

        minutes = minutes % 60;
        if (minutes < 10) {
            minutes = '0' + minutes;
        }

        return hours + ':' + minutes;
    },

    /**
     *
     * @param number
     * @param howManyDecimals
     * @returns {number}
     */
    roundNumber: function (number, howManyDecimals) {
        if (!howManyDecimals) {
            return Math.round(number);
        }

        var multiplyAndDivideBy = Math.pow(10, howManyDecimals);
        return Math.round(number * multiplyAndDivideBy) / multiplyAndDivideBy;
    }
};
require('sugar');

module.exports = {

    /**
     *
     * @param response
     */
    handleResponseError: function (response) {
        $.event.trigger('response-error', [response]);
        $.event.trigger('hide-loading');
    },

    /**
     *
     */
    closePopup: function ($event, that) {
        if ($event.target.className === 'popup-outer') {
            that.showPopup = false;
        }
    },

    /**
     *
     * @param array
     * @param id
     * @returns {*}
     */
    findIndexById: function (array, id) {
        return _.indexOf(array, _.findWhere(array, {id: id}));
    },

    /**
     *
     * @param boolean
     * @returns {number}
     */
    convertBooleanToInteger: function (boolean) {
        if (boolean) {
            return 1;
        }
        return 0;
    },

    formatDateToSql: function (date) {
        return Date.create(date).format('{yyyy}-{MM}-{dd}');
    },

    formatDateToLong: function (date) {
        return Date.create(date).format('{Weekday} {dd} {Month} {yyyy}');
    }
};
var RecipesRepository = {

    /**
     *
     * @returns {*}
     */
    getArrayOfIngredientsAndSteps: function () {
        var stringOfIngredientsAndSteps = this.formatString($("#quick-recipe").html());
        return this.convertFormattedRecipeStringToArrayOfIngredientsAndSteps(stringOfIngredientsAndSteps);
    },

    /**
     *
     */
    modifyQuickRecipeHtml: function (arrayOfIngredientsAndSteps) {
        var html = '';
        for (var i = 0; i < arrayOfIngredientsAndSteps.length; i++) {
            html+= '<div>' + arrayOfIngredientsAndSteps[i] + '</div>';
        }
        $("#quick-recipe").html(html);
    },

    /**
     *
     * @param arrayOfIngredientsAndSteps
     * @returns {*|Array.<T>|string|Blob|ArrayBuffer}
     */
    getIngredients: function (arrayOfIngredientsAndSteps) {
        var stepsIndex = this.getStepsIndex(arrayOfIngredientsAndSteps);
        return arrayOfIngredientsAndSteps.slice(0, stepsIndex);
    },

    /**
     *
     * @param arrayOfIngredientsAndSteps
     * @returns {*|Array.<T>|string|Blob|ArrayBuffer}
     */
    getSteps: function (arrayOfIngredientsAndSteps) {
        var stepsIndex = this.getStepsIndex(arrayOfIngredientsAndSteps);
        return arrayOfIngredientsAndSteps.slice(stepsIndex+1);
    },

    /**
     *
     * @param string
     * @returns {*}
     */
    convertFormattedRecipeStringToArrayOfIngredientsAndSteps: function (string) {
        //turn the string into an array of divs by first splitting at the br tags
        var array = string.split('<br>');

        //remove any empty elements from the array
        array = _.without(array, '');

        //Chrome was putting this line at the top. Remove that:
        //<!--?xml version="1.0" encoding="UTF-8" standalone="no"?-->
        if (array[0].indexOf('<!--') !== -1 && array[0].indexOf('-->') !== -1) {
            array.shift();
        }

        //Remove white space
        for (var i = 0; i < array.length; i++) {
            array[i] = array[i].trim();
        }

        return array;
    },

    /**
     * The string may contain unwanted br tags and
     * both opening and closing div tags.
     * Format the string into a string of div tags to
     * populate the html of the wysiwyg.
     * @param string
     * @returns {string|*}
     */
    formatString: function (string) {
        //Remove any closing div tags and replace any opening div tags with a br tag.
        while (string.indexOf('<div>') !== -1 || string.indexOf('</div>') !== -1) {
            string = string.replace('<div>', '<br>').replace('</div>', '');
        }

        //var formattedString = "";
        //var array = string.split('<br>');

        //make formattedString a string with div tags
        //for (var j = 0; j < array.length; j++) {
        //    formattedString += '<div>' + array[j] + '</div>';
        //}

        //string = formattedString;

        return string;
    },

    /**
     * Check for the possibilities of words that indicate
     * which line the steps starts on
     *
     * @param lines
     * @returns {*|number|Number}
     */
    getStepsIndex: function (lines) {
        var possibilities = [
            'steps',
            'preparation',
            'directions',
            'method'
        ];

        //Convert lines to lower case
        var linesLower = [];
        for (var i = 0; i < lines.length; i++) {
            linesLower.push(lines[i].toLowerCase());
        }

        //Find the index of the word that indicates the start of the steps
        for (var i = 0; i < possibilities.length; i++) {
            if (linesLower.indexOf(possibilities[i]) !== -1) {
                return linesLower.indexOf(possibilities[i]);
            }
            //Allow for colon after the word
            else if (linesLower.indexOf(possibilities[i] + ':') !== -1) {
                return linesLower.indexOf(possibilities[i] + ':');
            }
        }
    },

    /**
     * ingredients is an array of strings.
     * The string should include the quantity, unit, food, and description,
     * providing the user has entered them.
     * We want to take each string and turn it into an object with
     * food, unit, quantity and description properties.
     * Then return the new array of objects.
     * @param ingredients (array)
     * @returns {Array}
     */
    convertIngredientStringsToObjects: function (ingredients) {
        var ingredientsAsObjects = [];
        var that = this;

        $(ingredients).each(function () {
            var ingredientAsString = this;
            var ingredientAsObject = {};

            ingredientAsObject.description = that.getIngredientDescription(ingredientAsString);

            var quantityUnitAndFood = that.getIngredientQuantityUnitAndFood(ingredientAsString);

            //$line is now just the quantity, unit and food, without the description
            //split $line into an array with quantity, unit and food
            var split = quantityUnitAndFood.split(' ');

            //Add the quantity, unit and food to the ingredientAsObject
            ingredientAsObject.quantity = split[0];
            ingredientAsObject.unit = split[1];
            ingredientAsObject.food = split[2];

            //Add the item object to the items array
            ingredientsAsObjects.push(ingredientAsObject);
        });

        return ingredientsAsObjects;
    },


    /**
     * If there is a description,
     * separate the description from the quantity, unit and food
     * @param ingredientAsString
     * @returns {*}
     */
    getIngredientDescription: function (ingredientAsString) {
        var split = this.splitDescriptionFromQuantityUnitAndFood(ingredientAsString);

        if (split[1]) {
            return split[1].trim();
        }

        //There is no description
        return '';
    },

    /**
     *
     * @returns {*}
     */
    getIngredientQuantityUnitAndFood: function (ingredientAsString) {
        var split = this.splitDescriptionFromQuantityUnitAndFood(ingredientAsString);

        return split[0];
    },

    /**
     *
     * @param ingredientAsString
     * @returns {*}
     */
    splitDescriptionFromQuantityUnitAndFood: function (ingredientAsString) {
        if (ingredientAsString.indexOf(',') !== -1) {
            return ingredientAsString.split(',');
        }

        return [ingredientAsString];
    },

    /**
     *
     * @param ingredients
     * @returns {Array}
     */
    checkIngredientsForErrors: function (ingredients) {
        var lineNumber = 0;
        this.errors = [];
        var that = this;

        $(ingredients).each(function () {
            var ingredient = this;
            lineNumber++;

            that.checkIngredientContainsQuantityUnitAndFood(ingredient, lineNumber);
            ingredient = that.checkIngredientQuantityIsValid(ingredient, lineNumber);
        });

        return this.errors;
    },

    /**
     *
     * @param ingredient
     * @param lineNumber
     */
    checkIngredientContainsQuantityUnitAndFood: function (ingredient, lineNumber) {
        if (!ingredient.quantity || !ingredient.unit || !ingredient.food) {
            this.errors.push('Quantity, unit, and food have not all been included on line ' + lineNumber);
            $("#quick-recipe > div").eq(lineNumber-1).css('background', 'red');
        }
    },

    /**
     *
     * @param ingredient
     * @param lineNumber
     */
    checkIngredientQuantityIsValid: function (ingredient, lineNumber) {
        var checkedQuantity = this.checkQuantityIsValid(ingredient.quantity);
        if (!checkedQuantity) {
            //Quantity is invalid
            this.errors.push('Quantity is invalid on line ' + lineNumber);
            $("#quick-recipe > div").eq(lineNumber-1).css('background', 'red');
        }
        else {
            // Quantity is valid and if it was a fraction, it has now been converted to a decimal.
            ingredient.quantity = checkedQuantity;
        }

        return ingredient;
    },

    /**
     * Check the quantity for any invalid characters.
     * If the quantity is a fraction, convert it to a decimal.
     * @param quantity
     * @returns {*}
     */
    checkQuantityIsValid: function (quantity) {
        for (var i = 0; i < quantity.length; i++) {
            var character = quantity[i];

            if (isNaN(character) && character !== '.' && character !== '/') {
                //character is not a number, '.', or '/'. The quantity is invalid.
                quantity = false;
            }
            else {
                quantity = this.convertQuantityToDecimal(quantity);
            }
        }

        return quantity;
    },

    /**
     * Check if $quantity is a fraction, and if so, convert to decimal
     * @param quantity
     * @returns {*}
     */
    convertQuantityToDecimal: function (quantity) {
        if (quantity.indexOf('/') !== -1) {
            //it is a fraction
            var parts = quantity.split('/');
            quantity = parseInt(parts[0], 10) / parseInt(parts[1], 10);
        }

        return quantity;
    }
}
var HelpersRepository = require('./HelpersRepository');
require('sugar');

module.exports = {

    state: {
        exercises: [],
        date: {
            typed: Date.create('today').format('{dd}/{MM}/{yyyy}'),
            long: HelpersRepository.formatDateToLong('today'),
            sql: HelpersRepository.formatDateToSql('today')
        },
        selectedExercise: {
            program: {},
            series: {},
            defaultUnit: {
                data: {}
            }
        },
        exerciseUnits: [],
        programs: []
    },

    /**
     *
     */
    getExercises: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/exercises').then(function (response) {
            store.state.exercises = response.data;
            $.event.trigger('hide-loading');
        }, function (response) {
            HelpersRepository.handleResponseError(response);
        });
        // that.$http.get('/api/exercises', function (response) {
        //
        // })
        // .error(function (response) {
        //
        // });
    },

    /**
     *
     */
    getExerciseUnits: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/exerciseUnits').then(function (response) {
            store.state.exerciseUnits = response.data;
            $.event.trigger('hide-loading');
        }, function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },

    /**
     *
     */
    getExercisePrograms: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/exercisePrograms').then(function (response) {
            store.state.programs = response.data;
            $.event.trigger('hide-loading');
        }, function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },

    /**
    *
    * @param exercise
    */
    updateExercise: function (exercise) {
        var index = HelpersRepository.findIndexById(this.state.exercises, exercise.id);
        this.state.exercises.$set(index, exercise);
    },

    /**
     * 
     * @param date
     */
    setDate: function (date) {
        this.state.date.typed = Date.create(date).format('{dd}/{MM}/{yyyy}');
        this.state.date.long = HelpersRepository.formatDateToLong(date);
        this.state.date.sql = HelpersRepository.formatDateToSql(date);
    }
};
var TimersRepository = {

    /**
     *
     * @param entry
     * @param date
     * @returns {{start: *}}
     */
    setData: function (entry, date) {
        var data = {
            start: this.calculateStartDateTime(entry, date)
        };

        if (entry.finish) {
            data.finish = this.calculateFinishTime(entry, date);
        }

        if (entry.activity) {
            data.activity_id = entry.activity.id;
        }

        return data;
    },

    /**
     *
     * @param byDate
     * @param date
     * @returns {string}
     */
    calculateUrl: function (byDate, date) {
        var url = '/api/timers';
        if (byDate) {
            url+= '?byDate=true';
        }
        else if (date) {
            url+= '?date=' + date;
        }

        return url;
    },

    /**
     *
     * @param entry
     * @param date
     * @returns {*}
     */
    calculateStartDateTime: function (entry, date) {
        if (date) {
            return this.calculateStartDate(entry, date) + ' ' + this.calculateStartTime(entry);
        }
        else {
            //The 'start' timer button has been clicked rather than entering the time manually, so make the start now
            return moment().format('YYYY-MM-DD HH:mm:ss');
        }

    },

    /**
     *
     * @param entry
     * @param date
     * @returns {*}
     */
    calculateStartDate: function (entry, date) {
        if (entry.startedYesterday) {
            return moment(date, 'YYYY-MM-DD').subtract(1, 'days').format('YYYY-MM-DD');
        }
        else {
            return date;
        }
    },

    /**
     *
     * @param entry
     * @param date
     * @returns {*}
     */
    calculateFinishTime: function (entry, date) {
        if (entry.finish) {
            return date + ' ' + Date.parse(entry.finish).toString('HH:mm:ss');
        }
        else {
            //The stop timer button has been pressed. Make the finish time now.
            return moment().format('YYYY-MM-DD HH:mm:ss');
        }

    },

    /**
     *
     * @param entry
     * @returns {string}
     */
    calculateStartTime: function (entry) {
        return Date.parse(entry.start).toString('HH:mm:ss');
    }
};
module.exports = {
    template: '#entries-for-specific-exercise-and-date-and-unit-popup-template',
    data: function () {
        return {
            showPopup: false,
            entries: {}
        };
    },
    components: {},
    methods: {

        /**
         * Get all the the user's entries for a particular exercise
         * with a particular unit on a particular date.
         * @param entry
         */
        getEntriesForSpecificExerciseAndDateAndUnit: function (entry) {
            $.event.trigger('show-loading');

            var data = {
                date: this.date.sql,
                exercise_id: entry.exercise.data.id,
                exercise_unit_id: entry.unit.id
            };

            this.$http.get('api/exerciseEntries/specificExerciseAndDateAndUnit', data).then(function (response) {
                this.entries = response;
                this.showPopup = true;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteExerciseEntry: function (entry) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/exerciseEntries/' + entry.id).then(function (response) {
                    this.entries = _.without(this.entries, entry);
                    //This might be unnecessary to do each time, and it fetches a lot
                    //of data for just deleting one entry.
                    //Perhaps do it when the popup closes instead?
                    $.event.trigger('get-exercise-entries-for-the-day');
                    $.event.trigger('provide-feedback', ['Entry deleted', 'success']);
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-entries-for-specific-exercise-and-date-and-unit-popup', function (event, entry) {
                that.getEntriesForSpecificExerciseAndDateAndUnit(entry);
            });
        }
    },
    props: [
        'date'
    ],
    ready: function () {
        this.listen();
    }
};

module.exports = {
    template: '#exercise-entries-template',
    data: function () {
        return {
            exerciseEntries: [],
            showExerciseEntryInputs: false,
            selectedExercise: {
                unit: {}
            },
            shared: store.state
        };
    },
    computed: {
        date: function () {
          return this.shared.date;
        }
    },
    components: {},
    methods: {

        /**
         *
         * @param entry
         */
        showEntriesForSpecificExerciseAndDateAndUnitPopup: function (entry) {
            $.event.trigger('show-entries-for-specific-exercise-and-date-and-unit-popup', [entry]);
        },

        /**
         *
         */
        getEntriesForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseEntries/' + this.date.sql).then(function (response) {
                this.exerciseEntries = response.data;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         * Similar method to this in SeriesExercisesComponent
         */
        insertExerciseSet: function (exercise) {
            $.event.trigger('show-loading');
            var data = {
                date: this.date.sql,
                exercise_id: exercise.data.id,
                exerciseSet: true
            };

            this.$http.post('/api/exerciseEntries', data).then(function (response) {
                $.event.trigger('provide-feedback', ['Set added', 'success']);
                this.getEntriesForTheDay();
                this.exerciseEntries = response.data;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            /**
             * For updating the exercise entries from the
             * series controller on the series page
             */
            $(document).on('get-exercise-entries-for-the-day', function (event) {
                that.getEntriesForTheDay();
            });
            $(document).on('date-changed', function (event) {
                that.getEntriesForTheDay();
            });
            $(document).on('exercise-entry-added', function (event, data) {
                //Todo: all the entries I think are actually in the data (unnecessarily)
                that.getEntriesForTheDay();
            });
            /**
             * For updating the exercise entries from the
             * series controller on the series page
             */
            //$(document).on('getExerciseEntries', function (event, data) {
            //    that.exerciseEntries = data;
            //});
        }
    },
    props: [
        
    ],
    ready: function () {
        this.listen();
        this.getEntriesForTheDay();
    }
};
module.exports = {
    template: '#exercise-popup-template',
    data: function () {
        return {
            showPopup: false
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        updateExercise: function () {
            $.event.trigger('show-loading');

            var data = ExercisesRepository.setData(this.selectedExercise);

            this.$http.put('/api/exercises/' + this.selectedExercise.id, data).then(function (response) {
                this.selectedExercise = response.data;
                store.updateExercise(response.data);


                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Exercise updated', 'success']);
                $.event.trigger('hide-loading');
                $("#exercise-step-number").val("");
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteExercise: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/exercises/' + this.selectedExercise.id).then(function (response) {
                    var index = _.indexOf(this.exercises, _.findWhere(this.exercises, {id: this.selectedExercise.id}));
                    this.exercises = _.without(this.exercises, this.exercises[index]);
                    $.event.trigger('provide-feedback', ['Exercise deleted', 'success']);
                    this.showPopup = false;
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-exercise-popup', function (event) {
                that.showPopup = true;
            });
        }
    },
    props: [
        'selectedExercise',
        'exercises',
        'exerciseSeries',
        'programs',
        'units'
    ],
    ready: function () {
        this.listen();
    }
};

module.exports = {
    template: '#exercise-units-page-template',
    data: function () {
        return {
            units: [],
            newUnit: {}
        };
    },
    components: {},
    methods: {
        /**
         *
         */
        getUnits: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseUnits').then(function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertUnit: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newUnit.name
            };

            this.$http.post('/api/exerciseUnits', data).then(function (response) {
                this.units.push(response.data);
                $.event.trigger('provide-feedback', ['Unit created', 'success']);
                //this.$broadcast('provide-feedback', 'Unit created', 'success');
                $.event.trigger('hide-loading');
                $("#create-new-exercise-unit").val("");
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param unit
         */
        deleteUnit: function (unit) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/exerciseUnits/' + unit.id).then(function (response) {
                    this.units = _.without(this.units, unit);
                    $.event.trigger('provide-feedback', ['Unit deleted', 'success']);
                    //this.$broadcast('provide-feedback', 'Unit deleted', 'success');
                    $.event.trigger('hide-loading');

                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getUnits();
    }
};
var ExercisesRepository = require('../../repositories/ExercisesRepository');

module.exports = {
    template: '#exercises-page-template',
    data: function () {
        return {
            date: store.state.date,
            exerciseSeries: [],
            exerciseSeriesHistory: [],
            showNewSeriesFields: false,
            showNewExerciseFields: false,
            selectedSeries: {
                exercises: {
                    data: []
                }
            },
            showExerciseEntryInputs: false,
            programs: store.state.programs,
            shared: store.state,
            showStretches: false,
            filterByName: '',
            filterByDescription: '',
            filterByPriority: 1,
            filterBySeries: '',
            showFilters: false
        };
    },
    computed: {
        selectedExercise: function () {
          return this.shared.selectedExercise;
        },
        units: function () {
            return this.shared.exerciseUnits;
        }
    },
    components: {},
    filters: {
        filterExercises: function (exercises) {
            var that = this;

            //Sort
            exercises = _.chain(exercises)
                .sortBy(function (exercise) {return exercise.stepNumber})
                .sortBy(function (exercise) {return exercise.series.id})
                .sortBy('priority')
                .sortBy(function (exercise) {
                    return exercise.lastDone * -1
                })
                .partition(function (exercise) {
                    return exercise.lastDone === null;
                })
                .flatten()
                .value();

            //Filter
            return exercises.filter(function (exercise) {
                var filteredIn = true;

                //Priority filter
                if (that.filterByPriority && exercise.priority != that.filterByPriority) {
                    filteredIn = false;
                }

                //Name filter
                if (that.filterByName && exercise.name.indexOf(that.filterByName) === -1) {
                    filteredIn = false;
                }

                //Description filter
                if (exercise.description && exercise.description.indexOf(that.filterByDescription) === -1) {
                    filteredIn = false;
                }

                else if (!exercise.description && that.filterByDescription !== '') {
                    filteredIn = false;
                }

                //Stretches files
                if (!that.showStretches && exercise.stretch) {
                    filteredIn = false;
                }

                //Series filter
                if (that.filterBySeries && exercise.series.name != that.filterBySeries && that.filterBySeries !== 'all') {
                    filteredIn = false;
                }

                return filteredIn;
            });
        },

        filterSeries: function (series) {
            var that = this;

            //Sort
            series = _.chain(series)
                .sortBy('priority')
                .sortBy('lastDone')
                .value();

            /**
             * @VP:
             * This method feels like a lot of code for just
             * a simple thing-ordering series by their lastDone value,
             * putting those with a null lastDone value on the end.
             * I tried underscore.js _.partition with _.flatten,
             * but it put 0 values on the end,
             * (I had trouble getting the predicate parameter of the _.partition method to work.)
             */
            series = this.moveLastDoneNullToEnd(series);

            //Filter
            return series.filter(function (thisSeries) {
                var filteredIn = true;

                //Priority filter
                if (that.priorityFilter && thisSeries.priority != that.priorityFilter) {
                    filteredIn = false;
                }

                return filteredIn;
            });
        },
    },
    methods: {

        /**
         *
         */
        insertExerciseSet: function (exercise) {
            $.event.trigger('show-loading');
            var data = {
                date: this.shared.date.sql,
                exercise_id: exercise.id,
                exerciseSet: true
            };

            this.$http.post('/api/exerciseEntries', data).then(function (response) {
                exercise.lastDone = 0;
                $.event.trigger('provide-feedback', ['Set added', 'success']);
                $.event.trigger('get-exercise-entries-for-the-day');
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        showExercisePopup: function (exercise) {
            $.event.trigger('show-loading');
            this.$http.get('/api/exercises/' + exercise.id).then(function (response) {
                this.selectedExercise = response.data;
                $.event.trigger('show-exercise-popup');
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         * For the series filter
         * @param series
         * @returns {*}
         */
        moveLastDoneNullToEnd: function (series) {
            //Get the series that have lastDone null values
            var seriesWithNullLastDone = _.filter(series, function (oneSeries) {
                return oneSeries.lastDone == null;
            });

            //Remove the series that have lastDone null values
            for (var i = 0; i < seriesWithNullLastDone.length; i++) {
                var index = _.indexOf(series, _.findWhere(series, {id: seriesWithNullLastDone[i].id}));
                series = _.without(series, series[index]);
            }

            //Add the series that have lastDone null values back on the
            //end of the series array
            for (var i = 0; i < seriesWithNullLastDone.length; i++) {
                series.push(seriesWithNullLastDone[i]);
            }

            return series;
        },

        /**
         *
         */
        getSeries: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries').then(function (response) {
                this.exerciseSeries = response.data;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        getExerciseSeriesHistory: function (key) {
            $.event.trigger('show-loading');

            //Find the series. The exercises were grouped according to series, so all we have is the series name (key).
            var series = _.find(this.exerciseSeries, function (series) {
                return series.name === key;
            });

            this.$http.get('api/seriesEntries/' + series.id).then(function (response) {
                //For displaying the name of the series in the popup
                this.selectedSeries = series;
                this.exerciseSeriesHistory = response.data;
                $.event.trigger('show-series-history-popup');
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        getExercisesInSeries: function (series) {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries/' + series.id).then(function (response) {
                this.selectedSeries = response.data;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        showExerciseSeriesPopup: function (key) {
            //Find the series. The exercises were grouped according to series, so all we have is the series name (key).
            var series = _.find(this.exerciseSeries, function (series) {
                return series.name === key;
            });

            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries/' + series.id).then(function (response) {
                this.selectedSeries = response.data;
                $.event.trigger('show-series-popup');
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getSeries();
    }
};


require('bootstrap');

module.exports = {
    template: '#navbar-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {
        /**
         *
         */
        showNewManualTimerPopup: function () {
            $.event.trigger('show-new-manual-timer-popup');
        },
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
};

module.exports = {
    template: '#new-exercise-template',
    data: function () {
        return {
            newExercise: {}
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        insertExercise: function () {
            $.event.trigger('show-loading');
            var data = ExercisesRepository.setData(this.newExercise);

            this.$http.post('/api/exercises', data).then(function (response) {
                if (this.exercises) {
                    //If adding new exercise from the series page,
                    //this.exercises isn't specified
                    this.exercises.push(response);
                }

                $.event.trigger('provide-feedback', ['Exercise created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'showNewExerciseFields',
        'exercises',
        'programs',
        'exerciseSeries',
        'units'
    ],
    ready: function () {

    }
};

module.exports = {
    template: '#new-exercise-entry-template',
    data: function () {
        return {
            newEntry: {},
            units: []
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getUnits: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseUnits').then(function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertEntry: function () {
            $.event.trigger('show-loading');

            //this.newEntry.exercise.unit_id = $("#exercise-unit").val();
            //$("#exercise").val("").focus();

            var data = {
                date: this.date.sql,
                exercise_id: this.newEntry.id,
                quantity: this.newEntry.quantity,
                unit_id: this.newEntry.unit.id
            };

            this.$http.post('/api/exerciseEntries', data).then(function (response) {
                //this.exerciseEntries = response;
                $.event.trigger('exercise-entry-added', [response]);
                $.event.trigger('provide-feedback', ['Entry created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'date'
    ],
    events: {
        'option-chosen': function (option) {
            this.newEntry = option;
            this.newEntry.unit = option.defaultUnit.data;
            this.newEntry.quantity = option.defaultQuantity;
        }
    },
    ready: function () {
        this.getUnits();
    }
};
module.exports = {
    template: '#new-series-template',
    data: function () {
        return {
            newSeries: {}
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        insertSeries: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newSeries.name
            };

            this.$http.post('/api/exerciseSeries', data).then(function (response) {
                this.exerciseSeries.push(response.data);
                $.event.trigger('provide-feedback', ['Series created', 'success']);
                this.showLoading = false;
                this.newSeries.name = '';
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'showNewSeriesFields',
        'exerciseSeries'
    ],
    ready: function () {

    }
};
module.exports = {
    template: '#series-history-popup-template',
    data: function () {
        return {
            showPopup: false,
            filterByExercise: ''
        };
    },
    components: {},
    filters: {
        exerciseFilter: function (entries) {
            var that = this;
            return entries.filter(function (entriesForDay) {
                return entriesForDay.exercise.data.name.indexOf(that.filterByExercise) !== -1;
            });
        }
    },
    methods: {

        /**
         *
         */
        closePopup: function ($event) {
            if ($event.target.className === 'popup-outer') {
                this.showPopup = false;
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-series-history-popup', function (event, series) {
                that.selectedSeries = series;
                that.showPopup = true;
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'exerciseSeriesHistory',
        'selectedSeries'
    ],
    ready: function () {
        this.listen();
    }
};

module.exports = {
    template: '#series-popup-template',
    data: function () {
        return {
            showPopup: false
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        updateSeries: function () {
            $.event.trigger('show-loading');

            var data = {
                name: this.selectedSeries.name,
                priority: this.selectedSeries.priority,
                workout_ids: this.selectedSeries.workout_ids
            };

            this.$http.put('/api/exerciseSeries/' + this.selectedSeries.id, data).then(function (response) {
                var index = _.indexOf(this.exerciseSeries, _.findWhere(this.exerciseSeries, {id: this.selectedSeries.id}));
                this.exerciseSeries[index].name = response.data.name;
                this.exerciseSeries[index].priority = response.data.priority;
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Series updated', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteSeries: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/exerciseSeries/' + this.selectedSeries.id).then(function (response) {
                    //this.exerciseSeries = _.without(this.exerciseSeries, this.selectedSeries);
                    var index = _.indexOf(this.exerciseSeries, _.findWhere(this.exerciseSeries, {id: this.selectedSeries.id}));
                    this.exerciseSeries = _.without(this.exerciseSeries, this.exerciseSeries[index]);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Series deleted', 'success']);
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         */
        closePopup: function ($event) {
            if ($event.target.className === 'popup-outer') {
                this.showPopup = false;
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-series-popup', function (event, series) {
                that.selectedSeries = series;
                that.showPopup = true;
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'selectedSeries',
        'exerciseSeries'
    ],
    ready: function () {
        this.listen();
    }
};
var FoodPopup = Vue.component('food-popup', {
    template: '#food-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedFood: {
                //food: {},
                defaultUnit: {
                    data: {}
                },
                unitIds: []
            },
            units: []
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        updateFood: function () {
            $.event.trigger('show-loading');

            var data = {
                name: this.selectedFood.name,
                default_unit_id: this.selectedFood.defaultUnit.data.id,
                unit_ids: this.selectedFood.unitIds,
            };

            var that = this;

            setTimeout(function () {
                that.$http.put('/api/foods/' + that.selectedFood.id, data).then(function (response) {
                    that.selectedFood = response;
                    $.event.trigger('provide-feedback', ['Food updated', 'success']);
                    $.event.trigger('food-updated', [response]);
                    $.event.trigger('hide-loading');
                }, function (response) {
                    that.handleResponseError(response);
                });
            }, 1000);
        },

        /**
         *
         */
        updateDefaultUnit: function (unit) {
            $.event.trigger('show-loading');

            this.selectedFood.defaultUnit.data = unit;

            var data = {
                default_unit_id: this.selectedFood.defaultUnit.data.id,
            };

            this.$http.put('/api/foods/' + this.selectedFood.id, data).then(function (response) {
                this.selectedFood = response;
                $.event.trigger('provide-feedback', ['Food updated', 'success']);
                $.event.trigger('food-updated', [response]);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        updateCalories: function (unit) {
            $.event.trigger('show-loading');

            var data = {
                updatingCalories: true,
                unit_id: unit.id,
                calories: unit.calories
            };

            this.$http.put('/api/foods/' + this.selectedFood.id, data).then(function (response) {
                $.event.trigger('provide-feedback', ['Calories updated', 'success']);
                $.event.trigger('food-updated', [response]);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        getUnits: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/foodUnits?includeCaloriesForSpecificFood=true&food_id=' + this.selectedFood.id).then(function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param $event
         */
        closePopup: function ($event) {
            if ($event.target.className === 'popup-outer') {
                this.showPopup = false;
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-food-popup', function (event, food) {
                that.selectedFood = food;
                that.getUnits();
                that.showPopup = true;
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
    }
});

module.exports = {
    template: '#food-units-page-template',
    data: function () {
        return {
            units: [],
            newUnit: {}
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getUnits: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/foodUnits').then(function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertUnit: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newUnit.name
            };

            this.$http.post('/api/foodUnits', data).then(function (response) {
                this.units.push(response);
                $.event.trigger('provide-feedback', ['Unit created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteUnit: function (unit) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/foodUnits/' + unit.id).then(function (response) {
                    this.units = _.without(this.units, unit);
                    //var index = _.indexOf(this.units, _.findWhere(this.units, {id: this.unit.id}));
                    //this.units = _.without(this.units, this.units[index]);
                    $.event.trigger('provide-feedback', ['Unit deleted', 'success']);
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getUnits();
    }
};


module.exports = {
    template: '#foods-page-template',
    data: function () {
        return {
            calories: {},
            newItem: {},
            foods: [],
            foodsFilter: '',
            newFood: {}
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getFoods: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/foods').then(function (response) {
                this.foods = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        //getMenu: function () {
        //    if ($scope.foods.length > 0 && $scope.recipes.length > 0) {
        //        $scope.menu = select.getMenu($scope.foods, $scope.recipes);
        //    }
        //},

        /**
         *
         */
        getFood: function (food) {
            $.event.trigger('show-loading');
            this.$http.get('/api/foods/' + food.id).then(function (response) {
                $.event.trigger('show-food-popup', [response]);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertFood: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newFood.name
            };

            this.$http.post('/api/foods', data).then(function (response) {
                this.foods.push(response);
                $.event.trigger('provide-feedback', ['Food created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteFood: function (food) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/foods/' + food.id).then(function (response) {
                    this.foods = _.without(this.foods, food);
                    $.event.trigger('provide-feedback', ['Food deleted', 'success']);
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('food-updated', function (event, food) {
                var index = _.indexOf(that.foods, _.findWhere(that.foods, {id: food.id}));
                that.foods[index].name = food.name;
                that.foods[index].defaultUnit = food.defaultUnit;
                that.foods[index].defaultCalories = food.defaultCalories;
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        $(".wysiwyg").wysiwyg();
        this.getFoods();
        this.listen();
    }
};







module.exports = {
    template: '#menu-entries-template',
    data: function () {
        return {
            menuEntries: menuEntries,
            temporaryRecipePopup: {},
            selected: {
                dropdown_item: {},
                food: {},
                unit: {}
            }
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        deleteMenuEntry: function (entry) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/menuEntries/' + entry.id).then(function (response) {
                    this.menuEntries = _.without(this.menuEntries, entry);
                    $.event.trigger('provide-feedback', ['MenuEntry deleted', 'success']);
                    $.event.trigger('menu-entry-deleted');
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         */
        getEntriesForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/menuEntries/' + this.date.sql).then(function (response) {
                this.menuEntries = response.data;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('menu-entry-added', function (event, entry) {
                $.event.trigger('show-loading');
                if (entry.date === that.date.sql) {
                    that.menuEntries.push(entry)
                }
            });
            $(document).on('date-changed', function (event) {
                that.getEntriesForTheDay();
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'date'
    ],
    ready: function () {
        this.listen();
    }
};



module.exports = {
    template: '#new-food-entry-template',
    data: function () {
        return {
            newIngredient: {
                food: {
                    units: {
                        data: []
                    },
                    defaultUnit: {
                        data: {}
                    }
                },
                unit: {}
            }
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        addIngredientToRecipe: function () {
            if (this.recipeIsTemporary) {
                $.event.trigger('add-ingredient-to-temporary-recipe', [this.newIngredient]);
            }
            else {
                $.event.trigger('show-loading');

                var data = {
                    addIngredient: true,
                    food_id: this.newIngredient.food.id,
                    unit_id: this.newIngredient.unit.id,
                    quantity: this.newIngredient.quantity,
                    description: this.newIngredient.description
                };

                this.$http.put('/api/recipes/' + this.selectedRecipe.id, data).then(function (response) {
                    this.selectedRecipe.ingredients.data.push({
                        food: {
                            data: {
                                name: this.newIngredient.food.name
                            }
                        },
                        unit: {
                            data: {
                                name: this.newIngredient.unit.name
                            }
                        },
                        quantity: this.newIngredient.quantity,
                        description: this.newIngredient.description,
                    });
                    $.event.trigger('provide-feedback', ['Food added', 'success']);
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },


        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'date',
        'selectedRecipe',
        'recipeIsTemporary'
    ],
    events: {
        'option-chosen': function (option) {
            this.newIngredient.food = option;
            this.newIngredient.unit = option.defaultUnit.data;
        }
    },
    ready: function () {

    }
};
module.exports = {
    template: '#new-menu-entry-template',
    data: function () {
        return {
            newIngredient: {
                food: {
                    units: {
                        data: []
                    },
                    defaultUnit: {
                        data: {}
                    }
                },
                unit: {},
                type: ''
            },
            recipeEntry: {},
            entryNumberForRecipe: 0
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        insertMenuEntry: function () {
            $.event.trigger('show-loading');
            var data = {
                date: this.date.sql,
                food_id: this.newIngredient.food.id,
                unit_id: this.newIngredient.unit.id,
                quantity: this.newIngredient.quantity,
            };

            $.event.trigger('get-entries');

            $("#new-menu-entry-food").focus();

            this.$http.post('/api/menuEntries', data).then(function (response) {
                this.newIngredient.description = '';
                this.newIngredient.quantity = '';
                $("#new-ingredient-food-name").focus();
                $.event.trigger('provide-feedback', ['Menu entry created', 'success']);
                $.event.trigger('menu-entry-added', [response]);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param ingredient
         */
        insertEntry: function (ingredient) {
            var data = {
                date: this.date.sql,
                food_id: ingredient.food.data.id,
                recipe_id: this.recipeEntry.id,
                unit_id: ingredient.unit.data.id,
                quantity: ingredient.quantity,
            };

            this.$http.post('/api/menuEntries', data).then(function (response) {
                //This adds the entry to the entries with the JS
                $.event.trigger('menu-entry-added', [response]);
                this.entryNumberForRecipe++;
                //If it's the last of the entries for the recipe being added, do stuff
                if (this.entryNumberForRecipe == this.recipeEntry.ingredients.data.length) {
                    $.event.trigger('provide-feedback', ['Recipe entries created', 'success']);
                    //I think this just updates the calorie info for the day
                    $.event.trigger('get-entries');
                    $.event.trigger('hide-loading');
                }
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         * Insert each entry for a recipe, one at a time
         * @param recipe
         */
        insertEntriesForRecipe: function (recipe) {
            $.event.trigger('show-loading');

            this.entryNumberForRecipe = 0;
            this.recipeEntry = recipe;

            for (var i = 0; i < recipe.ingredients.data.length; i++) {
                this.insertEntry(recipe.ingredients.data[i]);
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('insert-entries-for-recipe', function (event, recipe) {
                that.insertEntriesForRecipe(recipe);
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'date'
    ],
    events: {
        'option-chosen': function (option) {
            if (option.type === 'food') {
                this.newIngredient.food = option;
                this.newIngredient.type = 'food';
                if (option.defaultUnit) {
                    this.newIngredient.unit = option.defaultUnit.data;
                }
            }
            else if (option.type === 'recipe') {
                this.newIngredient = option;
                $.event.trigger('show-temporary-recipe-popup', [option]);
            }
        }
    },
    ready: function () {
        this.listen();
    }
};
var NewQuickRecipe = Vue.component('new-quick-recipe', {
    template: '#new-quick-recipe-template',
    data: function () {
        return {
            errors: {},
            showPopup: false,
            showHelp: false,
            newRecipe: {
                similarNames: []
            },
            similarNames: [],
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        toggleHelp: function () {
            this.showHelp = !this.showHelp;
        },

        /**
         * End goal of the function:
         * Call RecipesFactory.insertQuickRecipe, with $check_similar_names as true.
         * Send the contents, steps, and name of new recipe.
         *
         * The PHP checks for similar names and returns similar names if found.
         * The JS checks for similar names in the response.
         *
         * If they exist, a popup shows.
         * From there, the user can click a button
         * which fires quickRecipeFinish,
         * sending the recipe info again
         * but this time without the similar name check.
         *
         * If none exist, the recipe should have been entered with the PHP
         * and things should update accordingly on the page.
         */
        respondToEnterRecipeBtnClick: function () {
            //remove any previous error styling so it doesn't wreck up the html
            $("#quick-recipe > *").removeAttr("style");
            $("#quick-recipe-errors").hide();

            var arrayOfIngredientsAndSteps = RecipesRepository.getArrayOfIngredientsAndSteps();

            this.addPropertiesToRecipe(arrayOfIngredientsAndSteps);
            RecipesRepository.modifyQuickRecipeHtml(arrayOfIngredientsAndSteps);
            this.checkForAndHandleErrors();

            if (this.errors.length < 1) {
                //Prompt the user for the recipe name
                this.newRecipe.name = prompt('name your recipe');

                //If the user changes their mind and cancels
                if (!this.newRecipe.name) {
                    return;
                }

                this.checkForSimilarNames();
            }
            else {
                $("#quick-recipe-errors").show();
            }
        },

        /**
        *
        */
        showNewRecipeFields: function () {
            this.addingNewRecipe = true;
            this.editingRecipe = false;
        },

        /**
         *
         */
        checkForSimilarNames: function () {
            $.event.trigger('show-loading');

            var data = {
                ingredients: this.newRecipe.ingredients
            };

            this.$http.get('/api/quickRecipes/checkForSimilarNames', data).then(function (response) {
                $.event.trigger('hide-loading');
                this.similarNames = response;

                if (response.units || response.foods) {
                    $.event.trigger('provide-feedback', ['Similar names were found', 'success']);
                    this.showPopup = true;
                }
                else {
                    this.insertRecipe();
                }

            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertRecipe: function () {
            $.event.trigger('show-loading');
            if (this.similarNames.foods || this.similarNames.units) {
                this.chooseCorrectFoodName();
                this.chooseCorrectUnitName();
                this.showPopup = false;
            }

            var data = {
                name: this.newRecipe.name,
                ingredients: this.newRecipe.ingredients,
                steps: this.newRecipe.steps,
                checkForSimilarNames: this.checkForSimilarNames
            };

            this.$http.post('/api/quickRecipes', data).then(function (response) {
                $.event.trigger('provide-feedback', ['Recipe created', 'success']);
                $.event.trigger('hide-loading');
                this.recipes.push(response.data);
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        chooseCorrectFoodName: function () {
            var that = this;
            $(this.similarNames.foods).each(function () {
                if (this.selected === this.existingFood.name) {
                    // Use the existing food rather than creating a new food.
                    that.newRecipe.ingredients[this.index].food = this.existingFood.name;
                }
            });
        },

        /**
         *
         */
        chooseCorrectUnitName: function () {
            var that = this;
            $(this.newRecipe.similarNames.units).each(function () {
                if (this.selected === this.existingUnit.name) {
                    //Use the existing unit rather than creating a new unit
                     that.newRecipe.ingredients[this.index].unit = this.existingUnit.name;
                }
            });
        },

        /**
         *
         */
        checkForAndHandleErrors: function () {
            this.errors = [];
            this.errors = RecipesRepository.checkIngredientsForErrors(this.newRecipe.ingredients);
        },

        /**
         *
         */
        addPropertiesToRecipe: function (arrayOfIngredientsAndSteps) {
            this.newRecipe.ingredients = RecipesRepository.getIngredients(arrayOfIngredientsAndSteps);
            this.newRecipe.steps = RecipesRepository.getSteps(arrayOfIngredientsAndSteps);
            this.newRecipe.ingredients = RecipesRepository.convertIngredientStringsToObjects(this.newRecipe.ingredients);
        },

        /**
         *
         */
        closePopup: function ($event) {
            if ($event.target.className === 'popup-outer') {
                this.showPopup = false;
            }
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'recipes'
    ],
    ready: function () {

    }
});

var RecipePopup = Vue.component('recipe-popup', {
    template: '#recipe-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedRecipe: {
                steps: [],
                ingredients: []
            },
            newIngredient: {
                food: {}
            },
            editingMethod: false
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-recipe-popup', function (event, recipe) {
                that.selectedRecipe = recipe;
                that.showPopup = true;
            });
            $(document).on('add-ingredient-to-recipe', function (event, ingredient) {
                that.addIngredientToRecipe(ingredient);
            });
        },

        /**
        *
        */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },
        
        /**
        *
        */
        updateRecipe: function () {
            $.event.trigger('show-loading');

            var string = $("#edit-recipe-method").html();
            var lines = RecipesRepository.formatString(string, $("#edit-recipe-method")).items;
            var steps = [];

            $(lines).each(function () {
                steps.push(this);
            });

            this.selectedRecipe.steps = steps;

            var data = {
                name: this.selectedRecipe.name,
                steps: this.selectedRecipe.steps,
                tag_ids: this.selectedRecipe.tag_ids
            };

            this.$http.put('/api/recipes/' + this.selectedRecipe.id, data).then(function (response) {
                var index = _.indexOf(this.recipes, _.findWhere(this.recipes, {id: this.selectedRecipe.id}));
                this.recipes[index].name = response.name;
                this.recipes[index].tags = response.tags;
                this.recipes[index].tag_ds = response.tag_ids;
                this.editingMethod = false;
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Recipe updated', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        deleteIngredientFromRecipe: function (ingredient) {
            $.event.trigger('show-loading');

            var data = {
                removeIngredient: true,
                food_id: ingredient.food.data.id,
                unit_id: ingredient.unit.data.id
            };

            this.$http.put('/api/recipes/' + this.selectedRecipe.id, data).then(function (response) {
                this.selectedRecipe.ingredients.data = _.without(this.selectedRecipe.ingredients.data, ingredient);
                $.event.trigger('provide-feedback', ['Ingredient removed from recipe', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        toggleEditMethod: function () {
            //Toggle the visibility of the wysywig
            this.editingMethod = !this.editingMethod;

            //If we are editing the recipe, prepare the html of the wysiwyg
            if (this.editingMethod) {
                var text;
                var string = "";

                //convert the array into a string so I can make the wysiwyg display the steps
                $(this.selectedRecipe.steps).each(function () {
                    text = this.text;
                    text = text + '<br>';
                    string+= text;
                });
                $("#edit-recipe-method").html(string);
            }
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'tags',
        'recipes'
    ],
    ready: function () {
        this.listen();
    }
});

var RecipeTags = Vue.component('recipe-tags', {
    template: '#recipe-tags-template',
    data: function () {
        return {
            newTag: {}
        };
    },
    components: {},

    methods: {

        /**
        *
        */
        insertTag: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newTag.name
            };

            this.$http.post('/api/recipeTags', data).then(function (response) {
                this.tags.push(response.data);
                $.event.trigger('provide-feedback', ['Tag created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        deleteTag: function (tag) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/recipeTags/' + tag.id).then(function (response) {
                    this.tags = _.without(this.tags, tag);
                    $.event.trigger('provide-feedback', ['Tag deleted', 'success']);
                    //this.$broadcast('provide-feedback', 'Tag deleted', 'success');
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'tags',
        'recipesTagFilter'
    ],
    ready: function () {

    }
});

var Recipes = Vue.component('recipes', {
    template: '#recipes-template',
    data: function () {
        return {
            newRecipe: {},
            recipesNameFilter: '',
            //recipesTagFilter: ''
        };
    },
    components: {},
    filters: {
        recipesFilter: function (recipes) {
            var that = this;

            return recipes.filter(function (recipe) {
                var containsName = recipe.name.indexOf(that.recipesNameFilter) !== -1;
                var containsTags = true;
                var tagIdsForRecipe = _.pluck(recipe.tags.data, 'id');
                var count = 0;

                if (that.recipesTagFilter.length > 0) {
                    containsTags = false;
                    for (var i = 0; i < that.recipesTagFilter.length; i++) {
                        if (tagIdsForRecipe.indexOf(that.recipesTagFilter[i]) !== -1) {
                            //Recipe contains the tag
                            count++;
                        }
                    }
                    if (count === that.recipesTagFilter.length) {
                        containsTags = true;
                    }
                }

                return containsName && containsTags;
            });
        }
    },
    methods: {

        /**
         *
         * @param recipe
         */
        showRecipePopup: function (recipe) {
            $.event.trigger('show-loading');
            this.$http.get('/api/recipes/' + recipe.id).then(function (response) {
                $.event.trigger('show-recipe-popup', [response]);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        insertRecipe: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newRecipe.name
            };

            this.$http.post('/api/recipes', data).then(function (response) {
                this.recipes.push(response.data);
                $.event.trigger('provide-feedback', ['Recipe created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        showNewRecipeFields: function () {
            this.addingNewRecipe = true;
            this.editingRecipe = false;
        },

        /**
        *
        */
        deleteRecipe: function (recipe) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/recipes/' + recipe.id).then(function (response) {
                    this.recipes = _.without(this.recipes, recipe);
                    $.event.trigger('provide-feedback', ['Recipe deleted', 'success']);
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'tags',
        'recipesTagFilter',
        'recipes'
    ],
    ready: function () {
        $(".wysiwyg").wysiwyg();
    }
});
















//$scope.getMenu = function () {
//    if ($scope.foods.length > 0 && $scope.recipes.length > 0) {
//        $scope.menu = select.getMenu($scope.foods, $scope.recipes);
//    }
//};






//
//
///**
// * Add a unit to a food or remove the unit from the food.
// * The method name is old and should probably be changed.
// * @param $unit_id
// */
//$scope.insertOrDeleteUnitInCalories = function ($unit_id) {
//    //Check if the checkbox is checked
//    if ($scope.food_popup.food_units.indexOf($unit_id) === -1) {
//        //It is now unchecked. Remove the unit from the food.
//        FoodsFactory.deleteUnitFromCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
//            $scope.food_popup = response.data;
//        });
//    }
//    else {
//        // It is now checked. Add the unit to the food.
//        FoodsFactory.insertUnitInCalories($scope.food_popup.food.id, $unit_id).then(function (response) {
//            $scope.food_popup = response.data;
//        });
//    }
//};
//
///**
// * update
// */
//

//
//$scope.updateCalories = function ($keycode, $unit_id, $calories) {
//    if ($keycode === 13) {
//        FoodsFactory.updateCalories($scope.food_popup.food.id, $unit_id, $calories).then(function (response) {
//            $scope.food_popup = response.data;
//        });
//    }
//};
//
//$scope.updateDefaultUnit = function ($food_id, $unit_id) {
//    FoodUnitsFactory.updateDefaultUnit($food_id, $unit_id).then(function (response) {
//        $scope.food_popup = response.data;
//    });
//};



module.exports = {
    template: '#recipes-page-template',
    data: function () {
        return {
            tags: [],
            recipes: [],
            recipesTagFilter: []
        };
    },
    components: {},
    computed: {
        //recipesTagFilter: function () {
        //    return _.pluck(this.tags, 'id');
        //}
    },
    methods: {

        /**
         *
         */
        getTags: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/recipeTags').then(function (response) {
                this.tags = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        getRecipes: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/recipes').then(function (response) {
                this.recipes = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getRecipes();
        this.getTags();
    }
};

module.exports = {
    template: '#temporary-recipe-popup-template',
    data: function () {
        return {
            showPopup: false,
            portion: 1,
            recipe: {
                ingredients: {}
            }
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getRecipe: function (recipe) {
            $.event.trigger('show-loading');
            this.$http.get('/api/recipes/' + recipe.id).then(function (response) {
                this.recipe = response;

                $(this.recipe.ingredients.data).each(function () {
                    this.originalQuantity = this.quantity;
                });

                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertFoodIntoTemporaryRecipe: function () {
            //we are adding a food to a temporary recipe
            var $unit_name = $("#temporary-recipe-popup-unit option:selected").text();
            this.temporaryRecipePopup.contents.push({
                "food_id": this.temporaryRecipePopup.food.id,
                "name": this.temporaryRecipePopup.food.name,
                "quantity": this.temporaryRecipePopup.quantity,
                "unit_id": $("#temporary-recipe-popup-unit").val(),
                "unit_name": $unit_name,
                "units": this.temporaryRecipePopup.food.units
            });

            $("#temporary-recipe-food-input").val("").focus();
        },

        /**
         *
         */
        setRecipePortion: function () {
            var that = this;
            $(this.recipe.ingredients.data).each(function () {
                if (this.originalQuantity) {
                    //making sure we don't alter the quantity of a food
                    //that has been added to the temporary recipe
                    //(by doing the if check)
                    this.quantity = this.originalQuantity * that.portion;
                }
            });
        },

        /**
         *
         */
        deleteIngredientFromTemporaryRecipe: function (ingredient) {
            this.recipe.ingredients.data = _.without(this.recipe.ingredients.data, ingredient);
        },

        /**
         *
         */
        closePopup: function ($event) {
            if ($event.target.className === 'popup-outer') {
                this.showPopup = false;
            }
        },

        /**
         *
         */
        insertEntriesForRecipe: function () {
            $.event.trigger('insert-entries-for-recipe', [this.recipe]);
            this.showPopup = false;
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-temporary-recipe-popup', function (event, recipe) {
                that.getRecipe(recipe);
                that.showPopup = true;
            });
            $(document).on('add-ingredient-to-temporary-recipe', function (event, ingredient) {
                console.log(ingredient);
                console.log(that.recipe.ingredients.data[0]);
                that.recipe.ingredients.data.push({
                    food: {
                        data: {
                            id: ingredient.food.id,
                            name: ingredient.food.name,
                            units: {data: ingredient.food.units.data},
                            defaultUnit: ingredient.food.defaultUnit,
                        }
                    },
                    unit: {data: ingredient.unit},
                    quantity: ingredient.quantity
                });
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //'insertEntriesForRecipe'
    ],
    ready: function () {
        this.listen();
    }
};

module.exports = {
    template: '#autocomplete-template',
    data: function () {
        return {
            autocompleteOptions: [],
            chosenOption: {
                name: ''
            },
            showDropdown: false,
            currentIndex: 0
        };
    },
    components: {},
    methods: {

        /**
         *
         * @param keycode
         */
        respondToKeyup: function (keycode) {
            if (keycode !== 13 && keycode !== 38 && keycode !== 40 && keycode !== 39 && keycode !== 37) {
                //not enter, up, down, right or left arrows
                this.populateOptions();
            }
            else if (keycode === 38) {
                //up arrow pressed
                if (this.currentIndex !== 0) {
                    this.currentIndex--;
                }
            }
            else if (keycode === 40) {
                //down arrow pressed
                if (this.autocompleteOptions.length - 1 !== this.currentIndex) {
                    this.currentIndex++;
                }
            }
            else if (keycode === 13) {
                this.respondToEnter();
            }
        },

        /**
         *
         */
        populateOptions: function () {
            //fill the dropdown
            $.event.trigger('show-loading');
            this.$http.get(this.url + '?typing=' + this.chosenOption.name, function (response) {
                this.autocompleteOptions = response.data;
                this.showDropdown = true;
                this.currentIndex = 0;
                $.event.trigger('hide-loading');
            })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        respondToEnter: function () {
            if (this.showDropdown) {
                //enter is for the autocomplete
                this.selectOption();
            }
            else {
                //enter is to add the entry
                this.insertItemFunction();
            }
        },

        /**
         *
         */
        selectOption: function () {
            this.chosenOption = this.autocompleteOptions[this.currentIndex];
            this.showDropdown = false;
            if (this.idToFocusAfterAutocomplete) {
                var that = this;
                setTimeout(function () {
                    $("#" + that.idToFocusAfterAutocomplete).focus();
                }, 100);
            }
            this.$dispatch('option-chosen', this.chosenOption);
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        'url',
        'autocompleteField',
        'insertItemFunction',
        'idToFocusAfterAutocomplete'
    ],
    ready: function () {

    }
};
var DatesRepository = require('../../repositories/DatesRepository');
// require('sugar');

module.exports = {
    template: '#date-navigation-template',
    data: function () {
        return {
            date: store.state.date
        };
    },
    components: {},
    watch: {
        'date.typed': function (newValue, oldValue) {
            $("#date").val(this.date.typed);
            $.event.trigger('date-changed');
        }
    },
    methods: {
        /**
         *
         * @param $number
         */
        goToDate: function ($number) {
            DatesRepository.goToDate($number);
        },

        /**
         *
         */
        goToToday: function () {
            DatesRepository.today();
        },

        /**
         *
         * @param date
         * @returns {boolean}
         */
        changeDate: function (date) {
            var date = date || $("#date").val();
            DatesRepository.changeDate(date);
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }

    },
    props: [

    ],
    ready: function () {

    }

};

module.exports = {
    template: "#feedback-template",
    data: function () {
        return {
            feedbackMessages: []
        };
    },
    methods: {
        listen: function () {
            var that = this;
            $(document).on('provide-feedback', function (event, message, type) {
                that.provideFeedback(message, type);
            });
            $(document).on('response-error', function (event, response) {
                that.provideFeedback(that.handleResponseError(response), 'error');
            })
        },
        provideFeedback: function (message, type) {
            var newMessage = {
                message: message,
                type: type
            };

            var that = this;

            this.feedbackMessages.push(newMessage);

            setTimeout(function () {
                that.feedbackMessages = _.without(that.feedbackMessages, newMessage);
            }, 3000);
        },
        handleResponseError: function (response) {
            if (typeof response !== "undefined") {
                var $message;

                switch(response.status) {
                    case 503:
                        $message = 'Sorry, application under construction. Please try again later.';
                        break;
                    case 401:
                        $message = 'You are not logged in';
                        break;
                    case 422:
                        var html = "<ul>";

                        for (var i = 0; i < response.length; i++) {
                            var error = response[i];
                            for (var j = 0; j < error.length; j++) {
                                html += '<li>' + error[j] + '</li>';
                            }
                        }

                        html += "</ul>";
                        $message = html;
                        break;
                    default:
                        $message = response.error;
                        break;
                }
            }
            else {
                $message = 'There was an error';
            }

            return $message;

        }
    },
    events: {
        'provide-feedback': function (message, type) {
            this.provideFeedback(message, type);
        },
        'response-error': function (response) {
            this.provideFeedback(this.handleResponseError(response), 'error');
        }
    },
    ready: function () {
        this.listen();
    },
};
module.exports = {
    data: function () {
        return {
            showLoading: false
        };
    },
    template: "#loading-template",
    props: [
        //'showLoading'
    ],
    methods: {
        listen: function () {
            var that = this;
            $(document).on('show-loading', function (event, message, type) {
                that.showLoading = true;
            });
            $(document).on('hide-loading', function (event, message, type) {
                that.showLoading = false;
            });
        }
    },
    ready: function () {
        this.listen();
    }
};
module.exports = {
    template: '#activities-page-template',
    data: function () {
        return {
            activities: [],
            newActivity: {},
        };
    },
    components: {},
    filters: {
        formatDuration: function (minutes) {
            return FiltersRepository.formatDuration(minutes);
        }
    },
    methods: {

        /**
         *
         */
        getActivities: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/activities').then(function (response) {
                this.activities = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertActivity: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newActivity.name,
                color: this.newActivity.color
            };

            this.$http.post('/api/activities', data).then(function (response) {
                this.activities.push(response);
                $.event.trigger('provide-feedback', ['Activity created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param activity
         */
        showActivityPopup: function (activity) {
            $.event.trigger('show-activity-popup', [activity]);
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getActivities();
    }
};

var ActivityPopup = Vue.component('activity-popup', {
    template: '#activity-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedActivity: {}
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        updateActivity: function () {
            $.event.trigger('show-loading');

            var data = {
                name: this.selectedActivity.name,
                color: this.selectedActivity.color
            };

            this.$http.put('/api/activities/' + this.selectedActivity.id, data).then(function (response) {
                var index = _.indexOf(this.activities, _.findWhere(this.activities, {id: this.selectedActivity.id}));
                this.activities[index] = response;
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Activity updated', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteActivity: function () {
            if (confirm("Are you sure? The timers for the activity will be deleted, too!")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/activities/' + this.selectedActivity.id).then(function (response) {
                    this.activities = _.without(this.activities, this.selectedActivity);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Activity deleted', 'success']);
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
        *
        */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-activity-popup', function (event, activity) {
                that.selectedActivity = activity;
                that.showPopup = true;
            });
        }
    },
    props: [
        'activities'
    ],
    ready: function () {
        this.listen();
    }
});

var DatesRepository = require('../../repositories/DatesRepository');

module.exports = {
    template: '#graphs-page-template',
    data: function () {
        return {
            date: store.state.date,
            timers: []
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getTimers: function () {
            $.event.trigger('show-loading');
            var url = TimersRepository.calculateUrl(false, this.date.sql);

            this.$http.get(url).then(function (response) {
                this.timers = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getTimers();
    }
};


module.exports = {
    template: '#new-manual-timer-template',
    data: function () {
        return {
            newManualTimer: {
                activity: {}
            },
            showPopup: true
        };
    },
    components: {},
    methods: {
        /**
         * Instead of starting and stopping the timer,
         * enter the start and stop times manually
         */
        insertManualTimer: function () {
            $.event.trigger('show-loading');
            var data = TimersRepository.setData(this.newManualTimer, this.date.sql);
            $('#timer-clock').timer({format: '%H:%M:%S'});

            this.$http.post('/api/timers/', data).then(function (response) {
                this.timers.push(response);
                console.log(router);
                $.event.trigger('manual-timer-created');
                $.event.trigger('provide-feedback', ['Manual entry created', 'success']);
                $.event.trigger('hide-loading');
                router.go('/timers');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        setDefaultActivity: function () {
            this.newManualTimer.activity = this.activities[0];
        },

        /**
         *
         */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('activities-loaded', function (event) {
                setTimeout(function () {
                    that.setDefaultActivity();
                }, 500);
            });

            $(document).on('show-new-manual-timer-popup', function (event) {
                if (that.$route.path.indexOf('/timers') !== -1) {
                    //We're on the timers page so we can show the popup
                    that.showPopup = true;
                }
                else {
                    //Wait for the timers page to load before showing the popup
                    setTimeout(function () {
                        that.showPopup = true;
                    }, 5000);
                }
            });
        }

    },
    props: [
        'activities',
        'date',
        'timers'
    ],
    ready: function () {
        this.listen();
        this.setDefaultActivity();
    }
};

var NewTimer = Vue.component('new-timer', {
    template: '#new-timer-template',
    data: function () {
        return {
            newTimer: {
                activity: {}
            },
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        startTimer: function () {
            $.event.trigger('show-loading');
            var data = TimersRepository.setData(this.newTimer);
            $('#timer-clock').timer({format: '%H:%M:%S'});

            this.$http.post('/api/timers/', data).then(function (response) {
                this.timerInProgress = response;
                $.event.trigger('provide-feedback', ['Timer started', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        stopTimer: function () {
            $.event.trigger('show-loading');
            $('#timer-clock').timer('remove');

            var data = {
                finish: TimersRepository.calculateFinishTime(this.timerInProgress)
            };

            this.$http.put('/api/timers/' + this.timerInProgress.id, data).then(function (response) {
                this.timerInProgress = false;
                this.timers.push(response);
                $.event.trigger('timer-stopped');
                $.event.trigger('provide-feedback', ['Timer updated', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        setDefaultActivity: function () {
            this.newTimer.activity = this.activities[0];
        },

        /**
         *
         */
        checkForTimerInProgress: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/timers/checkForTimerInProgress').then(function (response) {
                if (response.activity) {
                    this.resumeTimerOnPageLoad(response);
                }
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param timer
         */
        resumeTimerOnPageLoad: function (timer) {
            this.timerInProgress = timer;
            var seconds = moment().diff(moment(timer.start, 'YYYY-MM-DD HH:mm:ss'), 'seconds');
            $('#timer-clock').timer({
                format: '%H:%M:%S',
                //The timer has already started
                seconds: seconds
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('activities-loaded', function (event) {
                setTimeout(function () {
                    that.setDefaultActivity();
                }, 500);
            });
        }

    },
    props: [
        'activities',
        'timerInProgress',
        'showTimerInProgress',
        'timers'
    ],
    ready: function () {
        this.listen();
        this.checkForTimerInProgress();
    }
});

var TimerPopup = Vue.component('timer-popup', {
    template: '#timer-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedTimer: {
                id: '',
                start: '',
                finish: '',
                activity: {
                    data: {}
                }
            }
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        updateTimer: function () {
            $.event.trigger('show-loading');

            var data = {
                start: this.selectedTimer.start,
                finish: this.selectedTimer.finish,
                activity_id: this.selectedTimer.activity.data.id
            };

            this.$http.put('/api/timers/' + this.selectedTimer.id, data).then(function (response) {
                var index = _.indexOf(this.timers, _.findWhere(this.timers, {id: this.selectedTimer.id}));
                this.timers[index].start = response.start;
                this.timers[index].finish = response.finish;
                this.timers[index].activity = response.activity;
                $.event.trigger('provide-feedback', ['Timer updated', 'success']);
                this.showPopup = false;
                $.event.trigger('hide-loading');
            }, function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        },

        /**
         *
         */
        deleteTimer: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/timers/' + this.selectedTimer.id).then(function (response) {
                    $.event.trigger('timer-deleted', [this.selectedTimer]);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Timer deleted', 'success']);
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
        *
        */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-timer-popup', function (event, timer) {
                //So that the timer doesn't appear updated if the user closes the popup without saving
                that.selectedTimer.id = timer.id;
                that.selectedTimer.start = timer.start;
                that.selectedTimer.finish = timer.finish;
                that.selectedTimer.activity = timer.activity;
                that.showPopup = true;
            });
        }
    },
    props: [
        'activities',
        'timers'
    ],
    ready: function () {
        this.listen();
    }
});

module.exports = {
    template: '#timers-page-template',
    data: function () {
        return {
            date: store.state.date,
            timers: [],
            activities: [],
            timersFilter: false,
            activitiesFilter: '',
            activitiesWithDurationsForTheWeek: [],
            activitiesWithDurationsForTheDay: [],
            timerInProgress: false,
            showTimerInProgress: true,
        };
    },
    filters: {
        formatDateTime: function (dateTime, format) {
            if (!format) {
                return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('hh:mm:ssa DD/MM');
            }
            else if (format === 'seconds') {
                return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('ss a DD/MM');
            }
            else if (format === 'hoursAndMinutes') {
                return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('hh:mm');
            }
            else if (format === 'object') {
                return {
                    seconds: moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('ss')
                };
            }
        },
        doubleDigits: function (number) {
            if (number < 10) {
                return '0' + number;
            }

            return number;
        },
        formatDuration: function (minutes) {
            return FiltersRepository.formatDuration(minutes);
        }
    },
    components: {},
    methods: {

        /**
         *
         * @param timer
         */
        showTimerPopup: function (timer) {
            $.event.trigger('show-timer-popup', [timer]);
        },

        /**
         *
         */
        getActivities: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/activities').then(function (response) {
                this.activities = response;
                $.event.trigger('activities-loaded');
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        getTimers: function () {
            $.event.trigger('show-loading');
            var url = TimersRepository.calculateUrl(false, this.date.sql);

            this.$http.get(url).then(function (response) {
                this.timers = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param timer
         * @returns {boolean}
         */
        filterTimers: function (timer) {
            if (this.timersFilter) {
                return timer.activity.data.name.indexOf(this.timersFilter) !== -1;
            }
            return true;

        },

        /**
         *
         * @param minutes
         * @returns {number}
         */
        formatMinutes: function (minutes) {
            return minutes * 10;
        },

        /**
         *
         */
        getTotalMinutesForActivitiesForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/activities/getTotalMinutesForDay?date=' + this.date.sql).then(function (response) {
                this.activitiesWithDurationsForTheDay = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        getTotalMinutesForActivitiesForTheWeek: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/activities/getTotalMinutesForWeek?date=' + this.date.sql).then(function (response) {
                this.activitiesWithDurationsForTheWeek = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        ///**
        // *
        // */
        //showNewManualTimerPopup: function () {
        //    $.event.trigger('show-new-manual-timer-popup');
        //},

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('date-changed', function (event) {
                that.getTimers();
                that.getTotalMinutesForActivitiesForTheDay();
                that.getTotalMinutesForActivitiesForTheWeek();
            });

            $(document).on('timer-deleted', function (event, timer) {
                var index = HelpersRepository.findIndexById(that.timers, timer.id);
                that.timers = _.without(that.timers, that.timers[index]);
                that.getTotalMinutesForActivitiesForTheDay();
                that.getTotalMinutesForActivitiesForTheWeek();
            });

            $(document).on('timer-stopped', function (event) {
                that.getTotalMinutesForActivitiesForTheDay();
                that.getTotalMinutesForActivitiesForTheWeek();
            });

            $(document).on('manual-timer-created', function (event) {
                that.getTotalMinutesForActivitiesForTheDay();
                that.getTotalMinutesForActivitiesForTheWeek();
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            $.event.trigger('hide-loading');
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getActivities();
        this.getTimers();
        this.getTotalMinutesForActivitiesForTheDay();
        this.getTotalMinutesForActivitiesForTheWeek();
        this.listen();
    }
};
var DatesRepository = require('../repositories/DatesRepository');
var FiltersRepository = require('../repositories/FiltersRepository');

module.exports = {
    template: '#entries-page-template',
    data: function () {
        return {
            date: store.state.date,
            calories: {
                day: caloriesForTheDay,
                averageFor7Days: calorieAverageFor7Days,
            }
        }
    },
    components: {},
    filters: {
        roundNumber: function (number, howManyDecimals) {
            return FiltersRepository.roundNumber(number, howManyDecimals);
        }
    },
    methods: {
        /**
         *
         */
        mediaQueries: function () {
            // enquire.register("screen and (max-width: 890px", {
            //     match: function () {
            //         $("#avg-calories-for-the-week-text").text('Avg: ');
            //     },
            //     unmatch: function () {
            //         $("#avg-calories-for-the-week-text").text('Avg calories (last 7 days): ');
            //     }
            // });
        },

        /**
         * Get all the user's entries for the current date:
         * exercise entries
         * menu entries
         * weight
         * calories for the day
         * calorie average for the week
         */
        //getEntries: function () {
        //    $.event.trigger('get-entries');
        //},

        /**
         * Get calories for the day and average calories for 7 days
         */
        getCalorieInfoForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('api/calories/' + this.date.sql).then(function (response) {
                this.calories.day = response.data.forTheDay;
                this.calories.averageFor7Days = response.data.averageFor7Days;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('get-entries', function (event) {
                that.getCalorieInfoForTheDay();
            });
            $(document).on('date-changed', function (event) {
                that.getCalorieInfoForTheDay();
            });
            $(document).on('menu-entry-added, menu-entry-deleted', function (event) {
                that.getCalorieInfoForTheDay();
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        $("#food").val("");
        this.mediaQueries();
        this.listen();
    }
};
require('bootstrap');
//This didn't work
// require('bootstrap-wysiwyg');
var MediumEditor = require('medium-editor');
// require('summernote');

module.exports = {
    template: '#journal-page-template',
    data: function () {
        return {
            date: store.state.date,
            filterResults: [],
            journalEntry: {},
        };
    },
    components: {},
    methods: {
        /**
         *
         */
        getJournalEntry: function () {
            $.event.trigger('show-loading');
            this.$http.get('api/journal/' + this.date.sql).then(function (response) {
                this.journalEntry = response.data.data;
                this.$nextTick(function () {
                    var editor = new MediumEditor('.wysiwyg', {
                        // placeholder: false
                    });
                });

                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param entry
         */
        selectJournalEntryFromFilterResults: function (entry) {
            this.date = {
                typed: entry.date,
                sql: moment(entry.date, 'DD/MM/YY').format('YYYY-MM-DD HH:mm:ss')
            }
            this.getJournalEntry();
        },

        /**
         *
         */
        filterJournalEntries: function () {
            var typing = $("#filter-journal").val();

            $.event.trigger('show-loading');
            this.$http.get('/api/journal?typing=' + typing, function (response) {
                this.filterResults = response.data;
                $.event.trigger('hide-loading');
            })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        clearFilterResults: function () {
            this.filterResults = [];
            $("#filter-journal").val("");
        },

        /**
         * If the id of the journal entry exists, update the entry.
         * If not, insert the entry.
         */
        insertOrUpdateJournalEntry: function () {
            if (this.journalEntry.id) {
                this.updateEntry();
            }
            else {
                this.insertEntry();
            }
        },

        /**
         *
         */
        showNewSleepEntryPopup: function () {
            $.event.trigger('show-new-sleep-entry-popup');
        },

        /**
         *
         */
        updateEntry: function () {
            $.event.trigger('show-loading');

            var data = {
                text: $("#journal-entry").html()
            };

            this.$http.put('/api/journal/' + this.journalEntry.id, data).then(function (response) {
                this.journalEntry = response.data.data;
                $.event.trigger('provide-feedback', ['Entry updated', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertEntry: function () {
            $.event.trigger('show-loading');
            var data = {
                date: this.date.sql,
                text: $("#journal-entry").html()
            };

            this.$http.post('/api/journal', data).then(function (response) {
                this.journalEntry = response.data.data;
                $.event.trigger('provide-feedback', ['Entry created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('date-changed', function (event) {
                that.getJournalEntry();
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        // $(".wysiwyg").wysiwyg();

        // new MediumEditor('.editable');
        // $('.wysiwyg').summernote();
        this.listen();
        this.getJournalEntry();
    }
};


module.exports = {
    template: '#new-sleep-entry-template',
    data: function () {
        return {
            date: store.state.date,
            newSleepEntry: {
                startedYesterday: true
            },
            showPopup: false
        };
    },
    components: {},
    methods: {
        /**
         *
         */
        insertSleepEntry: function () {
            $.event.trigger('show-loading');
            var data = TimersRepository.setData(this.newSleepEntry, this.date.sql);

            this.$http.post('/api/timers', data).then(function (response) {
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Sleep entry created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-new-sleep-entry-popup', function (event) {
                that.showPopup = true;
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
    }
};

var Vue = require('vue');

module.exports = {
    template: '#weight-template',
    data: function () {
        return {
            weight: {},
            editingWeight: false,
            addingNewWeight: false,
            newWeight: {},
            store: store.state
        };
    },
    computed: {
        date: function () {
          return this.store.date;
        }
    },
    components: {},
    filters: {
        roundNumber: function (number, howManyDecimals) {
            return FiltersRepository.roundNumber(number, howManyDecimals);
        }
    },
    methods: {

        /**
         *
         */
        showNewWeightOrEditWeightFields: function () {
            if (this.weight.id) {
                this.showEditWeightFields();
            }
            else {
                this.showNewWeightFields();
            }
        },

        /**
         *
         */
        insertWeight: function () {
            $.event.trigger('show-loading');
            var data = {
                date: this.date.sql,
                weight: this.newWeight.weight
            };

            this.$http.post('/api/weights', data).then(function (response) {
                this.weight = response.data;
                this.addingNewWeight = false;
                $.event.trigger('provide-feedback', ['Weight created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        updateWeight: function () {
            $.event.trigger('show-loading');

            var data = {
                weight: this.weight.weight
            };

            this.$http.put('/api/weights/' + this.weight.id, data).then(function (response) {
                this.weight = response.data;
                this.editingWeight = false;
                $.event.trigger('provide-feedback', ['Weight updated', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        showNewWeightFields: function () {
            this.addingNewWeight = true;
            this.editingWeight = false;
        },

        /**
         *
         */
        showEditWeightFields: function () {
            this.editingWeight = true;
            this.addingNewWeight = false;
            setTimeout(function () {
                $("#weight").focus();
            }, 500);
        },

        /**
         *
         */
        getWeightForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('api/weights/' + this.date.sql).then(function (response) {
                this.weight = response.data;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('get-entries', function (event) {
                that.getWeightForTheDay();
            });
            $(document).on('date-changed', function (event) {
                that.getWeightForTheDay();
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [

    ],
    ready: function () {
        $("#weight").val("");
        this.getWeightForTheDay();
        this.listen();
    }
};


var Vue = require('vue');
var VueRouter = require('vue-router');
Vue.use(VueRouter);
global.$ = require('jquery');
global.jQuery = require('jquery');
global._ = require('underscore');
global.store = require('./repositories/SharedRepository');
var VueResource = require('vue-resource');
Vue.use(VueResource);
require('./config.js');
global.HelpersRepository = require('./repositories/HelpersRepository');
global.FiltersRepository = require('./repositories/FiltersRepository');
Date.setLocale('en-AU');

var App = Vue.component('app', {
    ready: function () {
        store.getExercises(this);
        store.getExerciseUnits(this);
        store.getExercisePrograms(this);
    }
});

//Components
Vue.component('navbar', require('./components/exercises/NavbarComponent'));
Vue.component('feedback', require('./components/shared/FeedbackComponent'));
Vue.component('loading', require('./components/shared/LoadingComponent'));
Vue.component('date-navigation', require('./components/shared/DateNavigationComponent'));
Vue.component('autocomplete', require('./components/shared/AutocompleteComponent'));


Vue.component('weight', require('./components/WeightComponent'));
Vue.component('new-menu-entry', require('./components/menu/NewMenuEntryComponent'));
Vue.component('new-food-entry', require('./components/menu/NewFoodEntryComponent'));
Vue.component('menu-entries', require('./components/menu/MenuEntriesComponent'));
Vue.component('temporary-recipe-popup', require('./components/menu/TemporaryRecipePopupComponent'));
Vue.component('new-exercise', require('./components/exercises/NewExerciseComponent'));
Vue.component('new-exercise-entry', require('./components/exercises/NewExerciseEntryComponent'));
Vue.component('exercise-entries', require('./components/exercises/ExerciseEntriesComponent'));
Vue.component('entries-for-specific-exercise-and-date-and-unit-popup', require('./components/exercises/EntriesForSpecificExerciseAndDateAndUnitPopupComponent'));
Vue.component('series-history-popup', require('./components/exercises/SeriesHistoryPopupComponent'));
Vue.component('series-popup', require('./components/exercises/SeriesPopupComponent'));
Vue.component('new-series', require('./components/exercises/NewSeriesComponent'));
Vue.component('exercise-popup', require('./components/exercises/ExercisePopupComponent'));
Vue.component('new-sleep-entry', require('./components/NewSleepEntryComponent'));

var router = new VueRouter({
    hashbang: false
});

router.map({
    '/': {
        component: require('./components/EntriesPageComponent'),
//         //subRoutes: {
//         //    //default for if no id is specified
//         //    '/': {
//         //        component: Item
//         //    },
//         //    '/:id': {
//         //        component: Item
//         //    }
//         //}
    },
    '/entries': {
        component: require('./components/EntriesPageComponent'),
    },
    '/exercises': {
        component: require('./components/exercises/ExercisesPageComponent')
    },
    '/exercise-units': {
        component: require('./components/exercises/ExerciseUnitsPageComponent')
    },
    '/foods': {
        component: require('./components/menu/FoodsPageComponent')
    },
    '/recipes': {
        component: require('./components/menu/RecipesPageComponent')
    },
    '/journal': {
        component: require('./components/JournalPageComponent')
    },
    '/food-units': {
        component: require('./components/menu/FoodUnitsPageComponent')
    },
    '/timers': {
        component: require('./components/timers/TimersPageComponent'),
        subRoutes: {
            //'/': {
            //    component: TimersPage
            //},
            '/new-manual': {
                component: require('./components/timers/NewManualTimerComponent')
            }
        }
    },
    '/activities': {
        component: require('./components/timers/ActivitiesPageComponent')
    },
    '/graphs': {
        component: require('./components/timers/GraphsPageComponent')
    },
});

router.start(App, 'body');





//# sourceMappingURL=all.js.map
