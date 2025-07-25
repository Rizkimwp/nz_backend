<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Load file Midtrans SDK
		require_once APPPATH . 'third_party/Midtrans/Midtrans.php';

		// Load config custom kamu (application/config/midtrans.php)
		$this->config->load('midtrans');

		// Ambil konfigurasi dari file midtrans.php
		$midtrans = $this->config->item('midtrans');

		// Load model dan helper
		$this->load->model('Product_model');
		$this->load->model('Product_transaction_model');
		$this->load->model('Product_transaction_detail_model');
		$this->load->helper(['string', 'security']);

		// Set header (jika API)
		// Tambahkan header CORS
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization");

		// Handle preflight request (OPTIONS)
		if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			http_response_code(200);
			exit();
		}
		// Set konfigurasi Midtrans
		\Midtrans\Config::$serverKey = $midtrans['server_key'];
		\Midtrans\Config::$isProduction = $midtrans['is_production'];
		\Midtrans\Config::$isSanitized = $midtrans['is_sanitized'];
		\Midtrans\Config::$is3ds = $midtrans['is_3ds'];
	}
	// Checkout
	public function payment()
	{
		$rawData = json_decode(file_get_contents('php://input'), true);

		// Validasi awal
		if (
			!isset($rawData['products']) || !is_array($rawData['products']) ||
			!isset($rawData['name'], $rawData['phone'], $rawData['email'])
		) {
			http_response_code(400);
			echo json_encode(['message' => 'Invalid input']);
			return;
		}

		try {
			// 1. Generate booking_trx_id
			$booking_trx_id = strtoupper(random_string('alnum', 10));

			$sub_total_amount = 0;
			$grand_total_amount = 0;

			// 2. Simpan transaksi produk utama
			$transaction_data = [
				'booking_trx_id' => $booking_trx_id,
				'name' => $rawData['name'],
				'phone' => $rawData['phone'],
				'email' => $rawData['email'],
				'address' => isset($rawData['address']) ? $rawData['address'] : null,
				'sub_total_amount' => 0,
				'grand_total_amount' => 0,
				'created_at' => date('Y-m-d H:i:s')
			];

			$transaction_id = $this->Product_transaction_model->insert($transaction_data);

			// 3. Simpan detail produk
			foreach ($rawData['products'] as $prod) {
				$product = $this->Product_model->get_by_id($prod['product_id']);
				if (!$product)
					throw new Exception('Product not found');

				// hitung subtotal
				$subtotal = $product->price * $prod['quantity'];

				// kurangi stok
				$this->Product_model->decrease_stock($prod['product_id'], $prod['quantity']);

				$this->Product_transaction_detail_model->insert([
					'product_transaction_id' => $transaction_id,
					'product_id' => $prod['product_id'],
					'quantity' => $prod['quantity'],
					'price' => $product->price,
					'sub_total' => $subtotal,
				]);

				$sub_total_amount += $subtotal;
			}

			$grand_total_amount = $sub_total_amount;

			// 4. Update transaksi utama dengan total
			$this->Product_transaction_model->update($transaction_id, [
				'sub_total_amount' => $sub_total_amount,
				'grand_total_amount' => $grand_total_amount,
			]);

			// 5. Midtrans Snap Token
			$params = [
				'transaction_details' => [
					'order_id' => $booking_trx_id,
					'gross_amount' => $grand_total_amount,
				],
				'customer_details' => [
					'first_name' => $rawData['name'],
					'email' => $rawData['email'],
					'phone' => $rawData['phone'],
				]
			];

			$snapToken = \Midtrans\Snap::getSnapToken($params);

			echo json_encode([
				'snap_token' => $snapToken,
				'transaction_id' => $transaction_id,
				'booking_trx_id' => $booking_trx_id,
			]);
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(['message' => $e->getMessage()]);
		}
	}

	public function cancelTransaction()
	{
		// Cek apakah transaksi ada
		$input = json_decode(file_get_contents("php://input"), true);
		$id = $input['transaction_id'] ?? null;
		$transaction = $this->Product_transaction_model->get_by_id($id);
		if (!$transaction) {
			return $this->output
				->set_content_type('application/json')
				->set_status_header(404)
				->set_output(json_encode(['status' => false, 'message' => 'Transaksi tidak ditemukan']));
		}

		// Ambil detail transaksi untuk mengembalikan stok
		$details = $this->Product_transaction_detail_model->get_by_transaction_id($id);

		// Kembalikan stok produk
		foreach ($details as $detail) {
			$this->Product_model->increase_stock($detail->product_id, $detail->quantity);
		}

		// Hapus detail transaksi
		$this->Product_transaction_detail_model->delete_by_transaction_id($id);

		// Hapus transaksi utama
		$this->Product_transaction_model->delete($id);

		// Respon sukses
		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(['status' => true, 'message' => 'Transaksi berhasil dibatalkan']));
	}


	public function markAsPaid()
	{
		$input = json_decode(file_get_contents("php://input"), true);
		$id = $input['transaction_id'] ?? null;

		if (!$id) {
			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode([
					'status' => false,
					'message' => 'transaction_id wajib diisi'
				]));
		}

		$updated = $this->Product_transaction_model->updateIsPaid($id);

		if ($updated) {
			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode([
					'status' => true,
					'message' => 'Transaksi ditandai sebagai dibayar'
				]));
		} else {
			return $this->output
				->set_content_type('application/json')
				->set_status_header(500)
				->set_output(json_encode([
					'status' => false,
					'message' => 'Gagal update status pembayaran'
				]));
		}
	}

	// Get Product Publshed
	public function getPublishedProduct()
	{
		try {
			$products = $this->Product_model->get_published_products();
			echo json_encode([
				'status' => 'success',
				'data' => $products
			]);
		} catch (Exception $e) {
			echo json_encode([
				'status' => 'error',
				'message' => $e->getMessage()
			]);
		}
	}

	// GEt Product By Slug
	public function getProductBySlug($slug)
	{
		try {
			$product = $this->Product_model->get_product_by_slug($slug);

			if (!$product) {
				echo json_encode([
					'status' => 'error',
					'message' => 'Product not found'
				]);
				return;
			}

			echo json_encode([
				'status' => 'success',
				'data' => $product
			]);
		} catch (Exception $e) {
			echo json_encode([
				'status' => 'error',
				'message' => $e->getMessage()
			]);
		}
	}


}