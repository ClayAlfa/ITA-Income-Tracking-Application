<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Actions\Consignment\StartDailyShopAction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class PosController extends Controller
{
    /**
     * Menampilkan halaman form buka toko/input produk.
     * Mengambil data partner yang aktif untuk dropdown.
     */
    public function createOpen(): Response
    {
        return Inertia::render('Pos/OpenShop', [
            'partners' => Partner::where('is_active', true)
                ->select(['id', 'name'])
                ->get()
        ]);
    }

    /**
     * Menyimpan data produk konsinyasi baru menggunakan StartDailyShopAction.
     * * @param Request $request
     * @param StartDailyShopAction $startDailyShopAction
     * @return RedirectResponse
     */
    public function storeOpen(Request $request, StartDailyShopAction $startDailyShopAction): RedirectResponse
    {
        // 1. Validasi Input Dasar
        // Kita validasi 'markup' hanya boleh 5, 10, atau 15 sesuai permintaan.
        $validated = $request->validate([
            'partner_id'    => 'required|exists:partners,id',
            'product_name'  => 'required|string|max:255',
            'initial_stock' => 'required|integer|min:1',
            'base_price'    => 'required|numeric|min:0',
            'markup'        => 'required|in:5,10,15', 
            'shop_session_id' => 'nullable|integer', // Optional, tergantung sistem sesi teman Anda
        ]);

        try {

            $dataForAction = [
                'partner_id'        => $validated['partner_id'],
                'product_name'      => $validated['product_name'],
                'initial_stock'     => $validated['initial_stock'],
                'base_price'        => $validated['base_price'],
                'markup_percentage' => (int) $validated['markup'], 
                'shop_session_id'   => $validated['shop_session_id'] ?? null,
            ];

            $startDailyShopAction->execute($request->user(), $dataForAction);

            return redirect()
                ->back()
                ->with('success', 'Produk ' . $validated['product_name'] . ' berhasil ditambahkan!'); 
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function index(): Response
    {
        return Inertia::render('Pos/Dashboard');
    }

    
}