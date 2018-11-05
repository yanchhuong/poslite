<?php 

class DeliveriesController extends CI_Controller 

{

	public function index() {
		$this->load->database();
		$this->load->model('PriceModel');
	 
		$data['page'] = "New Delivery";
		$data['suppliers'] = $this->db->get('supplier')->result();
 
		$this->load->view('header',$data);
		$this->load->view('side_menu');
		$this->load->view('delivery/index',$data);
		$this->load->view('footer');
	}


	public function insert() {
	 
		$this->load->database();

		$data = array(
			'supplier_id' => $this->input->post('supplier_id')

			);

		$this->db->insert('delivery',$data);
		
		$delivery_id = $this->db->insert_id();

		if ($delivery_id) {
			$data = array(
				'item' => $this->input->post('item_name'),
				'price' => $this->input->post('price'),
				'quantity' => $this->input->post('quantity'),
				'delivery_id' => $delivery_id
			);
			$this->db->insert('delivery_details', $data);
			$this->session->set_flashdata('success', 'Delivery submitted successfully');
			
			return redirect('new-delivery');
		}
	}

	public function deliveries() {
		$this->load->database();
		
		$dataSet = [];
		$deliveries = $this->db->get('delivery')->result();

		foreach ($deliveries as $delivery) {

			$delivery_details = $this->db->where('delivery_id', $delivery->id)->get('delivery_details')->row();
			if ($delivery_details) {
				$dataSet[] = [
					'supplier_name' => $this->db->where('id', $delivery->supplier_id)->get('supplier')->row()->name,
					'item' => $delivery_details->item,
					'price' => $delivery_details->price,
					'quantity' => $delivery_details->quantity
				];
			}
		}

	  

		$data['page'] = "Deliveries"; 
 		$data['dataSet'] = $dataSet;
		$this->load->view('header',$data);
		$this->load->view('side_menu');
		$this->load->view('delivery/deliveries',$data);
		$this->load->view('footer');
	}

}