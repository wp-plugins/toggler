tinyMCEPopup.requireLangPack();

var TogglerDialog = {
	init : function() {
		var f = document.forms[0];

		// Get the selected contents as text and place it in the input
		f.connector.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.theme.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.inline.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.ghost.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.hover.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.group.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.text_show.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.text_hide.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});

		f.icon.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.icon_size.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.icon_background.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.icon_name.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.icon_reset.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.icon_left.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.icon_top.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.icon_float.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
	},

	insert : function() {
		// Insert the contents from the input into the document
		var f = document.forms[0];
		var s = ' ';
		var t0 = '="';
		var t1 = '" ';
		var begin = '[toggler ';
		var end = '[/toggler]';
		var sParam = '';
		var tParam = '';
		var qParam = '';
		
		var sVal = ["switch"];
		var sAtt = ["role"];
		var tVal = ["target"]
		var tAtt = ["role"];

		var qAtt = ["theme"]
		var qVal = [f.theme.value];

		if (f.theme.value != 'no-theme') {
			qAtt.push("title");
			qVal.push(f.title.value);
		}

		var icon;

		// Get the selected contents as text and place it in the input
		sVal.push(f.connector.value);
		tVal.push(f.connector.value);
		sAtt.push("connector");
		tAtt.push("connector");

		sVal.push(f.inline.value);
		tVal.push(f.inline.value);
		qVal.push(f.inline.value);
		sAtt.push("inline");
		tAtt.push("inline");
		qAtt.push("inline");

		tVal.push(f.ghost.value);
		qVal.push(f.ghost.value);
		tAtt.push("ghost");
		qAtt.push("ghost");
		
		sVal.push(f.hover.value);
		qVal.push(f.hover.value);
		sAtt.push("hover");
		qAtt.push("hover");
		
		sVal.push(f.group.value);
		tVal.push(f.group.value);
		qVal.push(f.group.value);
		sAtt.push("group");
		tAtt.push("group");
		qAtt.push("group");

		sVal.push(f.text_show.value);
		qVal.push(f.text_show.value);
		sAtt.push("text_show");
		qAtt.push("text_show");

		sVal.push(f.text_hide.value);
		qVal.push(f.text_hide.value);
		sAtt.push("text_hide");
		qAtt.push("text_hide");

		
		icon = f.icon.value;
		if (icon == 'plus') {
			sAtt.push('icon');
			qAtt.push('icon');
			sVal.push('plus');
			qVal.push('plus');
			if (f.icon_size.value != "") { 
				sAtt.push('icon_size');
				qAtt.push('icon_size');
				sVal.push(f.icon_size.value);
				qVal.push(f.icon_size.value);
			}
			if (f.icon_background.value != "") { 
				sAtt.push('icon_background');
				qAtt.push('icon_background');
				sVal.push(f.icon_background.value);
				qVal.push(f.icon_background.value);
			}
			if (f.icon_color.value != "") { 
				sAtt.push('icon_color');
				qAtt.push('icon_color');
				sVal.push(f.icon_color.value);
				qVal.push(f.icon_color.value);
			}
			if (f.icon_left.value != "") { 
				sAtt.push('icon_left');
				qAtt.push('icon_left');
				sVal.push(f.icon_left.value);
				qVal.push(f.icon_left.value);
			}
			if (f.icon_top.value != "") { 
				sAtt.push('icon_top');
				qAtt.push('icon_top');
				sVal.push(f.icon_top.value);
				qVal.push(f.icon_top.value);
			}
			if (f.icon_position.value != "") { 
				sAtt.push('icon_position');
				qAtt.push('icon_position');
				sVal.push(f.icon_position.value);
				qVal.push(f.icon_position.value);
			}
		}

		for(i=0; i<sVal.length; i++) {
			if (sVal[i] != "") sParam += s + sAtt[i] + t0 + sVal[i] + t1;
		}

		for(i=0; i<tVal.length; i++) {
			if (tVal[i] != "") tParam += s + tAtt[i] + t0 + tVal[i] + t1;
		}

		for(i=0; i<qVal.length; i++) {
			if (qVal[i] != "") qParam += s + qAtt[i] + t0 + qVal[i] + t1;
		}

		

		if (f.theme.value == 'no-theme') {
			var switchCode = begin + sParam + ']Add Your Switch Here' + end;
			var targetCode = begin + tParam + ']Add Your Target Here' + end;
	
			var shortcode = switchCode + targetCode;
		} else {
			var quickCode = begin + qParam + ']Add Your Content Here' + end;
	
			var shortcode = quickCode;
		}

		tinyMCEPopup.editor.execCommand('mceInsertContent', false, shortcode);
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(TogglerDialog.init, TogglerDialog);
