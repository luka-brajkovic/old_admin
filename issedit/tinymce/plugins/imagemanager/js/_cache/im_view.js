if (typeof deconcept == "undefined"){var deconcept = new Object(); }if (typeof deconcept.util == "undefined"){deconcept.util = new Object(); }if (typeof deconcept.SWFObjectUtil == "undefined"){deconcept.SWFObjectUtil = new Object(); }deconcept.SWFObject = function(_1, id, w, h, _5, c, _7, _8, _9, _a){if (!document.getElementById){return; }this.DETECT_KEY = _a?_a:"detectflash"; this.skipDetect = deconcept.util.getRequestParameter(this.DETECT_KEY); this.params = new Object(); this.variables = new Object(); this.attributes = new Array(); if (_1){this.setAttribute("swf", _1); }if (id){this.setAttribute("id", id); }if (w){this.setAttribute("width", w); }if (h){this.setAttribute("height", h); }if (_5){this.setAttribute("version", new deconcept.PlayerVersion(_5.toString().split("."))); }this.installedVer = deconcept.SWFObjectUtil.getPlayerVersion(); if (!window.opera && document.all && this.installedVer.major > 7){deconcept.SWFObject.doPrepUnload = true; }if (c){this.addParam("bgcolor", c); }var q = _7?_7:"high"; this.addParam("quality", q); this.setAttribute("useExpressInstall", false); this.setAttribute("doExpressInstall", false); var _c = (_8)?_8:window.location; this.setAttribute("xiRedirectUrl", _c); this.setAttribute("redirectUrl", ""); if (_9){this.setAttribute("redirectUrl", _9); }}; deconcept.SWFObject.prototype = {useExpressInstall:function(_d){this.xiSWFPath = !_d?"expressinstall.swf":_d; this.setAttribute("useExpressInstall", true); }, setAttribute:function(_e, _f){this.attributes[_e] = _f; }, getAttribute:function(_10){return this.attributes[_10]; }, addParam:function(_11, _12){this.params[_11] = _12; }, getParams:function(){return this.params; }, addVariable:function(_13, _14){this.variables[_13] = _14; }, getVariable:function(_15){return this.variables[_15]; }, getVariables:function(){return this.variables; }, getVariablePairs:function(){var _16 = new Array(); var key; var _18 = this.getVariables(); for (key in _18){_16[_16.length] = key + "=" + _18[key]; }return _16; }, getSWFHTML:function(){var _19 = ""; if (navigator.plugins && navigator.mimeTypes && navigator.mimeTypes.length){if (this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType", "PlugIn"); this.setAttribute("swf", this.xiSWFPath); }_19 = "<embed type=\"application/x-shockwave-flash\" src=\"" + this.getAttribute("swf") + "\" width=\"" + this.getAttribute("width") + "\" height=\"" + this.getAttribute("height") + "\" style=\"" + this.getAttribute("style") + "\""; _19 += " id=\"" + this.getAttribute("id") + "\" name=\"" + this.getAttribute("id") + "\" "; var _1a = this.getParams(); for (var key in _1a){_19 += [key] + "=\"" + _1a[key] + "\" "; }var _1c = this.getVariablePairs().join("&"); if (_1c.length > 0){_19 += "flashvars=\"" + _1c + "\""; }_19 += "/>"; } else{if (this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType", "ActiveX"); this.setAttribute("swf", this.xiSWFPath); }_19 = "<object id=\"" + this.getAttribute("id") + "\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\"" + this.getAttribute("width") + "\" height=\"" + this.getAttribute("height") + "\" style=\"" + this.getAttribute("style") + "\">"; _19 += "<param name=\"movie\" value=\"" + this.getAttribute("swf") + "\" />"; var _1d = this.getParams(); for (var key in _1d){_19 += "<param name=\"" + key + "\" value=\"" + _1d[key] + "\" />"; }var _1f = this.getVariablePairs().join("&"); if (_1f.length > 0){_19 += "<param name=\"flashvars\" value=\"" + _1f + "\" />"; }_19 += "</object>"; }return _19; }, write:function(_20){if (this.getAttribute("useExpressInstall")){var _21 = new deconcept.PlayerVersion([6, 0, 65]); if (this.installedVer.versionIsValid(_21) && !this.installedVer.versionIsValid(this.getAttribute("version"))){this.setAttribute("doExpressInstall", true); this.addVariable("MMredirectURL", escape(this.getAttribute("xiRedirectUrl"))); document.title = document.title.slice(0, 47) + " - Flash Player Installation"; this.addVariable("MMdoctitle", document.title); }}if (this.skipDetect || this.getAttribute("doExpressInstall") || this.installedVer.versionIsValid(this.getAttribute("version"))){var n = (typeof _20 == "string")?document.getElementById(_20):_20; n.innerHTML = this.getSWFHTML(); return true; } else{if (this.getAttribute("redirectUrl") != ""){document.location.replace(this.getAttribute("redirectUrl")); }}return false; }}; deconcept.SWFObjectUtil.getPlayerVersion = function(){var _23 = new deconcept.PlayerVersion([0, 0, 0]); if (navigator.plugins && navigator.mimeTypes.length){var x = navigator.plugins["Shockwave Flash"]; if (x && x.description){_23 = new deconcept.PlayerVersion(x.description.replace(/([a-zA-Z]|\s)+/, "").replace(/(\s+r|\s+b[0-9]+)/, ".").split(".")); }} else{if (navigator.userAgent && navigator.userAgent.indexOf("Windows CE") >= 0){var axo = 1; var _26 = 3; while (axo){try{_26++; axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash." + _26); _23 = new deconcept.PlayerVersion([_26, 0, 0]); } catch (e){axo = null; }}} else{try{var axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7"); } catch (e){try{var axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6"); _23 = new deconcept.PlayerVersion([6, 0, 21]); axo.AllowScriptAccess = "always"; } catch (e){if (_23.major == 6){return _23; }}try{axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash"); } catch (e){}}if (axo != null){_23 = new deconcept.PlayerVersion(axo.GetVariable("$version").split(" ")[1].split(",")); }}}return _23; }; deconcept.PlayerVersion = function(_29){this.major = _29[0] != null?parseInt(_29[0]):0; this.minor = _29[1] != null?parseInt(_29[1]):0; this.rev = _29[2] != null?parseInt(_29[2]):0; }; deconcept.PlayerVersion.prototype.versionIsValid = function(fv){if (this.major < fv.major){return false; }if (this.major > fv.major){return true; }if (this.minor < fv.minor){return false; }if (this.minor > fv.minor){return true; }if (this.rev < fv.rev){return false; }return true; }; deconcept.util = {getRequestParameter:function(_2b){var q = document.location.search || document.location.hash; if (_2b == null){return q; }if (q){var _2d = q.substring(1).split("&"); for (var i = 0; i < _2d.length; i++){if (_2d[i].substring(0, _2d[i].indexOf("=")) == _2b){return _2d[i].substring((_2d[i].indexOf("=") + 1)); }}}return ""; }}; deconcept.SWFObjectUtil.cleanupSWFs = function(){var _2f = document.getElementsByTagName("OBJECT"); for (var i = _2f.length - 1; i >= 0; i--){_2f[i].style.display = "none"; for (var x in _2f[i]){if (typeof _2f[i][x] == "function"){_2f[i][x] = function(){}; }}}}; if (deconcept.SWFObject.doPrepUnload){if (!deconcept.unloadSet){deconcept.SWFObjectUtil.prepUnload = function(){__flash_unloadHandler = function(){}; __flash_savedUnloadHandler = function(){}; window.attachEvent("onunload", deconcept.SWFObjectUtil.cleanupSWFs); }; window.attachEvent("onbeforeunload", deconcept.SWFObjectUtil.prepUnload); deconcept.unloadSet = true; }}if (!document.getElementById && document.all){document.getElementById = function(id){return document.all[id]; }; }var getQueryParamValue = deconcept.util.getRequestParameter; var FlashObject = deconcept.SWFObject; var SWFObject = deconcept.SWFObject; (function($){window.ViewDialog = {currentWin:$.WindowManager.find(window), init:function(){var t = this, args; t.args = args = $.extend({path:'{default}', visual_path:'/'}, t.currentWin.getArgs()); t.singeViewTpl = $.templateFromScript('#singleview_template'); t.mpgTpl = $.templateFromScript('#mpg_template'); t.rmTpl = $.templateFromScript('#rm_template'); t.movTpl = $.templateFromScript('#mov_template'); t.dcrTpl = $.templateFromScript('#dcr_template'); t.footerFullTpl = $.templateFromScript('#single_footer_full'); t.footerNoEditTpl = $.templateFromScript('#single_footer_no_edit'); t.footerSimpleTpl = $.templateFromScript('#single_footer_simple'); $('#prev').click(function(e){if (!$(e.target).hasClass('disabled'))t.getMediaInfo(t.prevMedia); }); $('#next').click(function(e){if (!$(e.target).hasClass('disabled'))t.getMediaInfo(t.nextMedia); }); $('#gallery').click(function(e){if (!$(e.target).hasClass('disabled'))t.currentWin.close(); }); $('#singlefooter').click(function(e){var el = e.target, a = el.nodeName == 'A'?el:$(el).parents('a')[0]; if (a){a = $(a); if (a.attr('id') == 'deleteit' && !a.hasClass('disabled'))t.deleteFile(t.path); if (a.attr('id') == 'edit' && !a.hasClass('disabled'))t.editFile(t.path); }}); $().keydown(function(e){switch (e.keyCode){case 32:case 110:case 34:case 39:case 40:t.getMediaInfo(t.nextMedia); break; case 102:case 33:case 37:case 38:t.getMediaInfo(t.prevMedia); break; case 27:t.currentWin.close(); break; }}); $(window).bind('resize', function(){t.resizeView(); }); $('#singlecontent').click(function(e){if (e.target.nodeName == 'IMG' && $(e.target).hasClass('viewimage')){if (args.onselect){RPC.insertFile({relative_urls:args.relative_urls, document_base_url:s.document_base_url, default_base_url:s.default_base_url, no_host:args.remove_script_host || args.no_host, path:t.path, progress_message:$.translate("{#message.insert}"), insert_filter:args.insert_filter, oninsert:function(o){args.onselect(o); t.currentWin.close(); }}); } else t.currentWin.close(); }}); t.getMediaInfo(args.path); focus(); }, getMediaInfo:function(p){var t = this; t.path = p; RPC.exec('im.getMediaInfo', {path:p}, function(data){var res, row, tpl, footTpl; res = RPC.toArray(data.result); row = res[0]; t.nextMedia = row.next; t.prevMedia = row.prev; if (!row.next)$('#next').addClass('disabled'); else $('#next').removeClass('disabled'); if (!row.prev)$('#prev').addClass('disabled'); else $('#prev').removeClass('disabled'); footTpl = t.footerSimpleTpl; switch (row.type){case "jpg":case "jpeg":case "gif":case "png":case "bmp":tpl = t.singeViewTpl; footTpl = t.footerFullTpl; break; case "mpg":case "mpeg":case "wma":case "wmv":case "asf":case "avi":tpl = t.mpgTpl; break; case "qt":case "mov":tpl = t.movTpl; break; case "rm":case "ram":tpl = t.rmTpl; break; case "dcr":tpl = t.dcrTpl; break; case "swf":var so = new SWFObject("../../stream/index.php?cmd=im.streamFile&path=" + escape(row.path), "swfMovie", row.width, row.height, "7", "#FFFFFF"); so.addParam("quality", "high"); so.addParam("scale", "showall"); so.addParam("wmode", "transparent"); so.write("singlecontent"); $('#singlefooter').html(t.footerNoEditTpl, row); return; case "flv":var so = new SWFObject("flvplayer/flvPlayer.swf", "flvPlayer", row.width, row.height, "8", "#FFFFFF"); so.addVariable("flvToPlay", "../../../stream/index.php?cmd=im.streamFile%26path=" + escape(row.path)); so.addVariable("hiddenGui", "false"); so.addVariable("showScaleModes", "true"); so.addVariable("autoStart", "false"); so.addParam("allowFullScreen", "true"); so.write("singlecontent"); $('#singlefooter').html(t.footerNoEditTpl, row); return; }$('#singlecontent').html(tpl, row); $('#singlefooter').html(footTpl, row); if (row.custom){if (!row.custom.editable)$('#singleview .editsingle a.edt').addClass('disabled'); else $('#singleview .editsingle a.edt').removeClass('disabled'); }t.resizeView(); }); }, resizeView:function(){$('#singleimg').css({'width':$.winWidth() - 60, 'height':$.winHeight() - 100}); }, deleteFile:function(p){var t = this; $.WindowManager.confirm($.translate('{#view.confirm_delete}'), function(s){if (s){if (!t.isDemo()){RPC.exec('im.deleteFiles', {path0:p}, function(data){var args; if (!RPC.handleError({message:'{#error.delete_failed}', visual_path:t.args.visual_path, response:data})){if (t.args.ondelete)t.args.ondelete(p); if (t.nextMedia)t.getMediaInfo(t.nextMedia); else if (t.prevMedia)t.getMediaInfo(t.prevMedia); else t.currentWin.close(); }}); }}}); }, editFile:function(p){var t = this; $.WindowManager.open({url:'edit.html'}, {is_demo:t.args.is_demo, path:p, visual_path:this.visualPath, onsave:function(){t.getMediaInfo(t.path); }}).maximize(); }, isDemo:function(){if (this.args.is_demo){$.WindowManager.info($.translate('{#error.demo}')); return true; }}}; $(function(e){ViewDialog.init(); }); })(jQuery);