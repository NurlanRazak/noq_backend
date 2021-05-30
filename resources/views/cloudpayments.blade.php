<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <form id="paymentFormSample" autocomplete="off">
        <input type="text" data-cp="cardNumber" value="4111 1111 1111 1111">
        <input type="text" data-cp="expDateMonth" value="12">
        <input type="text" data-cp="expDateYear" value="23">
        <input type="text" data-cp="cvv" value="123">
        <input type="text" data-cp="name" value="test">
        <button type="submit">Оплатить 100 р.</button>
    </form>

	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="https://widget.cloudpayments.ru/bundles/checkout"></script>
	<script>
	    var checkout;
	    this.createCryptogram = function (e) {
			e.preventDefault()

	        var result = checkout.createCryptogramPacket();

	        if (result.success) {
	            // сформирована криптограмма
				window.open("/api/user_bankcard/create?code=" + result.packet + '&name=test', '_blank').focus();
	        }
	        else {
	            // найдены ошибки в введённых данных, объект `result.messages` формата:
	            // { name: "В имени держателя карты слишком много символов", cardNumber: "Неправильный номер карты" }
	            // где `name`, `cardNumber` соответствуют значениям атрибутов `<input ... data-cp="cardNumber">`
	           for (var msgName in result.messages) {
	               console.log(result.messages[msgName]);
	           }
	        }
	    };


	    $(function () {

	        /* Создание checkout */
	        checkout = new cp.Checkout(
	        // public id из личного кабинета
	        "pk_d48ddcdec81d75de879179d2c37d7",
	        // тег, содержащий поля данных карты
	        document.getElementById("paymentFormSample"));
	    });

		document.getElementById("paymentFormSample").onsubmit = this.createCryptogram;

	</script>
</body>

</html>
