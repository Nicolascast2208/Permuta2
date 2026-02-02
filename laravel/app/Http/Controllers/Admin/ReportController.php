<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->input('date_range', '30days');
        $startDate = $this->getStartDate($dateRange);
        $endDate = now();

        $stats = $this->getStats($startDate, $endDate);
        $charts = $this->getChartsData($startDate, $endDate);
        $topData = $this->getTopData($startDate, $endDate);

        return view('admin.reports.index', compact('stats', 'charts', 'topData', 'dateRange'));
    }

    public function export(Request $request)
    {
        $dateRange = $request->input('date_range', '30days');
        $startDate = $this->getStartDate($dateRange);
        $endDate = now();

        $data = $this->getExportData($startDate, $endDate);
        $filename = 'reporte_' . $startDate->format('Y-m-d') . '_a_' . $endDate->format('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        
        // Encabezados
        fputcsv($output, ['Reporte de Plataforma - ' . $startDate->format('d/m/Y') . ' a ' . $endDate->format('d/m/Y')]);
        fputcsv($output, []);

        // Resumen
        fputcsv($output, ['RESUMEN GENERAL']);
        fputcsv($output, ['Ingresos Totales', '$' . number_format($data['stats']['total_revenue'], 0, ',', '.')]);
        fputcsv($output, ['Total Pagos', $data['stats']['total_payments']]);
        fputcsv($output, ['Nuevos Usuarios', $data['stats']['total_users']]);
        fputcsv($output, ['Productos Publicados', $data['stats']['total_products']]);
        fputcsv($output, ['Ofertas Realizadas', $data['stats']['total_offers']]);
        fputcsv($output, []);

        // Pagos
        fputcsv($output, ['DETALLE DE PAGOS']);
        fputcsv($output, ['ID', 'Usuario', 'Tipo', 'Monto', 'Estado', 'Fecha']);
        foreach ($data['payments'] as $payment) {
            fputcsv($output, [
                $payment->id,
                $payment->user->name,
                $payment->formatted_type,
                '$' . number_format($payment->amount, 0, ',', '.'),
                $payment->formatted_status,
                $payment->created_at->format('d/m/Y H:i')
            ]);
        }
        fputcsv($output, []);

        // Usuarios
        fputcsv($output, ['NUEVOS USUARIOS']);
        fputcsv($output, ['ID', 'Nombre', 'Email', 'Fecha Registro']);
        foreach ($data['users'] as $user) {
            fputcsv($output, [
                $user->id,
                $user->name,
                $user->email,
                $user->created_at->format('d/m/Y')
            ]);
        }

        fclose($output);
        exit;
    }

    private function getStartDate($dateRange)
    {
        switch ($dateRange) {
            case '7days':
                return now()->subDays(7);
            case '30days':
                return now()->subDays(30);
            case '90days':
                return now()->subDays(90);
            case '1year':
                return now()->subYear();
            default:
                return now()->subDays(30);
        }
    }

    private function getStats($startDate, $endDate)
    {
        return [
            'total_revenue' => Payment::approved()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount'),
            'total_payments' => Payment::approved()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'total_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_products' => Product::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_offers' => Offer::whereBetween('created_at', [$startDate, $endDate])->count(),
            'pending_payments' => Payment::pending()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'failed_payments' => Payment::failed()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
        ];
    }

    private function getChartsData($startDate, $endDate)
    {
        // Ingresos por día
        $revenueByDay = Payment::approved()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Pagos por tipo
        $paymentsByType = Payment::approved()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('type')
            ->get();

        // Usuarios registrados por día
        $usersByDay = User::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Productos por categoría
      $productsByCategory = Product::whereBetween('products.created_at', [$startDate, $endDate])
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('categories.name', DB::raw('COUNT(*) as count'))
        ->groupBy('categories.name', 'categories.id')
        ->get();

     return [
        'revenueByDay' => $revenueByDay,
        'paymentsByType' => $paymentsByType,
        'usersByDay' => $usersByDay,
        'productsByCategory' => $productsByCategory,
    ];
    }

    private function getTopData($startDate, $endDate)
    {
        return [
            'top_users' => User::withCount(['payments' => function($query) use ($startDate, $endDate) {
                $query->approved()->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['payments' => function($query) use ($startDate, $endDate) {
                $query->approved()->whereBetween('created_at', [$startDate, $endDate]);
            }], 'amount')
            ->orderBy('payments_sum_amount', 'desc')
            ->take(10)
            ->get(),

            'top_products' => Product::withCount(['offers as offers_count' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderBy('offers_count', 'desc')
            ->take(10)
            ->get(),

            'top_categories' => Category::withCount(['products as products_count' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderBy('products_count', 'desc')
            ->take(10)
            ->get(),
        ];
    }

    private function getExportData($startDate, $endDate)
    {
        return [
            'stats' => $this->getStats($startDate, $endDate),
            'payments' => Payment::with(['user'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get(),
            'users' => User::whereBetween('created_at', [$startDate, $endDate])->get(),
        ];
    }
}