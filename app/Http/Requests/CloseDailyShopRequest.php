<?php //Clay-X

namespace App\Http\Requests; //Clay-X

use Illuminate\Foundation\Http\FormRequest; //Clay-X

class CloseDailyShopRequest extends FormRequest //Clay-X
{ //Clay-X
    /**
     * Determine if the user is authorized to make this request.
     * //Clay-X
     * @return bool //Clay-X
     */
    public function authorize(): bool //Clay-X
    { //Clay-X
        // Clay-X: User hanya bisa menutup shop milik mereka sendiri
        return true; //Clay-X
    } //Clay-X

    /**
     * Get the validation rules that apply to the request.
     * //Clay-X
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string> //Clay-X
     */
    public function rules(): array //Clay-X
    { //Clay-X
        return [ //Clay-X
            // Clay-X: Validasi actual_cash harus numeric dan minimal 0
            'actual_cash' => 'required|numeric|min:0', //Clay-X
            // Clay-X: Validasi items array - harus ada minimal satu item
            'items' => 'required|array|min:1', //Clay-X
            // Clay-X: Validasi setiap item harus punya id dan remaining_stock
            'items.*.id' => 'required|integer|exists:daily_consignments,id', //Clay-X
            // Clay-X: Validasi remaining_stock harus numeric dan minimal 0
            'items.*.remaining_stock' => 'required|numeric|min:0', //Clay-X
        ]; //Clay-X
    } //Clay-X

    /**
     * Get the error messages for the defined validation rules.
     * //Clay-X
     * @return array<string, string> //Clay-X
     */
    public function messages(): array //Clay-X
    { //Clay-X
        return [ //Clay-X
            // Clay-X: Pesan error untuk actual_cash
            'actual_cash.required' => 'Actual cash is required.', //Clay-X
            'actual_cash.numeric' => 'Actual cash must be a number.', //Clay-X
            'actual_cash.min' => 'Actual cash cannot be negative.', //Clay-X
            // Clay-X: Pesan error untuk items
            'items.required' => 'Items data is required.', //Clay-X
            'items.array' => 'Items must be an array.', //Clay-X
            'items.min' => 'At least one item is required.', //Clay-X
            // Clay-X: Pesan error untuk item ID
            'items.*.id.required' => 'Item ID is required.', //Clay-X
            'items.*.id.integer' => 'Item ID must be an integer.', //Clay-X
            'items.*.id.exists' => 'Item does not exist.', //Clay-X
            // Clay-X: Pesan error untuk remaining_stock
            'items.*.remaining_stock.required' => 'Remaining stock is required for each item.', //Clay-X
            'items.*.remaining_stock.numeric' => 'Remaining stock must be a number.', //Clay-X
            'items.*.remaining_stock.min' => 'Remaining stock cannot be negative.', //Clay-X
        ]; //Clay-X
    } //Clay-X
} //Clay-X
