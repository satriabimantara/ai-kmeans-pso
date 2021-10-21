<?php
class Navbar_list_model
{
    private $data;
    public function __construct()
    {
        $this->data = array();
    }
    public function page_data()
    {
        $this->data['nav_list'] = array(
            'nav-item-1' => ' <li class="nav-item"><a class="nav-link" href="' . BASEURL . 'data/data_original">Data Asli</a></li>',
            'nav-item-2' => ' <li class="nav-item"><a class="nav-link" href="' . BASEURL . 'data/data_normalisasi">Data Hasil Normalisasi</a></li>',
        );
        return $this->data['nav_list'];
    }
    public function page_metode()
    {
        $this->data['nav_list'] = array(
            'nav-item-1' => ' <li class="nav-item"><a class="nav-link" data-toggle="modal" data-target="#modalCariKlusterOptimal" href="' . BASEURL . 'metode/">Cari Kluster Optimal</a></li>',
            'nav-item-2' => ' <li class="nav-item page_metode"><a class="nav-link" data-toggle="modal" data-target="#modalMetode" href="' . BASEURL . 'metode/pengujian">Metode</a></li>',
        );
        return $this->data['nav_list'];
    }
    public function page_pengujian()
    {
        $this->data['nav_list'] = array(
            'nav-item-1' => ' <li class="nav-item"><a class="nav-link page_pengujian" data-toggle="modal" data-target="#modalPengujianMetode" href="' . BASEURL . 'pengujian/">Uji Metode</a></li>',
        );
        return $this->data['nav_list'];
    }
}
