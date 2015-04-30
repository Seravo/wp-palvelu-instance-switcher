function wpisSetShadow( instance , domain ){
	Cookies.set('shadow', instance, { expires: 172800, domain: domain,path: '/' });
	window.location.reload(true);
}
