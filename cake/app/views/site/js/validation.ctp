laboratree.validation = {
	alphaNumeric: function(v) {
		return Ext.form.VTypes.alphanum(v);
	},
	between: function(v, min, max) {
		var len = v.length;
		return (len >= min && len <= max) ? true : false;
	},
	blank: function(v) {
		var regexp = /[^\\s]/;
		return !regexp.test(v);
	},
	isUnique: function(v) {
		return true;
	},
	notEmpty: function(v) {
		var regexp = /[^\s]+/m;
		return regexp.test(v);
	},
	email: function(v) {
		return Ext.form.VTypes.email(v);
	},
	url: function(v) {
		return Ext.form.VTypes.url(v);
	},
	minLength: function(v, min) {
		var len = v.length;
		return (len >= min);
	},
	maxLength: function(v, max) {
		var len = v.length;
		return (len <= max)
	},
	numeric: function(v) {
		var regexp = /^[-+]?\\b[0-9]*\\.?[0-9]+\\b$/;
		return regexp.test(v);
	},
	inList:	function(v, list) {
		if(v in list) {
			return true;
		}
		return false;
	},
	boolean: function(v) {
		if(v === 0 || v === 1 || v === '0' || v === '1' || v === true || v === false)
		{
			return true;
		}
		return false;
	},
	ip: function(v) {
		var regexp = /^(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])$/;
		return regexp.test(v);
	},
};
	
Ext.apply(Ext.form.VTypes, {
<?php
	foreach($validation as $model => $fields)
	{
		foreach($fields as $field => $rules)
		{
			if(array_key_exists('rule', $rules))
			{
				$rule = $rules['rule'];
				if(is_array($rule))
				{
					$rule = $rule[0];
				}

				$rules = array(
					$rule => $rules,
				);
			}

			$key = Inflector::variable(str_replace('-', '_', $model . '_' . $field));
			echo "\t$key: function(v) {\n";

			foreach($rules as $rule_id => $rule)
			{
				if(is_string($rule))
				{
					$rule = array(
						'rule' => $rule,
					);
				}

				if(!array_key_exists('rule', $rule))
				{
					continue;
				}

				$ruleName = $rule['rule'];
				if(is_array($rule['rule']))
				{
					$ruleName = $rule['rule'][0];
				}

				$message = 'Field is required.';
				if(array_key_exists('message', $rule))
				{
					$message = $rule['message'];
				}

				switch($ruleName) {
					case 'between':
						echo "\t\tif(!laboratree.validation.between(v, " . $rule['rule'][1] . ", " . $rule['rule'][2] . ")) {\n";
						echo "\t\t\tthis.{$key}Text = '" . addslashes($message) . "';\n";
						echo "\t\t\treturn false;\n";
						echo "\t\t}\n";
						break;
					case 'minLength':
						echo "\t\tif(!laboratree.validation.minLength(v, " . $rule['rule'][1] . ")) {\n";
						echo "\t\t\tthis.{$key}Text = '" . addslashes($message) . "';\n";
						echo "\t\t\treturn false;\n";
						echo "\t\t}\n";
						break;
					case 'maxLength':
						echo "\t\tif(!laboratree.validation.maxLength(v, " . $rule['rule'][1] . ")) {\n";
						echo "\t\t\tthis.{$key}Text = '" . addslashes($message) . "';\n";
						echo "\t\t\treturn false;\n";
						echo "\t\t}\n";
						break;
					case 'inList':
						echo "\t\tif(!laboratree.validation.inList(v, {\n";
						foreach($rule['rule'][1] as $entry)
						{
							echo "\t\t\t$entry: '',\n";
						}
						echo "\t\t})) {\n";
						echo "\t\t\tthis.{$key}Text = '" . addslashes($message) . "';\n";
						echo "\t\t\treturn false;\n";
						echo "\t\t}\n";
						break;
					default:
						echo "\t\tif(!laboratree.validation.$ruleName(v)) {\n";
						echo "\t\t\tthis.{$key}Text = '" . addslashes($message) . "';\n";
						echo "\t\t\treturn false;\n";
						echo "\t\t}\n";
				}
			}
			echo "\t\treturn true;\n";
			echo "\t},\n";
		}
	}
?>
});
