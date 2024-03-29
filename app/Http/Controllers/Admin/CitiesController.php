<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCityRequest;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CitiesController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('city_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cities = City::with(['country', 'state'])->get();

        $countries = Country::get();

        $states = State::get();

        return view('admin.cities.index', compact('cities', 'countries', 'states'));
    }

    public function create()
    {
        abort_if(Gate::denies('city_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $states = State::pluck('state_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.cities.create', compact('countries', 'states'));
    }

    public function store(StoreCityRequest $request)
    {
        $city = City::create($request->all());

        return redirect()->route('admin.cities.index');
    }

    public function edit(City $city)
    {
        abort_if(Gate::denies('city_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $states = State::pluck('state_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $city->load('country', 'state');

        return view('admin.cities.edit', compact('countries', 'states', 'city'));
    }

    public function update(UpdateCityRequest $request, City $city)
    {
        $city->update($request->all());

        return redirect()->route('admin.cities.index');
    }

    public function show(City $city)
    {
        abort_if(Gate::denies('city_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $city->load('country', 'state');

        return view('admin.cities.show', compact('city'));
    }

    public function destroy(City $city)
    {
        abort_if(Gate::denies('city_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $city->delete();

        return back();
    }

    public function massDestroy(MassDestroyCityRequest $request)
    {
        City::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function get_by_state(Request $request)
    {
        abort_unless(\Gate::allows('city_access'), 401);

        if (!$request->state_id) {
            $html = '<option value="">'.trans('global.pleaseSelect').'</option>';
        } else {
            //$html = '';
            $html = '<option value="">'.trans('global.pleaseSelect').'</option>';
            $cities = City::where('state_id', $request->state_id)->get();
            foreach ($cities as $city) {
                $html .= '<option value="'.$city->id.'">'.$city->city_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
