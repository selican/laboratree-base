laboratree.session = {
	duration: 60 * 120 * 1000 /* Current Session Duration (seconds * minutes * milliseconds) */
};

laboratree.session.init = function() {
	laboratree.session.timer = window.setTimeout(laboratree.session.timeout, laboratree.session.duration);
};

laboratree.session.destroy = function() {
	if(laboratree.session.timer)
	{
		window.clearTimeout(laboratree.session.timer);
	}
};

laboratree.session.timeout = function() {
	Ext.Msg.show({
		title: 'Session Expired',
		msg: 'Your session has expired. Please login again to continue.',
		buttons: Ext.Msg.OK,
		fn: laboratree.session.doLogout,
		icon: Ext.MessageBox.WARNING
	});
};

laboratree.session.doLogout = function() {
	window.location = laboratree.links.users.logout;
};

if(window.addEventListener) {
	window.addEventListener('load', laboratree.session.init, false);
	window.addEventListener('unload', laboratree.session.destroy, false);
} else if(window.attachEvent) {
	window.attachEvent('onload', laboratree.session.init);
	window.attachEvent('onunload', laboratree.session.destroy);
} else {
	laboratree.session.init();
}
