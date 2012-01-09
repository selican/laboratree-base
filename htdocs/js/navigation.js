laboratree.navigation = {};

laboratree.navigation.create = function(div, sections) {
	var navigation = new Ext.Container({
		layout: 'accordion'
		/*
		layout: 'vbox',
		layoutConfig: {
			align: 'stretch',
			pack: 'start'
		}
		*/
	});

	var tpl = new Ext.XTemplate(
		'<tpl for=".">',
			'<div class="navigation-item"><a href="{url}" title="{title}" onclick="return laboratree.navigation.confirm(\'{prompt}\');">{title}</a></div>',
		'</tpl>'
	);

	var id;
	for(id in sections) {
		if(sections.hasOwnProperty(id)) {
			var section = sections[id];

			var panel = new Ext.Panel({
				id: 'nav-' + id,
				title: section.title,
				autoHeight: true,

				items: new Ext.DataView({
					store: new Ext.data.ArrayStore({
						fields: ['title', 'url', 'prompt'],
						data: section.items
					}),

					tpl: tpl,

					autoHeight: true,
					overClass: 'x-navigation-over',
					itemSelector: 'div.navigation-item'
				})
			});

			navigation.add(panel);
		}
	}

	navigation.render(div);
};

laboratree.navigation.grouplist = function(select) {
	var selected = $('option:selected', select).val();
	var parts = selected.split(':');

	if(parts.length > 1) {
		if(parts[0] == 'group') {
			window.location = String.format(laboratree.links.groups.dashboard, parts[1]);
		} else if(parts[0] == 'project') {
			window.location = String.format(laboratree.links.projects.dashboard, parts[1]);
		}
	}
};

laboratree.navigation.confirm = function(msg) {
	if(msg && msg != 'null')
	{
		return confirm(msg);
	}

	return true;
};
