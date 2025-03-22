<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_nestedset {
    
    private $CI;
    
    public function __construct(){
        $this->CI =&get_instance();
    }
    
    // tự động tạo node gốc nếu là bảng trắng
    public function check_empty($table = '') {
        $count = $this->CI->db->from($table)->count_all_results();
        if($count == 0) {
            $post_data['title'] = 'Root';
            $post_data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            //$post_data['FK_id_user_created'] = $this->CI->auth['id'];
            $this->CI->db->insert($table, $post_data);
            //$this->CI->my_string->php_redirect(ITQ_BASE_URL.'backend/'.current(explode('_', $table)).'/add'.end(explode('_', $table)));
        }
    }
    
    // Mảng lựa chọn danh mục đổ xuống
    public function dropdown ($table = '', $param = null, $type = 'tag') {
        $temp = null;
        if($param == null) {
            $data = $this->CI->db->select('id, title, level')->from($table)->order_by('lft asc')->get()->result_array();
        }
        else {
            $data = $this->CI->db->select('id, title, level')->from($table)->where($param)->order_by('lft asc')->get()->result_array();
        }
        if(isset($data) && count($data)) {
            if($type == 'tag') {
                foreach($data as $key => $val) {
                    $temp[$val['id']] = str_repeat('|----- ', $val['level']).$val['title'];
                }
            }
            else if($type == 'item') {
                foreach($data as $key => $val) {
                    if($val['level'] == 0) {
                        $temp['0'] = '---';
                    }
                    else {
                        $temp[$val['id']] = str_repeat('|----- ', $val['level']).$val['title'];
                    }
                }
            }
        }
        return $temp;
    }
    
    // mảng dữ liệu để hiển thị danh sách
    public function data($table = '', $data = '', $param = null) {
        if($param == null) {
            $data = $this->CI->db->from($table)->order_by('order asc, lft asc')->get()->result_array();
        }
        else {
            $data = $this->CI->db->from($table)->where($param)->order_by('order asc, lft asc')->get()->result_array();
        }
        return $data;
    }
    
    // mảng dữ liệu
    public function arr($table = '') {
        return $this->CI->db->select('id, title, parentid, level, order')->from($table)->order_by('order asc, id asc')->get()->result_array();
    }
    
    // chi tiết
    public function get($table = '', $param = null) {
        return $this->CI->db->select('id, title, parentid, level, order')->from($table)->where($param)->get()->row_array();
    }
    
    // đệ quy
    public function recursive($id = 0, $arr = null, $tree = null) {
        foreach($arr as $val) {
            if($val['parentid'] == $id) {
                $tree[] = $val;
                $tree = $this->recursive($val['id'], $arr, $tree);
            }
        }
        return $tree;
    }
    
    // set level
    public function level($table = '') {
        $data = $this->CI->my_nestedset->recursive(0, $this->CI->my_nestedset->arr($table));
        if(isset($data) && count($data)) {
            // duyệt tuần tự từ trên xuống theo mảng đệ quy
            foreach($data as $key => $val) {
                // nếu là node Root
                if($val['parentid'] == 0) {
                    $level = 0;
                }
                // nếu không phải node Root thì lấy level của cấp cha đó + thêm 1
                else if($val['parentid'] > 0) {
                    $parent = $this->CI->my_nestedset->get($table, array('id' => $val['parentid']));
                    $level = ($parent['level'] + 1);
                }
                $this->CI->db->where('id', $val['id'])->update($table, array('level' => $level));
            }
        }
    }
    
    // tạo left right
    public function lftrgt($table = '') {
        $data = $this->CI->my_nestedset->recursive(0, $this->CI->my_nestedset->arr($table));
        if(isset($data) && count($data)) {
            $i = 0;
            $max = null;
            $flag = 0;
            foreach($data as $key => $val) {
                // tổng số node ocn của node
                $countSubItem = count($this->recursive($val['id'], $data));
                // các node đầu tiên trong level
                if(!isset($max[$val['level']])) {
                    $left = $i;
                    $right = ($countSubItem * 2) + 1 + $i;
                    $max[$val['level']] = $right;
                    if($left + 1 == $right) {
                        $flag = 1;
                    }
                    else {
                        $i++;
                    }
                }
                else {
                    // các node được duyệt ngay sau khi node lá
                    if($flag == 1){
                        $flag = 0;
                        $i = $max[$val['level']] + 1;
                        $left = $i;
                        $right = ($countSubItem * 2) + 1 + $i;
                        $max[$val['level']] = $right;
                        if($left + 1 == $right) {
                            $flag = 1;
                        }
                        else{
                            $i++;
                        }
                    }
                    else{
                        $left = $i;
                        $right = ($countSubItem * 2) + 1 + $i;
                        $max[$val['level']] = $right;
                        if($left + 1 == $right){
                            $flag = 1;
                        }
                        else{
                            $i++;
                        }
                    }
                }
                $this->CI->db->where('id', $val['id'])->update($table, array('lft' => $left, 'rgt' => $right));
            }
        }
    }
    
    //
    public function set($table = ''){
        $this->CI->my_nestedset->level($table);
        $this->CI->my_nestedset->lftrgt($table);
    }
    
    public function check_parentid($table = '', $parentid = 0, $catid =0){
        if($parentid == $catid){
            $this->CI->form_validation->set_message('_parentid', 'cant choose itself.');
            return false;
        }
        $data = $this->CI->db->select('lft, rgt')->from($table)->where(array('id' => $catid))->get()->row_array();
        if(isset($data) && count($data)){
            $children = $this->CI->db->select('id')->from($table)->where(array('lft >' => $data['lft'], 'lft <' => $data['rgt']))->get()->result_array();
            if(isset($children) && count($children)){
                foreach($children as $key => $val){
                    if($parentid == $val['id']){
                        $this->CI->form_validation->set_message('_parentid','cant choose itself is parent.');
                        return false;
                    }
                }
            }
        }
        else{
            $this->CI->form_validation->set_message('_parentid', 'parent tag isnt exist.');
            return false;
        }
        return true;
    }
    
    // danh sách node con
    public function children($table = '', $param = null){
        $temp = null;
        $children = $this->CI->db->select('id')->from($table)->where($param)->get()->result_array();
        if(isset($children) && count($children)){
            foreach($children as $key => $val){
                $temp[] = $val['id'];
            }
        }
        return $temp;
    }
}