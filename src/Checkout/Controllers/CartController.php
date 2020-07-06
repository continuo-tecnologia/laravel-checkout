<?php

namespace MatheusFS\Laravel\Checkout\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use MatheusFS\Laravel\Checkout\Address;
use MatheusFS\Laravel\Checkout\Checkout;
use MatheusFS\Laravel\Checkout\Entities\Item;
use MatheusFS\Laravel\Checkout\Entities\Person;
use MatheusFS\Laravel\Checkout\Events\CartUpdated;
use MatheusFS\Laravel\Checkout\Facades\Cart;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Address as PagarMeAddress;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Billing;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Customer;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Shipping;

class CartController extends Controller {

    public function add(Request $request) {

        $product = Product::find($request->product_id);
        $item = new Item($product->id, $product->label, $product->price);

        $cart_json = Cart::increment($item);

        $this->broadcast($cart_json);
        return [Cart::getId() => $cart_json];
    }

    public function remove(Request $request) {

        $product = Product::find($request->product_id);
        $item = new Item($product->id, $product->label, $product->price);

        $cart_json = Cart::decrement($item);
        
        $this->broadcast($cart_json);
        return [Cart::getId() => $cart_json];
    }

    public function html() {

        return response(Cart::renderMinicart());
    }

    public function count() {

        return response(Cart::countItems());
    }

    public function broadcast($payload){
        
        $user_id = Auth::check() ? Auth::id() : Session::getId();
        broadcast(new CartUpdated($user_id, $payload));
    }

    public function checkout(Request $request) {

        # Request creates objects from inputs name
        $customer = (object) $request->customer;
        $shipping = (object) $request->shipping;

        $person = new Person(
            $customer->name,
            $customer->email,
            $customer->cpf,
            $customer->phone_number
        );

        $address = new Address(
            $shipping->zipcode,
            $shipping->street_number,
            $shipping->complementary,
            $shipping->street,
            $shipping->neighborhood,
            $shipping->city,
            $shipping->state,
            'br' # Default country, if you operate w/ more than one country you can modify the request to ask user
        );

        # Normalize address w/ Pagar.me v4 API
        $address = new PagarMeAddress($address);

        # Normalize customer w/ Pagar.me v4 API
        $Customer = new Customer($person);

        # Normalize billing w/ Pagar.me v4 API
        $Billing = new Billing($address);

        # Normalize shipping w/ Pagar.me v4 API
        $Shipping = new Shipping(
            $address,
            $shipping->fee,
            (new \DateTime())->add(new \DateInterval('P14D'))
        );

        # Initiate facade
        $checkout = new Checkout;

        Cart::collect()->each(function ($item) use ($checkout) {

            # Add normalized item to checkout
            $checkout->addItem($item);
        });

        # Attach customer, billing and shipping to Checkout
        $checkout->setCustomer($Customer);
        $checkout->setBilling($Billing);
        $checkout->setShipping($Shipping);

        # Redirect the user to the generated payment link
        return $checkout->redirectToPaymentLink();
    }
}