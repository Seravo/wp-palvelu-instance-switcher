function wpisSaveCookies( instance , domain ){
	
	Cookies.set('shadow', instance, { expires: 7, domain: domain,path: '/' });
	//document.cookie="shadow=" + instance + ";domain=" + domain + ";path=/";
	alert(md5("http://" + window.location.hostname))
	
	//window.location.reload(true);
	
}
