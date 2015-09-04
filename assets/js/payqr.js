$(function() {
	if(typeof payQR  !== "undefined")
	{
		payQR.onPaid(function(data) {

			clear_cart();

			var message = "Ваш заказ #" + data.orderId + " успешно оплачен на сумму: " + data.amount + "! ";

			if(typeof data.userData !== "undefined" && typeof data.userData.new_account !== "undefined" && (data.userData.new_account == true || data.userData.new_account == "true"))
			{
				message += " Администратор сайта свяжется с вами в самое ближайшее время!";
			}

			alert(message);

			window.location.replace(window.location.origin);
		});
	}
});

function clear_cart()
{
	console.log("Clear cart");

	$.ajax({
		method: "POST",
		url: window.location.origin + "/payqr/clear_cart",
		data: {clear_cart:"clear"}
	});
}