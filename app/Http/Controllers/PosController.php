<?php

namespace App\Http\Controllers;

use App\Models\DailyConsignment; //Clay-X
use App\Models\Partner;
use App\Http\Requests\CloseDailyShopRequest; //Clay-X
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PosController extends Controller
{
    /**
     * Render the POS dashboard.
     */
    public function index(): Response
    {
        return Inertia::render('Pos/Dashboard', new \App\ViewModels\PosDashboardViewModel());
    }

    /**
     * Show form to open shop (start daily session).
     */
    public function createOpen(): Response
    {
        // Clay-X: Pass available partners to the OpenShop page so the select has options
        $partners = Partner::select(['id', 'name'])->orderBy('name')->get();

        return Inertia::render('Pos/OpenShop', [
            'partners' => $partners,
        ]);
    }

    /**
     * Store new daily shop session (Start Shop).
     */
    public function store(Request $request, \App\Actions\Consignment\StartDailyShopAction $startDailyShopAction)
    {
        $validated = $request->validate([
            'start_cash' => 'required|numeric|min:0',
        ]);

        try {
            $startDailyShopAction->execute($request->user(), $validated['start_cash']);
            return redirect()->route('pos.dashboard')->with('success', 'Shop opened successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show form to close shop (reconcile daily session).
     */
    public function createClose(): Response
    {
        // We might want to pass current session data here using ViewModel or direct query
        // For now preventing closing if not open is handled by UI or middleware ideally.
        $session = DailyConsignment::where('input_by_user_id', Auth::id())
            ->whereNull('closed_at')
            ->whereNotNull('start_cash')
            ->firstOrFail();

        return Inertia::render('Pos/CloseShop', [
            'session' => $session,
        ]);
    }

    /**
     * Get daily consignment items for the current session (API endpoint).
     * //Clay-X
     * @param Request $request //Clay-X
     * @return \Illuminate\Http\JsonResponse //Clay-X
     */
    public function getDailyConsignments(Request $request) //Clay-X
    { //Clay-X
        // Clay-X: Ambil query parameters
        $date = $request->query('date'); //Clay-X
        $userId = $request->query('user_id'); //Clay-X

        // Clay-X: Fetch items dengan status != 'closed'
        $items = DailyConsignment::where('date', $date) //Clay-X
            ->where('input_by_user_id', $userId) //Clay-X
            ->where(function ($query) { //Clay-X
                // Clay-X: Exclude session record (yang punya start_cash tapi no items yet)
                $query->whereNotNull('product_name')->orWhere('status', '=', 'open'); //Clay-X
            }) //Clay-X
            ->where('status', '!=', 'closed') //Clay-X
            ->select([ //Clay-X
                'id', 'product_name', 'initial_stock', 'remaining_stock', //Clay-X
                'selling_price', 'base_price' //Clay-X
            ]) //Clay-X
            ->get(); //Clay-X

        // Clay-X: Return response JSON
        return response()->json($items); //Clay-X
    } //Clay-X

    /**
     * Update (Close) daily shop session using CloseDailyShopAction.
     * //Clay-X
     * @param \App\Http\Requests\CloseDailyShopRequest $request //Clay-X
     * @param DailyConsignment $dailyConsignment //Clay-X
     * @param \App\Actions\Consignment\CloseDailyShopAction $closeDailyShopAction //Clay-X
     * @return \Illuminate\Http\RedirectResponse //Clay-X
     */
    public function updateClose(
        \App\Http\Requests\CloseDailyShopRequest $request, //Clay-X
        DailyConsignment $dailyConsignment, //Clay-X
        \App\Actions\Consignment\CloseDailyShopAction $closeDailyShopAction //Clay-X
    ) { //Clay-X
        // Clay-X: Pastikan session adalah shop session yang valid (memiliki start_cash)
        if ($dailyConsignment->start_cash === null) { //Clay-X
            abort(404, 'Not a shop session.'); //Clay-X
        } //Clay-X

        // Clay-X: Ambil validated data dari request
        $validated = $request->validated(); //Clay-X

        try { //Clay-X
            // Clay-X: Panggil action untuk menutup shop dan hitung profit
            $result = $closeDailyShopAction->execute( //Clay-X
                $dailyConsignment, //Clay-X
                $validated['items'], //Clay-X
                $validated['actual_cash'] //Clay-X
            ); //Clay-X

            // Clay-X: Return response dengan data profit jika request adalah JSON API
            if ($request->expectsJson()) { //Clay-X
                return response()->json($result); //Clay-X
            } //Clay-X

            // Clay-X: Return redirect dengan success message untuk form submission
            return redirect()->route('pos.dashboard')->with('success', $result['message']); //Clay-X
        } catch (\Exception $e) { //Clay-X
            // Clay-X: Handle exception dan return error response
            if ($request->expectsJson()) { //Clay-X
                return response()->json([ //Clay-X
                    'success' => false, //Clay-X
                    'message' => $e->getMessage(), //Clay-X
                ], 422); //Clay-X
            } //Clay-X

            // Clay-X: Return redirect dengan error message
            return redirect()->back()->withErrors(['error' => $e->getMessage()]); //Clay-X
        } //Clay-X
    } //Clay-X
}
