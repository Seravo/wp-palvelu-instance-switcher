function wpisSetShadow( instance , domain ){
	
	//Cookies.set('shadow', instance, { expires: 7, domain: domain,path: '/' });
	//document.cookie="shadow=" + instance + ";domain=" + domain + ";path=/";
	//alert(md5("http://" + window.location.hostname))
	
	//var oldAuthCookie = Cookies.get('shadow');
	var md5Url = md5('http://' + window.location.hostname);
	
	//alert(Cookies.get('wordpress_logged_in_0696ec620303196b914caed6c9a767bf'));
	//window.location.reload(true);
	//
}
function wpisSaveCookie( name , value, domain ){
	Cookies.set(name,value, {expires: 8000000, domain: domain, path: '/'});
	
}
