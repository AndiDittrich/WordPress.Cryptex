(function(){
	// initialize
	jQuery(document).ready(function(){
		// colorpicker
		jQuery('.CryptexColorChooser').ColorPicker({
			onSubmit : function(hsb, hex, rgb, el){
				jQuery(el).val('#' + hex);
				jQuery(el).css('background-color', '#' + hex);
				jQuery(el).ColorPickerHide();
			},
			onBeforeShow : function(){
				jQuery(this).ColorPickerSetColor(this.value);
			}
		});
		
		// custom font path
		jQuery('#cryptex-font-source').change(function(){
			if (this.value=='custom'){
				jQuery('#CryptexCustomFontpath').show();
			}else{
				jQuery('#CryptexCustomFontpath').hide();
			}
		}).change();
	});
})();
