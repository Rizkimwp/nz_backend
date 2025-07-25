<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rajaongkir extends CI_Controller
{

	private $api_key = '0DorcWHLee428f18d59fff52PqvEOOOv';

	public function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
		$this->load->helper('curl'); // helper untuk curl_get dan curl_post
	}

	public function getDomesticDestination()
	{
		$search = $this->input->get('search');
		if (!$search) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Search query required'
			]);
			return;
		}

		$url = 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=' . urlencode($search) . '&limit=10&offset=2';

		$headers = [
			'key: ' . $this->api_key
		];

		$response = curl_get($url, $headers);

		echo $response;
	}

	public function getDomesticCost()
	{
		$rawData = json_decode($this->input->raw_input_stream, true);

		$origin = $rawData['origin'] ?? null;
		$destination = $rawData['destination'] ?? null;
		$weight = $rawData['weight'] ?? null;

		$courier = 'jne:sicepat:ide:sap:jnt:ninja:tiki:lion:anteraja:pos:ncs:rex:rpx:sentral:star:wahana:dse';

		if (!$origin || !$destination || !$weight) {
			echo json_encode([
				'status' => 'error',
				'message' => 'All query required'
			]);
			return;
		}

		$url = 'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost';

		$headers = [
			'key: ' . $this->api_key,
			'Content-Type: application/x-www-form-urlencoded'
		];

		$data = [
			'origin' => $origin,
			'destination' => $destination,
			'weight' => $weight,
			'courier' => $courier
		];

		$response = curl_post($url, $data, $headers);

		echo $response;
	}
}