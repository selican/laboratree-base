laboratree.permissions = {};
laboratree.permissions.masks = {};

laboratree.permissions.makeDashboard = function(div, table_type, table_id) {
	laboratree.permissions.dashboard = new laboratree.permissions.Dashboard(div, table_type, table_id);

	laboratree.permissions.masks.dashboard = new Ext.LoadMask('dashboard', {
		msg: 'Loading...'
	});
	laboratree.permissions.masks.dashboard.show();

	Ext.Ajax.request({
		url: String.format(laboratree.links.permissions[table_type], table_id) + '.json',
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

			if(!data.roles) {
				request.failure(response, request);
				return;
			}

			var first = 0;
			Ext.each(data.roles, function(role, index, allRoles) {
				var group = laboratree.permissions.dashboard.addPermissions(role);
				if(!first) {
					first = group;
				}
			}, this);

			laboratree.permissions.dashboard.panel.setActiveGroup(first);
			first.setActiveTab(first.getMainItem());
			laboratree.permissions.dashboard.panel.doLayout(false, true);

			// Add Listener
			laboratree.permissions.dashboard.panel.addListener('beforegroupchange', function(panel, group, activeGroup) {
				if(group.id == 'addrole') {
					laboratree.permissions.dashboard.addRole();
					return false;
				}

				return true;
			});

			laboratree.permissions.masks.dashboard.hide();
		},
		failure: function(response, request) {
			laboratree.permissions.masks.dashboard.hide();
		},
		scope: this
	});
}

laboratree.permissions.Dashboard = function(div, table_type, table_id) {
	Ext.QuickTips.init();

	this.div = div;
	this.table_type = table_type;
	this.table_id = table_id;

	/*
	 * This is necessary. GroupTabPanel is an extension of
	 * TabPanel which deals with tabs directly. When
	 * GroupTabPanel is initialized, it expects there to
	 * be tabs to initialize, however, in a GroupTabPanel
	 * these tabs are actually children of 'groups' which
	 * do the initialization.
	 */
	Ext.ux.GroupTabPanel.prototype.initTab = function(item, index) {};

	/* Create 'Read Only' group template. */
	var tt = new Ext.Template(
		'<li class="{cls}" id="{id}">',
		'<a class="x-grouptabs-expand" onclick="return false;"></a>',
		'<a class="x-grouptabs-text {iconCls}" href="#" onclick="return false;">',
		'<span>{text}</span></a>',
		'</li>'
	);
	tt.disableFormats = true;
	tt.compile();
	Ext.ux.GroupTabPanel.prototype.groupReadOnlyTpl = tt;

	/* Create normal group template */
	var tt = new Ext.Template(
		'<li class="{cls}" id="{id}">',
		'<a class="x-grouptabs-expand" onclick="return false;"></a>',
		'<a class="x-grouptabs-text {iconCls}" href="#" onclick="return false;">',
		'<span>{text}</span></a>',
		'<a class="x-grouptabs-edit" href="#" onclick="laboratree.permissions.dashboard.editRole(\'{id}\'); return false;">Edit</a>',
		'&nbsp;|&nbsp;',
		'<a class="x-grouptabs-delete" href="#" onclick="laboratree.permissions.dashboard.deleteRole(\'{id}\'); return false;">Delete</a>',
		'</li>'
	);
	tt.disableFormats = true;
	tt.compile();
	Ext.ux.GroupTabPanel.prototype.groupTpl = tt;

	/* We do this so we can support read only groups */
	Ext.ux.GroupTabPanel.prototype.initGroup = function(group, index) {
		var before = this.strip.dom.childNodes[index];
		var p = this.getTemplateArgs(group);

		if(index === 0) {
			p.cls += ' x-tab-first';
		}
		p.cls += ' x-grouptabs-main';
		p.text = group.getMainItem().title;

		if(group.readOnly) {
			var el = before ? this.groupReadOnlyTpl.insertBefore(before, p) : this.groupReadOnlyTpl.append(this.strip, p),
				tl = this.createCorner(el, 'top-' + this.tabPosition),
				bl = this.createCorner(el, 'bottom-' + this.tabPosition);
		} else {
			var el = before ? this.groupTpl.insertBefore(before, p) : this.groupTpl.append(this.strip, p),
				tl = this.createCorner(el, 'top-' + this.tabPosition),
				bl = this.createCorner(el, 'bottom-' + this.tabPosition);
		}

		group.tabEl = el;
		if(group.expanded) {
			this.expandGroup(el);
		}

		if(Ext.isIE6 || (Ext.isIE && !Ext.isStrict)) {
			bl.setLeft('-10px');
			bl.setBottom('-5px');
			tl.setLeft('-10px');
			tl.setTop('-5px');
		}

		this.mon(group, {
			scope: this,
			changemainitem: this.onGroupChangeMainItem,
			beforetabchange: this.onGroupBeforeTabChange
		});
	};

	/*
	 * Fix the item being null by using tp instead.
	 * Also set toolbars to empty to fix a bug.
	 */
	Ext.ux.GroupTab.prototype.onRemove = function(item) {
		this.toolbars = [];

		Ext.destroy(Ext.get(this.getTabEl(item)));
		this.stack.remove(item);
		item.un('disable', this.onItemDisabled, this);
		item.un('enable', this.onItemEnabled, this);
		item.un('titlechange', this.onItemTitleChanged, this);
		item.un('iconchange', this.onItemIconChanged, this);
		item.un('beforeshow', this.onBeforeShowItem, this);
	};

	this.panel = new Ext.ux.GroupTabPanel({
		anchor: '100% 100%',
		activeGroup: 0,
		alternateColor: true,

		items: [{
			xtype: 'grouptab',
			id: 'addrole',
			readOnly: true,

			items: [{
				title: 'Add Role'
			}]
		}]
	});

	this.form = new Ext.form.FormPanel({
		id: 'dashboard',
		frame: true,
		renderTo: div,

		height: 600,
		width: '100%',

		items: this.panel,

		standardSubmit: true,

		buttonAlign: 'center',
		buttons: [{
			text: 'Save',
			handler: function()
			{
				laboratree.permissions.dashboard.form.getForm().submit({
					url: String.format(laboratree.links.permissions[laboratree.permissions.dashboard.table_type], laboratree.permissions.dashboard.table_id)
				});
			}
		}]
	});
}

laboratree.permissions.Dashboard.prototype.addPermissions = function(role) {
	var featureConfig = [];
	Ext.each(role.features, function(feature, index, allFeatures) {
		var permissionConfig = [];
		Ext.each(feature.permissions, function(permission, index, allPermissions) {
			var checked = new Boolean(parseInt(permission.value)).valueOf();
			permissionConfig.push({
				xtype: 'hidden',
				name: 'data[RolesPermissions][' + role.id + '][' + feature.id + '][' + permission.id + ']',
				value: 0
			},{
				xtype: 'checkbox',
				fieldLabel: permission.title,
				name: 'data[RolesPermissions][' + role.id + '][' + feature.id + '][' + permission.id + ']',
				inputValue: 1,
				checked: checked
			})
		}, this);

		featureConfig.push({
			xtype: 'fieldset',
			title: feature.name,
			autoHeight: true,
			labelWidth: 200,

			items: permissionConfig
		});
	}, this);

	var readOnly = new Boolean(parseInt(role.read_only)).valueOf();

	return this.panel.insert(this.panel.items.items.length - 1, {
		id: 'role-' + role.id,
		xtype: 'grouptab',
		readOnly: readOnly,
		expanded: true,
		items: [{
			title: role.name,
			autoScroll: true,
			items: [{
				anchor: '100%',
				autoHeight: true,
				border: false,
				bodyStyle: 'margin: 5px',
				items: featureConfig
			}]
		}]
	});
}

laboratree.permissions.Dashboard.prototype.addRole = function() {
	Ext.Msg.prompt('Role Name', 'Please enter the name of the role:', function(btn, text) {
		if(btn == 'ok') {
			Ext.Ajax.request({
				url: String.format(laboratree.links.permissions.add, laboratree.permissions.dashboard.table_type, laboratree.permissions.dashboard.table_id) + '.json',
				params: {
					role: text,
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

					if(!data.role) {
						request.failure(response, request);
						return;
					}

					var group = laboratree.permissions.dashboard.addPermissions(data.role);
					laboratree.permissions.dashboard.panel.setActiveGroup(group);
					group.setActiveTab(group.getMainItem());

					laboratree.permissions.dashboard.panel.doLayout(false, true);
				},
				failure: function(response, request) {
				},
				scope: this
			});
		}
	});
}

laboratree.permissions.Dashboard.prototype.editRole = function(id) {
	var cmpId = id.split('__')[1];
	var roleId = cmpId.split('-')[1];

	var role = Ext.getCmp(cmpId);
	if(!role) {
		return false;
	}

	var name = role.items.items[0].title;

	Ext.Msg.prompt('Role Name', 'Please enter the name of the role:', function(btn, text) {
		if(btn == 'ok') {
			Ext.Ajax.request({
				url: String.format(laboratree.links.permissions.edit, roleId) + '.json',
				params: {
					role: text
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

					role.items.items[0].setTitle(text);
				},
				failure: function(response, request) {
				},
				scope: this
			});
		}
	}, this, false, name);
}

laboratree.permissions.Dashboard.prototype.deleteRole = function(id) {
	var cmpId = id.split('__')[1];
	var roleId = cmpId.split('-')[1];

	var role = Ext.getCmp(cmpId);
	if(!role) {
		return false;
	}

	Ext.Msg.confirm('Delete Role', 'Are you sure?', function(btn) {
		if(btn == 'yes') {
			Ext.Ajax.request({
				url: String.format(laboratree.links.permissions['delete'], roleId) + '.json',
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

					laboratree.permissions.dashboard.panel.remove(role);
					laboratree.permissions.dashboard.panel.setActiveGroup(0);
				},
				failure: function(response, request) {
				},
				scope: this
			});
		}
	}, this);
}

laboratree.permissions.makeManage = function(div) {
	laboratree.permissions.manage = new laboratree.permissions.Manage(div);
}

laboratree.permissions.Manage = function(div) {
	this.div = div;

	this.tree = new Ext.tree.TreePanel({
		height: 600,
		renderTo: div,

		rootVisible: false,
		autoScroll: true,
		animate: false,
		containerScroll: true,
		lines: true,

		loader: new Ext.tree.TreeLoader({
			url: laboratree.links.permissions.manage + '.json'
		}),

		root: new Ext.tree.AsyncTreeNode({
			text: 'Root',
			expanded: true
		}),

		contextMenu: new Ext.menu.Menu({
			items: [{
				id: 'add-permission',
				text: 'Add Permission'
			},{
				id: 'edit-permission',
				text: 'Edit Permission'
			},{
				id: 'delete-permission',
				text: 'Delete Permission'
			}],

			listeners: {
				itemclick: function(item) {
					var node = item.parentMenu.contextNode;
					var node_id = node.attributes.id;

					switch(item.id) {
						case 'add-permission':
							laboratree.permissions.manage.addPermission(node_id);
							break;
						case 'edit-permission':
							laboratree.permissions.manage.editPermission(node_id);
							break;
						case 'delete-permission':
							laboratree.permissions.manage.deletePermission(node_id);
							break;
					}
				}
			}
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
}

laboratree.permissions.Manage.prototype.addPermission = function(permission_id) {
}

laboratree.permissions.Manage.prototype.editPermission = function(permission_id) {
}

laboratree.permissions.Manage.prototype.deletePermission = function(permission_id) {
}
