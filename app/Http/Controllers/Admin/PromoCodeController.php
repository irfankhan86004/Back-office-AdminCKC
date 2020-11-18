<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\PromoCodeRequest;
use App\Models\PromoCode;
use Carbon\Carbon;
use Form;

class PromoCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:16');
    }

    public function ajax_listing(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search['value'];
            $promoCodes = PromoCode::select('promo_codes.*');

            $order_array = [
                0   => 'id',
                1   => 'name',
                2   => 'code',
                3   => 'value',
                4   => 'used',
                5   => 'created_at',
            ];

            if ($search != '') {
                $promoCodes = $promoCodes->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', '%'.$search.'%')
                        ->orWhere('name', 'LIKE', '%'.$search.'%')
                        ->orWhere('code', 'LIKE', '%'.$search.'%')
                        ->orWhere('created_at', 'LIKE', '%'.$search.'%');
                });
            }

            // Order
            foreach ($request->order as $order) {
                $promoCodes = $promoCodes->orderBy($order_array[$order['column']], $order['dir']);
            }

            $promoCodes_total = $promoCodes->distinct('id')->count('id');
            $promoCodes = $promoCodes->skip($request->start)->take($request->length)->get();

            $recordsTotal = count($promoCodes);
            $recordsFiltered = $promoCodes_total;
            $data = [];
            $i = 0;

            foreach ($promoCodes as $promoCode) {
                $data[$i][] =   '<div class="text-center">'.$promoCode->id.'</div>';

                $data[$i][] =   $promoCode->name;

                $data[$i][] =   $promoCode->code;

                if($promoCode->type == 'percentage') {
                    $data[$i][] =   number_format($promoCode->value, 0) . ' %';
                } else {
                    $data[$i][] =   $promoCode->value . ' €';
                }

                if(!empty($promoCode->max_use)) {
                    $data[$i][] = $promoCode->used . ' / ' . $promoCode->max_use;
                } else {
                    $data[$i][] = $promoCode->used;
                }

                $data[$i][] =   $promoCode->created_at;

                $data[$i][] =   '<div class="text-right">
                                    '.Form::open(['method' => 'DELETE', 'route' => ['promo-codes.destroy', $promoCode->id], 'id' => 'delete-'.$promoCode->id]).'
                                    <div class="btn-group btn-group-sm">
                                        <a href="' . route('promo-codes.edit', [$promoCode->id]) . '" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
                                        <a href="#" type="submit" class="btn btn-danger delete" data-entry="' . $promoCode->id . '" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
                                    </div>
                                    '.Form::close().'
                                </div>';
                $i++;
            }

            $results = [
                'draw'              => $request->draw,
                'recordsTotal'      => $recordsTotal,
                'recordsFiltered'   => $recordsFiltered,
                'data'              => $data
            ];

            return response()->json($results);
        }
    }

    public function index()
    {
        $promoCodes = PromoCode::count();
        return view('admin.promo-codes.index', compact('promoCodes'));
    }

    public function edit(PromoCode $promoCode)
    {
        return view('admin.promo-codes.edit', compact('promoCode'));
    }

    public function update(PromoCodeRequest $request, PromoCode $promoCode)
    {
        $promoCode->update($request->all());
        session()->flash('notification', ['type' => 'success', 'text' => 'Le code promo a été mis à jour avec succès']);
        return redirect()->route('promo-codes.index');
    }

    public function create()
    {
        return view('admin.promo-codes.create');
    }

    public function store(PromoCodeRequest $request)
    {
        $promoCode = PromoCode::create($request->all());
        session()->flash('notification', ['type' => 'success', 'text' => 'Le code promo a été créé avec succès']);
        return redirect()->route('promo-codes.index');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        session()->flash('notification', ['type' => 'error', 'text' => 'Code promo supprimé']);
        return redirect()->route('promo-codes.index');
    }
}
