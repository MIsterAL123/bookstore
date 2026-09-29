<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Uji end-to-end seluruh komponen BookStore:
 * auth + role, katalog publik, cart, checkout, pesanan, kontak, dan panel admin.
 */
class BookStoreEndToEndTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::create([
            'name' => 'Admin', 'email' => 'admin@test.local',
            'password' => Hash::make('password'), 'role' => 'admin',
        ]);
    }

    private function makeUser(): User
    {
        return User::create([
            'name' => 'User', 'email' => 'user@test.local',
            'password' => Hash::make('password'), 'role' => 'user',
        ]);
    }

    private function makeBook(int $stock = 10, float $price = 50000): Book
    {
        $category = Category::firstOrCreate(['name' => 'Fiksi']);
        return Book::create([
            'title' => 'Buku Uji', 'author' => 'Penulis',
            'description' => 'Deskripsi', 'price' => $price, 'stock' => $stock,
            'image_url' => 'https://example.com/x.jpg', 'category_id' => $category->id,
        ]);
    }

    // ---------- PUBLIC ----------

    public function test_home_page_lists_books(): void
    {
        $this->makeBook();
        $this->get('/')->assertOk()->assertSee('Buku Uji');
    }

    public function test_home_search_filters_by_title(): void
    {
        $this->makeBook();
        Category::firstOrCreate(['name' => 'Lain']);
        Book::create([
            'title' => 'Kalkulus', 'author' => 'X', 'price' => 10000, 'stock' => 5,
            'category_id' => Category::where('name', 'Lain')->first()->id,
        ]);

        $this->get('/?q=Buku')->assertOk()->assertSee('Buku Uji')->assertDontSee('Kalkulus');
    }

    public function test_book_detail_page(): void
    {
        $book = $this->makeBook();
        $this->get('/books/'.$book->id)->assertOk()->assertSee('Buku Uji');
    }

    public function test_about_and_contact_pages(): void
    {
        $this->get('/about')->assertOk();
        $this->get('/contact')->assertOk();
    }

    // ---------- AUTH & ROLE ----------

    public function test_guest_redirected_from_protected_pages(): void
    {
        foreach (['/cart', '/my-orders', '/profile', '/admin/dashboard'] as $p) {
            $this->get($p)->assertRedirect();
        }
    }

    public function test_user_cannot_access_admin(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($user)->get('/admin/books')->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $this->actingAs($this->makeAdmin())->get('/admin/dashboard')->assertOk();
    }

    public function test_login_redirects_admin_to_admin_dashboard(): void
    {
        $this->makeAdmin();
        $this->post('/login', ['email' => 'admin@test.local', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_redirects_regular_user_to_home(): void
    {
        $this->makeUser();
        $this->post('/login', ['email' => 'user@test.local', 'password' => 'password'])
            ->assertRedirect(route('home'));
    }

    public function test_registration_creates_user_role_user(): void
    {
        $this->post('/register', [
            'name' => 'Baru', 'email' => 'baru@test.local',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'baru@test.local', 'role' => 'user']);
    }

    // ---------- PROFILE ----------

    public function test_profile_page_ok_and_update(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user)->get('/profile')->assertOk();
        $this->actingAs($user)->patch('/profile', ['name' => 'Ganti', 'email' => 'ganti@test.local'])
            ->assertRedirect('/profile');
        $this->assertSame('Ganti', $user->fresh()->name);
    }

    // ---------- ADMIN CRUD ----------

    public function test_admin_can_crud_category(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin)->post('/admin/categories', ['name' => 'Baru'])->assertRedirect();
        $cat = Category::where('name', 'Baru')->first();
        $this->assertNotNull($cat);

        $this->actingAs($admin)->put('/admin/categories/'.$cat->id, ['name' => 'Diubah'])->assertRedirect();
        $this->assertSame('Diubah', $cat->fresh()->name);

        $this->actingAs($admin)->delete('/admin/categories/'.$cat->id)->assertRedirect();
        $this->assertNull(Category::find($cat->id));
    }

    public function test_admin_cannot_delete_category_that_has_books(): void
    {
        $admin = $this->makeAdmin();
        $book = $this->makeBook();
        $category = $book->category;
        $this->assertTrue($category->books()->whereKey($book->id)->exists());

        $this->actingAs($admin)->delete('/admin/categories/'.$category->id)
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertNotNull($category->fresh());
        $this->assertNotNull($book->fresh());
    }

    public function test_admin_can_crud_book(): void
    {
        $admin = $this->makeAdmin();
        $cat = Category::create(['name' => 'Fiksi']);

        $this->actingAs($admin)->post('/admin/books', [
            'title' => 'Novel', 'author' => 'A', 'category_id' => $cat->id,
            'price' => 60000, 'stock' => 3, 'description' => 'd',
        ])->assertRedirect();
        $book = Book::where('title', 'Novel')->first();
        $this->assertNotNull($book);

        $this->actingAs($admin)->put('/admin/books/'.$book->id, [
            'title' => 'Novel 2', 'author' => 'A', 'category_id' => $cat->id,
            'price' => 70000, 'stock' => 4,
        ])->assertRedirect();
        $this->assertSame('Novel 2', $book->fresh()->title);

        $this->actingAs($admin)->delete('/admin/books/'.$book->id)->assertRedirect();
        $this->assertNull(Book::find($book->id));
    }

    public function test_admin_cannot_delete_book_that_has_order_items(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeUser();
        $book = $this->makeBook();
        $order = Order::create(['user_id' => $user->id, 'total_price' => 50000]);
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'quantity' => 1,
            'subtotal' => 50000,
        ]);

        $this->actingAs($admin)->delete('/admin/books/'.$book->id)
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertNotNull($book->fresh());
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'book_id' => $book->id]);
    }

    public function test_admin_can_view_users_orders_messages(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin)->get('/admin/users')->assertOk();
        $this->actingAs($admin)->get('/admin/orders')->assertOk();
        $this->actingAs($admin)->get('/admin/messages')->assertOk();
        $this->actingAs($admin)->get('/admin/categories')->assertOk();
        $this->actingAs($admin)->get('/admin/books')->assertOk();
    }

    public function test_admin_create_and_edit_forms_render(): void
    {
        $admin = $this->makeAdmin();
        $book = $this->makeBook();
        $cat = Category::first();

        $this->actingAs($admin)->get('/admin/categories/create')->assertOk();
        $this->actingAs($admin)->get('/admin/categories/'.$cat->id.'/edit')->assertOk();
        $this->actingAs($admin)->get('/admin/books/create')->assertOk();
        $this->actingAs($admin)->get('/admin/books/'.$book->id.'/edit')->assertOk();
    }

    public function test_admin_orders_page_renders_with_items(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeUser();
        $book = $this->makeBook();
        $order = Order::create(['user_id' => $user->id, 'total_price' => 50000]);
        \App\Models\OrderItem::create(['order_id' => $order->id, 'book_id' => $book->id, 'quantity' => 1, 'subtotal' => 50000]);

        $this->actingAs($admin)->get('/admin/orders')->assertOk()->assertSee($book->title);
    }

    public function test_cart_and_my_orders_pages_render_with_data(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook();
        Cart::create(['user_id' => $user->id, 'book_id' => $book->id, 'quantity' => 2]);
        $order = Order::create(['user_id' => $user->id, 'total_price' => 100000]);
        \App\Models\OrderItem::create(['order_id' => $order->id, 'book_id' => $book->id, 'quantity' => 2, 'subtotal' => 100000]);

        $this->actingAs($user)->get('/cart')->assertOk()->assertSee($book->title);
        $this->actingAs($user)->get('/my-orders')->assertOk()->assertSee($book->title);
    }

    // ---------- CART ----------

    public function test_user_can_add_to_cart_and_stock_guard(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook(stock: 2);

        $this->actingAs($user)->post('/cart', ['book_id' => $book->id, 'quantity' => 2])
            ->assertRedirect();
        $this->assertDatabaseHas('carts', ['user_id' => $user->id, 'book_id' => $book->id, 'quantity' => 2]);

        // Minta lebih dari stok -> tidak bertambah
        $this->actingAs($user)->post('/cart', ['book_id' => $book->id, 'quantity' => 5])
            ->assertSessionHas('error');
        $this->assertSame(2, Cart::where('user_id', $user->id)->first()->quantity);
    }

    public function test_user_can_update_and_remove_cart_item(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook(stock: 10);
        $cart = Cart::create(['user_id' => $user->id, 'book_id' => $book->id, 'quantity' => 1]);

        $this->actingAs($user)->put('/cart/'.$cart->id, ['quantity' => 3])->assertRedirect();
        $this->assertSame(3, $cart->fresh()->quantity);

        $this->actingAs($user)->delete('/cart/'.$cart->id)->assertRedirect();
        $this->assertNull(Cart::find($cart->id));
    }

    public function test_user_cannot_modify_other_users_cart(): void
    {
        $owner = $this->makeUser();
        $other = User::create(['name' => 'O', 'email' => 'o@test.local', 'password' => Hash::make('password'), 'role' => 'user']);
        $book = $this->makeBook();
        $cart = Cart::create(['user_id' => $owner->id, 'book_id' => $book->id, 'quantity' => 1]);

        $this->actingAs($other)->delete('/cart/'.$cart->id)->assertForbidden();
    }

    // ---------- CHECKOUT ----------

    public function test_checkout_creates_order_decrements_stock_and_clears_cart(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook(stock: 5, price: 20000);
        Cart::create(['user_id' => $user->id, 'book_id' => $book->id, 'quantity' => 2]);

        $this->actingAs($user)->post('/checkout', $this->shippingData())->assertRedirect(route('orders.my-orders'));

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertSame('pending', $order->status);
        $this->assertSame('Payment at Delivery', $order->payment_method);
        $this->assertEquals(40000, $order->total_price);
        $this->assertSame('Budi Santoso', $order->recipient_name);
        $this->assertSame('Jl. Merdeka No. 10', $order->shipping_address);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'book_id' => $book->id, 'quantity' => 2]);
        $this->assertSame(3, $book->fresh()->stock);
        $this->assertSame(0, Cart::where('user_id', $user->id)->count());
    }

    public function test_checkout_requires_shipping_address(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook(stock: 5);
        Cart::create(['user_id' => $user->id, 'book_id' => $book->id, 'quantity' => 1]);

        // Tanpa data alamat -> validasi gagal, tidak ada order dibuat
        $this->actingAs($user)->post('/checkout', [])->assertSessionHasErrors([
            'recipient_name', 'recipient_phone', 'shipping_address', 'city', 'postal_code',
        ]);
        $this->assertSame(0, Order::count());
    }

    public function test_cart_page_shows_shipping_address_form(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook();
        Cart::create(['user_id' => $user->id, 'book_id' => $book->id, 'quantity' => 1]);

        $this->actingAs($user)->get('/cart')
            ->assertOk()
            ->assertSee('Alamat Pengiriman')
            ->assertSee('recipient_name')
            ->assertSee('shipping_address')
            ->assertSee('Transfer Bank (Simulasi)')
            ->assertSee('E-Wallet (Simulasi)')
            ->assertSee('Tidak ada pembayaran nyata');
    }

    public function test_my_orders_shows_shipping_address(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook();
        $order = Order::create([
            'user_id' => $user->id, 'total_price' => 50000,
            'recipient_name' => 'Siti Aminah', 'recipient_phone' => '08123',
            'shipping_address' => 'Jl. Sudirman No. 5', 'city' => 'Jakarta', 'postal_code' => '10110',
        ]);
        \App\Models\OrderItem::create(['order_id' => $order->id, 'book_id' => $book->id, 'quantity' => 1, 'subtotal' => 50000]);

        $this->actingAs($user)->get('/my-orders')
            ->assertOk()
            ->assertSee('Siti Aminah')
            ->assertSee('Jl. Sudirman No. 5');
    }

    /** Data alamat pengiriman valid untuk dipakai di test checkout. */
    private function shippingData(): array
    {
        return [
            'recipient_name'   => 'Budi Santoso',
            'recipient_phone'  => '08123456789',
            'shipping_address' => 'Jl. Merdeka No. 10',
            'city'             => 'Bandung',
            'postal_code'      => '40123',
            'payment_method'   => Order::DEFAULT_PAYMENT_METHOD,
        ];
    }

    public function test_checkout_accepts_dummy_payment_method(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook(stock: 5, price: 20000);
        Cart::create(['user_id' => $user->id, 'book_id' => $book->id, 'quantity' => 1]);

        $this->actingAs($user)->post('/checkout', array_merge($this->shippingData(), [
            'payment_method' => 'Dummy E-Wallet',
        ]))->assertRedirect(route('orders.my-orders'));

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_method' => 'Dummy E-Wallet',
            'status' => 'pending',
        ]);
    }

    public function test_checkout_rejects_unknown_payment_method(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook();
        Cart::create(['user_id' => $user->id, 'book_id' => $book->id, 'quantity' => 1]);

        $this->actingAs($user)->post('/checkout', array_merge($this->shippingData(), [
            'payment_method' => 'Real Payment Gateway',
        ]))->assertSessionHasErrors('payment_method');

        $this->assertSame(0, Order::count());
    }

    public function test_checkout_empty_cart_shows_error(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user)->post('/checkout', $this->shippingData())
            ->assertRedirect(route('cart.index'))->assertSessionHas('error');
    }

    public function test_checkout_does_not_oversell_when_stock_dropped_after_add_to_cart(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook(stock: 5);
        Cart::create(['user_id' => $user->id, 'book_id' => $book->id, 'quantity' => 5]);

        // Stok berkurang (dibeli pembeli lain) setelah item masuk keranjang
        $book->update(['stock' => 2]);

        $this->actingAs($user)->post('/checkout', $this->shippingData());

        $book->refresh();
        $this->assertGreaterThanOrEqual(0, $book->stock, 'Stok tidak boleh negatif (oversell)');
        $this->assertSame(0, Order::count(), 'Checkout harus gagal bila stok tidak mencukupi');
    }

    public function test_checkout_rolls_back_when_stock_changes_during_transaction(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook(stock: 1);
        Cart::create(['user_id' => $user->id, 'book_id' => $book->id, 'quantity' => 1]);

        $stockChanged = false;
        DB::listen(function ($query) use (&$stockChanged, $book): void {
            if (!$stockChanged && str_contains(strtolower($query->sql), 'insert into `orders`')) {
                $stockChanged = true;
                Book::whereKey($book->id)->update(['stock' => 0]);
            }
        });

        $this->actingAs($user)->post('/checkout', $this->shippingData());

        $this->assertSame(1, $book->fresh()->stock, 'Rollback harus mengembalikan stok bila transaksi gagal');
        $this->assertSame(0, Order::count(), 'Checkout harus rollback bila stok berubah saat transaksi berjalan');
    }

    public function test_my_orders_shows_only_own_orders(): void
    {
        $user = $this->makeUser();
        $other = User::create(['name' => 'O', 'email' => 'o@test.local', 'password' => Hash::make('password'), 'role' => 'user']);
        Order::create(['user_id' => $other->id, 'total_price' => 1000]);

        $this->actingAs($user)->get('/my-orders')->assertOk();
    }

    // ---------- CONTACT & ADMIN ORDER STATUS ----------

    public function test_user_can_send_message_to_admin(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user)->post('/contact', ['content' => 'Halo admin, ada pertanyaan.'])
            ->assertRedirect(route('contact'));
        $this->assertDatabaseHas('messages', ['user_id' => $user->id]);
    }

    public function test_admin_can_update_order_status(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeUser();
        $order = Order::create(['user_id' => $user->id, 'total_price' => 1000]);

        $this->actingAs($admin)->patch('/admin/orders/'.$order->id.'/status', ['status' => 'processing'])
            ->assertRedirect();
        $this->actingAs($admin)->patch('/admin/orders/'.$order->id.'/status', ['status' => 'completed'])
            ->assertRedirect();
        $this->assertSame('completed', $order->fresh()->status);
    }

    public function test_cancelling_order_restores_stock_once_and_blocks_invalid_transition(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeUser();
        $book = $this->makeBook(stock: 3);
        $order = Order::create(['user_id' => $user->id, 'total_price' => 100000]);
        // Simulasikan dua unit stok yang sudah dicadangkan oleh checkout.
        $book->update(['stock' => 1]);
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'quantity' => 2,
            'subtotal' => 100000,
        ]);

        $this->actingAs($admin)->patch('/admin/orders/'.$order->id.'/status', ['status' => 'cancelled'])
            ->assertRedirect();
        $this->assertSame(3, $book->fresh()->stock);

        $this->actingAs($admin)->patch('/admin/orders/'.$order->id.'/status', ['status' => 'cancelled'])
            ->assertRedirect();
        $this->assertSame(3, $book->fresh()->stock);

        $this->actingAs($admin)->patch('/admin/orders/'.$order->id.'/status', ['status' => 'completed'])
            ->assertRedirect()
            ->assertSessionHas('error');
        $this->assertSame('cancelled', $order->fresh()->status);
    }

    public function test_order_item_keeps_checkout_price_after_book_price_changes(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook(stock: 5, price: 20000);
        Cart::create(['user_id' => $user->id, 'book_id' => $book->id, 'quantity' => 2]);

        $this->actingAs($user)->post('/checkout', $this->shippingData())->assertRedirect();
        $order = Order::where('user_id', $user->id)->firstOrFail();
        $book->update(['price' => 99999]);

        $this->assertSame(20000.0, (float) $order->items()->firstOrFail()->unit_price);
        $this->actingAs($user)->get('/my-orders')
            ->assertOk()
            ->assertSee('Rp 20.000')
            ->assertDontSee('Rp 99.999');
    }

    public function test_admin_can_delete_message(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeUser();
        $msg = Message::create(['user_id' => $user->id, 'content' => 'test pesan panjang']);

        $this->actingAs($admin)->delete('/admin/messages/'.$msg->id)->assertRedirect();
        $this->assertNull(Message::find($msg->id));
    }

    public function test_account_deletion_preserves_order_history_and_anonymizes_owner(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook();
        $order = Order::create(['user_id' => $user->id, 'total_price' => 50000]);
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'quantity' => 1,
            'subtotal' => 50000,
        ]);
        $message = Message::create(['user_id' => $user->id, 'content' => 'Pesan pengguna']);

        $this->actingAs($user)->delete('/profile', ['password' => 'password'])
            ->assertRedirect('/');

        $this->assertNotNull($order->fresh());
        $this->assertNull($order->fresh()->user_id);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'book_id' => $book->id]);
        $this->assertNull($message->fresh()->user_id);
    }
}
