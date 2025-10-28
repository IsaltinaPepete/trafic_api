<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\ImageReport;
use App\Models\Report;
use Exception;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    public function index() {
        $reports = Report::all();
        return response()->json($reports, 200);
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
                    $image->move(storage_path('app/public/reports/'), $imageName);
                    $reportImage->url = $imageName;
                    $reportImage->report_id = $report->id;
                    $reportImage->save();

                }
            }
            $this->predict($report);
            return response()->json($report, 200);
        } catch (\Exception $e) {

            return response()->json("Erro ao submeter Denuncia", 404);
        }


    }

    public function predict(Report $report) {
        try {
            $response = Http::post('http://172.24.20.156:8000/predict', [
                'text' => $report->incident_description
            ]);



            $report->is_traffic = $response->json()['is_human_traffic'];
            $report->type = $response->json()['type'];
            $report->save();
            return response()->json($report, 200);
        } catch (Exception $e) {

            return response()->json("Erro ao classificar Denuncia", 404);
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

    public function statistics() {
        $total_reports = Report::all()->count();
        $traffic_reports = Report::where('is_traffic', true)->count();
        $traffic_per_weeks = Report::all()->sortDesc();
        $traffic_per_type = Report::all()->groupBy("type");

        return response()->json([
            'total_reports' => $total_reports,
            'traffic_reports' => $traffic_reports,
            'traffic_per_weeks' => $traffic_per_weeks,
            'traffic_per_type' => $traffic_per_type
        ]);
    }
}
