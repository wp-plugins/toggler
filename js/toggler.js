// Toggler JScript
// Written by Nimrod Tsabari / omniWP
// Since version 0.1b
// Releases Version 2.0

function updateGruopTarget(connector, classToAdd) {
	jQuery(document).ready(function($) {
		$('.' + connector).addClass(classToAdd);
	});
}

function updateExtClasses(role, tag, ext, defaultState, classToAdd, connector) {
	jQuery(document).ready(function($) {
		if ($(tag + ext).length) {
			switch (role) {
				case 'switch' :
					$(tag + ext).addClass(defaultState).addClass("toggler-ext toggler-target").addClass(function(index, currentClass) {
						var thisClass = $(this);
						if (thisClass.hasClass(classToAdd))
							classToAdd = "";
						var migrateId = (thisClass.hasClass(ext)) ? "" : " " + ext;
						return classToAdd + migrateId;
					});
					break;
				case 'target' :
					$(tag + ext).addClass("toggler-link-ext").addClass(function(index, currentClass) {
						var thisClass = $(this);
						if (thisClass.hasClass(classToAdd))
							classToAdd = "";
						var migrateId = (thisClass.hasClass(ext)) ? "" : " " + ext;
						return classToAdd;
					});
					$(tag + ext).bind('click', function() {
						var Classes = $(this).attr('class');
						var classPos = Classes.indexOf('toggler-class');
						var nbspPos = Classes.indexOf(' ', classPos + 1);
						if (nbspPos < 0) {
							var theClass = Classes.substr(classPos);
						} else {
							var theClass = Classes.substr(classPos, (nbspPos - classPos));
						}
						var parent = $('.toggler-target.' + theClass);
						if (parent.hasClass('toggler-ext')) {
							var target = parent;
						} else {
							var target = parent.children('div');
						}
						var targetClass = target.attr('class');
						if (targetClass.indexOf('toggler-hide') > -1) {
							newClass = targetClass.replace('toggler-hide', 'toggler-show');
						} else {
							newClass = targetClass.replace('toggler-show', 'toggler-hide');
						}
						target.removeClass().addClass(newClass);
					});
					break;
				default :
					return '';
			}
		} else {
			return '';
		}
	});
}


jQuery(document).ready(function($) {
	$('.toggler-link.toggler-click').bind('click', function() {
		var winLoc = $(window).scrollTop();
		var Classes = $(this).attr('class');
		var iconOn = $(this).find('.toggler-icon-on');
		var iconOff = $(this).find('.toggler-icon-off');
		var textOn = $(this).find('.toggler-replace-show');
		var textOff = $(this).find('.toggler-replace-hide');
		var isQuick = $(this).hasClass('toggler-quick');
		var targetClass = '';
		
		if (!isQuick) { 
			var classPos = Classes.indexOf('toggler-class');
			var nbspPos = Classes.indexOf(' ', classPos + 1);
			if (nbspPos < 0) {
				var theClass = Classes.substr(classPos);
			} else {
				var theClass = Classes.substr(classPos, (nbspPos - classPos));
			}
		
			var isExt = $('.toggler-target.' + theClass).hasClass('toggler-ext');
			if (isExt) {
				var target = $('.toggler-target.' + theClass);
				targetClass = target.attr('class');
			} else {
				var target = $('.toggler-target.' + theClass).children('div');
				targetClass = target.attr('class');
			}

		} else {
			var target = $(this).next('.toggler-target').children('div');
			targetClass = target.attr('class');
		}


		var focus = false;
		var isGroup = false;
		var groupClass = "";
		var groupPos = Classes.indexOf('toggler-group-');

		if (groupPos > -1) {
			var nbspPos = Classes.indexOf(' ', groupPos + 1);
			var groupClass = Classes.substr(groupPos, (nbspPos - groupPos));
			if (isExt) {
				var group = $('.' + groupClass + '.toggler-target');
			} else {
				var group = $('.' + groupClass + '.toggler-target').children('div');
			}
			var switchGroup = $('.' + groupClass + '.toggler-link');
			var isGroup = true;
		}
		
		if (targetClass.indexOf('toggler-hide') > -1) {
			if (isGroup) {
				group.each(function() {
					var thisClass = $(this).attr('class').replace('toggler-show', 'toggler-hide');
					$(this).removeClass().addClass(thisClass);
				});
				switchGroup.each(function() {
					var iconGroupOn = $(this).find('.toggler-icon-on');
					var iconGroupOff = $(this).find('.toggler-icon-off');
					var textGroupOn = $(this).find('.toggler-replace-show');
					var textGroupOff = $(this).find('.toggler-replace-hide');
					if (iconGroupOn.length) { iconGroupOn.removeClass('toggler-icon-hide').removeClass('toggler-icon-show').addClass('toggler-icon-show'); iconGroupOff.removeClass('toggler-icon-hide').removeClass('toggler-icon-show').addClass('toggler-icon-hide'); }
					if (textGroupOn.length) { textGroupOn.removeClass('toggler-icon-hide').removeClass('toggler-icon-show').addClass('toggler-icon-show'); textGroupOff.removeClass('toggler-icon-hide').removeClass('toggler-icon-show').addClass('toggler-icon-hide'); }
				});
			}
			if (iconOn.length) { iconOn.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); iconOff.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); }
			if (textOn.length) { textOn.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); textOff.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); }
			newClass = targetClass.replace('toggler-hide', 'toggler-show');
		} else {
			if (iconOn.length) { iconOn.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); iconOff.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); }
			if (textOn.length) { textOn.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); textOff.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); }
			newClass = targetClass.replace('toggler-show', 'toggler-hide');
		}

		target.removeClass().addClass(newClass);
		var targetLoc = target.offset().top;

		if ((isGroup) && (targetClass.indexOf('toggler-hide') > -1) && (winLoc > targetLoc )) {
			$('body').animate({
				scrollTop : target.offset().top
			}, 1000);
		}
	});
	
	$('.toggler-link.toggler-hover').bind('hover', function() {
		var winLoc = $(window).scrollTop();
		var Classes = $(this).attr('class');
		var classPos = Classes.indexOf('toggler-class');
		var nbspPos = Classes.indexOf(' ', classPos + 1);
		var iconOn = $(this).find('.toggler-icon-on');
		var iconOff = $(this).find('.toggler-icon-off');
		var textOn = $(this).find('.toggler-replace-show');
		var textOff = $(this).find('.toggler-replace-hide');
		var isQuick = $(this).hasClass('toggler-quick');
		var targetClass = '';

		if (!isQuick) { 
			if (nbspPos < 0) {
				var theClass = Classes.substr(classPos);
			} else {
				var theClass = Classes.substr(classPos, (nbspPos - classPos));
			}
			var isExt = $('.toggler-target.' + theClass).hasClass('toggler-ext');
			if (isExt) {
				var target = $('.toggler-target.' + theClass);
				targetClass = target.attr('class');
			} else {
				var target = $('.toggler-target.' + theClass).children('div');
				targetClass = target.attr('class');
			}

		} else {
			var target = $(this).next('.toggler-target').children('div');
			targetClass = target.attr('class');
		}

		var focus = false;
		var isGroup = false;
		var groupClass = "";
		var groupPos = Classes.indexOf('toggler-group-');

		if (groupPos > -1) {
			var nbspPos = Classes.indexOf(' ', groupPos + 1);
			var groupClass = Classes.substr(groupPos, (nbspPos - groupPos));
			if (isExt) {
				var group = $('.' + groupClass + '.toggler-target');
			} else {
				var group = $('.' + groupClass + '.toggler-target').children('div');
			}
			var isGroup = true;
		}

		if (targetClass.indexOf('toggler-hide') > -1) {
			if (isGroup) {
				group.each(function() {
					var thisClass = $(this).attr('class').replace('toggler-show', 'toggler-hide');
					$(this).removeClass().addClass(thisClass);
				});
				switchGroup.each(function() {
					var iconGroupOn = $(this).find('.toggler-icon-on');
					var iconGroupOff = $(this).find('.toggler-icon-off');
					var textGroupOn = $(this).find('.toggler-replace-show');
					var textGroupOff = $(this).find('.toggler-replace-hide');
					if (iconGroupOn.length) { iconGroupOn.removeClass('toggler-icon-hide').removeClass('toggler-icon-show').addClass('toggler-icon-show'); iconGroupOff.removeClass('toggler-icon-hide').removeClass('toggler-icon-show').addClass('toggler-icon-hide'); }
					if (textGroupOn.length) { textGroupOn.removeClass('toggler-icon-hide').removeClass('toggler-icon-show').addClass('toggler-icon-show'); textGroupOff.removeClass('toggler-icon-hide').removeClass('toggler-icon-show').addClass('toggler-icon-hide'); }
				});
			}	
			if (iconOn.length) { iconOn.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); iconOff.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); }
			if (textOn.length) { textOn.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); textOff.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); }
			newClass = targetClass.replace('toggler-hide', 'toggler-show');
		} else {
			if (iconOn.length) { iconOn.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); iconOff.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); }
			if (textOn.length) { textOn.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); textOff.toggleClass('toggler-icon-hide').toggleClass('toggler-icon-show'); }
			newClass = targetClass.replace('toggler-show', 'toggler-hide');
		}

		target.removeClass().addClass(newClass);
		var targetLoc = target.offset().top;

		if ((isGroup) && (targetClass.indexOf('toggler-hide') > -1) && (winLoc > targetLoc )) {
			$('body').animate({
				scrollTop : target.offset().top
			}, 1000);
		}
	});
});