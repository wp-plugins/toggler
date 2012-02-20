// Toggler JScript
// Written by Nimrod Tsabari
// Since version 0.1b

jQuery(function($) {
	$('.toggler-link').click(function() {
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

function updateExtClasses(tag, ext, defaultState, classToAdd){
	jQuery(document).ready(function($) {
		if ($(tag + ext).length) {
			$(tag + ext).addClass(defaultState).addClass("toggler-ext toggler-target").addClass(function(index, currentClass) {
				var thisClass = $(this);
				if (thisClass.hasClass(classToAdd)) classToAdd = "";
				var migrateId = (thisClass.hasClass(ext)) ? "" : " " + ext;
				return classToAdd + migrateId;
			});
		} else { return ''; }
	});
}