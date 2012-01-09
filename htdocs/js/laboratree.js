/*jslint bitwise: true, browser: true, continue: true, unparam: true, rhino: true, sloppy: true, eqeq: true, sub: false, vars: true, white: true, plusplus: true, maxerr: 150, indent: 4 */
/*global laboratree: false, Ext: false */ 
if(!Array.prototype.map) {
	Array.prototype.map = function(fun) {
		var len = this.length >>> 0;

		if(typeof fun != "function") {
			throw new TypeError();
		}

		var res = Array[len];
		var thisp = arguments[1];

		var i;
		for(i = 0; i < len; i++) {
			if(i in this) {
				res[i] = fun.call(thisp, this[i], i, this);
			}
		}
		return res;
	};
}

if(!Array.prototype.filter) {
	Array.prototype.filter = function(fun) {
		var len = this.length;

		if(typeof fun != "function") {
			throw new TypeError();
		}

		var res = Array[len];
		var thisp = arguments[1];

		var i;
		for(i = 0; i < len; i++) {
			if(i in this) {
				var val = this[i];
				if(fun.call(thisp, val, i, this)) {
					res.push(val);
				}
			}
		}

		return res;
	};
}

if(!Array.prototype.diff) {
	Array.prototype.diff = function(a) {
		return this.filter(function(i) {return !(a.indexOf(i) > -1);});
	};
}

Ext.BLANK_IMAGE_URL = 'http://static.selican.com/img/extjs/default/s.gif';

var laboratree = {
	LOG_DEBUG: 0,
	LOG_INFO: 1,
	LOG_WARN: 2,
	LOG_ERROR: 3,
	LOG_FATAL: 4,
	LOG_NONE: 5
};

laboratree.logLevel = laboratree.LOG_NONE;
laboratree.log = function(level, msg) {
	if(!level) {
		level = laboratree.LOG_FATAL;
	}

	if(level < laboratree.logLevel) {
		return;
	}

	if(console) {
		switch(level) {
			case laboratree.LOG_DEBUG:
				console.debug(msg);
				break;
			case laboratree.LOG_INFO:
				console.info(msg);
				break;
			case laboratree.LOG_WARN:
				console.warn(msg);
				break;
			case laboratree.LOG_ERROR:
				//console.error(msg);
				console.log(msg);
				break;
			case laboratree.LOG_FATAL:
				//console.error(msg);
				console.log(msg);
				break;
			default:
				console.log(msg);
		}
	}
}

laboratree.format = function(format) {
	var result = String.format.apply(this, arguments);
	return result.replace('undefined', '');
}

laboratree.debug = function(msg) { laboratree.log(laboratree.LOG_DEBUG, msg); }
laboratree.info = function(msg) { laboratree.log(laboratree.LOG_INFO, msg); }
laboratree.warn = function(msg) { laboratree.log(laboratree.LOG_WARN, msg); }
laboratree.error = function(msg) { laboratree.log(laboratree.LOG_ERROR, msg); }
laboratree.fatal = function(msg) { laboratree.log(laboratree.LOG_FATAL, msg); }

laboratree.util = {};
laboratree.util.camelize = function(str) {
	if(!str) {
		return null;
	}

	var camelize = Ext.util.Format.capitalize(str);
	
	if(str.indexOf('_') != -1) {
		var words = str.split('_');

		for(var idx in words) {
			if(typeof words[idx] == 'string') {
				words[idx] = Ext.util.Format.capitalize(words[idx]);
			}
		}

		camelize = words.join('');
	}

	return camelize;
}

laboratree.ContactBox = function(config) {
	this.linkTpl = new Ext.Template(
		'<a class="contact-token" href="#" tabindex="-1" id="token_{token}">',
		'<input type="hidden" name="data[Contact][tokens][]" value="{token}" />',
		'{name}',
		'<span class="contact-token-remove" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" ext:qtip="Click to Remove">&nbsp;</span>',
		'</a>'
	);
	this.linkTpl.compile();

	Ext.form.ComboBox.superclass.constructor.call(this, config);
}

Ext.extend(laboratree.ContactBox, Ext.form.ComboBox, {
	setValue: function(v) {
		var record = this.findRecord(this.valueField, v);
		if(record) {
			this.addLink(record);
		}

		this.setRawValue('');
		this.lastSelectionText = '';
		this.value = '';

		return this;
	},
	addLink: function(record) {
		if(!Ext.get('token_' + record.data.token)) {
			var link = this.linkTpl.apply(record.data);
			Ext.DomHelper.insertBefore(this.el, link);
		}
	},
	removeLink: function(record) {
		if(Ext.get('token_' + record.data.token)) {
			//remove link
		}
	}
});

Ext.reg('contactbox', laboratree.ContactBox);

Date.prototype.subtract = function(interval, value) {
	return this.add(interval, (value * -1));
}

Date.prototype.toUTC = function(time) {
	var local = new Date();
	var offset = local.getTimezoneOffset();

	/**
	 * This is add instead of subtract because
	 * the offset is positive if the timezone
	 * is negative, and vice-versa.
	 */
	var adjusted = this.add(Date.MINUTE, offset);

	return adjusted;
}

Date.prototype.toLocal = function() {
	var local = new Date();
	var offset = local.getTimezoneOffset();

	/**
	 * This is subtract instead of add because
	 * the offset is positive if the timezone
	 * is negative, and vice-versa.
	 */
	var adjusted = this.subtract(Date.MINUTE, offset);

	return adjusted;
}

Ext.override(Ext.grid.CheckboxSelectionModel, {
	clearSelections: Ext.grid.CheckboxSelectionModel.prototype.clearSelections.createSequence(function(fast){
		var hdCheckbox = this.grid.getEl().select('.x-grid3-hd-checker-on');
		if(hdCheckbox) {
			hdCheckbox.removeClass('x-grid3-hd-checker-on');
		}
	}),
});

if(typeof Range != 'undefined') {
	if (typeof Range.prototype.createContextualFragment == "undefined") {
		Range.prototype.createContextualFragment = function (html) {
			var doc = window.document;
			var container = doc.createElement("div");
			container.innerHTML = html;
			var frag = doc.createDocumentFragment(), n;
			while ((n = container.firstChild)) {
				frag.appendChild(n);
			}
			return frag;
		};
	}
}

laboratree.helpPopup = function(windowTitle, windowContent) {
	var helpWindow = new Ext.Window({
		title: windowTitle,
		layout: 'fit',
		width: 300,
		padding: 10,
		closeAction: 'hide',

		items: {
			xtype: 'container',
			html: windowContent 
		},

		buttons: [{
			text: 'Close',
			handler: function(){
				helpWindow.close();
			}
		}]
	});

        helpWindow.show();
};
