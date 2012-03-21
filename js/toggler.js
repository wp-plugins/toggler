// Toggler JScript
// Written by Nimrod Tsabari
// Since version 0.1b


function updateExtClasses(role, tag, ext, defaultState, classToAdd, connector){
	jQuery(document).ready(function($) {
		if ($(tag + ext).length) {
			switch (role) {
			case 'switch' :
				$(tag + ext).addClass(defaultState).addClass("toggler-ext toggler-target").addClass(function(index, currentClass) {
					var thisClass = $(this);
					if (thisClass.hasClass(classToAdd)) classToAdd = "";
					var migrateId = (thisClass.hasClass(ext)) ? "" : " " + ext;
					return classToAdd + migrateId;
				});
				break;
			case 'target' :
				$(tag + ext).addClass("toggler-link-ext").addClass(function(index, currentClass) {
					var thisClass = $(this);
					if (thisClass.hasClass(classToAdd)) classToAdd = "";
					var migrateId = (thisClass.hasClass(ext)) ? "" : " " + ext;
					return classToAdd;
				});
				$(tag+ext).bind('click',function() {
					var Classes = $(this).attr('class');
					var classPos = Classes.indexOf('toggler-class');
					var nbspPos = Classes.indexOf(' ',classPos+1);
					if (nbspPos < 0) {
						var theClass = Classes.substr(classPos);
					} else {
						var theClass = Classes.substr(classPos,(nbspPos-classPos));
					}
					console.log(theClass);
					var parent = $('.toggler-target.' + theClass);
					if (parent.hasClass('toggler-ext')) {
						var target = parent;
					} else {
						var target = parent.children('div');
					}
					var targetClass = target.attr('class');
					if (targetClass.indexOf('toggler-hide') > -1) {
						newClass = targetClass.replace('toggler-hide','toggler-show');
					} else {
						newClass = targetClass.replace('toggler-show','toggler-hide');
					}
					target.removeClass().addClass(newClass);
					});
				break;
			default :
				return '';
			}
		} else { return ''; }
	});
}

jQuery(document).ready(function($) {
	$('.toggler-link').bind('click',function() {
		var Classes = $(this).attr('class');
		var classPos = Classes.indexOf('toggler-class');
		var nbspPos = Classes.indexOf(' ',classPos+1);
		if (nbspPos < 0) {
			var theClass = Classes.substr(classPos);
		} else {
			var theClass = Classes.substr(classPos,(nbspPos-classPos));
		}
		var isExt = $('.toggler-target.' + theClass).hasClass('toggler-ext');
		if (isExt) {
			var target = $('.toggler-target.' + theClass);
			var targetClass = target.attr('class');
		} else {
			var target = $('.toggler-target.' + theClass).children('div');
			var targetClass = target.attr('class');
		}
		if (targetClass.indexOf('toggler-hide') > -1) {
			newClass = targetClass.replace('toggler-hide','toggler-show');
		} else {
			newClass = targetClass.replace('toggler-show','toggler-hide');
		}
		target.removeClass().addClass(newClass);
	});
});