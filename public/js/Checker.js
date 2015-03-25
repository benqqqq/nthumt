var checker = {
	inputs : [],
	constrains : [],
	
	add : function(input, constrain) {
		var required = function(input) {			
				return $(input).val() != '';
			};
		
		var con = constrain || required;
	
		this.inputs.push(input);
		this.constrains.push(con);
	},
	
	check : function() {
		var result = {invalids : []};
		for (var i in this.inputs) {
			var input = this.inputs[i];
			var isValid = this.constrains[i];
			if (!isValid(input)) {
				result.invalids.push(input);
			}
		}
		result.isPass = result.invalids.length == 0; 
		return result;
	},
}