<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\ImageReport;
use App\Models\Report;
use Exception;

class ReportController extends Controller
{
    public function index() {
        $reports = Report::all();
        $images = ImageReport::all();
        return response()->json($images, 200);
    }

    public function store(ReportRequest $request) {

        try {
            $report = new Report();
            $images = $request->file('images');
            $data = $request->validated();
            unset($data['images']);
            $report->fill($data);
            $report->save();

            if ($images) {
                foreach ($images as $image) {
                    $reportImage = new ImageReport();
                    $imageName = md5($image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $image->store(storage_path('app/public/reports/'), $imageName);
                    $reportImage->url = $imageName;
                    $reportImage->report_id = $report->id;
                    $reportImage->save();

                }
            }
            return response()->json($report, 200);
        } catch (\Exception $e) {
            return response()->json("Erro ao submeter Denuncia", 404);
        }


    }

    public function show($id) {
        try {
            $report = Report::findOrFail($id);
            return response()->json($report, 200);
        } catch (\Exception ) {
            return response()->json("Erro ao buscar Denuncia", 404);
        }
    }

    public function destroy($id) {
        try {
            $destroyed = Report::destroy($id);
            if (!$destroyed) {
                throw new \Exception();
            }
            return response()->json("Denuncia removida com sucesso!", 200);
        } catch (\Exception) {
            return response()->json("Erro ao apagar Denuncia", 404);
        }
    }
}
