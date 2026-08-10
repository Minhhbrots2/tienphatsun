<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Link Management - ISOCMS</title>
    <script type="text/javascript" src="{$PCMS_URL}/editor/jquery/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="{$PCMS_URL}/editor/tiny_mce/tiny_mce_popup.js"></script>
	<script type="text/javascript" src="{$PCMS_URL}/editor/tiny_mce/utils/mctabs.js"></script>
	<script type="text/javascript" src="{$PCMS_URL}/editor/tiny_mce/utils/form_utils.js"></script>
	<script type="text/javascript" src="{$PCMS_URL}/editor/tiny_mce/utils/validate.js"></script>
	<script type="text/javascript" src="{$PCMS_URL}/editor/tiny_mce/plugins/advlink/js/advlink.js"></script>
	<link href="{$PCMS_URL}/editor/tiny_mce/plugins/advlink/css/advlink.css" rel="stylesheet" type="text/css" />
</head>
<body id="advlink" style="" dir="ltr" screen_capture_injected="true">
    <form onsubmit="insertAction();return false;" action="#">
		<div class="tabs">
			<ul>
				<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;">General</a></span></li>
				<li id="popup_tab"><span><a href="javascript:mcTabs.displayTab('popup_tab','popup_panel');" onmousedown="return false;">Popup</a></span></li>
				<li id="events_tab"><span><a href="javascript:mcTabs.displayTab('events_tab','events_panel');" onmousedown="return false;">Events</a></span></li>
				<li id="advanced_tab"><span><a href="javascript:mcTabs.displayTab('advanced_tab','advanced_panel');" onmousedown="return false;">Advanced</a></span></li>
			</ul>
		</div>

		<div class="panel_wrapper">
			<div id="general_panel" class="panel current">
				<fieldset>
					<legend>General properties</legend>

					<table border="0" cellpadding="4" cellspacing="0">
						<tbody><tr>
						  <td class="nowrap"><label id="hreflabel" for="href">Link URL</label></td>
						  <td><table border="0" cellspacing="0" cellpadding="0">
								<tbody><tr>
								  <td><input id="href" name="href" type="text" class="mceFocus" value="" onchange="selectByValue(this.form,'linklisthref',this.value);">
                                  <!--Ajax to save Tags for post-->
                                <link rel="stylesheet" href="{$URL_CSS}/jquery.autocomplete.css" type="text/css" />
                                <script type="text/javascript" src="{$URL_JS}/jquery.bgiframe.min.js"></script>
                                <script type="text/javascript" src="{$URL_JS}/jquery.autocomplete.min.js"></script>
                                {$listAvailableLink}
                                  {literal}
                                  <script type="text/javascript">
								  	$(document).ready(function(){
										$('#href').autocomplete(availableTags, {
											minChars: 0,
											width: 350,
											matchContains: true,
											autoFill: false,
											formatItem: function(row, i, max) {
												return row.name;
											},
											formatResult: function(row) {
												return row.val;
											}
										});
									});
								  </script> 
                                  {/literal}
                                  </td>
								  <td id="hrefbrowsercontainer"></td>
								</tr>
							  </tbody></table></td>
						</tr>
						<tr id="linklisthrefrow" style="display: none; ">
							<td class="column1"><label for="linklisthref">Link list</label></td>
							<td colspan="2" id="linklisthrefcontainer"></td>
						</tr>
						<tr>
							<td class="column1"><label for="anchorlist">Anchors</label></td>
							<td colspan="2" id="anchorlistcontainer"><select id="anchorlist" name="anchorlist" class="mceAnchorList" o2nfocus="tinyMCE.addSelectAccessibility(event, this, window);" onchange="this.form.href.value=this.options[this.selectedIndex].value;"><option value="">---</option></select></td>
						</tr>
						<tr>
							<td><label id="targetlistlabel" for="targetlist">Target</label></td>
							<td id="targetlistcontainer"><select id="targetlist" name="targetlist" onf2ocus="tinyMCE.addSelectAccessibility(event, this, window);" onchange="this.form.target.value=this.options[this.selectedIndex].value;"><option value="_self">Open in this window / frame</option><option value="_blank">Open in new window (_blank)</option><option value="_parent">Open in parent window / frame (_parent)</option><option value="_top">Open in top frame (replaces all frames) (_top)</option></select></td>
						</tr>
						<tr>
							<td class="nowrap"><label id="titlelabel" for="title">Title</label></td>
							<td><input id="title" name="title" type="text" value=""></td>
						</tr>
						<tr>
							<td><label id="classlabel" for="classlist">Class</label></td>
							<td>
								 <select id="classlist" name="classlist" onchange="changeClass();">
									<option value="" selected="selected">-- Not set --</option>
								 <option value="aligncenter">aligncenter</option><option value="alignleft">alignleft</option><option value="alignright">alignright</option><option value="wp-caption">wp-caption</option><option value="wp-caption-dd">wp-caption-dd</option></select>
							</td>
						</tr>
					</tbody></table>
				</fieldset>
			</div>

			<div id="popup_panel" class="panel">
				<fieldset>
					<legend>Popup properties</legend>

					<input type="checkbox" id="ispopup" name="ispopup" class="radio" onclick="setPopupControlsDisabled(!this.checked);buildOnClick();">
					<label id="ispopuplabel" for="ispopup">Javascript popup</label>

					<table border="0" cellpadding="0" cellspacing="4">
						<tbody><tr>
							<td class="nowrap"><label for="popupurl">Popup URL</label>&nbsp;</td>
							<td>
								<table border="0" cellspacing="0" cellpadding="0">
									<tbody><tr>
										<td><input type="text" name="popupurl" id="popupurl" value="" onchange="buildOnClick();" disabled=""></td>
										<td id="popupurlbrowsercontainer"></td>
									</tr>
								</tbody></table>
							</td>
						</tr>
						<tr>
							<td class="nowrap"><label for="popupname">Window name</label>&nbsp;</td>
							<td><input type="text" name="popupname" id="popupname" value="" onchange="buildOnClick();" disabled=""></td>
						</tr>
						<tr>
							<td class="nowrap"><label>Size</label>&nbsp;</td>
							<td class="nowrap">
								<input type="text" id="popupwidth" name="popupwidth" value="" onchange="buildOnClick();" disabled=""> x
								<input type="text" id="popupheight" name="popupheight" value="" onchange="buildOnClick();" disabled=""> px
							</td>
						</tr>
						<tr>
							<td class="nowrap" id="labelleft"><label>Position (X/Y)</label>&nbsp;</td>
							<td class="nowrap">
								<input type="text" id="popupleft" name="popupleft" value="" onchange="buildOnClick();" disabled=""> /                                
								<input type="text" id="popuptop" name="popuptop" value="" onchange="buildOnClick();" disabled=""> (c /c = center)
							</td>
						</tr>
					</tbody></table>

					<fieldset>
						<legend>Options</legend>

						<table border="0" cellpadding="0" cellspacing="4">
							<tbody><tr>
								<td><input type="checkbox" id="popuplocation" name="popuplocation" class="checkbox" onchange="buildOnClick();" disabled=""></td>
								<td class="nowrap"><label id="popuplocationlabel" for="popuplocation">Show location bar</label></td>
								<td><input type="checkbox" id="popupscrollbars" name="popupscrollbars" class="checkbox" onchange="buildOnClick();" disabled=""></td>
								<td class="nowrap"><label id="popupscrollbarslabel" for="popupscrollbars">Show scrollbars</label></td>
							</tr>
							<tr>
								<td><input type="checkbox" id="popupmenubar" name="popupmenubar" class="checkbox" onchange="buildOnClick();" disabled=""></td>
								<td class="nowrap"><label id="popupmenubarlabel" for="popupmenubar">Show menu bar</label></td>
								<td><input type="checkbox" id="popupresizable" name="popupresizable" class="checkbox" onchange="buildOnClick();" disabled=""></td>
								<td class="nowrap"><label id="popupresizablelabel" for="popupresizable">Make window resizable</label></td>
							</tr>
							<tr>
								<td><input type="checkbox" id="popuptoolbar" name="popuptoolbar" class="checkbox" onchange="buildOnClick();" disabled=""></td>
								<td class="nowrap"><label id="popuptoolbarlabel" for="popuptoolbar">Show toolbars</label></td>
								<td><input type="checkbox" id="popupdependent" name="popupdependent" class="checkbox" onchange="buildOnClick();" disabled=""></td>
								<td class="nowrap"><label id="popupdependentlabel" for="popupdependent">Dependent (Mozilla/Firefox only)</label></td>
							</tr>
							<tr>
								<td><input type="checkbox" id="popupstatus" name="popupstatus" class="checkbox" onchange="buildOnClick();" disabled=""></td>
								<td class="nowrap"><label id="popupstatuslabel" for="popupstatus">Show status bar</label></td>
								<td><input type="checkbox" id="popupreturn" name="popupreturn" class="checkbox" onchange="buildOnClick();" checked="checked" disabled=""></td>
								<td class="nowrap"><label id="popupreturnlabel" for="popupreturn">Insert 'return false'</label></td>
							</tr>
						</tbody></table>
					</fieldset>
				</fieldset>
			</div>

			<div id="advanced_panel" class="panel">
			<fieldset>
					<legend>Advanced properties</legend>

					<table border="0" cellpadding="0" cellspacing="4">
						<tbody><tr>
							<td class="column1"><label id="idlabel" for="id">Id</label></td> 
							<td><input id="id" name="id" type="text" value=""></td> 
						</tr>

						<tr>
							<td><label id="stylelabel" for="style">Style</label></td>
							<td><input type="text" id="style" name="style" value=""></td>
						</tr>

						<tr>
							<td><label id="classeslabel" for="classes">Classes</label></td>
							<td><input type="text" id="classes" name="classes" value="" onchange="selectByValue(this.form,'classlist',this.value,true);"></td>
						</tr>

						<tr>
							<td><label id="targetlabel" for="target">Target name</label></td>
							<td><input type="text" id="target" name="target" value="" onchange="selectByValue(this.form,'targetlist',this.value,true);"></td>
						</tr>

						<tr>
							<td class="column1"><label id="dirlabel" for="dir">Language direction</label></td> 
							<td>
								<select id="dir" name="dir"> 
										<option value="">-- Not set --</option> 
										<option value="ltr">Left to right</option> 
										<option value="rtl">Right to left</option> 
								</select>
							</td> 
						</tr>

						<tr>
							<td><label id="hreflanglabel" for="hreflang">Target language</label></td>
							<td><input type="text" id="hreflang" name="hreflang" value=""></td>
						</tr>

						<tr>
							<td class="column1"><label id="langlabel" for="lang">Language code</label></td> 
							<td>
								<input id="lang" name="lang" type="text" value="">
							</td> 
						</tr>

						<tr>
							<td><label id="charsetlabel" for="charset">Target character encoding</label></td>
							<td><input type="text" id="charset" name="charset" value=""></td>
						</tr>

						<tr>
							<td><label id="typelabel" for="type">Target MIME type</label></td>
							<td><input type="text" id="type" name="type" value=""></td>
						</tr>

						<tr>
							<td><label id="rellabel" for="rel">Relationship page to target</label></td>
							<td><select id="rel" name="rel"> 
									<option value="">-- Not set --</option> 
									<option value="lightbox">Lightbox</option> 
									<option value="alternate">Alternate</option> 
									<option value="designates">Designates</option> 
									<option value="stylesheet">Stylesheet</option> 
									<option value="start">Start</option> 
									<option value="next">Next</option> 
									<option value="prev">Prev</option> 
									<option value="contents">Contents</option> 
									<option value="index">Index</option> 
									<option value="glossary">Glossary</option> 
									<option value="copyright">Copyright</option> 
									<option value="chapter">Chapter</option> 
									<option value="subsection">Subsection</option> 
									<option value="appendix">Appendix</option> 
									<option value="help">Help</option> 
									<option value="bookmark">Bookmark</option>
									<option value="nofollow">No Follow</option>
									<option value="tag">Tag</option>
								</select> 
							</td>
						</tr>

						<tr>
							<td><label id="revlabel" for="rev">Relationship target to page</label></td>
							<td><select id="rev" name="rev"> 
									<option value="">-- Not set --</option> 
									<option value="alternate">Alternate</option> 
									<option value="designates">Designates</option> 
									<option value="stylesheet">Stylesheet</option> 
									<option value="start">Start</option> 
									<option value="next">Next</option> 
									<option value="prev">Prev</option> 
									<option value="contents">Contents</option> 
									<option value="index">Index</option> 
									<option value="glossary">Glossary</option> 
									<option value="copyright">Copyright</option> 
									<option value="chapter">Chapter</option> 
									<option value="subsection">Subsection</option> 
									<option value="appendix">Appendix</option> 
									<option value="help">Help</option> 
									<option value="bookmark">Bookmark</option> 
								</select> 
							</td>
						</tr>

						<tr>
							<td><label id="tabindexlabel" for="tabindex">Tabindex</label></td>
							<td><input type="text" id="tabindex" name="tabindex" value=""></td>
						</tr>

						<tr>
							<td><label id="accesskeylabel" for="accesskey">Accesskey</label></td>
							<td><input type="text" id="accesskey" name="accesskey" value=""></td>
						</tr>
					</tbody></table>
				</fieldset>
			</div>

			<div id="events_panel" class="panel">
			<fieldset>
					<legend>Events</legend>

					<table border="0" cellpadding="0" cellspacing="4">
						<tbody><tr>
							<td class="column1"><label for="onfocus">onfocus</label></td> 
							<td><input id="onfocus" name="onfocus" type="text" value=""></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onblur">onblur</label></td> 
							<td><input id="onblur" name="onblur" type="text" value=""></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onclick">onclick</label></td> 
							<td><input id="onclick" name="onclick" type="text" value=""></td> 
						</tr>

						<tr>
							<td class="column1"><label for="ondblclick">ondblclick</label></td> 
							<td><input id="ondblclick" name="ondblclick" type="text" value=""></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onmousedown">onmousedown</label></td> 
							<td><input id="onmousedown" name="onmousedown" type="text" value=""></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onmouseup">onmouseup</label></td> 
							<td><input id="onmouseup" name="onmouseup" type="text" value=""></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onmouseover">onmouseover</label></td> 
							<td><input id="onmouseover" name="onmouseover" type="text" value=""></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onmousemove">onmousemove</label></td> 
							<td><input id="onmousemove" name="onmousemove" type="text" value=""></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onmouseout">onmouseout</label></td> 
							<td><input id="onmouseout" name="onmouseout" type="text" value=""></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onkeypress">onkeypress</label></td> 
							<td><input id="onkeypress" name="onkeypress" type="text" value=""></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onkeydown">onkeydown</label></td> 
							<td><input id="onkeydown" name="onkeydown" type="text" value=""></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onkeyup">onkeyup</label></td> 
							<td><input id="onkeyup" name="onkeyup" type="text" value=""></td> 
						</tr>
					</tbody></table>
				</fieldset>
			</div>
		</div>

		<div class="mceActionPanel">
			<input type="submit" id="insert" name="insert" value="Insert">
			<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();">
		</div>
    </form>


<div style="display:none" id="addthis-extension-script"></div></body>
</html>
