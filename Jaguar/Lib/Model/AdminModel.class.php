<?php
/**
 * Admin 管理后台基础模型
 */
class AdminModel extends Model {
	protected  $autoCheckFields = false;
    //修改权限    
    public function updateRole($ma_arr){
    	if($ma_arr['fag']){
    		return $this->query('update '.C('DB_PREFIX')."users_roles set ma_ids='$ma_arr[ma_ids]' where ur_id=$ma_arr[ur_id]");
    	}else{
    		if('add'==$ma_arr['act']){
    			return $this->query('insert into  '.C('DB_PREFIX')."users_roles (ur_name,ur_url,ur_lock,ma_ids,sc_ids,HC_SMP)values('$ma_arr[ur_name]','$ma_arr[ur_url]','$ma_arr[ur_lock]','','','$ma_arr[hc_smp]')");
    		}else if('mr'==$ma_arr['act']){
    			return $this->query('update '.C('DB_PREFIX')."users_roles set sc_ids='$ma_arr[sc_ids]' where ur_id=$ma_arr[ur_id]");
    		}else{
    			return $this->query('update '.C('DB_PREFIX')."users_roles set HC_SMP='$ma_arr[hc_smp]',ur_name='$ma_arr[ur_name]',ur_url='$ma_arr[ur_url]',ur_lock='$ma_arr[ur_lock]' where ur_id=$ma_arr[ur_id]");
    		}
    	}
    }
    
    //获取权限
    public function getOneRole($rid){
        $resut = $this->fquery("select * from " . C('DB_PREFIX') . "users_roles where ur_id = ?",array($rid));
    	return $resut[0];
    } 

    //获取权限数据
    public function getMAList(){
    	$pri_arr=array();
    	$m_list = $this->query("select m_code,m_name,m_desc from " . C('DB_PREFIX') . "models");
    	$ma_list = $this->query("select ma.ma_id,m.m_code,m.m_code||'_'||ma.ma_id as maid,m.m_name||'@@'||ma.ma_action as ma_name,ma.ma_desc from ".C('DB_PREFIX')."model_action ma left join ".C('DB_PREFIX')."models m on ma.m_code=m.m_code");
    	foreach($m_list as $m){
    		foreach($ma_list as $ma){
    			if($ma['m_code']==$m['m_code']){
    				$pri_arr[$m['m_code']]['m_code']=$m['m_code'];
    				$pri_arr[$m['m_code']]['ma_ids'][]=$ma;
    			}
	    	}
    	}
    	return array('m_list'=>$m_list,'ma_list'=>$pri_arr);
    }
    
    /**
    * 获取列表
    * @access public
    * @param array $array 查询条件数组
    * @param array $array2 查询条件数组
    * @return array
    */
    public function getList($array,$array2){
    	return array('data'=>$this->select($array),'count'=>$this->select($array2));
    }
    
    /**
     * 保存数据字典
     * @param int $type 0为数据字典组，1为数据字典项，默认为0
     * @param array $array 保存的数据
     */
    public function save_dic($array,$type='0'){
    	if('1'==$type){
    		$dic_group = $this->get_dic('2',$array['dataitem_id']);
    		if($dic_group){
    			return $this->fquery('update '.C('DB_PREFIX').'datadic_items set dataitem_name=? ,dataitem_code=? , group_code=?  where dataitem_id=?',array($array['dataitem_name'],$array['dataitem_code'],$array['group_code'],$array['dataitem_id']));
    		}else{
    			return $this->fquery('insert into '.C('DB_PREFIX').'datadic_items(dataitem_code,dataitem_name,group_code) values(?,?,?)',array($array['dataitem_code'],$array['dataitem_name'],$array['group_code']));
    		}
    	}else{
    		$dic_group = $this->get_dic($type='1',$array['group_id']);
    		if($dic_group){
    			return $this->fquery('update '.C('DB_PREFIX').'datadic_groups set group_name=?,group_code=? where group_id=?',array($array['group_name'],$array['group_code'],$array['group_id']));
    		}else{
    			return $this->fquery('insert into '.C('DB_PREFIX').'datadic_groups(group_code,group_name) values(?,?)',array($array['group_code'],$array['group_name']));
    		}
    	}
    }
    
    /**
     * 获得字典项数据
     * @param int $type 0为数据字典集，1为字典组，2为字典项
     * @param string $code 查询的编码值
     */
    public function get_dic($type='0',$code){
    	if('1'==$type && isset($code)){
    		return $this->fquery('select * from '.C('DB_PREFIX').'datadic_groups where group_id=?', array($code));
    	}else if('2'==$type && isset($code)){
    		return $this->fquery('select * from '.C('DB_PREFIX').'datadic_items where dataitem_id=?', array($code));
    	}else if('3'==$type && isset($code)){
    		return $this->fquery('select * from '.C('DB_PREFIX').'datadic_items where group_code=?', array($code));
    	}else if('1'==$type){
    		$dic_group = RE('dic_group');
    		if(empty($dic_group)){
    			$dic_group = $this->fquery('select * from '.C('DB_PREFIX').'datadic_groups order by group_code asc');
    			RE('dic_group',$dic_group);
    		}
    		return $dic_group;
    	}else{
    		return $this->fquery('SELECT group_name,dataitem_code,dataitem_name from '.C('DB_PREFIX').'datadic_items i left join '.C('DB_PREFIX').'datadic_groups g on i.group_code=g.group_code order by i.dataitem_code asc');
    	}
    }
    
    /*
     * 修改状态
     */    
    public function save_dic_status($array){
        return $this->fquery('update '.C('DB_PREFIX').'datadic_items set is_show=? where dataitem_id=?',array($array['is_show'],$array['dataitem_id']));
    }
    
    //执行sql命令
    public function exec_sql($sql,$type=true){
    	$sql = str_replace('&lt;','<',str_replace('&gt;','>',$sql));
    	return $this->fquery($sql);
    }
}