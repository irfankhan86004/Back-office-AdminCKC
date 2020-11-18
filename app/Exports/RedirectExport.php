<?php

namespace App\Exports;

use App\Models\RedirectU;
use Maatwebsite\Excel\Concerns\FromCollection;

class RedirectExport implements FromCollection
{
	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function collection()
	{
		return RedirectU::latest()->get();
	}
}
