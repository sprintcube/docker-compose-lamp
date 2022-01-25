function selectorElSwitch(k,t){
	var sel = $('#edit-fields > main');
	
	for(let i = 0; i < sel.length; i++){
		let keyName = sel.eq(i).find('input#field'),
			selectorData = sel.eq(i).find('select#fieldType > option');
			
		if(t === 'ds'){
			if(keyName === k.capitalize()){
				selectorData.eq(5).prop('selected', true);
			}
		}
		if(t === 'phg'){
			if(keyName === k.capitalize()){
				selectorData.eq(2 * 3).prop('selected', true);
			}
		}
		if(t === 'cst'){
			if(keyName === k.capitalize()){
				selectorData.eq(6 - 2).prop('selected', true);
			}
		}
		if(t === 'int'){
			if(keyName === k.capitalize()){
				selectorData.eq(6 / 3).prop('selected', true);
			}
		}
		
		if(t === 'pcr'){
			if(keyName === k.capitalize()){
				selectorData.eq(6 / 2).prop('selected', true);
			}
		}
		
		if(t === 'slc'){
			if(keyName === k.capitalize()){
				selectorData.eq(6 + 1).prop('selected', true);
			}
		}
		else{
			if(keyName === k.capitalize()){
				selectorData.eq(1).prop('selected', true);
			}
		}
	}
	
}


const isSmartDS = (data) => {
	let r = data.response;
	
	for(const key of r.keys()){
		var d = r[key];
		
		for(let i = 0; i < d.length; i++){
			var validPm = JSON.parse(d[i]);
			
			if(validPm.df || validPm.ds){ selectorElSwitch(key, 'ds'); }
		}
	}
}
const isPhotogallery = (data) => {
	let r = data.response;
	
	for(const key of r.keys()){
		var d = r[key];
		
		for(let i = 0; i < d.length; i++){
			var validPm = JSON.parse(d[i]);
			
			if(validPm.imageFormats && validPm.imageCounts){ selectorElSwitch(key, 'phg'); }
		}
	}
}
const isCost = (data) => {
	let r = data.response;
	
	for(const key of r.keys()){
		var d = r[key];
		
		for(let i = 0; i < d.length; i++){
			var validPm = ParseFloat(d[i]);
			
			if(validPm){ selectorElSwitch(key, 'cst'); }
		}
	}
}
const isInteger = (data) => {
	let r = data.response;
	
	for(const key of r.keys()){
		var d = r[key];
		
		for(let i = 0; i < d.length; i++){
			var validPm = ParseInt(d[i]);
			
			if(validPm){ selectorElSwitch(key, 'int'); }
		}
	}
}
const isPrecentable = (data) => {
	let r = data.response;
	
	for(const key of r.keys()){
		var d = r[key];
		
		for(let i = 0; i < d.length; i++){
			var validPm = ParseInt(d[i]);
			
			if(validPm){ selectorElSwitch(key, 'pcr'); }
		}
	}
}
const isSelecting = (data) => {
	let r = data.response;
	
	for(const key of r.keys()){
		var d = r[key];
		
		for(let i = 0; i < d.length; i++){
			var validPm = (d[i].indexOf('[') && d[i].indexOf(']')) && d[i].match('/[,]/g').length === 1;
			
			if(validPm){ selectorElSwitch(key, 'slc'); }
		}
	}
}
const isDefault = (data) => {
	let r = data.response;
	
	for(const key of r.keys()){
		var d = r[key];
		selectorElSwitch(key, 'txt');
	}
}
