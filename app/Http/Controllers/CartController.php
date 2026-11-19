<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Src\Contracts\CatalogSourceInterface;

class CartController extends Controller
{
    public function __construct(
        private CatalogSourceInterface $catalog,
    ) {
    }

    public function index(): View
    {
        return view('cart', [
            'cart' => session('cart', []),
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = $this->catalog->findProductById($request->integer('product_id'));
        abort_if(! $product, 404);

        $store = $this->catalog->findStoreById($product->getStoreId());
        abort_if(! $store, 404);

        $qty = max(1, $request->integer('quantity', 1));
        $cart = session('cart', []);
        $productId = $product->getId();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $qty;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'store_id' => $store->getId(),
                'store_name' => $store->getName(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $qty,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('notice', 'Barang ditambahkan ke keranjang.');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart = session('cart', []);
        $id = $request->integer('product_id');
        $qty = $request->integer('quantity');

        if (isset($cart[$id])) {
            if ($qty <= 0) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $qty;
            }
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index');
    }

    public function remove(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $cart = session('cart', []);
        unset($cart[$request->integer('product_id')]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_id' => ['required', 'integer'],
            'name' => ['nullable', 'string', 'max:120'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $store = $this->catalog->findStoreById((int) $data['store_id']);
        abort_if(! $store, 404);

        $items = array_filter(session('cart', []), fn (array $item) => (int) $item['store_id'] === $store->getId());

        if (empty($items)) {
            return redirect()->route('cart.index');
        }

        $lines = ["Halo admin *{$store->getName()}*, saya ingin memesan:", ''];
        $total = 0;

        foreach ($items as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            $lines[] = "- {$item['name']} ({$item['quantity']}x) = Rp " . number_format($subtotal, 0, ',', '.');
        }

        $lines[] = '';
        $lines[] = 'Total: Rp ' . number_format($total, 0, ',', '.');
        if (! empty($data['name'])) {
            $lines[] = "Nama: {$data['name']}";
        }
        if (! empty($data['whatsapp'])) {
            $lines[] = "No. WA: {$data['whatsapp']}";
        }
        if (! empty($data['address'])) {
            $lines[] = "Alamat/Catatan: {$data['address']}";
        }

        $waUrl = 'https://wa.me/' . $store->getCleanWhatsappDigits() . '?text=' . rawurlencode(implode("\n", $lines));

        return redirect()->away($waUrl);
    }
}
