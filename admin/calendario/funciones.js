function ValidaFecha(a){
	var err=0
	if (a.length != 10) err = 1; 
	d = a.substring(0, 2) // dia
	c = a.substring(2, 3)  // '/'
	b = a.substring(3, 5)  // mes
	e = a.substring(5, 6)  // '/'
	f = a.substring(6, 10)  // año
	// test basico de error
	if (b<1 || b>12) err = 1; 
	if (c != '/')    err = 1; 
	if (d<1 || d>31) err = 1; 
	if (e != '/')    err = 1; 
	if (f< 1900 || f > 2999) err = 1; 
//test avanzado de error
	// meses de 30 days
	if (b==4 || b==6 || b==9 || b==11){
		if (d==31) err=1
	}
	// febrero, año bisiesto
	if (b==2){
		// feb
		var g=parseInt(f/4)
		if (isNaN(g)) {
			err=1
		}
		if (d>29) err=1
		if (d==29 && ((f/4)!=parseInt(f/4))) err=1
	}
	if (err==1){
		return false;
	}
	else{
		return true;
	}
}

