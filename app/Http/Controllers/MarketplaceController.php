<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public const PRODUCTS = [
        'bp-monitor' => ['name' => 'Digital Blood Pressure Monitor', 'price' => 89.00, 'category' => 'Home monitoring', 'icon' => 'fa-heart-pulse', 'description' => 'A simple upper-arm monitor for daily readings.', 'requires_prescription' => false],
        'glucose-kit' => ['name' => 'Glucose Care Kit', 'price' => 45.00, 'category' => 'Diabetes care', 'icon' => 'fa-droplet', 'description' => 'Starter kit for confident blood sugar tracking.', 'requires_prescription' => false],
        'first-aid' => ['name' => 'Family First-Aid Kit', 'price' => 39.00, 'category' => 'Essentials', 'icon' => 'fa-kit-medical', 'description' => 'Everyday essentials for home and travel.', 'requires_prescription' => false],
        'wellness-pack' => ['name' => 'Wellness Starter Pack', 'price' => 29.00, 'category' => 'Wellbeing', 'icon' => 'fa-leaf', 'description' => 'A compact pack for healthy routines.', 'requires_prescription' => false],
        'thermometer' => ['name' => 'Infrared Thermometer', 'price' => 35.00, 'category' => 'Home monitoring', 'icon' => 'fa-temperature-half', 'description' => 'Quick, touch-free temperature checks.', 'requires_prescription' => false],
        'care-journal' => ['name' => 'Care Journal', 'price' => 12.00, 'category' => 'Wellbeing', 'icon' => 'fa-book-medical', 'description' => 'Keep appointments, symptoms, and questions in one place.', 'requires_prescription' => false],
        'amoxicillin-500' => ['name' => 'Prescription Medicine — Amoxicillin 500 mg', 'price' => 18.00, 'category' => 'Prescription medicine', 'icon' => 'fa-prescription-bottle-medical', 'description' => 'Available only with an active doctor approval.', 'requires_prescription' => true],
        'amlodipine-5' => ['name' => 'Prescription Medicine — Amlodipine 5 mg', 'price' => 15.00, 'category' => 'Prescription medicine', 'icon' => 'fa-prescription-bottle-medical', 'description' => 'Available only with an active doctor approval.', 'requires_prescription' => true],
    ];

    public function index()
    {
        return view('marketplace.index', [
            'products' => self::PRODUCTS,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function cart()
    {
        return view('marketplace.cart', [
            'items' => $this->cartItems(),
            'cartCount' => $this->cartCount(),
            'total' => $this->cartTotal(),
        ]);
    }

    public function add(Request $request, string $product)
    {
        abort_unless(isset(self::PRODUCTS[$product]), 404);

        $cart = session('marketplace_cart', []);
        if (self::PRODUCTS[$product]['requires_prescription']) {
            $approval = Prescription::where('patient_id', auth()->id())
                ->where('medicine_id', $product)->where('status', 'approved')
                ->whereDate('expires_at', '>=', today())->latest()->first();
            if (! $approval || ($cart[$product] ?? 0) >= $approval->quantity) {
                return back()->with('error', 'This medicine requires an active doctor approval with remaining quantity.');
            }
        }
        $cart[$product] = min(($cart[$product] ?? 0) + 1, 10);
        session(['marketplace_cart' => $cart]);

        return back()->with('success', self::PRODUCTS[$product]['name'].' added to your cart.');
    }

    public function update(Request $request, string $product)
    {
        $quantity = max(0, min((int) $request->input('quantity', 1), 10));
        $cart = session('marketplace_cart', []);

        if ($quantity === 0) {
            unset($cart[$product]);
        } else {
            $cart[$product] = $quantity;
        }

        session(['marketplace_cart' => $cart]);

        return redirect()->route('marketplace.cart');
    }

    public function checkout()
    {
        $items = $this->cartItems();
        abort_if($items === [], 422, 'Your cart is empty.');

        session([
            'marketplace_last_order' => [
                'number' => 'MS-'.strtoupper(str()->random(8)),
                'items' => $items,
                'total' => $this->cartTotal(),
            ],
        ]);
        session()->forget('marketplace_cart');

        return redirect()->route('marketplace.success');
    }

    public function success()
    {
        $order = session('marketplace_last_order');
        abort_unless($order, 404);

        return view('marketplace.success', compact('order'));
    }

    private function cartItems(): array
    {
        $items = [];
        foreach (session('marketplace_cart', []) as $id => $quantity) {
            if (! isset(self::PRODUCTS[$id])) {
                continue;
            }
            $items[] = ['id' => $id, 'quantity' => $quantity] + self::PRODUCTS[$id] + ['subtotal' => self::PRODUCTS[$id]['price'] * $quantity];
        }

        return $items;
    }

    private function cartCount(): int
    {
        return array_sum(session('marketplace_cart', []));
    }

    private function cartTotal(): float
    {
        return array_sum(array_column($this->cartItems(), 'subtotal'));
    }
}
