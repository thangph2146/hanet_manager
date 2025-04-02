<?php

namespace App\Modules\sukien\Entities;

use CodeIgniter\Entity\Entity;

class SukienHistoryEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['thoi_gian_thay_doi', 'created_at', 'updated_at'];
    protected $casts   = [
        'id'                => 'int',
        'su_kien_id'        => 'int',
        'nguoi_thay_doi_id' => 'int',
        'version'           => 'int',
    ];
    
    /**
     * Lấy ID sự kiện
     */
    public function getSuKienId()
    {
        return $this->attributes['su_kien_id'] ?? 0;
    }
    
    /**
     * Đặt ID sự kiện
     */
    public function setSuKienId(int $suKienId)
    {
        $this->attributes['su_kien_id'] = $suKienId;
        
        return $this;
    }
    
    /**
     * Lấy ID người thay đổi
     */
    public function getNguoiThayDoiId()
    {
        return $this->attributes['nguoi_thay_doi_id'] ?? 0;
    }
    
    /**
     * Đặt ID người thay đổi
     */
    public function setNguoiThayDoiId(int $nguoiThayDoiId)
    {
        $this->attributes['nguoi_thay_doi_id'] = $nguoiThayDoiId;
        
        return $this;
    }
    
    /**
     * Lấy phiên bản sự kiện
     */
    public function getVersion()
    {
        return $this->attributes['version'] ?? 0;
    }
    
    /**
     * Đặt phiên bản sự kiện
     */
    public function setVersion(int $version)
    {
        $this->attributes['version'] = $version;
        
        return $this;
    }
    
    /**
     * Lấy thời gian thay đổi
     */
    public function getThoiGianThayDoi()
    {
        return $this->attributes['thoi_gian_thay_doi'] ?? null;
    }
    
    /**
     * Đặt thời gian thay đổi
     */
    public function setThoiGianThayDoi(string $thoiGianThayDoi)
    {
        $this->attributes['thoi_gian_thay_doi'] = $thoiGianThayDoi;
        
        return $this;
    }
    
    /**
     * Lấy dữ liệu thay đổi
     */
    public function getDataThayDoi()
    {
        return $this->attributes['data_thay_doi'] ?? '';
    }
    
    /**
     * Đặt dữ liệu thay đổi
     */
    public function setDataThayDoi(string $dataJson)
    {
        $this->attributes['data_thay_doi'] = $dataJson;
        
        return $this;
    }
    
    /**
     * Lấy mô tả thay đổi
     */
    public function getMoTaThayDoi()
    {
        return $this->attributes['mo_ta_thay_doi'] ?? '';
    }
    
    /**
     * Đặt mô tả thay đổi
     */
    public function setMoTaThayDoi(string $moTa)
    {
        $this->attributes['mo_ta_thay_doi'] = $moTa;
        
        return $this;
    }
    
    /**
     * Ghi lại lịch sử thay đổi sự kiện
     */
    public function luuLichSuThayDoi(SukienEntity $sukien, int $nguoiThayDoiId, string $moTa = '')
    {
        $this->setSuKienId($sukien->id);
        $this->setNguoiThayDoiId($nguoiThayDoiId);
        $this->setVersion($sukien->getVersion());
        $this->setThoiGianThayDoi(date('Y-m-d H:i:s'));
        $this->setDataThayDoi(json_encode($sukien->toArray()));
        $this->setMoTaThayDoi($moTa);
        
        return $this;
    }
    
    /**
     * Phân tích dữ liệu thay đổi và trả về mảng các thay đổi
     */
    public function phanTichThayDoi($dataThayDoiTruoc = null)
    {
        $thayDoi = [];
        $dataMoi = json_decode($this->getDataThayDoi(), true);
        
        if (!$dataMoi) {
            return $thayDoi;
        }
        
        // Nếu không có dữ liệu trước, chỉ trả về toàn bộ dữ liệu mới
        if (!$dataThayDoiTruoc) {
            return $dataMoi;
        }
        
        // Nếu có dữ liệu trước, so sánh và trả về thay đổi
        $dataCu = json_decode($dataThayDoiTruoc, true);
        
        foreach ($dataMoi as $key => $value) {
            if (!isset($dataCu[$key]) || $dataCu[$key] !== $value) {
                $thayDoi[$key] = [
                    'cu' => $dataCu[$key] ?? null,
                    'moi' => $value
                ];
            }
        }
        
        return $thayDoi;
    }
    
    /**
     * Mô tả thay đổi dưới dạng văn bản
     */
    public function moTaThayDoiDangVanBan($dataThayDoiTruoc = null)
    {
        $thayDoi = $this->phanTichThayDoi($dataThayDoiTruoc);
        $moTa = [];
        
        foreach ($thayDoi as $truong => $giaTri) {
            if (is_array($giaTri) && isset($giaTri['cu']) && isset($giaTri['moi'])) {
                $moTa[] = "Thay đổi $truong từ '{$giaTri['cu']}' thành '{$giaTri['moi']}'";
            } else {
                $moTa[] = "Thêm mới $truong: " . (is_array($giaTri) ? json_encode($giaTri) : $giaTri);
            }
        }
        
        return !empty($moTa) ? implode("; ", $moTa) : 'Không có thay đổi';
    }
}
