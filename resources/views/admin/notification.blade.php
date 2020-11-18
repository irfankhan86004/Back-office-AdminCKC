@if(Session::has('notification'))
<script>
	noty({
		layout: "topRight",
		theme: "metroui",
		type: "{!! Session::get('notification')['type'] !!}",
		text: "{!! Session::get('notification')['text'] !!}",
		timeout: 4000,
	});
</script>
@endif