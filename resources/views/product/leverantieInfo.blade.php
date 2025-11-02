@extends('layouts.app')

@if ($toonFallback)
	@push('head')
		<meta http-equiv="refresh" content="4;url={{ route('product.index') }}">
	@endpush
@endif

@section('content')
	<div class="row justify-content-center">
		<div class="col-lg-10">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h1 class="mb-0">{{ $title }}</h1>
				<a href="{{ route('product.index') }}" class="btn btn-link">&laquo; Terug naar overzicht</a>
			</div>

			@if ($leverancier)
				<div class="card mb-4">
					<div class="card-body">
						<h2 class="h5 mb-3">Leverancier</h2>
						<dl class="row mb-0">
							<dt class="col-sm-4">Naam leverancier</dt>
							<dd class="col-sm-8">{{ $leverancier->Naam }}</dd>
							<dt class="col-sm-4">Contactpersoon</dt>
							<dd class="col-sm-8">{{ $leverancier->Contactpersoon }}</dd>
							<dt class="col-sm-4">Leveranciernummer</dt>
							<dd class="col-sm-8">{{ $leverancier->Leveranciernummer }}</dd>
							<dt class="col-sm-4">Mobiel</dt>
							<dd class="col-sm-8">{{ $leverancier->Mobiel }}</dd>
						</dl>
					</div>
				</div>
			@endif

			<div class="card shadow-sm">
				<div class="card-body">
					<h2 class="h5 mb-3">Leveringen {{ $productNaam ? 'voor ' . $productNaam : '' }}</h2>

					<table class="table table-striped align-middle">
						<thead class="table-light">
							<tr>
								<th scope="col">Naam product</th>
								<th scope="col">Datum laatste levering</th>
								<th scope="col">Aantal</th>
								<th scope="col">Eerstvolgende levering</th>
							</tr>
						</thead>
						<tbody>
							@if ($toonFallback)
								<tr>
									<td colspan="4" class="text-center fw-semibold">
										Er is van dit product op dit moment geen voorraad aanwezig,
										de verwachte eerstvolgende levering is:
										{{ $verwachteLevering ? \Carbon\Carbon::parse($verwachteLevering)->format('d-m-Y') : 'onbekend' }}
									</td>
								</tr>
							@elseif ($leveringen->isEmpty())
								<tr>
									<td colspan="4" class="text-center">Er zijn nog geen leveringen geregistreerd.</td>
								</tr>
							@else
								@foreach ($leveringen as $levering)
									<tr>
										<td>{{ $levering->Naam }}</td>
										<td>{{ \Carbon\Carbon::parse($levering->DatumLevering)->format('d-m-Y') }}</td>
										<td>{{ $levering->Aantal }}</td>
										<td>
											{{ $levering->DatumEerstVolgendeLevering
												? \Carbon\Carbon::parse($levering->DatumEerstVolgendeLevering)->format('d-m-Y')
												: 'N.v.t.' }}
										</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection