<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman_controller extends CI_Controller
{   
	public function __construct(){
	    parent::__construct();
	    $this->load->model('peminjaman'); // Load SiswaModel ke controller ini
	    $this->load->library('session');
      // Load Pagination library
      $this->load->library('pagination');
  	}

  	public function pegawai(){
	    $data['pegawai'] = $this->peminjaman->getPegawai();
	    $this->load->view('pegawai/pegawai', $data);
  	}

  	public function buku(){
  		$data['buku'] = $this->peminjaman->getBuku();
	    $this->load->view('buku/buku', $data);	
  	}

  	public function anggota(){
  		$data['anggota'] = $this->peminjaman->getAnggota();
	    $this->load->view('anggota/dataAnggota', $data);	
  	}

  	public function peminjaman(){
  		$data['peminjaman'] = $this->peminjaman->getPeminjaman();
  		$data['anggota'] = $this->peminjaman->getAnggota();
  		$data['buku'] = $this->peminjaman->getBuku();
	    $this->load->view('peminjaman/peminjaman', $data);
  	}

  	public function tambahAnggota(){
	    if($this->input->post('submit')){ // Jika user mengklik tombol submit yang ada di form
	        $this->peminjaman->simpanAnggota(); // Panggil fungsi save() 
	        redirect('anggota');
	    }
	    $this->load->view('anggota/tambahAnggota');
  	}

  	public function tambahPegawai(){
	    if($this->input->post('submit')){ // Jika user mengklik tombol submit yang ada di form
	        $this->peminjaman->simpanPegawai(); // Panggil fungsi save() 
	        redirect('pegawai');
	    }
	    $this->load->view('pegawai/tambahPegawai');
  	}


  	public function tambahBuku(){
	    if($this->input->post('submit')){ // Jika user mengklik tombol submit yang ada di form
	        $res = $this->peminjaman->simpanBuku(); // Panggil fungsi save() 
	        redirect('buku');
	    }
	    $this->load->view('buku/tambahBuku');
  	}

  	public function editAnggota($kode){
	    if($this->input->post('submit')){ // Jika user mengklik tombol submit yang ada di form
	        $this->peminjaman->editAnggota($kode); // Panggil fungsi edit()
	        redirect('anggota');
	    }
	    $data['anggota'] = $this->peminjaman->viewAnggota_by($kode);
	    $this->load->view('anggota/editAnggota', $data);
    }

    public function editPegawai($kode){
	    if($this->input->post('submit')){ // Jika user mengklik tombol submit yang ada di form
	        $this->peminjaman->editPegawai($kode); // Panggil fungsi edit()
	        redirect('pegawai');
	    }
	    $data['pegawai'] = $this->peminjaman->viewPegawai_by($kode);
	    $this->load->view('pegawai/editPegawai', $data);
    }

    public function editBuku($kode){
	    if($this->input->post('submit')){ // Jika user mengklik tombol submit yang ada di form
	        $this->peminjaman->editBuku($kode); // Panggil fungsi edit()
	        redirect('buku');
	    }
	    $data['buku'] = $this->peminjaman->viewBuku_by($kode);
	    $this->load->view('buku/editBuku', $data);
    }

    public function deleteAnggota($kode){
    	$kode = $this->input->post('id');
      $this->peminjaman->deleteAnggota($kode); // Panggil fungsi delete() 
	    redirect('anggota');
    }
    public function deleteBuku($kode){
      $kode = $this->input->post('id');
    	$this->peminjaman->deleteBuku($kode); // Panggil fungsi delete() 
	    redirect('buku');
    }
    public function deletePegawai($kode){
      $kode = $this->input->post('id');
    	$this->peminjaman->deletePegawai($kode); // Panggil fungsi delete() 
	    redirect('pegawai');
    }

  	public function ubah($nis){
	    if($this->input->post('submit')){ // Jika user mengklik tombol submit yang ada di form
	        $this->mymodel->edit($nis); // Panggil fungsi edit()
	        redirect('');
	    }
	    $data['siswa'] = $this->mymodel->view_by($nis);
	    $this->load->view('ubah', $data);
    }

     public function hapus($nis){
	    $this->mymodel->delete($nis); // Panggil fungsi delete() yang ada di SiswaModel.php
	    redirect('');
  	}

  	public function simpanPeminjaman(){
  		if($this->input->post('submit')){ // Jika user mengklik tombol submit yang ada di form
	        $this->peminjaman->simpanPeminjaman(); // Panggil fungsi save() 
	        redirect('peminjaman');
	    }
	    $this->load->view('peminjaman/peminjaman');
  	}

  	public function bukuKembali($kode){
      $kode = $this->input->post('id');
  		$this->peminjaman->bukuKembali($kode);
  		redirect('peminjaman');
  	}

  	public function login(){
  		if($this->input->post('submit')){
  			$cek['petugas'] = $this->peminjaman->prosesLogin();
  			if($cek > 0){
  				// $uname = $this->input->post('username');
  				$this->peminjaman->lastLogin();
  				// $this->session->set_userdata('uname',$uname);
  				// $get = $this->peminjaman->getID();
		  		// $this->session->set_userdata('id',$get);
  				redirect('pegawai');
  			}
  			else{
  				echo "Username atau Password Salah";
  			}
  		}
  		$this->load->view('pegawai/login');
  	}

    public function loadRecord($rowno=0){

      // Row per page
      $rowperpage = 5;

      // Row position
      if($rowno != 0){
        $rowno = ($rowno-1) * $rowperpage;
      }
   
      // All records count
      $allcount = $this->Main_model->getrecordCount();

      // Get records
      $users_record = $this->Main_model->getData($rowno,$rowperpage);
   
      // Pagination Configuration
      $config['base_url'] = base_url().'index.php/User/loadRecord';
      $config['use_page_numbers'] = TRUE;
      $config['total_rows'] = $allcount;
      $config['per_page'] = $rowperpage;

      // Initialize
      $this->pagination->initialize($config);

      // Initialize $data Array
      $data['pagination'] = $this->pagination->create_links();
      $data['result'] = $users_record;
      $data['row'] = $rowno;
      echo json_encode($data);
  }
}

 ?>