<div id="modalAddNewsletter" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Ajouter un abonnement à la newsletter</h4>
			</div>
			{{ Form::open(['route' => 'newsletters.store', 'method' => 'POST']) }}
				<div class="modal-body">
					<div class="form-group">
						{{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'hello@gmail.com']) }}
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<button type="submit" class="btn btn-primary">Ajouter</button>
				</div>
			{{ Form::close() }}
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->