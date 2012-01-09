laboratree.users = {};
laboratree.users.masks = {};
laboratree.users.register = {};
laboratree.users.edit = {};
laboratree.users.dashboard = {};

laboratree.users.makeRegister = function(div, data_url) {
	laboratree.users.register = new laboratree.users.Register(div, data_url);
};

laboratree.users.Register = function(div, data_url) {
	Ext.QuickTips.init();

	this.container_div = div;
	this.data_url = data_url;

	Ext.QuickTips.init();

	Ext.form.Field.prototype.msgTarget = 'under';

	this.captcha = new Ext.ux.Recaptcha({
		id: 'UserCaptcha',
		publickey: '6LeNOQkAAAAAACzdA5vaViI4r9hEduo5Cb6nblIA',
		theme: 'red',
		lang: 'en',
		width: 320
	});

	this.store = new Ext.data.JsonStore({
		root: 'questions',
		url: data_url,
		fields: [
			'id', 'question'
		]
	});

	this.form = new Ext.form.FormPanel({
		labelWidth: 120,
		title: 'Registration',
		renderTo: div,
		width: '100%',
		frame: true,

		buttonAlign: 'center',

		defaultType: 'textfield',

		defaults: {
			anchor: '100%'
		},
		
		items: [{
			id: 'UserName',
			fieldLabel: 'Name',
			name: 'data[User][name]',
			allowBlank: false,
			maxLength: 255,
			validationEvent: false
		},{
			id: 'UserEmail',
			fieldLabel: 'Email',
			name: 'data[User][email]',
			allowBlank: false,
			maxLength: 255,
			validationEvent: false
		},{
			id: 'UserUsername',
			fieldLabel: 'Username',
			name: 'data[User][username]',
			allowBlank: false,
			minLength: 3,
			maxLength: 15,
			validationEvent: false
		},{
			id: 'UserPassword',
			fieldLabel: 'Password',
			name: 'data[User][password]',
			allowBlank: false,
			minLength: 8,
			maxLength: 255,
			validationEvent: false,
			inputType: 'password'
			
		},{
			id: 'UserPassword2',
			fieldLabel: 'Confirm Password',
			name: 'data[User][password2]',
			inputType: 'password',
			initialPassField: 'UserPassword',
			allowBlank: false,
			minLength: 8,
			maxLength: 255,
			validationEvent: false
		},this.captcha],
		
		buttons: [{
			text: 'Register',
			handler: function(){
				if(laboratree.users.register.form.getForm().isValid()) {
					laboratree.users.register.form.getForm().submit({
						url: laboratree.users.register.data_url,
						success: function(form, action) {
							Ext.Msg.alert('Success', action.result.msg, function() {
								window.location = String.format(laboratree.links.users.login, '');
							},this);
						},
						failure: function(form, action) {
							Recaptcha.reload();
							if(action.result.errors) {
								var model;
								for(model in action.result.errors) {
									if(action.result.errors.hasOwnProperty(model)) {
										var field;
										for(field in action.result.errors[model]) {
											if(action.result.errors[model].hasOwnProperty(field)) {
												var id = model + field.split('_').map(Ext.util.Format.capitalize).join('');

												var cmp = Ext.getCmp(id);
												if(cmp) {
													if(cmp.markInvalid) {
														cmp.markInvalid(action.result.errors[model][field]);
													} else {
														cmp.el.addClass('field-invalid');
													}
												}
											}
										}
									}
								}
							}
						}
					});
				} else {
					Recaptcha.reload();
				}
			}
		}]
	});
};

/**
 * User Account Management
 */
laboratree.users.makeEdit = function(div, section) {
	Ext.onReady(function(){
		laboratree.users.edit = new laboratree.users.Edit(div, section);	
		Ext.Ajax.request({
			url: laboratree.links.users.account + '.json',
			success: function(response, request) {
				var data = Ext.decode(response.responseText);
				if(!data) {
					request.failure(response, request);
					return false;
				}

				if(!data.success) {
					request.failure(response, request);
					return false;
				}

				if(!data.user) {
					request.failure(response, request);
					return false;
				}

				var user = data.user;
	
				/* User */
				var field;
				for(field in user.User) {
					if(user.User.hasOwnProperty(field)) {
						laboratree.users.edit.setField('User', field, user.User.field);
					}
				}
	
				var idx; /* loop index */

				/* UsersAddress */
				if(user.UsersAddress && user.UsersAddress.length > 0) {
					laboratree.users.edit.initAddress();
	
					for(idx in user.UsersAddress) {
						if(user.UsersAddress.hasOwnProperty(idx)) {
							laboratree.users.edit.addAddressInternal(user.UsersAddress.idx);
						}
					}
				}
	
				/* UsersEducation */
				if(user.UsersEducation && user.UsersEducation.length > 0) {
					laboratree.users.edit.initEducation();
	
					for(idx in user.UsersEducation) {
						if(user.UsersEducation.hasOwnProperty(idx)) {
							laboratree.users.edit.addEducationInternal(user.UsersEducation.idx);
						}
					}
				}
	
				/* UsersPhone */
				if(user.UsersPhone && user.UsersPhone.length > 0) {
					laboratree.users.edit.initPhone();
	
					for(idx in user.UsersPhone) {
						if(user.UsersPhone.hasOwnProperty(idx)) {
							laboratree.users.edit.addPhoneInternal(user.UsersPhone.idx);
						}
					}
				}
	
				/* UsersUrl */
				if(user.UsersUrl && user.UsersUrl.length > 0) {
					laboratree.users.edit.initUrl();
	
					for(idx in user.UsersUrl) {
						if(user.UsersUrl.hasOwnProperty(idx)) {
							laboratree.users.edit.addUrlInternal(user.UsersUrl.idx);
						}
					}
				}
	
				/* UsersAssociation */
				if(user.UsersAssociation && user.UsersAssociation.length > 0) {
					laboratree.users.edit.initAssociation();
	
					for(idx in user.UsersAssociation) {
						if(user.UsersAssociation.hasOwnProperty(idx)) {
							laboratree.users.edit.addAssociationInternal(user.UsersAssociation.idx);
						}
					}
				}

				/* UsersAward */
				if(user.UsersAward && user.UsersAward.length > 0) {
					laboratree.users.edit.initAward();
	
					for(idx in user.UsersAward) {
						if(user.UsersAward.hasOwnProperty(idx)) {
							laboratree.users.edit.addAwardInternal(user.UsersAward.idx);
						}
					}
				}
			},
			failure: function(response, request) {

			}
		});
	});
};

laboratree.users.Edit = function(div, section) {
	this.addresses = null;
	this.phones = null;
	this.urls = null;
	this.educations = null;
	this.associations = null;
	this.awards = null;

	this.stores = {};

	this.privacy = new Ext.form.ComboBox({
		id: 'UserPrivacy',
		fieldLabel: 'Privacy',
		triggerAction: 'all',
		forceSelection: true,
		mode: 'local',
		name: 'UserPrivacy',
		hiddenName: 'data[User][privacy]',
		valueField: 'value',
		displayField: 'name',
		anchor: '100%',
		emptyText: 'Select a privacy level...',
		selectOnFocus: true,

		store: new Ext.data.ArrayStore({
			fields: ['value', 'name'],
			data: [
				['private', 'Private'],
				['public', 'Public']
			]
		})
	});

	this.templates = {
		address: new Ext.Template([
			'<div class="middle">',
				'<div>No Address Entries</div>',
				'<div><a href="#" onclick="laboratree.users.edit.addAddress();" title="Add Address Entry">Add Address Entry</div>',
			'</div>'
		]),
		education: new Ext.Template([
			'<div class="middle">',
				'<div>No Education Entries</div>',
				'<div><a href="#" onclick="laboratree.users.edit.addEducation();" title="Add Education Entry">Add Education Entry</div>',
			'</div>'
		]),
		phone: new Ext.Template([
			'<div class="middle">',
				'<div>No Phone Number Entries</div>',
				'<div><a href="#" onclick="laboratree.users.edit.addPhone();" title="Add Phone Number Entry">Add Phone Number Entry</div>',
			'</div>'
		]),
		url: new Ext.Template([
			'<div class="middle">',
				'<div>No Website Entries</div>',
				'<div><a href="#" onclick="laboratree.users.edit.addUrl();" title="Add Website Entry">Add Website Entry</div>',
			'</div>'
		]),
		association: new Ext.Template([
			'<div class="middle">',
				'<div>No Association Entries</div>',
				'<div><a href="#" onclick="laboratree.users.edit.addAssociation();" title="Add Association Entry">Add Association Entry</div>',
			'</div>'
		]),
		award: new Ext.Template([
			'<div class="middle">',
				'<div>No Award Entries</div>',
				'<div><a href="#" onclick="laboratree.users.edit.addAward();" title="Add Award Entry">Add Award Entry</div>',
			'</div>'
		])
	};

	this.form = new Ext.FormPanel({
		labelAlign: 'top',
		title: 'Account Management',
		width: '100%',
		autoHeight: true,
		renderTo: div,
		buttonAlign: 'center',
		frame: true,
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
			xtype: 'fieldset',
			collapsible: true,
			title: 'Personal Information',
			defaultType: 'textfield',
			defaults: {
				forceLayout: true,
				anchor: '100%'
			},
			
			items: [{
				id: 'UserName',
				fieldLabel: 'Name',
				name: 'data[User][name]',
				allowBlank: false,
				maxLength: 255
			},{
				id: 'UserPicture',
				xtype: 'fileuploadfield',
				fieldLabel: 'Picture',
				name: 'data[User][picture]',
				emptyText: 'Select a GIF, JPG, or PNG image...',
				anchor: '100%',
				maxLength: 32
			}]
		},{
			xtype: 'fieldset',
			collapsible: true,
			title: 'Contact Information',
			autoHeight: true,
			defaultType: 'textfield',
			defaults: {
				forceLayout: true,
				anchor: '100%'
			},

			items: [{
				id: 'UserEmail',
				fieldLabel: 'Email',
				name: 'data[User][email]'
			},{
				xtype: 'tabpanel',
				activeTab: 0,
				height: 375,
				border: false,
				deferredRender: false,

				defaults: {
					layout: 'form',
					forceLayout: true,
					anchor: '100% 100%'
				},

				items: [{
					id: 'addresses',
					title: 'Addresses',
					defaults: {
						forceLayout: true,
						anchor: '100% 100%'
					},

					items: [{
						html: this.templates.address.apply()
					}]
				},{
					id: 'phones',
					title: 'Phone Numbers',
					defaults: {
						forceLayout: true,
						anchor: '100% 100%'
					},

					items: [{
						html: this.templates.phone.apply()
					}]
				},{
					id: 'urls',
					title: 'Websites',
					defaults: {
						forceLayout: true,
						anchor: '100% 100%'
					},

					items: [{
						html: this.templates.url.apply()
					}]
				}]
			}]
		},{
			xtype: 'fieldset',
			collapsible: true,
			title: 'Additional Information',
			autoHeight: true,
			defaultType: 'textfield',
			defaults: {
				forceLayout: true,
				anchor: '100%'
			},

			items: [{
				xtype: 'tabpanel',
				activeTab: 0,
				height: 225,
				border: false,
				deferredRender: false,

				style: {
					paddingBottom: '4px',
					marginBottom: '4px'
				},

				defaults: {
					layout: 'form',
					forceLayout: true,
					anchor: '100% 100%'
				},

				items: [{
					id: 'educations',
					title: 'Education',
					defaults: {
						forceLayout: true,
						anchor: '100% 100%'
					},

					items: [{
						html: this.templates.education.apply()
					}]
				},{
					id: 'associations',
					title: 'Associations',
					defaults: {
						forceLayout: true,
						anchor: '100% 100%'
					},

					items: [{
						html: this.templates.association.apply()
					}]
				},{
					id: 'awards',
					title: 'Awards',
					defaults: {
						forceLayout: true,
						anchor: '100% 100%'
					},

					items: [{
						html: this.templates.award.apply()
					}]
				}]
			},{
				id: 'UserInterests',
				xtype: 'textarea',
				fieldLabel: 'Interests',
				name: 'data[User][interests]',
				height: 100
			}]
		},{
			xtype: 'fieldset',
			collapsible: true,
			title: 'Privacy',
			items: [this.privacy]
		}],

		buttons: [{
			text: 'Save',
			handler: function() {
				if(laboratree.users.edit.form.getForm().isValid()) {
					laboratree.users.edit.form.getForm().submit({
						url: laboratree.links.users.account,
						success: function(form, action) {
														
							Ext.Msg.alert('Success', action.result.msg, function() {
							},this);
						},
						failure: function(form, action) {
							if(action.result.errors) {
								var model;
								for(model in action.result.errors) {
									if(action.result.errors.hasOwnProperty(model)) {
										var field;
										for(field in action.result.errors[model]) {
											if(action.result.errors[model].hasOwnProperty(field)) {
												var id = model + field.split('_').map(Ext.util.Format.capitalize).join('');

												var cmp = Ext.getCmp(id);
												if(cmp) {
													if(cmp.markInvalid) {
														cmp.markInvalid(action.result.errors[model][field]);
													} else {
														cmp.el.addClass('field-invalid');
													}
												}
											}
										}
									}
								}
							}
						}
					});
				}
			}
		}]
	});
};

/**
 * UsersAddress
 */
laboratree.users.Edit.prototype.resetAddress = function() {
	var tabPanel = Ext.getCmp('addresss');
	tabPanel.removeAll(true);
	tabPanel.add({
		html: this.templates.address.apply()
	});
	tabPanel.doLayout(false, true);

	laboratree.users.edit.addresss = null;
};

laboratree.users.Edit.prototype.initAddress = function() {
	this.addresses = new Ext.TabPanel({
		id: 'UsersAddress',
		minTabWidth: 110,
		maxTabWidth: 150,
		enableTabScroll: true,
		border: false,
		activeTab: 0,
		deferredRender: false,

		defaults: {
			layout: 'form',
			frame: true,
			autoScroll: true,
			closable: true,
			forceLayout: true,
			anchor: '100% 100%'
		},
					
		items: [{
			id: 'addaddress',
			title: 'Add Address',
			closable: false
		}],

		listeners: {
			beforetabchange: function(panel, newTab, currentTab) {
				if(currentTab && currentTab.id != 'addaddress') {
					if(newTab.id == 'addaddress') {
						laboratree.users.edit.addAddress();
						return false;
					}
				}
			}
		}
	});

	var tabPanel = Ext.getCmp('addresses');
	tabPanel.removeAll();
	tabPanel.add(this.addresses);
	tabPanel.doLayout(false, true);
};

laboratree.users.Edit.prototype.addAddress = function() {
	Ext.MessageBox.prompt('Add Address', 'Please enter a label:', function(btn, label) {
		if(btn == 'ok') {
			var address = {
				label: label,
				address1: null,
				address2: null,
				city: null,
				state: null,
				zip_code: null,
				country: null
			};

			laboratree.users.edit.addAddressInternal(address);
		}
	});	
};

laboratree.users.Edit.prototype.addAddressInternal = function(address) {
	if(!this.addresses) {
		this.initAddress();
	}

	if(!address.id) {
		address.id = Ext.id();
	}

	var tabPanel = Ext.getCmp('UsersAddress');
	var position = tabPanel.items.items.length - 1;

	var item = tabPanel.insert(position, {
		title: address.label,
		closable: true,
		defaultType: 'textfield',
		defaults: {
			forceLayout: true,
			anchor: '100%'
		},

		items: [{
			xtype: 'hidden',
			name: 'data[UsersAddress][' + address.id + '][label]',
			value: address.label
		},{
			fieldLabel: 'Address',
			name: 'data[UsersAddress][' + address.id + '][address1]',
			value: address.address1
		},{
			fieldLabel: 'Address 2',
			name: 'data[UsersAddress][' + address.id + '][address2]',
			value: address.address2
		},{
			fieldLabel: 'City',
			name: 'data[UsersAddress][' + address.id + '][city]',
			value: address.city
		},{
			fieldLabel: 'State',
			name: 'data[UsersAddress][' + address.id + '][state]',
			value: address.state
		},{
			fieldLabel: 'Zip Code',
			name: 'data[UsersAddress][' + address.id + '][zip_code]',
			value: address.zip_code
		},{
			fieldLabel: 'Country',
			name: 'data[UsersAddress][' + address.id + '][country]',
			value: address.country
		}]
	});

	item.show();
	tabPanel.doLayout(false, true);
};

/**
 * UsersEducation
 */
laboratree.users.Edit.prototype.resetEducation = function() {
	var tabPanel = Ext.getCmp('educations');
	tabPanel.removeAll(true);
	tabPanel.add({
		html: this.templates.education.apply()
	});
	tabPanel.doLayout(false, true);

	laboratree.users.edit.educations = null;
};

laboratree.users.Edit.prototype.initEducation = function() {
	this.educations = new Ext.TabPanel({
		id: 'UsersEducation',
		minTabWidth: 110,
		maxTabWidth: 150,
		enableTabScroll: true,
		border: false,
		activeTab: 0,
		deferredRender: false,

		defaults: {
			layout: 'form',
			frame: true,
			autoScroll: true,
			closable: true,
			forceLayout: true,
			anchor: '100% 100%'
		},
				
		items: [{
			id: 'addeducation',
			title: 'Add Education',
			closable: false
		}],

		listeners: {
			beforetabchange: function(panel, newTab, currentTab) {
				if(currentTab) {
					if(panel.items.items.length < 2) {
						laboratree.users.edit.resetEducation();
						return false;
					}
					else if(newTab.id == 'addeducation') {
						laboratree.users.edit.addEducation();
						return false;
					}
				}
			}
		}
	});

	var tabPanel = Ext.getCmp('educations');
	tabPanel.removeAll();
	tabPanel.add(this.educations);
	tabPanel.doLayout(false, true);
};

laboratree.users.Edit.prototype.addEducation = function() {
	Ext.MessageBox.prompt('Add Education', 'Please enter a label:', function(btn, label) {
		if(btn == 'ok') {
			var education = {
				label: label,
				institution: null,
				degree: null,
				years: null
			};

			laboratree.users.edit.addEducationInternal(education);
		}
	});	
};

laboratree.users.Edit.prototype.addEducationInternal = function(education) {
	if(!this.educations) {
		this.initEducation();
	}

	if(!education.id) {
		education.id = Ext.id();
	}

	var tabPanel = Ext.getCmp('UsersEducation');
	var position = tabPanel.items.items.length - 1;

	var item = tabPanel.insert(position, {
		title: education.label,
		closable: true,
		defaultType: 'textfield',
		defaults: {
			forceLayout: true,
			anchor: '100%'
		},

		items: [{
			xtype: 'hidden',
			name: 'data[UsersEducation][' + education.id + '][label]',
			value: education.label
		},{
			fieldLabel: 'Institution',
			name: 'data[UsersEducation][' + education.id + '][institution]',
			value: education.institution
		},{
			fieldLabel: 'Degree',
			name: 'data[UsersEducation][' + education.id + '][degree]',
			value: education.degree
		},{
			fieldLabel: 'Years',
			name: 'data[UsersEducation][' + education.id + '][years]',
			value: education.years
		}]
	});

	item.show();
	tabPanel.doLayout(false, true);
};

/**
 * UsersPhone
 */
laboratree.users.Edit.prototype.resetPhone = function() {
	var tabPanel = Ext.getCmp('phones');
	tabPanel.removeAll(true);
	tabPanel.add({
		html: this.templates.phone.apply()
	});
	tabPanel.doLayout(false, true);

	laboratree.users.edit.phones = null;
};

laboratree.users.Edit.prototype.initPhone = function() {
	this.phones = new Ext.TabPanel({
		id: 'UsersPhone',
		minTabWidth: 110,
		maxTabWidth: 150,
		enableTabScroll: true,
		border: false,
		activeTab: 0,
		deferredRender: false,

		defaults: {
			layout: 'form',
			frame: true,
			autoScroll: true,
			closable: true,
			forceLayout: true,
			anchor: '100% 100%'
		},

		items: [{
			id: 'addphone',
			title: 'Add Phone Number',
			closable: false
		}],

		listeners: {
			beforetabchange: function(panel, newTab, currentTab) {
				if(currentTab) {
					if(panel.items.items.length < 2) {
						laboratree.users.edit.resetPhone();
						return false;
					}
					else if(newTab.id == 'addphone') {
						laboratree.users.edit.addPhone();
						return false;
					}
				}
			}
		}
	});
	
	var tabPanel = Ext.getCmp('phones');
	tabPanel.removeAll();
	tabPanel.add(this.phones);
	tabPanel.doLayout(false, true);
};

laboratree.users.Edit.prototype.addPhone = function() {
	Ext.MessageBox.prompt('Add Phone Number', 'Please enter a label:', function(btn, label) {
		if(btn == 'ok') {
			var phone = {
				label: label,
				phone_number: null
			};

			laboratree.users.edit.addPhoneInternal(phone);
		}
	});
};

laboratree.users.Edit.prototype.addPhoneInternal = function(phone) {
	if(!this.phones) {
		this.initPhone();
	}

	if(!phone.id) {
		phone.id = Ext.id();
	}

	var tabPanel = Ext.getCmp('UsersPhone');
	var position = tabPanel.items.items.length - 1;

	var item = tabPanel.insert(position, {
		title: phone.label,
		closable: true,
		defaultType: 'textfield',
		defaults: {
			forceLayout: true,
			anchor: '100%'
		},

		items: [{
			xtype: 'hidden',
			name: 'data[UsersPhone][' + phone.id + '][label]',
			value: phone.label
		},{
			fieldLabel: 'Phone Number',
			name: 'data[UsersPhone][' + phone.id + '][phone_number]',
			value: phone.phone_number
		}]
	});

	item.show();
	tabPanel.doLayout(false, true);
};

/**
 * UsersUrl
 */
laboratree.users.Edit.prototype.resetUrl = function() {
	var tabPanel = Ext.getCmp('urls');
	tabPanel.removeAll(true);
	tabPanel.add({
		html: this.templates.url.apply()
	});
	tabPanel.doLayout(false, true);

	laboratree.users.edit.urls = null;
};

laboratree.users.Edit.prototype.initUrl = function() {
	this.urls = new Ext.TabPanel({
		id: 'UsersUrl',
		minTabWidth: 110,
		maxTabWidth: 150,
		enableTabScroll: true,
		border: false,
		activeTab: 0,
		deferredRender: false,

		defaults: {
			layout: 'form',
			frame: true,
			autoScroll: true,
			closable: true,
			forceLayout: true,
			anchor: '100% 100%'
		},

		items: [{
			id: 'addurl',
			title: 'Add Website',
			closable: false
		}],

		listeners: {
			beforetabchange: function(panel, newTab, currentTab) {
				if(currentTab) {
					if(panel.items.items.length < 2) {
						laboratree.users.edit.resetUrl();
						return false;
					}
					else if(newTab.id == 'addurl') {
						laboratree.users.edit.addUrl();
						return false;
					}
				}
			}
		}
	});

	var tabPanel = Ext.getCmp('urls');
	tabPanel.removeAll();
	tabPanel.add(this.urls);
	tabPanel.doLayout(false, true);
};

laboratree.users.Edit.prototype.addUrl = function() {
	Ext.MessageBox.prompt('Add Website', 'Please enter a label:', function(btn, label) {
		if(btn == 'ok') {
			var url = {
				label: label,
				link: null
			};

			laboratree.users.edit.addUrlInternal(url);
		}
	});
};

laboratree.users.Edit.prototype.addUrlInternal = function(url) {
	if(!this.urls) {
		this.initUrl();
	}

	if(!url.id) {
		url.id = Ext.id();
	}

	var tabPanel = Ext.getCmp('UsersUrl');
	var position = tabPanel.items.items.length - 1;

	var item = tabPanel.insert(position, {
		title: url.label,
		closable: true,
		defaultType: 'textfield',
		defaults: {
			forceLayout: true,
			anchor: '100%'
		},

		items: [{
			xtype: 'hidden',
			name: 'data[UsersUrl][' + url.id + '][label]',
			value: url.label
		},{
			fieldLabel: 'Url',
			name: 'data[UsersUrl][' + url.id + '][link]',
			value: url.link
		}]
	});

	item.show();
	tabPanel.doLayout(false, true);
};

/**
 * UsersAssociation
 */
laboratree.users.Edit.prototype.resetAssociation = function() {
	var tabPanel = Ext.getCmp('associations');
	tabPanel.removeAll(true);
	tabPanel.add({
		html: this.templates.association.apply()
	});
	tabPanel.doLayout(false, true);

	laboratree.users.edit.associations = null;
};

laboratree.users.Edit.prototype.initAssociation = function() {
	this.associations = new Ext.TabPanel({
		id: 'UsersAssociation',
		minTabWidth: 110,
		maxTabWidth: 150,
		enableTabScroll: true,
		border: false,
		activeTab: 0,
		deferredRender: false,

		defaults: {
			layout: 'form',
			frame: true,
			autoScroll: true,
			closable: true,
			forceLayout: true,
			anchor: '100% 100%'
		},

		items: [{
			id: 'addassociation',
			title: 'Add Association',
			closable: false
		}],

		listeners: {
			beforetabchange: function(panel, newTab, currentTab) {
				if(currentTab) {
					if(panel.items.items.length < 2) {
						laboratree.users.edit.resetAssociation();
						return false;
					}
					else if(newTab.id == 'addassociation') {
						laboratree.users.edit.addAssociation();
						return false;
					}
				}
			}
		}
	});

	var tabPanel = Ext.getCmp('associations');
	tabPanel.removeAll();
	tabPanel.add(this.associations);
	tabPanel.doLayout(false, true);
};


laboratree.users.Edit.prototype.addAssociation = function() {
	Ext.MessageBox.prompt('Add Association', 'Please enter a label:', function(btn, label) {
		if(btn == 'ok') {
			var association = {
				label: label,
				link: null
			};

			laboratree.users.edit.addAssociationInternal(association);
		}
	});
};

laboratree.users.Edit.prototype.addAssociationInternal = function(association) {
	if(!this.associations) {
		this.initAssociation();
	}

	if(!association.id) {
		association.id = Ext.id();
	}

	var tabPanel = Ext.getCmp('UsersAssociation');
	var position = tabPanel.items.items.length - 1;

	var item = tabPanel.insert(position, {
		title: association.label,
		closable: true,
		defaultType: 'textfield',
		defaults: {
			forceLayout: true,
			anchor: '100%'
		},

		items: [{
			xtype: 'hidden',
			name: 'data[UsersAssociation][' + association.id + '][label]',
			value: association.label
		},{
			fieldLabel: 'Association',
			name: 'data[UsersAssociation][' + association.id + '][association]',
			value: association.association
		},{
			fieldLabel: 'Role',
			name: 'data[UsersAssociation][' + association.id + '][role]',
			value: association.role
		}]
	});

	item.show();
	tabPanel.doLayout(false, true);
};

/**
 * UsersAward
 */
laboratree.users.Edit.prototype.resetAward = function() {
	var tabPanel = Ext.getCmp('awards');
	tabPanel.removeAll(true);
	tabPanel.add({
		html: this.templates.award.apply()
	});
	tabPanel.doLayout(false, true);

	laboratree.users.edit.awards = null;
};

laboratree.users.Edit.prototype.initAward = function() {
	this.awards = new Ext.TabPanel({
		id: 'UsersAward',
		minTabWidth: 110,
		maxTabWidth: 150,
		enableTabScroll: true,
		border: false,
		activeTab: 0,
		deferredRender: false,

		defaults: {
			layout: 'form',
			frame: true,
			autoScroll: true,
			closable: true,
			forceLayout: true,
			anchor: '100% 100%'
		},

		items: [{
			id: 'addaward',
			title: 'Add Award',
			closable: false
		}],

		listeners: {
			beforetabchange: function(panel, newTab, currentTab) {
				if(currentTab) {
					if(panel.items.items.length < 2) {
						laboratree.users.edit.resetAward();
						return false;
					}
					else if(newTab.id == 'addaward') {
						laboratree.users.edit.addAward();
						return false;
					}
				}
			}
		}
	});

	var tabPanel = Ext.getCmp('awards');
	tabPanel.removeAll();
	tabPanel.add(this.awards);
	tabPanel.doLayout(false, true);
};

laboratree.users.Edit.prototype.addAward = function() {
	Ext.MessageBox.prompt('Add Award', 'Please enter a label:', function(btn, label) {
		if(btn == 'ok') {
			var award = {
				label: label,
				link: null
			};

			laboratree.users.edit.addAwardInternal(award);
		}
	});
};

laboratree.users.Edit.prototype.addAwardInternal = function(award) {
	if(!this.awards) {
		this.initAward();
	}

	if(!award.id) {
		award.id = Ext.id();
	}

	var tabPanel = Ext.getCmp('UsersAward');
	var position = tabPanel.items.items.length - 1;

	var item = tabPanel.insert(position, {
		title: award.label,
		closable: true,
		defaultType: 'textfield',
		defaults: {
			forceLayout: true,
			anchor: '100%'
		},

		items: [{
			xtype: 'hidden',
			name: 'data[UsersAward][' + award.id + '][label]',
			value: award.label
		},{
			fieldLabel: 'Award',
			name: 'data[UsersAward][' + award.id + '][award]',
			value: award.award
		}]
	});

	item.show();
	tabPanel.doLayout(false, true);
};

laboratree.users.Edit.prototype.setField = function(model, field, value) {
	var id = model + laboratree.util.camelize(field);
	var component = Ext.getCmp(id);
	if(component) {
		component.setValue(value);
	}
};

laboratree.users.makeDashboard = function(div, data_url) {
	Ext.onReady(function(){
		laboratree.users.dashboard = new laboratree.users.Dashboard(div, data_url);
	});
};

laboratree.users.Dashboard = function(div, data_url) {
	Ext.QuickTips.init();

	Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
		expires: new Date(new Date().getTime() + (1000 * 60 * 60 * 24 * 7))
	}));

	this.div = div;
	this.data_url = data_url;

	this.state_id = 'state-user-dashboard';

	this.defaults = {
		groups: {
			collapsed: false,
			column: 'dashboard-column-left',
			position: 0
		},
		projects: {
			collapsed: false,
			column: 'dashboard-column-right',
			position: 0
		},
		colleagues: {
			collapsed: false,
			column: 'dashboard-column-left',
			position: 1
		}
	};

	this.stores = {
		groups: new Ext.data.JsonStore({
			root: 'groups',
			autoLoad: true,
			url: data_url,
			baseParams: {
				model: 'groups'
			},
			fields: ['id', 'name', 'session', 'type', 'email', 'privacy', 'image', 'members', 'projects', 'role']
		}),
		projects: new Ext.data.GroupingStore({
			autoLoad: true,
			url: data_url,
			baseParams: {
				model: 'projects'
			},
			reader: new Ext.data.JsonReader({
				root: 'projects',
				fields: ['id', 'name', 'group', 'group_type', 'group_id', 'members', 'role']
			}),
			groupField: 'group'
		}),
		colleagues: new Ext.data.JsonStore({
			root: 'colleagues',
			autoLoad: true,
			url: data_url,
			baseParams: {
				model: 'colleagues'
			},
			fields: ['id', 'name', 'username', 'session', 'type', 'activity']
		})
	};

	this.portlets = {
		groups: new Ext.grid.GridPanel({
			id: 'portlet-groups',
			height: 200,
			stripeRows: true,
			loadMask: {msg: 'Loading...'},

			store: this.stores.groups,

			autoExpandColumn: 'name',

			cm: new Ext.grid.ColumnModel({
				defaults: {
					sortable: true
				},

				columns: [{
					id: 'name',
					header: 'Name',
					dataIndex: 'name',
					renderer: this.renderGroupName
				},{
					id: 'role',
					header: 'Role',
					dataIndex: 'role',
					width: 100,
					renderer: this.renderGroupRole
				},{
					id: 'members',
					header: 'Members',
					dataIndex: 'members',
					width: 60
				}]
			})
		}),
		projects: new Ext.grid.GridPanel({
			id: 'portlet-projects',
			height: 200,
			stripeRows: true,
			loadMask: {msg: 'Loading...'},

			store: this.stores.projects,

			autoExpandColumn: 'name',
			cm: new Ext.grid.ColumnModel({
				defaults: {
					sortable: true
				},

				columns: [{
					id: 'name',
					header: 'Name',
					dataIndex: 'name',
					renderer: this.renderProjectName
				},{
					id: 'role',
					header: 'Role',
					dataIndex: 'role',
					width: 100,
					renderer: this.renderProjectRole
				},{
					id: 'members',
					header: 'Members',
					dataIndex: 'members',
					width: 60
				},{
					id: 'group',
					header: 'Group',
					dataIndex: 'group',
					hidden: true,
					renderer: this.renderProjectGrouping
				}]
			}),

			view: new Ext.grid.GroupingView({
				forceFit: true,
				showGroupName: false
			})
		}),
		colleagues: new Ext.grid.GridPanel({
			id: 'portlet-colleagues',
			height: 200,
			stripeRows: true,
			loadMask: {msg: 'Loading...'},

			store: this.stores.colleagues,

			autoExpandColumn: 'name',

			cm: new Ext.grid.ColumnModel({
				defaults: {
					sortable: true
				},

				columns: [{
					id: 'name',
					header: 'Name',
					dataIndex: 'name'
				},{
					id: 'status',
					header: 'Status',
					dataIndex: 'activity',
					width: 60,
					renderer: this.renderColleagueStatus
				}]
			})
		})
	};

	this.panels = {
		groups: {
			id: 'panel-groups',
			title: '<a href="' + laboratree.links.groups.user + '">Groups</a> <span class="create-link"><a href="' + laboratree.links.groups.create + '">- create group -</a></span>',
			layout: 'fit',

			tools: [{
				id:'plus',
				qtip: 'Create a Group',
				handler: function() {
					window.location = laboratree.links.groups.create;
				}
			},{
				id: 'restore',
				qtip: 'Groups Dashboard',
				handler: function() {
					window.location = laboratree.links.groups.index;
				}
			},{
				id: 'help',
				qtip: 'Help Groups',
				handler: function(event, toolEl, panel, tc) {
					Ext.Ajax.request({
						url: String.format(laboratree.links.help.site.index, 'user', 'groups') + '.json',
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

							laboratree.users.dashboard.helpPopup('User Group Help', data.help.content);
						},
						failure: function(response, request) {

						}
					});
				}
			}],

			items: this.portlets.groups,

			listeners: {
				expand: function(p) {
					var id = 'groups';
					laboratree.users.toggle(id, false);
				},
				collapse: function(p) {
					var id = 'groups';
					laboratree.users.toggle(id, true);
				}
			}
		},
		projects: {
			id: 'panel-projects',
			title: '<a href="' + laboratree.links.projects.user + '">Projects</a>',
			layout: 'fit',

			tools: [{
				id: 'restore',
				qtip: 'Projects Dashboard',
				handler: function() {
					window.location = laboratree.links.projects.user;
				}
			},{
				id: 'help',
				qtip: 'Help Projects',
				handler: function(event, toolEl, panel, tc) {
					Ext.Ajax.request({
						url: String.format(laboratree.links.help.site.index, 'user', 'projects') + '.json',
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

							laboratree.users.dashboard.helpPopup('User Project Help', data.help.content);
						},
						failure: function() {
						}
					});
				}

			}],

			items: this.portlets.projects,

			listeners: {
				expand: function(p) {
					var id = 'projects';
					laboratree.users.toggle(id, false);
				},
				collapse: function(p) {
					var id = 'projects';
					laboratree.users.toggle(id, true);
				}
			}
		},
		colleagues: {
			id: 'panel-colleagues',
			title: 'Colleagues',
			layout: 'fit',
			
			tools: [{
				id: 'help',
				qtip: 'Help Colleagues',
				handler: function(event, toolEl, panel, tc) {
					Ext.Ajax.request({
						url: String.format(laboratree.links.help.site.index, 'user', 'colleagues') + '.json',
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

							laboratree.users.dashboard.helpPopup('User Colleague Help', data.help.content);
						},
						failure: function() {
						}
					});
				}
			}],	
			
			items: this.portlets.colleagues,

			listeners: {
				expand: function(p) {
					var id = 'colleagues';
					laboratree.users.toggle(id, false);
				},
				collapse: function(p) {
					var id = 'colleagues';
					laboratree.users.toggle(id, true);
				}
			}
		}
	};

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

				Ext.each(laboratree.users.dashboard.portal.items.items, function(column, index, allColumns) {
					Ext.each(column.items.items, function(portlet, index, allPortlets) {
						var portlet_id = portlet.id.replace('panel-', '');

						states[portlet_id] = {
							collapsed: portlet.collapsed,
							column: column.id,
							position: index
						};
					}, this);
				}, this);

				Ext.state.Manager.set(laboratree.users.dashboard.state_id, states);

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

laboratree.users.Dashboard.prototype.renderGroupName = function(value, p, record) {
	return String.format('<a href="' + laboratree.links.groups.dashboard + '" title="{1}">{1}</a>', record.data.id, value);
};

laboratree.users.Dashboard.prototype.renderGroupRole = function(value, p, record) {
	return value.split('.').map(Ext.util.Format.capitalize).join(' ');
};

laboratree.users.Dashboard.prototype.renderProjectName = function(value, p, record) {
	return String.format('<a href="' + laboratree.links.projects.dashboard + '" title="{1}">{1}</a>', record.data.id, value);
};

laboratree.users.Dashboard.prototype.renderProjectRole = function(value, p, record) {
	return value.split('.').map(Ext.util.Format.capitalize).join(' ');
};

laboratree.users.Dashboard.prototype.renderProjectGrouping = function(value, p, record) {
	return String.format('<a href="' + laboratree.links.projects.group + '" title="{1}">{1}</a>', record.data.group_id, value);
};

laboratree.users.Dashboard.prototype.renderColleagueStatus = function(value, p, record) {
	var state = 'Offline';

	var nowDate = new Date();
	var gmtOffset = nowDate.getTimezoneOffset();
	var utcDate = nowDate.add(Date.MINUTE, gmtOffset);
	var utcTimestamp = parseInt(utcDate.format('U'), 10);

	var activityDate = new Date();
	activityDate = Date.parseDate(value, 'Y-m-d H:i:s');

	//return on invalid date
	if(!activityDate) {
		return state;
	}

	var activityTimestamp = parseInt(activityDate.format('U'), 10);	

	var timeDiff = utcTimestamp - activityTimestamp;

	if(timeDiff < 600) {
		state = 'Online';
	}

	return state;
};

laboratree.users.Dashboard.prototype.toggle = function(panel_id, collapsed) {
	var states = Ext.state.Manager.get(laboratree.users.dashboard.state_id, null);
	if(!states) {
		states = {};
	}
	
	if(!states[panel_id]) {
		var dflt = laboratree.users.dashboard.defaults[panel_id];

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

	Ext.state.Manager.set(laboratree.users.dashboard.state_id, states);
};
