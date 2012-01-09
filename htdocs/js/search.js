laboratree.search = {};
laboratree.search.masks = {};
laboratree.search.dashboard = {};
laboratree.search.grids = {};
laboratree.search.stores = {};
laboratree.search.render = {};

laboratree.search.makeSearch = function(div, data_url, query) {
	Ext.onReady(function(){
		laboratree.search.dashboard = new laboratree.search.Dashboard(div, data_url, query);
	});
};

laboratree.search.Dashboard = function(div, data_url, query) {
	this.div = div;
	this.data_url = data_url;
	this.query = query;

	this.dashboard = new Ext.Panel({
		id: 'search-dashboard',
		title: 'Search',
		renderTo: div,
		layout: 'vbox',
		layoutConfig: {
			pack: 'start',
			align: 'stretch'
		},
		width: '100%',
		height: 690,

		items:[{
			xtype: 'form',
			id: 'search_form',
			anchor: '100%',
			layout: 'hbox',
			layoutConfig: {
				pack: 'start',
				align: 'stretch'
			},

			flex: 1,

			height: 35,

			frame: true,
			items: [{
				xtype: 'textfield',
				id: 'search_query',
				emptyText: 'Search...',
				flex: 1,
				enableKeyEvents: true,
				listeners: {
					keyup: function(textfield, e) {
						if(e.getKey() == 13) {
							laboratree.search.dashboard.search();
						}
					}
				}		
			},{
				xtype: 'button',
				id: 'search_btn',
				text: 'Search',
				width: 100,
				handler: function() {
					laboratree.search.dashboard.search();
				}
			}]
		},{
			xtype: 'tabpanel',
			id: 'search_tabs',
			anchor: '100% 100%',

			flex: 1
		}]
	});

	if(query) {
		Ext.getCmp('search_query').setValue(query);
		this.search();
	}
};

laboratree.search.Dashboard.prototype.search = function() {
	laboratree.search.masks.search = new Ext.LoadMask('search-dashboard', {
		msg: 'Loading...'
	});
	laboratree.search.masks.search.show();

	Ext.Ajax.request({
		url: this.data_url,
		params: {
			query: Ext.getCmp('search_query').getValue()
		},
		success: function(response, request) {
			var data = Ext.decode(response.responseText);
			if(!data) {
				request.failure(response, request);
				return;
			}

			if(!data.success) {
				request.failure(response, request);
				return;
			}

			var tabpanel = Ext.getCmp('search_tabs');
			if(!tabpanel) {
				request.failure(response, request);
				return;
			}

			tabpanel.removeAll();

			var tab;
			if(data.total == 0)
			{
				tab = tabpanel.add({
					xtype: 'panel',
					title: 'No Results',
					layout: 'fit',

					items: [{
						border: false,
						bodyStyle: 'text-align: center; font-weight: bold; padding-top: 280px;',
						html: 'No Results Found for \'' + data.query + '\'.'
					}]
				});

				tab.show();

				tabpanel.setActiveTab(0);

				tabpanel.doLayout();
			}

			var model;
			for(model in data.results)
			{
				if(data.results.hasOwnProperty(model)) {
					var modelId = model.replace(' ', '_');
					var results = data.results.model;

					laboratree.search.stores[modelId] = new Ext.data.ArrayStore({
						fields: ['id', 'uniqId', 'model', 'score', 'view', 'date', 'title', 'description'],
						data: results
					});

					laboratree.search.grids[modelId] = new Ext.grid.GridPanel({
						width: 718,
						height: 600,
						stripeRows: true,
						
						store: laboratree.search.stores[modelId],

						colModel: new Ext.grid.ColumnModel({
							defaults: {
								sortable: true
							},	

							columns: [{
								id: 'title',
								header: 'Title',
								dataIndex: 'title',
								width: 255,
								renderer: laboratree.search.render.title
							},{
								id: 'description',
								header: 'Description',
								dataIndex: 'description',
								width: 410,
								renderer: laboratree.search.render.description
							},{
								id: 'score',
								header: 'Score',
								dataIndex: 'score',
								width: 50,
								renderer: laboratree.search.render.score
							}]
						}),

						viewConfig: {
							forceFit: true
						},

						sm: new Ext.grid.RowSelectionModel({
							singleSelect: true
						}),

						bbar: new Ext.PagingToolbar({
							pageSize: 30,
							store: laboratree.search.stores[modelId],
							displayInfo: true,
							displayMsg: 'Displaying ' + model + ' {0} - {1} of {2}',
							emptyMsg: 'No ' + model + ' to display'
						})
					});

					tab = tabpanel.add({
						title: model,
						items: [laboratree.search.grids[model]]
					});

					tab.show();

					tabpanel.setActiveTab(0);

					tabpanel.doLayout();
				}
			}

			laboratree.search.masks.search.hide();
		},
		failure: function(resposne, request) {
			laboratree.search.masks.search.hide();
		}
	});	
};

laboratree.search.render.title = function(value, p, record) {
	return String.format('<a href="{0}" title="{1}">{1}</a>', record.data.view, value);
};

laboratree.search.render.description = function(value, p, record) {
	return Ext.util.Format.ellipsis(value, 80, true);
};

laboratree.search.render.score = function(value, p, record) {
	return Math.floor(value * 100) + '%';
};
