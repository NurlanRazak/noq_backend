<html>
<head>
</head>

<body>
	<form name="downloadForm" action="{{ $url }}" method="POST">
	    <input type="hidden" name="PaReq" value="{{ $token }}">
	    <input type="hidden" name="MD" value="{{ $transaction_id }}">
	    <input type="hidden" name="TermUrl" value="http://pizza.local.com/api/user_bankcard/secure">
	</form>
	<script>
	    window.onload = submitForm;
	    function submitForm() { downloadForm.submit(); }
	</script>
</body>
</html>
