<?php
class addur extends DAO {
    
    private static $instance ;
    
    public static function newInstance() {
        if( !self::$instance instanceof self ) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }
    
    function __construct() {       
        $this->_sect = 'plugin_adduration';
        $this->_table = '`'.DB_TABLE_PREFIX.'t_item_durations`';
        $this->_table_item = '`'.DB_TABLE_PREFIX.'t_item`';
        $this->_table_category = '`'.DB_TABLE_PREFIX.'t_category`';
        
        parent::__construct();
    }
    
    function _install() {
        
        $file = osc_plugin_resource('ad_duration/assets/create_table.sql');
        $sql = file_get_contents($file);

        if (!$this->dao->importSQL($sql)) {
            throw new Exception( "Error importSQL::addur<br>".$file ) ;
        }
            
        $opts = $this->_opt();        
        foreach ($opts AS $k => $v) {
            osc_set_preference($k, $v[0], $this->_sect, $v[1]);
        }
        return true;            
    }
    
    function _uninstall() {                
        Preference::newInstance()->delete(array("s_section" => $this->_sect));            
        $this->dao->query(sprintf('DROP TABLE %s', $this->_table));    
    }
    
    function _opt() {                
        $opts = array(
            'activated' => array('1', 'BOOLEAN'),
            'block_selected' => array('1', 'BOOLEAN'),
            'durations' => array('1,3,7,14,30', 'STRING')
        );
        
        return $opts;
    }

    function _get($opt = 'durations') {        
        return osc_get_preference($opt, $this->_sect);
    }

    function _save($data) {
        foreach($data as $k => $v) {
            $type = ($k == 'durations' ? 'STRING' : 'BOOLEAN');
            if (!osc_set_preference($k, $v, $this->_sect, $type)) {
                return false;
            }    
        }
        return true;
    }

    function _form($data = false) {
        require_once(osc_plugin_path(dirname(dirname(__FILE__)).'/views/form.php'));
    }
    
    function _saveData($data) {
        $days = $this->_checkDays($data['catId'], $data['ads_duration']);
        
        if ($data['action'] == 'item_edit_post') {
            $id = $data['id'];    
        } elseif ($data['action'] == 'item_add_post') {
            $id = $data['itemId'];    
        }
        if ($days != '-1') {
            if ($days == 'infinity') {
                $date = '9999-12-31 23:59:59';
            } else {
                $date = date('Y-m-d H:i:s', time()+($days*24*3600));
            }
                
            if (!$this->dao->update($this->_table_item, array('dt_expiration' => $date), array('pk_i_id' => $id))) {
                return false;
            }    
        }
        
        
        return true;    
    }
    
    function _hasDuration($id) {
        $this->dao->select('*');
        $this->dao->from($this->_table_category);
        $this->dao->where('fk_i_item_id', $id);

        $result = $this->dao->get();
        if (!$result) { return false; }
        
        return $result->row();    
    }
    
    function _checkDays($cat, $days) {

        $this->dao->select('i_expiration_days');
        $this->dao->from($this->_table_category);
        $this->dao->where('pk_i_id', $cat);

        $result = $this->dao->get();
        if (!$result) { return false; }
        
        $row = $result->row();
        
        $catDays = $row['i_expiration_days'];
        $allowed = explode(",", osc_get_preference('durations', $this->_sect));
        
        if ($days == '0' && $catDays == '0') {
            return 'infinity';
        } elseif (!is_numeric($days) || $days < '0') {
            return '-1';
        } elseif ($days > $catDays && $catDays != '0') {
            return $catDays;
        } elseif (!in_array($days, $allowed)) {
            return $catDays;
        }
        
        return $days;
    }    
}
?>
