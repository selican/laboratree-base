laboratree.projects = {};
laboratree.projects.masks = {};
laboratree.projects.edit = {};
laboratree.projects.members = {};
laboratree.projects.create = {};

laboratree.projects.dashboard = {};

laboratree.projects.makeAddUser = function(div, data_url) {
	Ext.onReady(function() {
		laboratree.projects.adduser = new laboratree.projects.AddUser(div, data_url);

		Ext.Ajax.request({
			url: data_url,
			params: {
				action: 'pending'
			},
			success: function(response, request) {
				var data = Ext.decode(response.responseText);
				if(!data) {
					request.failure(response, request);
					return;
				}

				if(data.errors) {
					request.failure(response, request);
					return;
				}
			},
			failure: function(response, request) {
			}
		}, this);
	});
};

laboratree.projects.AddUser = function(div, data_url) {
	Ext.QuickTips.init();

	this.div = div;
	this.data_url = data_url;

	this.colleaguesStore = new Ext.data.JsonStore({
		root: 'colleagues',
		autoLoad: true,
		url: data_url,

		params: {
			action: 'colleagues'
		},

		idProperty: 'token',

		fields: [
			'token', 'name'
		]
	});

	this.colleaguesStore.setDefaultSort('name');

	this.colleaguesSm = new Ext.grid.CheckboxSelectionModel();

	this.searchStore = new Ext.data.JsonStore({
		root: 'results',
		url: data_url,

		params: {
			action: 'search'
		},

		idProperty: 'token',

		fields: [
			'token', 'name'
		]
	});

	this.searchStore.setDefaultSort('name');

	this.searchSm = new Ext.grid.CheckboxSelectionModel();

	this.search = new Ext.grid.GridPanel({
		id: 'search',
		
		anchor: '100%',
		height: 350,
		bodyStyle: 'border: 1px solid #bbbbbb;',
		store: this.searchStore,

		sm: this.searchSm,

		loadMask: {msg: 'Loading...'},

		cm: new Ext.grid.ColumnModel({
			defaults: {
				sortable: true
			},
			columns: [
			this.searchSm, {
				id: 'name',
				header: 'Name',
				dataIndex: 'name',
				width: 130,
				renderer: this.renderName
			}]
		}),

		tbar: [{
			xtype: 'textfield',
			id: 'search_query',
			emptyText: 'Search...',
			width: 445,
			enableKeyEvents: true,
			listeners: {
				keyup: function(textfield, e) {
					if(e.getKey() == 13) {
						laboratree.projects.adduser.doSearch();
					}
				}
			}
		},{
			xtype: 'button',
			id: 'search_btn',
			text: 'Search',
			width: 60,
			handler: function() {
				laboratree.projects.adduser.doSearch();
			}
		}],

		viewConfig: {
			forceFit: true
		}
	});

	this.colleagues = new Ext.grid.GridPanel({
		id: 'colleagues',
		
		anchor: '100%',
		height: 350,
		bodyStyle: 'border: 1px solid #bbbbbb;',
		store: this.colleaguesStore,

		sm: this.colleaguesSm,

		loadMask: {msg: 'Loading...'},

		cm: new Ext.grid.ColumnModel({
			defaults: {
				sortable: true
			},
			columns: [
			this.colleaguesSm, {
				id: 'name',
				header: 'Name',
				dataIndex: 'name',
				width: 100,
				renderer: this.renderName
			}]
		}),

		viewConfig: {
			forceFit: true
		}
	});

	this.form = new Ext.form.FormPanel({
		id: 'adduser',
		renderTo: div,
		layout: 'hbox',

		layoutConfig: {
			align: 'stretch',
			pack: 'start'
		},

		border: false,

		width: '100%',
		height: 450,
		padding: 10,

		buttonAlign: 'center',

		labelWidth: 75,

		defaults: {
			cls: 'section'
		},

		items: [{
			xtype: 'tabpanel',
			activeTab: 0,

			width: '65%',
			height: 450,

			frame: true,

			items: [{
				title: 'Add Colleagues',
				layout: 'absolute',
				height: 440,
				width: 440,
				frame: true,
				id: 'addCol',

				items: [
					this.colleagues
				],

				buttons: [{
					text: 'Add',
					cls: 'x-btn-text-icon',
					iconCls: 'x-btn-plus',
					handler: this.handleAddUser
				}]
			},{
				title: 'Search Users',
				layout: 'absolute',
				height: 440,
				width: 440,
				frame: true,
				id: 'searchPan',

				items: [
					this.search	
				],

				buttons: [{
					text: 'Add',
					cls: 'x-btn-text-icon',
					iconCls: 'x-btn-plus',
					handler: this.handleAddUser
				}]
			}]
		}]
	});
};

laboratree.projects.AddUser.prototype.doSearch = function() {
	var query = Ext.getCmp('search_query');
	if(query) {
		this.searchStore.load({
			params: {
				action: 'search',
				query: query.getValue()
			}
		});
	}
};

laboratree.projects.AddUser.prototype.handleAddUser = function() {
	var added = {};
	var add_users = [];
	var selections = [];
	var add = null;

	selections = laboratree.projects.adduser.searchSm.getSelections();
	Ext.each(selections, function(record) {
		added[record.data.token] = 1;
	});

	selections = laboratree.projects.adduser.colleaguesSm.getSelections();
	Ext.each(selections, function(record) {
		added[record.data.token] = 1;
	});

	var token;
	for(token in added) {
		if(added.hasOwnProperty(token)) {
			add_users.push(token);
		}
	}

	Ext.Ajax.request({
		url: laboratree.projects.adduser.data_url,
		params: {
			add_users: add_users.join(','),
		},
		success: function(response, request) {
			var data = Ext.decode(response.responseText);
			if(!data) {
				request.failure(response, request);
				return;
			}

			if(data.error) {
				request.failure(response, request);
				return;
			}

			laboratree.projects.adduser.searchStore.reload();
			laboratree.projects.adduser.colleaguesStore.reload();
		},
		failure: function(response, request) {
			var data = Ext.decode(response.responseText);
		},
		scope: this
	});
};

laboratree.projects.makeList = function(div, title, data_url) {
	Ext.onReady(function() {
		laboratree.projects.list = new laboratree.projects.List(div, title, data_url);
	});
};

laboratree.projects.List = function(div, title, data_url) {
	Ext.QuickTips.init();

	this.store = new Ext.data.JsonStore({
		root: 'projects',
		autoLoad: true,
		url: data_url,

		fields: [
			'id', 'name', 'members', 'role', 'permission'
		]
	});	

	this.store.setDefaultSort('name');

	var gridConfig = {
		id: 'projects',
		title: title,
		renderTo: div,
		width: '100%',
		height: 600,

		tools: [{
			id: 'refresh',
			qtip: 'Refresh Projects',
			handler: function(event, toolEl, panel, tc) {
				panel.store.reload();
			}
		}],

		store: this.store,
		loadMask: true,
		cm: new Ext.grid.ColumnModel({
			defaults: {
				sortable: true
			},
			columns: [{
				id: 'name',
				header: 'Name',
				dataIndex: 'name',
				width: 580,
				renderer: this.renderName
			},{
				id: 'role',
				header: 'Role',
				dataIndex: 'role',
				align: 'center',
				width: 120
			},{
				id: 'members',
				header: 'Members',
				align: 'center',
				dataIndex: 'members',
				width: 90
			},{
				id: 'actions',
				header: 'Actions',
				dataIndex: 'id',
				align: 'center',
				width: 170,
				renderer: this.renderActions
			}]
		}),

		bbar: new Ext.PagingToolbar({
			pageSize: 30,
			store: this.store,
			displayInfo: true,
			displayMsg: 'Displaying project {0} - {1} of {2}',
			emptyMsg: 'No projects to display'
		})
	};

	if(laboratree.site.permissions.project.add & laboratree.context.permissions.group) {
		gridConfig.title += ' <span class="create-link"><a href="' + String.format(laboratree.links.projects.create, laboratree.context.group_id) + '">- create project -</a></span>';

		gridConfig.tools.unshift({
			id:'plus',
			qtip: 'Create Project',
			handler: function() {
				window.location = String.format(laboratree.links.projects.create, laboratree.context.group_id);
			}
		});
	}

	this.grid = new Ext.grid.GridPanel(gridConfig);
};

laboratree.projects.List.prototype.deleteProject = function(project_id) {
	Ext.Msg.confirm('Delete Project', 'Are you sure?', function(btn) {
		if(btn == 'yes') {
			Ext.Ajax.request({
				url: String.format(laboratree.links.projects['delete'], project_id) + '.json',
				success: function(response, request) {
					var data = Ext.decode(response.responseText);
					if(data.error) {
						request.failure(response, request);
						return;
					}
					
					var record = laboratree.projects.list.store.getById(project_id);
					if(record) {
						laboratree.projects.list.store.remove(record);
					}
				},
				failure: function(response, request) {

				}
			});
		}
	});
};

laboratree.projects.List.prototype.leaveProject = function(project_id) {
	Ext.Msg.confirm('Leave Project', 'Are you sure?', function(btn) {
		if(btn == 'yes') {
			window.location = String.format(laboratree.links.projects.leave, project_id);
		}
	});
};

laboratree.projects.List.prototype.renderName = function(value, p, record) {
	return String.format('<a href="' + laboratree.links.projects.dashboard + '" title="{1}">{1}</a>', record.data.id, value);
};

laboratree.projects.List.prototype.renderActions = function(value, p, record) {
	var permission = 0;
	if(record.data.permission && record.data.permission.project) {
		permission = parseInt(record.data.permission.project);
	}

	var actions = '';

	if(laboratree.site.permissions.project.edit & permission) {
		actions += String.format('<a href="' + laboratree.links.projects.edit + '" title="Edit {1}">Edit</a>', value, record.data.name);
	}

	if(actions != '') {
		actions += '&nbsp;|&nbsp;';
	}

	actions += String.format('<a href="#" onclick="laboratree.projects.list.leaveProject({0}); return false;" title="Leave {1}">Leave</a>', value, record.data.name);

	if(laboratree.site.permissions.project['delete'] & permission) {
		if(actions != '') {
			actions += '&nbsp;|&nbsp;';
		}

		actions += String.format('<a href="#" onclick="laboratree.projects.list.deleteProject({0}); return false;" title="Delete {1}">Delete</a>', value, record.data.name);
	}

	return actions;
};

laboratree.projects.makeEdit = function(div) {
	Ext.onReady(function() {
		laboratree.projects.edit = new laboratree.projects.Edit(div);

		laboratree.projects.masks.edit = new Ext.LoadMask('edit', {
			msg: 'Loading...'
		});
		laboratree.projects.masks.edit.show();

		Ext.Ajax.request({
			url: String.format(laboratree.links.projects.edit, laboratree.context.project_id) + '.json',
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

				if(!data.project) {
					request.failure(response, request);
					return;
				}

				var name = Ext.getCmp('ProjectName');
				if(name) {
					name.setValue(data.project.name);
				}

				laboratree.projects.masks.edit.hide();
			},
			failure: function(response, request) {
				laboratree.projects.masks.edit.hide();
			}
		});
	});
};

laboratree.projects.Edit = function(div) {
	Ext.QuickTips.init();

	this.data_url = String.format(laboratree.links.projects.edit, laboratree.context.project_id) + '.json';

	this.stores = {};

	this.form = new Ext.FormPanel({
		id: 'edit',
		labelAlign: 'top',
		title: 'Edit Project',
		width: '100%',
		height: 160,
		renderTo: div,
		buttonAlign: 'center',
		fileUpload: true,
		standardSubmit: true,
		forceLayout: true,

		defaults: {
			autoHeight: true,
			labelWidth: 75,
			forceLayout: true,
			anchor: '100%'
		},
		
		items: [{
			id: 'ProjectName',
			xtype: 'textfield',
			fieldLabel: 'Name',
			name: 'data[Project][name]',
			allowBlank: false,
			vtype: 'projectName'
		},{
			id: 'ProjectPicture',
			xtype: 'fileuploadfield',
			fieldLabel: 'Picture',
			name: 'data[Project][picture]',
			emptyText: 'Select a GIF, JPG, or PNG image...',
			anchor: '100%',
			vtype: 'projectPicture'
		}],

		buttons: [{
			text: 'Save',
			handler: function() {
				if(laboratree.projects.edit.form.getForm().isValid()) {
					laboratree.projects.edit.form.getForm().submit({
						url: laboratree.projects.edit.data_url,
						waitMsg: 'Updating information...',
						success: function(formpanel, o) {
						}
					});
				}
			}
		}]
	});
};

laboratree.projects.makeCreate = function(div, data_url) {
	laboratree.projects.create = new laboratree.projects.Create(div, data_url);
};

laboratree.projects.Create = function(div, data_url) {
	this.form = new Ext.form.FormPanel({
		id: 'create',
		renderTo: div,

		labelAlign: 'top',

		title: 'Create Project',

		frame: true,

		width: '100%',
		autoHeight: true,

		buttonAlign: 'center',

		labelWidth: 120,

		url: data_url,
		standardSubmit: true,

		defaults: {
			anchor: '100%'
		},

		defaultType: 'textfield',

		items: [{
			id: 'ProjectName',
			fieldLabel: 'Project Name',
			name: 'data[Project][name]',
			allowBlank: false,
			vtype: 'projectName'
		}],

		buttons: [{
			text: 'Create',
			handler: function(){
				if(laboratree.projects.create.form.getForm().isValid()) {
					laboratree.projects.create.form.getForm().submit({
						waitMsg: 'Creating Project ...',
						url: data_url
					});
				}
			}
		}]
	});
};

laboratree.projects.makeMembers = function(div, project_id) {
	laboratree.projects.members = new laboratree.projects.Members(div, project_id);
};

laboratree.projects.Members = function(div, project_id) {
	this.div = div;
	this.project_id = project_id;

	this.roles = new Ext.data.JsonStore({
		root: 'roles',
		autoLoad: true,
		url: String.format(laboratree.links.projects.members, project_id) + '.json',
		baseParams: {
			action: 'roles',
		},
		fields: [
			'id', 'name'
		]
	});

	this.store = new Ext.data.JsonStore({
		root: 'members',
		autoLoad: true,
		url: String.format(laboratree.links.projects.members, project_id) + '.json',
		baseParams: {
			action: 'members'
		},
		fields: [
			'id', 'name', 'role', 'role_id', 'project_id'
		],
		writer: new Ext.data.JsonWriter(),
		listeners: {
			save: function(store, batch, data) {
				store.reload();
			}
		}
	});

	this.panel = new Ext.grid.GridPanel({
		id: 'members',
		title: 'Project Member Management',
		renderTo: div,

		store: this.store,
		width: '100%',
		height: 600,

		clicksToEdit: 1,

		frame: true,
		stripeRows: true,
		loadMask: {msg: 'Loading...'},

		cm: new Ext.grid.ColumnModel({
			defaults: {
				sortable: true
			},
			columns: [{
				id: 'name',
				header: 'Name',
				dataIndex: 'name',
				width: 565
			},{
				id: 'Role',
				header: 'Role',
				dataIndex: 'role',
				width: 200
			},{
				id: 'actions',
				header: 'Actions',
				dataIndex: 'id',
				width: 180,
				sortable: false,
				align: 'center',
				renderer: this.renderActions
			}]
		}),

		bbar: new Ext.PagingToolbar({
			pageSize: 15,
			store: this.store,
			displayInfo: true,
			displayMsg: 'Displaying member {0} - {1} of {2}',
			emptyMsg: 'No members to display'
		})
	});
};

laboratree.projects.Members.prototype.changeRole = function(userId) {
	var user = this.store.getById(userId);
	if(!user) {
		return false;
	}

	var form = new Ext.form.FormPanel({
		items: [{
			xtype: 'combo',
			store: this.roles,
			valueField: 'id',
			displayField: 'name',
		}]
	});

	var win = new Ext.Window({
		height: 100,
		width: 300,

		title: 'Change Role: ' + user.data.name,

		bodyStyle: 'padding: 5px;',

		items: [{
			layout: 'form',
			anchor: '100% 100%',

			labelWidth: 50,

			border: false,

			items: [{
				xtype: 'combo',
				store: this.roles,
				fieldLabel: 'Role',
				valueField: 'id',
				displayField: 'name',
				triggerAction: 'all',
				value: user.data.role_id,
			}],

			buttonAlign: 'center',
			buttons: [{
				text: 'Save',
				userId: userId,
				handler: function(btn) {
					var form = btn.ownerCt.ownerCt;
					var combo = form.items.items[0];

					var user = laboratree.projects.members.store.getById(btn.userId);
					if(!user) {
						return;
					}

					user.set('role', combo.getValue());
					laboratree.projects.members.store.commitChanges();
					laboratree.projects.members.store.save();

					var win = btn.findParentByType('window');
					if(win) {
						win.close();
					}
				}
			},{
				text: 'Cancel',
				handler: function(btn) {
					var win = btn.findParentByType('window');
					if(win) {
						win.close();
					}
				}
			}]
		}]
	});

	win.show();
};

laboratree.projects.Members.prototype.remove = function(user_id) {
	Ext.Msg.confirm('Remove Member', 'Are you sure?', function(btn) {
		if(btn == 'yes') {
			Ext.Ajax.request({
				url: String.format(laboratree.links.projects.removeuser, laboratree.projects.members.project_id, user_id) + '.json',
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

					var record = laboratree.projects.members.store.getById(user_id);
					if(record) {
						laboratree.projects.members.store.remove(record);
					}
				},
				failure: function(response, request) {
				},
				scope: this
			});
		}
	});
};

laboratree.projects.Members.prototype.renderActions = function(value, p, record) {
	var permission = laboratree.context.permissions.project;
	if(record.data.permission && record.data.permission.project) {
		permission = parseInt(record.data.permission.project, 10);
	}

	var actions = '';

	if(laboratree.site.permissions.project.members.edit & permission) {
		actions += '<a href="#" onclick="laboratree.projects.members.changeRole({0}); return false;" title="Change Role">Change Role</a>';
	}

	if(laboratree.site.permissions.project.members['delete'] & permission) {
		if(actions != '') {	
			actions += '&nbsp;|&nbsp;';
		}

		actions += '<a href="#" onclick="laboratree.projects.members.remove({0}); return false;" title="Remove User">Remove</a>';
	}

	return String.format(actions, value);
};

laboratree.projects.makeDashboard = function(div, data_url) {
	Ext.onReady(function(){
		laboratree.projects.dashboard = new laboratree.projects.Dashboard(div, data_url);
	});
};

laboratree.projects.Dashboard = function(div, data_url) {
	Ext.QuickTips.init();

	Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
		expires: new Date(new Date().getTime() + (1000 * 60 * 60 * 24 * 7))
	}));

	this.div = div;
	this.data_url = data_url;

	this.state_id = 'state-project-dashboard-' + laboratree.context.table_id;

	this.defaults = {
		members: {
			collapsed: false,
			column: 'dashboard-column-left',
			position: 0
		}
	};

	this.stores = {
		members: new Ext.data.JsonStore({
			root: 'members',
			autoLoad: true,
			url: data_url,
			baseParams: {
				model: 'members'
			},
			fields: ['id', 'name', 'username', 'session', 'type', 'activity']
		})
	};

	this.portlets = {
		members: new Ext.grid.GridPanel({
			id: 'portlet-members',
			height: 200,
			stripeRows: true,
			loadMask: {msg: 'Loading...'},

			store: this.stores.members,

			autoExpandColumn: 'name',

			cm: new Ext.grid.ColumnModel({
				defaults: {
					sortable: true
				},

				columns: [{
					id: 'name',
					header: 'Name',
					dataIndex: 'name',
					renderer: this.renderMemberName
				},{
					id: 'status',
					header: 'Status',
					dataIndex: 'activity',
					width: 60,
					renderer: this.renderMemberStatus
				}]
			})
		})
	};

	this.panels = {};
	
	/* Members */
	this.panels.members =  {
		id: 'panel-members',
		title: 'Members',
		layout: 'fit',
		
		tools: [{
			id: 'help',
			qtip: 'Help Project Members',
			handler: function(event, toolEl, panel, tc) {
				Ext.Ajax.request({
					url: String.format(laboratree.links.help.site.index, 'project', 'members') + '.json',
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

						if(!data.help) {
							request.failure(response, request);
							return;
						}

						laboratree.helpPopup('Project Member Help', data.help.content);
					},
					failure: function() {
					}
				});
			}
		}],

		items: this.portlets.members,

		listeners: {
			expand: function(p) {
				laboratree.projects.dashboard.toggle('members', false);
			},
			collapse: function(p) {
				laboratree.projects.dashboard.toggle('members', true);
			}
		}
	};

	if(laboratree.site.permissions.project.members.view & laboratree.context.permissions.project) {
		this.panels.members.title  = '<a href="' + String.format(laboratree.links.projects.members, laboratree.context.table_id) + '">Members</a>';

		this.panels.members.tools.unshift({
			id: 'restore',
			qtip: 'Members Dashboard',
			handler: function() {
				window.location = String.format(laboratree.links.projects.members, laboratree.context.table_id);
			}
		});
	}

	if(laboratree.site.permissions.project.members.add & laboratree.context.permissions.project) {
		this.panels.members.title += '<span class="create-link"><a href="' + String.format(laboratree.links.projects.adduser, laboratree.context.table_id) + '">- add member -</a></span>';

		this.panels.members.tools.unshift({
			id:'plus',
			qtip: 'Add a Project Member',
			handler: function() {
				window.location = String.format(laboratree.links.projects.adduser, laboratree.context.table_id);
			}
		});
	}

	this.portal = new Ext.ux.Portal({
		width: '100%',
		height: '100%',
		renderTo: div,
		border: false,

		items: [{
			id: 'dashboard-column-left',
			columnWidth: 0.5
		},{
			id: 'dashboard-column-right',
			columnWidth: 0.5
		}],

		listeners: {
			drop: function(e) {
				var panel_id = e.panel.id;
				var id = panel_id.replace('panel-', '');
				var states = {};

				Ext.each(laboratree.projects.dashboard.portal.items.items, function(column, index, allColumns) {
					Ext.each(column.items.items, function(portlet, index, allPortlets) {
						var portlet_id = portlet.id.replace('panel-', '');

						states[portlet_id] = {
							collapsed: portlet.collapsed,
							column: column.id,
							position: index
						};
					}, this);
				}, this);

				Ext.state.Manager.set(laboratree.projects.dashboard.state_id, states);

				return true;
			}
		}
	});

	var states = Ext.state.Manager.get(this.state_id, null);
	if(!states) {
		states = {};
	}

	var id;
	for(id in this.panels) {
		if(this.panels.hasOwnProperty(id)) {
			var state = states[id];
			if(!state) {
				state = this.defaults[id];

				if(!state) {
					continue;
				}
			}

			var panel = this.panels[id];
			if(!panel) {
				continue;
			}

			panel.collapsed = state.collapsed;

			var column = Ext.getCmp(state.column);
			if(!column) {
				continue;
			}

			column.insert(state.position, panel);
		}
	}

	this.portal.doLayout();
};

laboratree.projects.Dashboard.prototype.renderMemberName = function(value, p, record) {
	var permission = laboratree.context.permissions.project;
	if(record.data.permission && record.data.permission.project) {
		permission = parseInt(record.data.permission.project, 10);
	}

	var label = value;
	if(laboratree.site.permissions.project.members.view & permission) {
		label = value;
	}

	return label;
};

laboratree.projects.Dashboard.prototype.renderMemberStatus = function(value, p, record) {
	var state = 'Offline';

	var activityDate = new Date();
	activityDate = Date.parseDate(value, 'Y-m-d H:i:s');

	// return on an invalid date
	if(!activityDate) {
		return state;
	}

	var activityTimestamp = parseInt(activityDate.format('U'), 10);	

	var nowDate = new Date();
	var gmtOffset = nowDate.getTimezoneOffset();
	var utcDate = nowDate.add(Date.MINUTE, gmtOffset);
	var utcTimestamp = parseInt(utcDate.format('U'), 10);

	var timeDiff = utcTimestamp - activityTimestamp;

	if(timeDiff < 600) {
		state = 'Online';
	}

	return state;
};

laboratree.projects.Dashboard.prototype.toggle = function(panel_id, collapsed) {
	var states = Ext.state.Manager.get(laboratree.projects.dashboard.state_id, null);
	if(!states) {
		states = {};
	}

	if(!states[panel_id]) {
		var dflt = laboratree.projects.dashboard.defaults[panel_id];

		if(!dflt) {
			dflt = {
				collapsed: false,
				column: 'dashboard-column-left',
				position: 0
			};
		}

		states[panel_id] = dflt;
	}

	states[panel_id].collapsed = collapsed;

	Ext.state.Manager.set(laboratree.projects.dashboard.state_id, states);
};
