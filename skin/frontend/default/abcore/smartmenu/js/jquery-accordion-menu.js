
var snf = jQuery.noConflict();
jQuery(document).ready(function(){ 
	jQuery("ul.sf-menu").superfish({ 
		animation: {height:'show'},   // slide-down effect without fade-in 
		delay:     1200               // 1.2 second delay on mouseout 
	}); 
}); 
 
