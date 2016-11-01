<?php

namespace App\Http\Controllers\Admin\Store;

use App\Services\Store\ReportService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    /**
     * @var ReportService
     */
    protected $reportService;

    /**
     * ReportController constructor.
     * @param ReportService $reportService
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }
    
    public function sales(Request $request)
    {
        $data = $this->reportService->generateSalesReport($request->input('store'), $request->input('start'), $request->input('end'));
        $filters = [
            'start' => $request->input('start'),
            'end' => $request->input('end'),
            'store' => $request->input('store')
        ];
        return view('backend.store.report.sales', compact('data','filters'));
    }



}
