<?php
echo "111";

	// 版本更改channelController

	class Channel extends Model
	{
	    protected $table = 'channel';
	    public static function detail($id) {
		$channel = DB::table('channel')->where('channel_id', $id)->get();
		return $channel;
	    }
	    public static function detail_one($id,$version) {
	    	$channel = DB::table('channel')->where('channel_id', $id)->where('version',$version)->first();
	    	return $channel;
	    }
	}



	public function getDevByChannelId(Request $request) {
		$id = (int)$request->get('channel_id');
		$res = Channel::detail($id);		
		$version = $request->header('version');

		if($res) {
			$a=[];
			foreach ($res as $key => $val) {
				$a[]= $val->version;
			}
			$version1=max($a);			
			$res_one = Channel::detail_one($id,$version1);   // 查取单条数据
			
		    $is_ios = 0;	
		    $version2=$this->versionCompare($version,">=",$version1);

		    if ($version2) {
		    	$is_ios = 1;
		    }

		    $data = ['ios'=>$is_ios, 'android'=>$res_one->android, 'h5'=>$res_one->h5,'is_use'=>$res_one->is_use, 'list'=>[]];
		} else {
		    $data = ['ios'=>$res_one->ios, 'android'=>$res_one->android, 'h5'=>$res_one->h5,'is_use'=>$res_one->is_use, 'list'=>[]];
		}
		return $this->response->array(self::returnValue($data));	
    }


?>