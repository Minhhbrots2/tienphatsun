'use strict';
window.fbAsyncInit = function() {
    FB.init({
      appId      : '1812964272353811',
      xfbml      : true,
      version    : 'v2.9'
    });
    //FB.AppEvents.logPageView();
	FB.getLoginStatus(function(response) {
		if (response.status == "connected") {
			console.log('User authorized.');
		}else{
			console.log('User not authorized.');
		}
	});
};  
(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/vi_VN/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document));
function onSignIn(googleUser) {
  var profile = googleUser.getBasicProfile();
  console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
  console.log('Name: ' + profile.getName());
  console.log('Image URL: ' + profile.getImageUrl());
  console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
}
$(function(){
	$_document.on('click', '.signin-via-google.clickable', function(ev){
		ev.preventDefault();
		var $_this = $(this),
			mod_page = $_this.attr('mod_page'),
			act_page = $_this.attr('act_page');
		ggLogin(mod_page,act_page);
		return false;
	});
	$_document.on('click', '.signin-via-facebook.clickable', function(ev){
		ev.preventDefault();
		var $_this = $(this),
			mod_page = $_this.attr('mod_page'),
			act_page = $_this.attr('act_page');
		FB.login(function(response) {
			if (response.authResponse) {	
				fbLogin(response.authResponse.accessToken,mod_page,act_page);
			}else {
				console.log('User cancelled login or did not fully authorize.');
			}
		},{
			scope: 'public_profile,email'
		});
	});
	$('.logOutMe').click(function(e){
		e.preventDefault();
		$("#gbLoading").show();
		$.ajax ({
			type : "POST",
			url: path_ajax_script+"/index.php?mod=ajax&act=ajLogOutMe",
			dataType : "html",
			success : function(html){
				location.reload();
			}
		});
		return false;
	});
})
function fbLogin(accessToken,mod_page,act_page){
	var ajaxURL = "/checkAccountAJAX/";
	FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'}, function (response) {
		$.ajax({
		type : "POST",
		url : ajaxURL,
		data : {oauth_provider: '_facebook', fbUser: response},
		dataType : 'html',
		success : function(data){
				$("#gbLoading").hide();
				if(data.indexOf('_success') >= 0) {
					var htm = data.split('$$');
					if($.trim(return_url)==''){
						return_url = '/';
					}
					location.href = return_url;
				} else if(html.indexOf('_locked') >= 0 || html.indexOf('_existingAccount') >= 0){
					$Core.swal.error("Oops","Tài khoản của bạn đã tạm thời bị khóa hoặc không tồn tại.\n\t"
					+"Vui lòng liên hệ với quản trị viên để được hỗ trợ!");
				}
			}
		});
	});
}
function doLogOut(){
	if(_login_facebook){
		FB.getLoginStatus(function(response) {
			if (response.status == "connected") {
				/*FB.logout(function(response){});*/
			}
		}); 
	}
}
function ggLogin(mod_page, act_page) {
	var win       = window.open(_url, "LoginWithGoogle", 'width=800, height=600'); 
	var pollTimer = window.setInterval(function() { 
		try {
			if (win.document.URL.indexOf(REDIRECT) != -1) {
				window.clearInterval(pollTimer);
				var url =   win.document.URL;
				acToken =   gup(url, 'access_token');
				tokenType = gup(url, 'token_type');
				expiresIn = gup(url, 'expires_in');
				win.close();
				validateToken(acToken,mod_page,act_page);
			}
		} catch(e) {
			
		}
	}, 500);
}
function validateToken(token,mod_page,act_page) {
	$.ajax({
		url: VALIDURL + token,
		success: function(responseText){ 
			loggedIn = true;
			getUserInfo(mod_page,act_page);
		},  
		dataType: "jsonp"  
	});
}
function getUserInfo(mod_page,act_page) {
	$.ajax({
		url: 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' + acToken,
		success: function(resp) {
			var user = resp;
			$.ajax({
				url:'/checkGoogleAccount/',
				type:"POST",
				data:{
					'id':user.id,
					'link':user.link,
					'email':user.email,
					'full_name':user.name,
					'avatar':user.picture,
					'family_name':user.family_name,
					'given_name':user.given_name,
					'gender':user.gender,
					'verified_email':user.verified_email,
				},
				success: function(html){
					if(html.indexOf('_success') >= 0) {
						var htm = html.split('$$');
						if($.trim(return_url)==''){
							return_url = '/';
						}
						location.href = return_url;
					} else if(html.indexOf('_locked') >= 0 || html.indexOf('_existingAccount') >= 0){
						$Core.swal.error("Oops","Tài khoản của bạn đã tạm thời bị khóa hoặc không tồn tại.\n\t"
						+"Vui lòng liên hệ với quản trị viên để được hỗ trợ!");
					} 
					return false;
				}
			});
		},
		dataType: "jsonp"
	});
}
function gup(url, name) {
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\#&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( url );
	if( results == null )
		return "";
	else
		return results[1];
}