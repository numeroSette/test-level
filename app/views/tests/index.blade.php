@extends('layouts.scaffold')

@section('main')

<h1>All Tests</h1>

<p>{{ link_to_route('tests.create', 'Add new test') }}</p>

@if ($tests->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Description</th>
				<th>Languages_id</th>
				<th>Affiliates_id</th>
				<th>Active</th>
				<th align="center">Actions</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($tests as $test)
				<tr>
					<td>{{{ $test->description }}}</td>
					<td>{{{ Language::find($test->languages_id)->language }}}</td>
					<td>{{{ Affiliate::find($test->affiliates_id)->city }}}</td>
					<td>
						@if ($test->active == 1)
							<span class="label label-success">Ativo</span>
						@else
							<span class="label label-danger">Inativo</span>
						@endif
					</td>
					<td>
						<a href="#myModal" role="button" class="btn" data-toggle="modal">Adicionar questões</a>
					</td>
					<td>{{ link_to_route('tests.edit', 'Edit', array($test->id), array('class' => 'btn btn-info')) }}</td>
					<td>
						{{ Form::open(array('method' => 'DELETE', 'route' => array('tests.destroy', $test->id))) }}
							{{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
						{{ Form::close() }}
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no tests
@endif

<script>
	$(function () {
		$("#saveQuestion").bind('click',function () {
			var form = $('#formQuestion');
			var data = form.serialize();
			var method = form.attr('method');
			var url = form.attr('action');

			$.ajax({
				type: method,
				url: url,
				data: data
			}).success(function(s) {
				console.log('success:',s);
			}).error(function(e) {
				if(e.status != 400){
					alert('Ocorreu um erro interno, favor consultar o administrador');
				}else{
					var errorMessage = JSON.parse(e.responseText);
					var errorPlace = document.createElement('ul');
					errorPlace = $(errorPlace).addClass('errorPlace');
					$.each(errorMessage, function(index, val) {
						$('#errors').append('<li class="error">'+val+'</li>');
					});
					errorPlace.after($('#formQuestion'));
				}
			}).done(function( data ) {
				console.log('done',data);
  			})

		});
	});
</script>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Create Question</h3>
	</div>
	<div class="modal-body">
		<p>
			{{ Form::open(array('route' => 'questions.store', 'id' => 'formQuestion')) }}
				<ul>
					<li>
						{{ Form::label('description', 'Description:') }}
						{{ Form::text('description') }}
					</li>

					<li>
						{{ Form::label('audio_id', 'Audio_id:') }}
						{{ Form::input('number', 'audio_id') }}
					</li>
				</ul>
			{{ Form::close() }}
		</p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button class="btn btn-primary" id="saveQuestion">Save changes</button>
	</div>
</div>

@stop
