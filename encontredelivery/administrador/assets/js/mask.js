function mascaraMonetaria(valor) {
	var count          = 0;
	var valueEntire    = valor.split(".")[0];
	var valueDecimal   = valor.split(".")[1];
	var newValueEntire = "";
	
	for (var i = (valueEntire.length - 1); i >= 0; i--) {
		if (count == 3) {
			newValueEntire = "." + newValueEntire;
			count          = 0;
		}
		
		newValueEntire = valueEntire.charAt(i) + newValueEntire;
		count++;
	}
	
	return newValueEntire + "," + valueDecimal;
}
