<html>
<head>
</head>
<body>
	<script>
		if (window.ReactNativeWebView) {
			window.ReactNativeWebView.postMessage(JSON.stringify(@json($data)));
		} else {
			console.log('ReactNative not defined!');
		}
	</script>
</body>
</html>
