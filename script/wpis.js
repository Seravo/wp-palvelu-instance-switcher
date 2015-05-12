function wpisSetShadow( instance ){
	Cookies.set('shadow', instance, { expires: 172800 ,path: '/' });
	window.location.reload(true);
}
