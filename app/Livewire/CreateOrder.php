<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Admin\Product;

class CreateOrder extends Component {

    public $users, $products;
    public $selected_user = null;
    public $totalOrder = 0;
    public $selectedProducts = [];
    public $price;
    public $user_id;

    public function mount() {
        $this->users = User::all();
        $this->products = Product::all();
        $this->selectedProducts[] = ['product_id' => '', 'quantity' => 1, 'price' => 0, 'total' => 0];
    }
    
    public function updateProduct($index, $field)
{
    if ($field === 'product_id') {
        $this->updateProductPrice($index);
    } elseif ($field === 'quantity') {
        $this->updateProductTotal($index);
    }
}

    public function addProduct() {

        $this->selectedProducts[] = [
            'product_id' => '',
            'quantity' => 1,
            'price' => 0,
            'total' => 0
        ];
    }

    public function removeProduct($index) {
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts);
        $this->updateTotalOrder();
    }

    public function updatedSelectedProducts($value, $key) {
        $parts = explode('.', $key);
        if (count($parts) === 3) {
            $index = $parts[0];
            $field = $parts[2];

            if ($field === 'product_id') {
                $this->updateProductPrice($index);
            } elseif ($field === 'quantity') {
                $this->updateProductTotal($index);
            }
        }
    }

    public function updateProductPrice($index) {
        $product = Product::find($this->selectedProducts[$index]['product_id']);
        if ($product) {
            $this->selectedProducts[$index]['price'] = $product->price;
            $this->updateProductTotal($index);
        }
    }

    public function updateProductTotal($index) {
        $this->selectedProducts[$index]['total'] = $this->selectedProducts[$index]['quantity'] * $this->selectedProducts[$index]['price'];
        $this->updateTotalOrder();
    }

    public function updateTotalOrder() {
        $this->totalOrder = array_sum(array_column($this->selectedProducts, 'total'));
    }

    public function saveOrder() {
        // قم بتنفيذ عملية حفظ الطلب هنا
    }

    public function render() {
        return view('livewire.create-order');
    }
}
