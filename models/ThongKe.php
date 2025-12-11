<?php

class ThongKe
{
    private $tongKhoahoc;
    private $tongHocvien;
    private $tongGiangvien;
    private $doanhthu;
    private $khoahocPhobien;

    public function __construct($tongKhoahoc = 0, $tongHocvien = 0, $tongGiangvien = 0, $doanhthu = 0, $khoahocPhobien = [])
    {
        $this->tongKhoahoc = $tongKhoahoc;
        $this->tongHocvien = $tongHocvien;
        $this->tongGiangvien = $tongGiangvien;
        $this->doanhthu = $doanhthu;
        $this->khoahocPhobien = $khoahocPhobien;
    }

    // Getter methods
    public function getTongKhoahoc()
    {
        return $this->tongKhoahoc;
    }

    public function getTongHocvien()
    {
        return $this->tongHocvien;
    }

    public function getTongGiangvien()
    {
        return $this->tongGiangvien;
    }

    public function getDoanhthu()
    {
        return $this->doanhthu;
    }

    public function getKhoahocPhobien()
    {
        return $this->khoahocPhobien;
    }

    // Setter methods
    public function setTongKhoahoc($tongKhoahoc)
    {
        $this->tongKhoahoc = $tongKhoahoc;
    }

    public function setTongHocvien($tongHocvien)
    {
        $this->tongHocvien = $tongHocvien;
    }

    public function setTongGiangvien($tongGiangvien)
    {
        $this->tongGiangvien = $tongGiangvien;
    }

    public function setDoanhthu($doanhthu)
    {
        $this->doanhthu = $doanhthu;
    }

    public function setKhoahocPhobien($khoahocPhobien)
    {
        $this->khoahocPhobien = $khoahocPhobien;
    }
}
