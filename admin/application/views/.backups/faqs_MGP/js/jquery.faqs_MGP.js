$(function(){
	if($(".input-tags").length){
		$(".input-tags").selectize({
			delimiter: ",",
			persist: false,
			preload: true,
			valueField: 'text',
			labelField: 'text',
			searchField: 'text',
			create: function (input) {
				return {
					value: input,
					text: input,
				};
			},
			load: function(query, callback) {
				var self = $(this);
				$.ajax({
					url:path_ajax_script+'/index.php?mod='+mod+'&act=search_tag',
					type: 'GET',
					dataType:'json',
					cache: true,
					error: function() {
						callback();
					},
					success: function(res) {
						callback(res);
					}
				});
			}
		});
	}
	
});