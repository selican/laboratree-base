laboratree.admin = {};
laboratree.admin.masks = {};
laboratree.admin.dashboard = {};
laboratree.admin.dashboard.users = {};
laboratree.admin.dashboard.groups = {};
laboratree.admin.dashboard.projects = {};
laboratree.admin.dashboard.navigation = {};

laboratree.admin.render = {};
laboratree.admin.render.actions = {};

laboratree.admin.dashboard.makeUsers = function(div, data_url) {
	laboratree.admin.dashboard.users = new laboratree.admin.dashboard.Users(div, data_url);
};

laboratree.admin.dashboard.makeGroups = function(div, data_url) {
	laboratree.admin.dashboard.groups = new laboratree.admin.dashboard.Groups(div, data_url);
};

laboratree.admin.dashboard.makeProjects = function(div, data_url) {
	laboratree.admin.dashboard.projects = new laboratree.admin.dashboard.Projects(div, data_url);
};

laboratree.admin.dashboard.makeNavigation = function(div, data_url, reparent_url, reorder_url) {
	laboratree.admin.dashboard.navigation = new laboratree.admin.dashboard.Navigation(div, data_url, reparent_url, reorder_url);
};

laboratree.admin.dashboard.makePreferences = function(div, data_url) {
	laboratree.admin.dashboard.preferences = new laboratree.admin.dashboard.Preferences(div, data_url);
};

laboratree.admin.dashboard.Users = function(div, data_url) {
	Ext.QuickTips.init();

	this.store = new Ext.data.JsonStore({
		root: 'users',
		autoLoad: true,
		url: data_url,
		fields: [
			'id', 'name', 'username', 'email', 'confirmed', 'changepass', 'ip', 'admin', 'activity', 'privacy'
		]
	});	

	this.store.setDefaultSort('name');

	this.grid = new Ext.grid.GridPanel({
		id: 'users',
		title: 'User Management',
		renderTo: div,
		width: 840,
		height: 600,

		store: this.store,

		cm: new Ext.grid.ColumnModel({
			defaults: {
				sortable: true
			},
			columns: [{
				id: 'name',
				header: 'Name',
				dataIndex: 'name',
				width: 180,
				renderer: laboratree.admin.render.user
			},{
				id: 'username',
				header: 'Username',
				dataIndex: 'username',
				width: 120 
			},{
				id: 'email',
				header: 'Email',
				dataIndex: 'email',
				width: 180
			},{
				id: 'admin',
				header: 'Admin',
				dataIndex: 'admin',
				width: 45,
				renderer: laboratree.admin.render.boolean
			},{
				id: 'activity',
				header: 'Activity',
				dataIndex: 'activity',
				width: 120 
			},{
				id: 'actions',
				header: 'Actions',
				dataIndex: 'id',
				width: 70,
				renderer: laboratree.admin.render.actions.user
			}]
		}),

		tbar: [{
			id: 'users-search',
			width: 708,
			xtype: 'textfield',
			emptyText: 'Search...',
			enableKeyEvents: true,
			listeners: {
				keyup: function(textfield, e) {
					var query = textfield.getValue();
					laboratree.admin.dashboard.users.store.filter('name', query, true);
				}
			}
		}],

		bbar: new Ext.PagingToolbar({
			pageSize: 30,
			store: this.store,
			displayInfo: true,
			displayMsg: 'Displaying user {0} - {1} of {2}',
			emptyMsg: 'No users to display'
		})
	});
};

laboratree.admin.dashboard.Groups = function(div, data_url) {
	Ext.QuickTips.init();

	this.store = new Ext.data.JsonStore({
		root: 'groups',
		autoLoad: true,
		url: data_url,
		fields: [
			'id', 'name', 'email', 'privacy', 'members', 'projects'
		]
	});	

	this.store.setDefaultSort('name');

	this.grid = new Ext.grid.GridPanel({
		id: 'groups',
		title: 'Group Management',
		renderTo: div,
		width: 840,
		height: 600,

		store: this.store,

		cm: new Ext.grid.ColumnModel({
			defaults: {
				sortable: true
			},
			columns: [{
				id: 'name',
				header: 'Name',
				dataIndex: 'name',
				width: 270,
				renderer: laboratree.admin.render.group
			},{
				id: 'email',
				header: 'Email',
				dataIndex: 'email',
				width: 270 
			},{
				id: 'privacy',
				header: 'Privacy',
				dataIndex: 'privacy',
				width: 70,
				renderer: Ext.util.Format.capitalize
			},{
				id: 'members',
				header: 'Members',
				dataIndex: 'members',
				width: 70
			},{
				id: 'projects',
				header: 'Projects',
				dataIndex: 'projects',
				width: 70
			},{
				id: 'actions',
				header: 'Actions',
				dataIndex: 'id',
				width: 70,
				renderer: laboratree.admin.render.actions.group
			}]
		}),

		tbar: [{
			id: 'groups-search',
			width: 708,
			xtype: 'textfield',
			emptyText: 'Search...',
			enableKeyEvents: true,
			listeners: {
				keyup: function(textfield, e) {
					var query = textfield.getValue();
					laboratree.admin.dashboard.groups.store.filter('name', query, true);
				}
			}
		}],

		bbar: new Ext.PagingToolbar({
			pageSize: 30,
			store: this.store,
			displayInfo: true,
			displayMsg: 'Displaying group {0} - {1} of {2}',
			emptyMsg: 'No groups to display'
		})
	});
};

laboratree.admin.dashboard.Projects = function(div, data_url) {
	Ext.QuickTips.init();

	this.store = new Ext.data.JsonStore({
		root: 'projects',
		autoLoad: true,
		url: data_url,
		fields: [
			'id', 'name', 'email', 'privacy', 'members', 'projects'
		]
	});	

	this.store.setDefaultSort('name');

	this.grid = new Ext.grid.GridPanel({
		id: 'projects',
		title: 'Project Management',
		renderTo: div,
		width: 840,
		height: 600,

		store: this.store,

		cm: new Ext.grid.ColumnModel({
			defaults: {
				sortable: true
			},
			columns: [{
				id: 'name',
				header: 'Name',
				dataIndex: 'name',
				width: 295,
				renderer: laboratree.admin.render.project
			},{
				id: 'email',
				header: 'Email',
				dataIndex: 'email',
				width: 315 
			},{
				id: 'privacy',
				header: 'Privacy',
				dataIndex: 'privacy',
				width: 75,
				renderer: Ext.util.Format.capitalize
			},{
				id: 'members',
				header: 'Members',
				dataIndex: 'members',
				width: 65
			},{
				id: 'actions',
				header: 'Actions',
				dataIndex: 'id',
				width: 70,
				renderer: laboratree.admin.render.actions.project
			}]
		}),

		tbar: [{
			id: 'projects-search',
			width: 780,
			xtype: 'textfield',
			emptyText: 'Search...',
			enableKeyEvents: true,
			listeners: {
				keyup: function(textfield, e) {
					var query = textfield.getValue();
					laboratree.admin.dashboard.projects.store.filter('name', query, true);
				}
			}
		}],

		bbar: new Ext.PagingToolbar({
			pageSize: 30,
			store: this.store,
			displayInfo: true,
			displayMsg: 'Displaying project {0} - {1} of {2}',
			emptyMsg: 'No projects to display'
		})
	});
};

laboratree.admin.dashboard.Navigation = function(div, data_url, reparent_url, reorder_url) {
	Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
		expires: new Date(new Date().getTime() + (1000 * 60 * 60 * 24 * 7))
	}));

	Ext.QuickTips.init();

	this.details = '<div class="navigation_details"><i>Select an item to see more information...</i></div>';

	this.tpl = new Ext.Template(
		'<div class="navigation_details">',
		'<h2 class="navigation-panel-title"><a href="{url}">{title}</a></h2>',
		'<table>',
		'<tr><td><b>Controller</b>:</td><td>{controller}</td></tr>',
		'<tr><td><b>Action</b>:</td><td>{action}</td></tr>',
		'<tr><td><b>Role</b>:</td><td>{role}</td></tr>',
		'<tr><td><b>Type</b>:</td><td>{type}</td></tr>',
		'<tr><td><b>URL</b>:</td><td>{url}</td></tr>',
		'<tr><td colspan="2"><a href="javascript:laboratree.admin.makeAddNavigation({id});" title="Add Item to \'{title}\'">Add Item to \'{title}\'</a></td>',
		'<tr><td colspan="2"><a href="javascript:laboratree.admin.makeEditNavigation({id});" title="Edit \'{title}\'">Edit \'{title}\'</a></td>',
		'<tr><td colspan="2"><a href="javascript:laboratree.admin.dashboard.navigation.deleteNavigationItem({id});" title="Delete \'{title}\'">Delete \'{title}\'</a></td>',
		'</table>'
	);
	this.tpl.compile();

	this.tree = new Ext.ux.tree.ColumnTree({
		id: 'navigation',
		title: 'Navigation',
		rootVisible: false,
		autoScroll: true,

		columns: [{
			header: 'Title',
			width: 500,
			dataIndex: 'title',
			renderer: laboratree.admin.render.navigation
		},{
			header: 'URL',
			width: 220,
			dataIndex: 'url'
		}],

		animate: false,
		enableDD: true,
		containerScroll: true,
		enableHdMenu: false,

		lines: true,

		stateEvents: ['collapsenode', 'expandnode', 'movenode', 'nodedrop', 'insert'],
		stateful: true,
		getState: function() {
			var nodes = [];
			this.getRootNode().eachChild(function(child) {
				var storeTreeState = function(node, expandNodes) {
					if(node.isExpanded() && node.childNodes.length > 0) {
						expandNodes.push(node.getPath());
						node.eachChild(function(child) {
							storeTreeState(child, expandNodes);
						});
					}
				};
				storeTreeState(child, nodes);
			});

			return {
				expandedNodes: nodes
			};
		},
		applyState: function(state) {
			var that = this;
			this.getLoader().on('load', function() {
				var cookie = Ext.state.Manager.get('navigation');
				var nodes = cookie.expandedNodes;
				var i;
				for(i = 0; i < nodes.length; i++) {
					if(typeof nodes[i] != 'undefined') {
						that.expandPath(nodes[i]);
					}
				}
			});
		},

		tools: [{
			id: 'plus',
			qtip: 'Expand Navigation Tree',
			handler: function(event, toolEl, panel, tc) {
				panel.expandAll();
			}
		},{
			id: 'minus',
			qtip: 'Collapse Navigation Tree',
			handler: function(event, toolEl, panel, tc) {
				panel.collapseAll();
			}
		},{
			id: 'refresh',
			qtip: 'Refresh Navigation Tree',
			handler: function(event, toolEl, panel, tc) {
				var treeloader = panel.getLoader();
				var rootnode = panel.getRootNode();
				treeloader.load(rootnode, function() {
					rootnode.expand();
				});
			}
		}],

		contextMenu: new Ext.menu.Menu({
			items: [{
				id: 'add-navigation',
				text: 'Add Navigation'
			},{
				id: 'edit-navigation',
				text: 'Edit Navigation'
			},{
				id: 'delete-navigation',
				text: 'Delete Navigation'
			}],
			listeners: {
				itemclick: function(item) {
					var node = item.parentMenu.contextNode;
					var node_id = node.attributes.id;
					var parent_id = node.parentNode.attributes.id;
	
					switch(item.id) {
						case 'add-navigation':
							laboratree.admin.makeAddNavigation(node_id, parent_id);
							break;
						case 'edit-navigation':
							laboratree.admin.makeEditNavigation(node_id, parent_id);
							break;
						case 'delete-navigation':
							laboratree.admin.dashboard.navigation.deleteNavigationItem(node_id);
							break;
					}
				}
			}
		}),

		loader: new Ext.tree.TreeLoader({
			dataUrl: data_url,
			uiProviders: {
				'col': Ext.ux.tree.ColumnNodeUI
			},
			listeners: {
				beforeload: function(store, options) {
					laboratree.admin.masks.navigation = new Ext.LoadMask('navigation', {
						msg: 'Loading...'
					});
					laboratree.admin.masks.navigation.show();
				},
				load: function(store, records, options) {
					laboratree.admin.masks.navigation.hide();
				}
			}
		}),

		root: new Ext.tree.AsyncTreeNode({
			text: 'Navigation',
			allowDrop: false,
			draggable: false
		}),

		listeners: {
			contextmenu: function(node, e) {
				node.select();

				var c = node.getOwnerTree().contextMenu;
				c.contextNode = node;
				c.showAt(e.getXY());
			},
			startdrag: function(tree, node, e) {
				this.oldPosition = node.parentNode.indexOf(node);
				this.oldNextSibling = node.nextSibling;
                        },
			movenode: function(tree, node, oldParent, newParent, position) {
				var url = reparent_url;
		
				var params = {
					node: node.id,
					parent: newParent.id,
					position: position
				};
		
				if(oldParent == newParent) {
					url = reorder_url;
					
					params = {
						node: node.id,
						delta: (position-this.oldPosition)
					};
				}
			    
				tree.disable();
			    
				Ext.Ajax.request({
					url: url,
					params: params,
					success: function(response, request) {
						var data = Ext.decode(response.responseText);
						if(data.errors) {
							request.failure();
						}
						else if(data.success) {
							tree.enable();
						}
						else {
							tree.enable();
						}
					},
					failure: function() {
						tree.suspendEvents();
						oldParent.appendChild(node);
						if(this.oldNextSibling) {
							oldParent.insertBefore(node, this.oldNextSibling);
						}
			 
						tree.resumeEvents();
						tree.enable();
					}
				});
			},
			click: function(node, checked) {
				var el = Ext.getCmp('details-panel').body;
				if(node) {
					laboratree.admin.dashboard.navigation.tpl.overwrite(el, node.attributes);
				} else { 
					el.update(laboratree.admin.dashboard.navigation.details);
				}
			}
		}
	});

	this.hbox = new Ext.Panel({
		id: 'dashboard',
		layout: 'border',
		renderTo: div,
		width: 840,
		height: 600,
		items: [{
			id: 'tree-box',
			layout: 'fit',
			height: 470,
			width: 718,
			region: 'center',
			border: false,
			items: [this.tree]
		},{
			id: 'details-panel',
			title: 'Navigation Details',
			height: 220,
			width: 840,
			region: 'south',
			autoScroll: true,
			html: this.details,
			bodyStyle: 'padding: 5px;'
		}]
	});

};

laboratree.admin.makeAddNavigation = function(parent_id) {
	var data_url = laboratree.links.base + '/admin/navigation/add/' + parent_id + '.json';

	laboratree.admin.addNavigation = new laboratree.admin.AddNavigation(parent_id, data_url);
};

laboratree.admin.AddNavigation = function(parent_id, data_url) {
	this.parent_id = parent_id;
	this.data_url = data_url;

	var role = new Ext.form.ComboBox({
		id: 'role',
		fieldLabel: 'Role',
		triggerAction: 'all',
		forceSelection: true,
		lazyRender: true,
		mode: 'local',
		name: 'RoleDisplay',
		hiddenName: 'data[Navigation][role]',
		valueField: 'id',
		displayField: 'role',
		store: new Ext.data.ArrayStore({
			fields: ['id', 'role'],
			data: [
				['group.manager', 'Group Manager'],
				['group.member', 'Group Member'],
				['project.manager', 'Project Manager'],
				['project.member', 'Project Member'],
				['user.colleague', 'User Colleague'],
				['user.manager', 'User Manager'],
				['user', 'User']
			]
		})
	});

	var type = new Ext.form.ComboBox({
		id: 'type',
		fieldLabel: 'Type',
		allowBlank: false,
		triggerAction: 'all',
		forceSelection: true,
		lazyRender: true,
		mode: 'local',
		name: 'TypeDisplay',
		hiddenName: 'data[Navigation][type]',
		valueField: 'id',
		displayField: 'type',
		store: new Ext.data.ArrayStore({
			fields: ['id', 'type'],
			data: [
				['action', 'Action'],
				['controller', 'Controller'],
				['node', 'Node'],
				['role', 'Role']
			]
		})
	});

	this.form = new Ext.FormPanel({
		id: 'add-navigation',
		labelWidth: 75,
		frame: true,
		bodyStyle: 'padding: 10px 10px 0 10px;',

		items: [{
			id: 'title',
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Title',
			name: 'data[Navigation][title]',
			allowBlank: false
		},{
			id: 'controller',
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Controller',
			name: 'data[Navigation][controller]'
		},{
			id: 'action',
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Action',
			name: 'data[Navigation][action]'
		},role,type,{
			id: 'url',
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'URL',
			name: 'data[Navigation][url]'
		},{
			id: 'prompt',
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Prompt',
			name: 'data[Navigation][prompt]'
		}],

		buttons: [{
			text: 'Save',
			handler: function() {
				if(laboratree.admin.addNavigation.form.getForm().isValid()) {
					laboratree.admin.addNavigation.form.getForm().submit({
						url: laboratree.admin.addNavigation.data_url,
						success: function(formpanel, o) {
							var treeloader = laboratree.admin.dashboard.navigation.tree.getLoader();
							var parentnode = laboratree.admin.dashboard.navigation.tree.getNodeById(laboratree.admin.addNavigation.parent_id);
							if(parentnode) {
								treeloader.load(parentnode, function() {
									parentnode.expand();
								});
							}
							laboratree.admin.addNavigation.win.close();
						}
					});
				}
			}
		},{
			text: 'Cancel',
			handler: function() {
				laboratree.admin.addNavigation.win.close();
			}
		}]
	});

	this.win = new Ext.Window({
		title: 'Add Navigation Item',
		closable: true,
		width: 500,
		height: 250,
		layout: 'fit',
		items: [this.form]
	});

	this.win.show(this);
};

laboratree.admin.makeEditNavigation = function(navigation_id, parent_id) {
	var data_url = laboratree.links.base + '/admin/navigation/edit/' + navigation_id + '.json';

	if(!parent_id) {
		var node = laboratree.admin.dashboard.navigation.tree.getNodeById(navigation_id);
		if(!node) {
			return false;
		}

		parent_id = node.parentNode.attributes.id;
	}

	laboratree.admin.editNavigation = new laboratree.admin.EditNavigation(navigation_id, parent_id, data_url);

	laboratree.admin.masks['edit-navigation'] = new Ext.LoadMask('edit-navigation', {
		msg: 'Loading...'
	});
	laboratree.admin.masks['edit-navigation'].show();

	Ext.Ajax.request({
		url: data_url,
		params: {
			action: 'edit'
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

			if(!data.item) {
				request.failure(response, request);
				return;
			}

			var field;
			for(field in data.item) {
				if(data.item.hasOwnProperty(field)) {
					var value = data.item[field];

					var cmp = Ext.getCmp(field);
					if(cmp) {
						if(cmp.setValue) {
							cmp.setValue(value);
						}
					}
				}
			}
			laboratree.admin.masks['edit-navigation'].hide();
		},
		failure: function(response, request) {
			laboratree.admin.masks['edit-navigation'].hide();
		},
		scope: this
	});
};

laboratree.admin.EditNavigation = function(navigation_id, parent_id, data_url) {
	this.navigation_id = navigation_id;
	this.parent_id = parent_id;
	this.data_url = data_url;

	var role = new Ext.form.ComboBox({
		id: 'role',
		fieldLabel: 'Role',
		triggerAction: 'all',
		forceSelection: true,
		lazyRender: true,
		mode: 'local',
		name: 'RoleDisplay',
		hiddenName: 'data[Navigation][role]',
		valueField: 'id',
		displayField: 'role',
		store: new Ext.data.ArrayStore({
			fields: ['id', 'role'],
			data: [
				['group.manager', 'Group Manager'],
				['group.member', 'Group Member'],
				['project.manager', 'Project Manager'],
				['project.member', 'Project Member'],
				['user.colleague', 'User Colleague'],
				['user.manager', 'User Manager'],
				['user', 'User']
			]
		})
	});
		
	var type = new Ext.form.ComboBox({
		id: 'type',
		fieldLabel: 'Type',
		allowBlank: false,
		triggerAction: 'all',
		forceSelection: true,
		lazyRender: true,
		mode: 'local',
		name: 'TypeDisplay',
		hiddenName: 'data[Navigation][type]',
		valueField: 'id',
		displayField: 'type',
		store: new Ext.data.ArrayStore({
			fields: ['id', 'type'],
			data: [
				['action', 'Action'],
				['controller', 'Controller'],
				['node', 'Node'],
				['role', 'Role']
			]
		})
	});

	this.form = new Ext.FormPanel({
		id: 'edit-navigation',
		labelWidth: 75,
		frame: true,
		bodyStyle: 'padding: 10px 10px 0 10px;',

		items: [{
			id: 'title',
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Title',
			name: 'data[Navigation][title]',
			allowBlank: false
		},{
			id: 'controller',
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Controller',
			name: 'data[Navigation][controller]'
		},{
			id: 'action',
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Action',
			name: 'data[Navigation][action]'
		},role,type,{
			id: 'url',
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'URL',
			name: 'data[Navigation][url]'
		},{
			id: 'prompt',
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Prompt',
			name: 'data[Navigation][prompt]'
		}],

		buttons: [{
			text: 'Save',
			handler: function() {
				if(laboratree.admin.editNavigation.form.getForm().isValid()) {
					laboratree.admin.editNavigation.form.getForm().submit({
						url: laboratree.admin.editNavigation.data_url,
						success: function(formpanel, o) {
							var treeloader = laboratree.admin.dashboard.navigation.tree.getLoader();
							var parentnode = laboratree.admin.dashboard.navigation.tree.getNodeById(laboratree.admin.editNavigation.parent_id);
							if(parentnode) {
								treeloader.load(parentnode, function() {
									parentnode.expand();
								});
							}
							laboratree.admin.editNavigation.win.close();
						}
					});
				}
			}
		},{
			text: 'Cancel',
			handler: function() {
				laboratree.admin.editNavigation.win.close();
			}
		}]
	});

	this.win = new Ext.Window({
		title: 'Edit Navigation Item',
		closable: true,
		width: 500,
		height: 250,
		layout: 'fit',
		items: [this.form]
	});

	this.win.show(this);
};

laboratree.admin.dashboard.Navigation.prototype.deleteNavigationItem = function(node_id) {
	Ext.MessageBox.confirm('Delete Navigation Item', 'Are you sure you want to do that?', function(btn) {
		if(btn == 'yes') {
			Ext.Ajax.request({
				url: laboratree.links.base + '/admin/navigation/delete/' + node_id + '.json',
				success: function(response) {
					var treepanel = laboratree.admin.dashboard.navigation.tree;
					var treeloader = treepanel.getLoader();
					var node = treepanel.getNodeById(node_id);
					if(node) {
						node.remove();

						var el = Ext.getCmp('details-panel').body;
						el.update(laboratree.admin.dashboard.navigation.details);
					}
				}
			});
		}
	});
};

laboratree.admin.dashboard.Preferences = function(div, data_url, reparent_url, reorder_url) {
	Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
		expires: new Date(new Date().getTime() + (1000 * 60 * 60 * 24 * 7))
	}));

	Ext.QuickTips.init();

	this.tree = new Ext.ux.tree.ColumnTree({
		id: 'preferences',
		title: 'Preferences',
		rootVisible: false,
		autoScroll: true,

		renderTo: div,
		width: 840,
		height: 500,

		columns: [{
			header: 'Name',
			width: 700,
			dataIndex: 'name',
			renderer: laboratree.admin.render.preference
		}],

		animate: false,
		containerScroll: true,
		enableHdMenu: false,
		useArrows: true,

		stateEvents: ['collapsenode', 'expandnode', 'movenode', 'nodedrop', 'insert'],
		stateful: true,
		getState: function() {
			var nodes = [];
			this.getRootNode().eachChild(function(child) {
				var storeTreeState = function(node, expandNodes) {
					if(node.isExpanded() && node.childNodes.length > 0) {
						expandNodes.push(node.getPath());
						node.eachChild(function(child) {
							storeTreeState(child, expandNodes);
						});
					}
				};
				storeTreeState(child, nodes);
			});

			return {
				expandedNodes: nodes
			};
		},
		applyState: function(state) {
			var that = this;
			this.getLoader().on('load', function() {
				var cookie = Ext.state.Manager.get('preferences');
				var nodes = cookie.expandedNodes;
				var i;
				for(i = 0; i < nodes.length; i++) {
					if(typeof nodes[i] != 'undefined') {
						that.expandPath(nodes[i]);
					}
				}
			});
		},

		tools: [{
			id: 'plus',
			qtip: 'Expand Preference Tree',
			handler: function(event, toolEl, panel, tc) {
				panel.expandAll();
			}
		},{
			id: 'minus',
			qtip: 'Collapse Preference Tree',
			handler: function(event, toolEl, panel, tc) {
				panel.collapseAll();
			}
		},{
			id: 'refresh',
			qtip: 'Refresh Preference Tree',
			handler: function(event, toolEl, panel, tc) {
				var treeloader = panel.getLoader();
				var rootnode = panel.getRootNode();
				treeloader.load(rootnode, function() {
					rootnode.expand();
				});
			}
		}],

		contextMenu: new Ext.menu.Menu({
			items: [{
				id: 'add-preferences',
				text: 'Add Preference'
			},{
				id: 'edit-preferences',
				text: 'Edit Preference'
			},{
				id: 'delete-preferences',
				text: 'Delete Preference'
			}],
			listeners: {
				itemclick: function(item) {
					var node = item.parentMenu.contextNode;
					var node_id = node.attributes.id;
	
					switch(item.id) {
						case 'add-preferences':
							laboratree.admin.dashboard.preferences.addPreferenceItem(node_id);
							break;
						case 'edit-preferences':
							// TODO : Change this to AJAX form 
							window.location = '/admin/preferences/edit/' + node_id;
							break;
						case 'delete-preferences':
							laboratree.admin.dashboard.preferences.deletePreferenceItem(node_id);
							break;
					}
				}
			}
		}),

		loader: new Ext.tree.TreeLoader({
			dataUrl: data_url,
			uiProviders: {
				'col': Ext.ux.tree.ColumnNodeUI
			}
		}),

		root: new Ext.tree.AsyncTreeNode({
			text: 'Preferences'
		}),

		listeners: {
			contextmenu: function(node, e) {
				node.select();

				var c = node.getOwnerTree().contextMenu;
				c.contextNode = node;
				c.showAt(e.getXY());
			}
		}
	});
};

laboratree.admin.dashboard.Preferences.prototype.addPreferenceItem = function(parent_id) {
	var type = new Ext.form.ComboBox({
		fieldLabel: 'Type',
		allowBlank: false,
		triggerAction: 'all',
		forceSelection: true,
		lazyRender: true,
		mode: 'local',
		name: 'TypeDisplay',
		hiddenName: 'data[Preference][type]',
		valueField: 'id',
		displayField: 'type',
		store: new Ext.data.ArrayStore({
			fields: ['id', 'type'],
			data: [
				['context', 'Context'],
				['controller', 'Controller'],
				['feature', 'Feature'],
				['function', 'Function'],
				['table', 'Table']
			]
		})
	});

	var field = new Ext.form.ComboBox({
		fieldLabel: 'Field',
		triggerAction: 'all',
		forceSelection: true,
		lazyRender: true,
		mode: 'local',
		name: 'FieldDisplay',
		hiddenName: 'data[Preference][field]',
		valueField: 'id',
		displayField: 'field',
		store: new Ext.data.ArrayStore({
			fields: ['id', 'field'],
			data: [
				['boolean', 'Boolean'],
				['option', 'Options'],
				['text', 'Text']
			]
		})
	});

	var formpanel = new Ext.FormPanel({
		labelWidth: 75,
		frame: true,
		bodyStyle: 'padding: 10px 10px 0 10px;',

		items: [{
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Name',
			name: 'data[Preference][name]',
			allowBlank: false
		},{
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Title',
			name: 'data[Preference][title]',
			allowBlank: false
		},{
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Description',
			name: 'data[Preference][description]',
			allowBlank: false
		},type,field,{
			xtype: 'textfield',
			style: {
				width: '95%'
			},
			fieldLabel: 'Field Options',
			name: 'data[Preference][field_options]'
		}],

		buttons: [{
			text: 'Save',
			handler: function(btn) {
				var win = btn.findParentByType('window');
				if(formpanel.getForm().isValid()) {
					formpanel.getForm().submit({
						url: laboratree.links.base + '/admin/preferences/add/' + parent_id + '.json',
						success: function(formpanel, o) {
							var treeloader = laboratree.admin.dashboard.preferences.tree.getLoader();
							var parentnode = laboratree.admin.dashboard.preferences.tree.getNodeById(parent_id);
							if(parentnode) {
								treeloader.load(parentnode, function() {
									parentnode.expand();
								});
							}
							win.close();
						}
					});
				}
			}
		},{
			text: 'Cancel',
			handler: function(btn) {
				var win = btn.findParentByType('window');
				win.close();
			}
		}]
	});

	var win = new Ext.Window({
		title: 'Add Preference Item',
		closable: true,
		width: 500,
		height: 250,
		layout: 'fit',
		items: [formpanel]
	});

	win.show(this);
};

laboratree.admin.dashboard.Preferences.prototype.deletePreferenceItem = function(node_id) {
	Ext.MessageBox.confirm('Delete Preference Item', 'Are you sure you want to do that?', function(btn) {
		if(btn == 'yes') {
			Ext.Ajax.request({
				url: laboratree.links.base + '/admin/preferences/delete/' + node_id + '.json',
				success: function(response) {
					var treepanel = laboratree.admin.dashboard.preferences.tree;
					var treeloader = treepanel.getLoader();
					var node = treepanel.getNodeById(node_id);
					if(node) {
						node.remove();

						var el = Ext.getCmp('details-panel').body;
						el.update(laboratree.admin.dashboard.preferences.details);
					}
				}
			});
		}
	});
};

laboratree.admin.render.boolean = function(value, p, record) {
	return (value == '1') ? 'Yes' : 'No';
};

laboratree.admin.render.user = function(value, p, record) {
	return String.format('<a href="/users/profile/{0}" title="{1}">{1}</a>', record.id, value);
};

laboratree.admin.render.group = function(value, p, record) {
	return String.format('<a href="/groups/profile/{0}" title="{1}">{1}</a>', record.id, value);
};

laboratree.admin.render.project = function(value, p, record) {
	return String.format('<a href="/projects/profile/{0}" title="{1}">{1}</a>', record.id, value);
};

laboratree.admin.render.navigation = function(value, p, record) {
	return String.format('{0}: {1}', Ext.util.Format.capitalize(record.type), value);
};

laboratree.admin.render.preference = function(value, p, record) {
	return String.format('{0}: {1}', Ext.util.Format.capitalize(record.type), value);
};

laboratree.admin.render.actions.user = function(value, p, record) {
	//return String.format('<a href="/admin/users/edit/{0}" title="Edit {1}">Edit</a> | <a href="/admin/users/delete/{0}" title="Delete {1}">Delete</a>', value, record.data.name);
	return '';
};

laboratree.admin.render.actions.group = function(value, p, record) {
	//return String.format('<a href="/admin/groups/edit/{0}" title="Edit {1}">Edit</a> | <a href="/admin/groups/delete/{0}" title="Delete {1}">Delete</a>', value, record.data.name);
	return '';
};

laboratree.admin.render.actions.project = function(value, p, record) {
	//return String.format('<a href="/admin/projects/edit/{0}" title="Edit {1}">Edit</a> | <a href="/admin/projects/delete/{0}" title="Delete {1}">Delete</a>', value, record.data.name);
	return '';
};

laboratree.admin.render.role = function(role) {
	var parts = role.split('.');

	var i;
	for(i = 0; i < parts.length; i++) {
		parts[i] = Ext.util.Format.capitalize(parts[i]);
	}

	return parts.join(' ');
};
