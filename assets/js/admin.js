
$(function(){

	$('select[name^=payqr_button]').on('change', function(){

		//получаем тип страницы для которого скрываем характеристики
		var select_name = $(this).attr('name');

		//
		var page = $(this).attr('name').split("payqr_button_show_on_")[1];

		if(typeof page !== "undefined")
		{
			//производим скрытие всех опций с данной страницей, если выбрано "no"
			if($('option:selected', this).val() == "no")
			{
				$('[name^=payqr_'+page+']').closest('tr').hide();
			}

			if($('option:selected', this).val() == "yes")
			{
				$('[name^=payqr_'+page+']').closest('tr').show();
			}
		}
	});

	$("#save_payqr_form").on('submit', function(){

		window.location.replace(window.location.origin);

	});
});