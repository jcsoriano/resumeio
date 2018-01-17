<table id="{{ $id ?: 'table'}}">
	<thead>
		<tr>
			<th v-for="header in headers">@{{ header }}</th>
		</tr>
	</thead>
	<tbody>
		<tr v-for="row in table">
			<td v-for="cell in row">@{{ row[cell] }}</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th v-for="header in headers">@{{ header }}</th>
		</tr>
	</tfoot>
</table>
<script>
	new Vue({
		el: '#table',
		data: {
			headers: {{ json_encode($header) }},
			table: []
		},
		computed: {
	
		},
		created: function() {
			this.$http.post({{ $endpoint ?: '/permissions/manage' }})
				.then(function(response) {
					this.table = response.table;
				}, function(error) {
					console.log(error);
				});
		}
		methods: {
			
		}
	});
</script>