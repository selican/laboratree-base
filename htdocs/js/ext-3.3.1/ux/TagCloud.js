Ext.namespace('Ext.ux');

/**
 * @class Ext.ux.TagCloud
 * @extends Ext.Component
 * This class represents a tag cloud with support for remote loading and tag selection
 * @constructor
 * Create a new TagCloud
 * @param {Object} config A configuration object that sets properties for this tag cloud
 * The configuration object supports these properties:
         * <ul style="padding:5px;padding-left:16px;">
	     * <li>{Ext.data.Store} store - The data store for the cloud</li>
	     * <li>{String} displayField - The field from the data store to display</li>
		 * <li>{String} weightField - The field from the data store used to determine the ranking or "weight"</li>
		 * <li>{Boolean} displayWeight - (Optional) When true, the items in the cloud will also visually show their weight
	     * </ul>
 */
Ext.ux.TagCloud = function(config){

    // Call the parent class constructor 
	Ext.ux.TagCloud.superclass.constructor.call(this, config);

	if(this.store)
		this.setStore(this.store);
        
    this.displayField = this.displayField || 'tag';
    this.weightField = this.weightField || 'count';
		
    // private
    this.addEvents({
    
        /**
         * @event tagselect
         * Fires when a tag is selected (clicked on)
         * @param {Ext.ux.TagCloud}     cloud This tag cloud
         * @param {Ext.data.Record}     record The data record returned from the underlying store
         * @param {int}                 index The index of the selected item in the tag cloud
         */
        'tagselect' : true
    
    });
    
    this.nodes = [];
    
}	
 
Ext.extend(Ext.ux.TagCloud, Ext.Container, {
	defaultAutoCreate: {
		tag: 'div',
		cls: 'x-cloud'
	},
    
	getDataSource: function() {
		return this.store;
	},
    
	setStore: function(store) {
		store.on('load', this.refresh, this);
		this.store = store;

		this.store.remoteSort = false;
		this.store.sort(this.displayField, 'ASC');
	},

	onRender: function(ct, position) {	
		this.container = ct;
	
		if(this.el) {
			this.el = Ext.get(this.el);
			if(!this.target) {
				ct.dom.appendChild(this.el.dom);
			}
		} else {
			var cfg = this.getAutoCreate();
			if(!cfg.name) {
				cfg.name = this.name || this.id;
			}
			this.el = ct.createChild(cfg, position);
		}

		this.list = this.el.createChild({
			tag: 'div',
			cls: 'x-cloud-div'
		});
	},

	refresh: function() {
		this.clearNodes();
		this.getWeightDistribution();
        
		var records = this.store.getRange();
		for(var i=0; i < records.length; i++) {
			var count = records[i].data[this.weightField];
			var child = this.list.createChild({
				tag: 'a', 
				cls: 'x-cloud-item ' + this.getWeightClassification(count),
				html: records[i].data[this.displayField] + (this.displayWeight ? ' (' + count + ')' : '')
                	});
			
			child.on('click', this.onSelect, this);
		}
		
		this.list.fadeIn({
			duration: 0.5,
			block: true
		});
		
		this.nodes = this.list.dom.childNodes;
	},
	
	clearNodes: function() {
		while(this.list.dom.firstChild) {
			this.list.dom.removeChild(this.list.dom.firstChild);
		}
	},

	onSelect: function(e, t) {
		var item = t.parentNode;
		var index = this.indexOf(item);
        
		var selected = this.list.query('.x-cloud-item-selected');
		if(selected.length > 0) {
			Ext.get(selected[0]).removeClass('x-cloud-item-selected');
		}

		this.fireEvent('tagselect', this, this.getDataSource().getAt(index), index);
        
		Ext.EventObject.stopEvent(e);
	},
    
	indexOf: function(node) {
		var ns = this.nodes;
		for(var i = 0, len = ns.length; i < len; i++) {
			if(ns[i] == node) {
				return i;
			}
		}
		return -1;
	},

	getWeightClassification: function(weight) {
		if(weight == this.max) {
			return 'largest';
		} else if(weight == this.min) {
			return 'smallest';
		} else if(weight > (this.min + (this.distribution * 2))) {
			return 'large';
		} else if(weight > (this.min + this.distribution)) {
			return 'medium';
		}

		return 'small';
	},

	// private
	getWeightDistribution: function() {
		var records = this.store.getRange();
		if(records.length == 0) {
			this.max = this.min = 0;
			return;
		}
		
		this.max = records[0].data.count;
		this.min = records[0].data.count;

		for(var i = 0; i < records.length; i++) {
			var count = records[i].data[this.weightField];
			if(count > this.max) {
				this.max = count;
			}
			if(count < this.min) {
				this.min = count;
			}
		}

		if(!this.distribution) {
			this.distribution = (this.max - this.min) / 5;
		}
	}
});
