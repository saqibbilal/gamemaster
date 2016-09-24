<?php

class User_model extends CI_Model {

    public function getUserById($userId, $fields = '*' ,$type = '') {
//        $where = 1;
//        if($type !=''){
//            $where = " AND user_types_id = '".$this->db->escape_str($type)."'";
//        }
        $sqlQuery = "SELECT " . $fields . " FROM users WHERE user_id = '" . $this->db->escape_str($userId) . "'";
        $sqlResult = $this->db->query($sqlQuery)->row_array();
        return $sqlResult;
    }
    public function getUserByEmail($userEmail, $fields='*'){
		$sqlQuery = "SELECT ".$fields." FROM users WHERE email = '".$this->db->escape_str($userEmail)."'";
		$sqlResult = $this->db->query($sqlQuery)->row_array();
		return $sqlResult;
	}

    public function getUsersCount($search_term = '') {
//    public function getUsersCount($search_term = '', $user_types = 1) {
        $whereClause = " 1 = 1";
//        $whereClause .= " AND user_types_id = " . $this->db->escape_str($user_types);

        if (trim($search_term) != '' && $search_term != 'all') {
            $whereClause .= " AND ( username LIKE '%" . $this->db->escape_str($search_term) . "%' OR full_name LIKE '%" . $this->db->escape_str($search_term) . "%') ";
//            $whereClause .= " AND ( username LIKE '%" . $this->db->escape_str($search_term) . "%' OR full_name LIKE '%" . $this->db->escape_str($search_term) . "%' OR email LIKE '" . $this->db->escape_str($search_term) . "') ";
        }

        $sqlQuery = "SELECT COUNT(user_id) as CNT_ FROM users WHERE " . $whereClause . "";
        
        $sqlResults = $this->db->query($sqlQuery)->row_array();
        $totalUsers = $sqlResults['CNT_'];
        return $totalUsers;
    }

    public function getUsers( $pageNum = 1, $perPage = 10, $search_term = '') {
//    public function getUsers($user_types = 1, $pageNum = 1, $perPage = 10, $search_term = '') {
        $whereClause = " 1 = 1";
//        $whereClause .= " AND user_types_id = " . $this->db->escape_str($user_types);

        if (trim($search_term) != '' && $search_term != 'all') {
            $whereClause .= " AND ( username LIKE '%" . $this->db->escape_str($search_term) . "%' OR full_name LIKE '%" . $this->db->escape_str($search_term) . "%' ) ";
//            $whereClause .= " AND ( username LIKE '%" . $this->db->escape_str($search_term) . "%' OR full_name LIKE '%" . $this->db->escape_str($search_term) . "%' OR email LIKE '" . $this->db->escape_str($search_term) . "') ";
        }
        $sqlQuery = "SELECT * FROM users WHERE " . $whereClause . "  ORDER BY user_id ASC LIMIT " . ( ($pageNum - 1) * $perPage ) . ", " . $perPage . "";
        $sqlResults = $this->db->query($sqlQuery)->result_array();
        return $sqlResults;
    }

    public function addUser($userArray) {
        $userArray['created'] = date('Y-m-d H:i:s', time());
        $this->db->insert('users', $userArray);
        return $this->db->insert_id();
    }

    public function addUserTransaction($userTranArray) {
        $this->db->insert('user_transactions', $userTranArray);
        return $this->db->insert_id();
    }

    public function updateUser($userArray, $userID) {
        $userArray['updated'] = date('Y-m-d H:i:s', time());
        $this->db->where('user_id', $userID);
        $this->db->update('users', $userArray);
    }

    public function validateUserLogin($email, $userPassword) {
        $sqlQuery = "SELECT user_id, status,user_types_id FROM users WHERE email = '" . $this->db->escape_str($email) . "' AND password = '" . $this->db->escape_str(md5($userPassword)) . "'";
        $sqlResult = $this->db->query($sqlQuery)->row_array();
        return $sqlResult;
    }

    public function getUserTypes() {

        $sqlQuery = "SELECT user_types_id,Title FROM user_types ";
        $sqlResults = $this->db->query($sqlQuery)->result_array();
        return $sqlResults;
    }

    public function getUserPlans() {

        $sqlQuery = "SELECT id,title,amount FROM user_plans ";
        $sqlResults = $this->db->query($sqlQuery)->result_array();
        return $sqlResults;
    }

    public function getUserPlanById($id) {

        $sqlQuery = "SELECT id,title,amount FROM user_plans WHERE id= " . $this->db->escape_str($id);
        $sqlResults = $this->db->query($sqlQuery)->row_array();
        return $sqlResults;
    }

    public function getUserNetwork($user_id, $social_networks_id) {
        $sqlQuery = "SELECT * FROM user_networks WHERE users_id = " . $this->db->escape_str($user_id) . " AND social_networks_id= " . $this->db->escape_str($social_networks_id);
        $sqlResults = $this->db->query($sqlQuery)->row_array();
        return $sqlResults;
    }

    public function getUserNetworkByNetworkID($social_networks_id, $network_id) {
        $sqlQuery = "SELECT * FROM user_networks WHERE social_networks_id = " . $this->db->escape_str($social_networks_id) . " AND network_id= " . $this->db->escape_str($network_id);
        $sqlResults = $this->db->query($sqlQuery)->row_array();
        return $sqlResults;
    }

    public function addUserNetwork($user_network) {
        $user_network['created'] = date('Y-m-d H:i:s', time());
        $this->db->insert('user_networks', $user_network);
        return $this->db->insert_id();
    }

    public function getUserStats($statsData, $date = '') {
        $whereClause = " 1 = 1";
        if ($statsData['user_id']) {
            $whereClause .= " AND user_id = " . $this->db->escape_str($statsData['user_id']);
        }
        if ($statsData['user_networks_id']) {
            $whereClause .= " AND user_networks_id = " . $this->db->escape_str($statsData['user_networks_id']);
        }
        if ($statsData['post_feeds_id']) {
            $whereClause .= " AND post_feeds_id = " . $this->db->escape_str($statsData['post_feeds_id']);
        }
        if ($date != '') {
            $whereClause .= " AND DATE_FORMAT( `created` , '%Y-%m-%d' ) = '" . $this->db->escape_str($date) . "'";
        }

        $sqlQuery = "SELECT * FROM user_stats WHERE " . $whereClause." ";

        $sqlResult = $this->db->query($sqlQuery)->row_array();
        return $sqlResult;
    }

    public function addUserStats($statsData) {
        $statsData['created'] = date('Y-m-d H:i:s', time());
        $this->db->insert('user_stats', $statsData);
        return $this->db->insert_id();
    }

    public function updateUserStats($statsData, $user_stats_id) {
        $this->db->where('user_stats_id', $user_stats_id);
        $this->db->update('user_stats', $statsData);
    }

    public function getPostFeedsByPlatform($social_networks_id = 1) {
        $sqlQuery = "SELECT pf.*,un.network_username
            FROM post_feeds pf 
            LEFT JOIN user_networks un ON  pf.user_networks_id = un.user_networks_id 
            WHERE un.social_networks_id = '" . $social_networks_id . "'  order by pf.post_feeds_id desc ";

        $sqlResult = $this->db->query($sqlQuery)->result_array();
        return $sqlResult;
    }
    public function getPostFeedAnalyticsByUserId($user_id,$social_networks_id) { 
        $sqlQuery = "SELECT pf.photo,SUM(us.like_count) AS like_count,SUM(us.comment_count) AS comment_count ,SUM(us.share_count) AS share_count
            FROM post_feeds pf 
            JOIN user_networks un ON  pf.user_networks_id = un.user_networks_id 
            JOIN user_stats us ON us.post_feeds_id = pf.post_feeds_id 
            WHERE un.social_networks_id = '" . $social_networks_id . "' AND pf.users_id = '".$user_id."' ";
        $sqlResult = $this->db->query($sqlQuery)->result_array();
        return $sqlResult;
    }
    public function getPostFeedsById($post_feeds_id) {
        $sqlQuery = "SELECT pf.*,un.network_token,un.oauth_secret,un.network_username
            FROM post_feeds pf 
            JOIN user_networks AS un ON un.user_networks_id = pf.user_networks_id 
            WHERE pf.post_feeds_id = '" . $post_feeds_id . "'  ";
        $sqlResult = $this->db->query($sqlQuery)->row_array();
        return $sqlResult;
    }
    public function getUserStatsByPlatForm($social_networks_id = 1) {
        $sqlQuery = "SELECT SUM(us.like_count) AS like_count ,SUM(us.comment_count) AS comment_count,SUM(us.share_count) AS share_count,us.created
                     FROM user_stats us
                     LEFT JOIN user_networks un ON  un.user_networks_id = us.user_networks_id 
                     WHERE un.social_networks_id = '" . $social_networks_id . "'  GROUP BY DATE_FORMAT(us.created,'%Y-%m-%d') ";
        
        $sqlResult = $this->db->query($sqlQuery)->result_array();
        return $sqlResult;
    }
    
     public function getPhotoById($teamId, $fields = '*') {
        $sqlQuery = "SELECT " . $fields . " FROM photos WHERE photos_id = '" . $this->db->escape_str($teamId) . "'";
        $sqlResult = $this->db->query($sqlQuery)->row_array();
        return $sqlResult;
    }

    public function getPhotoByUserId($userId, $pageNum = 1, $perPage = 10, $fields = '*') {
        $sqlQuery = "SELECT " . $fields . " FROM photos WHERE users_id = '" . $this->db->escape_str($userId) . "' LIMIT " . ( ($pageNum - 1) * $perPage ) . ", " . $perPage . "";
        $sqlResult = $this->db->query($sqlQuery)->result_array();
        return $sqlResult;
    }

    public function getPhotoCountByUserId($userId) {
        $whereClause = " 1 = 1";
        $whereClause .= " AND users_id = '" . $this->db->escape_str($userId) . "'";
        $sqlQuery = "SELECT COUNT(photos_id) as CNT_ FROM photos WHERE " . $whereClause . "";
        $sqlResults = $this->db->query($sqlQuery)->row_array();
        $totalAdmins = $sqlResults['CNT_'];
        return $totalAdmins;
    }

    public function addPhoto($teamArray) {
        $teamArray['created'] = date('Y-m-d H:i:s', time());
        $this->db->insert('photos', $teamArray);
        return $this->db->insert_id();
    }

    public function updatePhoto($teamArray, $teamID) {
        $teamArray['updated'] = date('Y-m-d H:i:s', time());
        $this->db->where('photos_id', $teamID);
        $this->db->update('photos', $teamArray);
    }
    
    public function delete_photo($photoId){
        
        $this->db->where('photos_id', $photoId);
        $this->db->delete('photos');
        
    }
    
    
     public function addAmbassadorBrand($ambassadorBrandArray) {
        $ambassadorBrandArray['created'] = date('Y-m-d H:i:s', time());
        $this->db->insert('ambassador_brand', $ambassadorBrandArray);
        return $this->db->insert_id();
    }
    
    public function getAllAmbassadorsByBrandID($brand_id,$accepted=0) {
        $where  = ''; 
        if($accepted == 1){
            $where  = ' AND ab.accepted = 1'; 
        }
        $sqlQuery = "SELECT u.*,ab.accepted FROM users AS u 
                    JOIN ambassador_brand ab ON ab.ambassador_id = u.user_id
                    WHERE u.user_types_id = 1 $where AND ab.brand_id = '".$brand_id."' and ab.is_remove=0 ";
        
        $sqlResult = $this->db->query($sqlQuery)->result_array();
        return $sqlResult;
    }
    
    public function getAllAmbassadorsByBrandIDAll($brand_id,$accepted=0) {
        $where  = ''; 
        if($accepted == 1){
            $where  = ' AND ab.accepted = 1'; 
        }
        $sqlQuery = "SELECT u.*,ab.accepted FROM users AS u 
                    JOIN ambassador_brand ab ON ab.ambassador_id = u.user_id
                    WHERE u.user_types_id = 1 $where AND ab.brand_id = '".$brand_id."'  ";
        
        $sqlResult = $this->db->query($sqlQuery)->result_array();
        return $sqlResult;
    }
    
     public function getAllAmbassadorsHaveNotBrand() {
        $sqlQuery = "SELECT * FROM users 
                     WHERE user_id NOT IN (
                     SELECT ab.ambassador_id 
                    FROM ambassador_brand ab JOIN users u ON u.user_id = ab.ambassador_id 
                    WHERE ab.brand_id=48)
                    AND user_types_id = 1";
        $sqlResult = $this->db->query($sqlQuery)->result_array();
        return $sqlResult;
    }
     public function isAmbassadorAssociatedToBrand($id) {
        $sqlQuery = "SELECT * FROM users 
                    WHERE user_id NOT IN 
                        (SELECT ab.ambassador_id 
                            FROM ambassador_brand ab 
                            JOIN users u ON u.user_id = ab.ambassador_id 
                            WHERE ab.accepted = 1 and ab.is_remove=0 ) AND user_types_id = 1  AND user_id = '".$id."' ";
        $sqlResult = $this->db->query($sqlQuery)->result_array();
        return $sqlResult;
    }
    
    
    public function getAmbassadorStats($userID) {
        $sqlQuery = "SELECT SUM(us.like_count) AS like_count ,SUM(us.comment_count) AS comment_count, SUM(us.share_count) AS share_count,un.social_networks_id
                    FROM user_stats  AS us
                    JOIN user_networks AS un ON un.user_networks_id = us.user_networks_id
                    WHERE user_id = '".$userID."' 
                    GROUP BY un.social_networks_id";
        $sqlResult = $this->db->query($sqlQuery)->result_array();
        return $sqlResult;
    }
     public function getAllAmbassadorsByTeamID($team_id) {
        $sqlQuery = "SELECT u.*,t.team_members_id FROM users AS u 
                    join team_members t on t.user_id = u.user_id 
                    WHERE u.user_types_id = 1 AND t.team_id = '".$team_id."' and t.is_remove = '0'";
        $sqlResult = $this->db->query($sqlQuery)->result_array();
        return $sqlResult;
    }
     public function getCMSPageByType($pageType, $fields = '*') {
        $sqlQuery = "SELECT " . $fields . " FROM cms_pages WHERE type = '" . $this->db->escape_str($pageType) . "'";
        $sqlResult = $this->db->query($sqlQuery)->row_array();
        return $sqlResult;
    }
     public function getNotificationByUserId($userId, $fields = '*') {
        $sqlQuery = "SELECT " . $fields . " FROM user_notification_settings WHERE users_id = '" . $this->db->escape_str($userId) . "'";
        $sqlResult = $this->db->query($sqlQuery)->row_array();
        return $sqlResult;
    }
    public function addNotification($notificationArray) {
        $notificationArray['created'] = date('Y-m-d H:i:s', time());
        $this->db->insert('user_notification_settings', $notificationArray);
        return $this->db->insert_id();
    }
    public function updateNotification($notificationArray, $userID) {
        $notificationArray['updated'] = date('Y-m-d H:i:s', time());
        $this->db->where('users_id', $userID);
        $this->db->update('user_notification_settings', $notificationArray);
    }
    
    public function stats($user_id,$use_network_id = 0,$brand_id=1) {

		if(!$use_network_id)
		{
			/*$this->db->where('user_id', $user_id);
			$query = $this->db->get('user_stats');
			return $query->result_array();*/
			
			$query = 'SELECT * from user_stats where user_stats_id IN ( SELECT max(user_stats_id) FROM `user_stats` where `user_id` = '.$user_id.' 
                and post_feeds_id in(  
                SELECT pf.post_feeds_id FROM post_feeds pf 
                JOIN campaigns c ON pf.campaigns_id = c.campaigns_id
                WHERE c.user_id = "'.$brand_id.'" AND pf.users_id = "'.$user_id.'"
              ) group by post_feeds_id ) ';	
			
			$query = $this->db->query($query);
			return $query->result_array();

		}else
		{
			$query = 'SELECT * from user_stats where user_stats_id IN ( SELECT max(user_stats_id) FROM `user_stats` 
             where `user_networks_id` = '.$use_network_id.'  and post_feeds_id in(  
                SELECT pf.post_feeds_id FROM post_feeds pf 
                JOIN campaigns c ON pf.campaigns_id = c.campaigns_id
                WHERE c.user_id = "'.$brand_id.'" AND pf.users_id = "'.$user_id.'"
              ) 
             group by post_feeds_id ) ';	
			$query = $this->db->query($query);
			return $query->result_array();
		}
		
		
    }

    public function posts($user_id) {
        $this->db->where('users_id', $user_id);
        $this->db->from('post_feeds');
        $query = $this->db->count_all_results();
        return $query;
    } 
    
    public function postsNew($user_id,$brand_id) {
        $sql ="SELECT pf.* FROM post_feeds pf
          join campaigns c on c.campaigns_id=pf.campaigns_id
          WHERE pf.users_id='".$user_id."' and c.user_id='".$brand_id."'  ";
        $query = $this->db->query($sql)->result();
        return count($query);  
    }
    
    public function posts_amb($user_id,$pf_id) {
        $this->db->where('users_id', $user_id);
        $this->db->where('post_feeds_id', $pf_id);
        $this->db->from('post_feeds');
        $query = $this->db->count_all_results();
        return $query;
    }
    
    
    public function getAmbassadors($userID,$accepted=1){
        $this->db->where('brand_id',$userID);
		$this->db->where('accepted',$accepted);
        return $this->db->get('ambassador_brand')->result_array();
        
    }
    
    
    // messages 
    
    
    public function getInboxCount($receiverID, $recieverType) {
        $totalMessages = 0;
        $sqlQuery = "SELECT DISTINCT(CASE WHEN m.parent_id = 0 THEN m.id ELSE m.parent_id END) as messageID FROM messages m WHERE m.to_id = " . $receiverID . " AND receiver_type = '" . $recieverType . "' AND m.to_status <> 'deleted'";
        $sqlResults = $this->db->query($sqlQuery)->result('array');
        if ($sqlResults) {
            $ids = '';
            foreach ($sqlResults as $result) {
                $ids .= (($ids == '') ? '' : ',') . $result['messageID'];
            }
            $sqlQuery = "SELECT COUNT(m.id) as CNT_ FROM messages m WHERE m.id IN (" . $ids . ") AND m.to_status <> 'deleted'";
            $sqlResults = $this->db->query($sqlQuery)->row_array();
            $totalMessages = $sqlResults['CNT_'];
        }
        return $totalMessages;
    }

    public function getOutboxCount($senderID, $senderType) {
        $totalMessages = 0;
        $sqlQuery = "SELECT DISTINCT(CASE WHEN m.parent_id = 0 THEN m.id ELSE m.parent_id END) as messageID FROM messages m WHERE m.from_id = " . $senderID . " AND sender_type = '" . $senderType . "' AND m.from_status <> 'deleted'";
        $sqlResults = $this->db->query($sqlQuery)->result('array');
        if ($sqlResults) {
            $ids = '';
            foreach ($sqlResults as $result) {
                $ids .= (($ids == '') ? '' : ',') . $result['messageID'];
            }
            $sqlQuery = "SELECT COUNT(m.id) as CNT_ FROM messages m WHERE m.id IN (" . $ids . ") AND m.from_status <> 'deleted'";
            $sqlResults = $this->db->query($sqlQuery)->row_array();
            $totalMessages = $sqlResults['CNT_'];
        }
        return $totalMessages;
    }

    public function getDeletedCount($userID, $userType) {
        $totalMessages = 0;
        $sqlQuery = "SELECT DISTINCT(CASE WHEN m.parent_id = 0 THEN m.id ELSE m.parent_id END) as messageID FROM messages m WHERE (m.from_id = " . $userID . " OR m.to_id = " . $userID . ") AND ( receiver_type = '" . $userType . "' OR sender_type = '" . $userType . "') AND (m.to_status = 'deleted' OR m.from_status = 'deleted')";
        $sqlResults = $this->db->query($sqlQuery)->result('array');
        if ($sqlResults) {
            $ids = '';
            foreach ($sqlResults as $result) {
                $ids .= (($ids == '') ? '' : ',') . $result['messageID'];
            }
            $sqlQuery = "SELECT COUNT(m.id) as CNT_ FROM messages m WHERE m.id IN (" . $ids . ") AND (m.to_status = 'deleted' OR m.from_status = 'deleted')";
            $sqlResults = $this->db->query($sqlQuery)->row_array();
            $totalMessages = $sqlResults['CNT_'];
        }
        return $totalMessages;
    }

    public function getInboxMessages($receiverID, $recieverType, $pageNum = 1, $perPage = 3) {
        $messages = array();
       $sqlQuery = "SELECT DISTINCT(CASE WHEN m.parent_id = 0 THEN m.id ELSE m.parent_id END) as messageID FROM messages m WHERE m.to_id = " . $receiverID . " AND receiver_type = '" . $recieverType . "' AND m.to_status <> 'deleted'";
        $sqlResults = $this->db->query($sqlQuery)->result('array');
        if ($sqlResults) {
            $ids = '';
            foreach ($sqlResults as $result) {
                $ids .= (($ids == '') ? '' : ',') . $result['messageID'];
            }
        $sqlQuery = "SELECT m.* FROM messages m WHERE m.id IN (" . $ids . ") AND m.to_status <> 'deleted' ORDER BY m.id DESC LIMIT " . ( ($pageNum - 1) * $perPage ) . ", " . $perPage . "";
            $messages = $this->db->query($sqlQuery)->result('array');
        }
        return $messages;
    }

    public function getOutboxMessages($senderID, $senderType, $pageNum = 1, $perPage = 3) {
        $messages = array();
        $sqlQuery = "SELECT DISTINCT(CASE WHEN m.parent_id = 0 THEN m.id ELSE m.parent_id END) as messageID FROM messages m WHERE m.from_id = " . $senderID . " AND sender_type = '" . $senderType . "' AND m.from_status <> 'deleted'";
        $sqlResults = $this->db->query($sqlQuery)->result('array');
        if ($sqlResults) {
            $ids = '';
            foreach ($sqlResults as $result) {
                $ids .= (($ids == '') ? '' : ',') . $result['messageID'];
            }
            $sqlQuery = "SELECT m.* FROM messages m WHERE m.id IN (" . $ids . ") AND m.from_status <> 'deleted' ORDER BY m.id DESC LIMIT " . ( ($pageNum - 1) * $perPage ) . ", " . $perPage . "";
            $messages = $this->db->query($sqlQuery)->result('array');
        }
        return $messages;
    }

    public function getDeletedMessages($userID, $userType, $pageNum = 1, $perPage = 3) {
        $messages = array();
        $sqlQuery = "SELECT DISTINCT(CASE WHEN m.parent_id = 0 THEN m.id ELSE m.parent_id END) as messageID FROM messages m WHERE (m.from_id = " . $userID . " OR m.to_id = " . $userID . ") AND ( receiver_type = '" . $userType . "' OR sender_type = '" . $userType . "') AND (m.to_status = 'deleted' OR m.from_status = 'deleted')";
        $sqlResults = $this->db->query($sqlQuery)->result('array');
        if ($sqlResults) {
            $ids = '';
            foreach ($sqlResults as $result) {
                $ids .= (($ids == '') ? '' : ',') . $result['messageID'];
            }
            $sqlQuery = "SELECT m.* FROM messages m WHERE m.id IN (" . $ids . ") AND (m.to_status = 'deleted' OR m.from_status = 'deleted') ORDER BY m.id DESC LIMIT " . ( ($pageNum - 1) * $perPage ) . ", " . $perPage . "";
            $messages = $this->db->query($sqlQuery)->result('array');
        }
        return $messages;
    }

    public function getMessageByID($messageID, $userID = 0) {
        $sqlQuery = "SELECT * FROM messages m WHERE m.id = " . $this->db->escape_str($messageID) . " " . ( $userID > 0 ? " AND (m.from_id = " . $userID . " OR m.to_id = " . $userID . ")" : "" ) . " LIMIT 1";
        $sqlResults = $this->db->query($sqlQuery)->row_array();
        return $sqlResults;
    }

    public function getMessageThread($messageID) {
        $sqlQuery = "SELECT * FROM messages m WHERE m.id = " . $messageID . " OR m.parent_id = " . $messageID . " ORDER BY id ASC";
        $sqlResults = $this->db->query($sqlQuery)->result('array');
        return $sqlResults;
    }

    public function getLastMessageOfThread($messageID) {
        $sqlQuery = "SELECT * FROM messages m WHERE m.id = " . $messageID . " OR m.parent_id = " . $messageID . " ORDER BY id DESC LIMIT 1";
        $sqlResults = $this->db->query($sqlQuery)->row_array();
        return $sqlResults;
    }

    public function getInboxLastMessage($messageID, $receiverID, $recieverType) {
        $sqlQuery = "SELECT * FROM messages m WHERE (m.id = " . $messageID . " OR m.parent_id = " . $messageID . ") AND m.to_id = " . $receiverID . " AND m.receiver_type = '" . $recieverType . "' ORDER BY m.id DESC LIMIT 1";
        $sqlResult = $this->db->query($sqlQuery)->row_array();
        return $sqlResult;
    }

    public function getOutboxLastMessage($messageID, $receiverID, $recieverType) {
        $sqlQuery = "SELECT * FROM messages m WHERE (m.id = " . $messageID . " OR m.parent_id = " . $messageID . ") AND m.from_id = " . $receiverID . " AND m.sender_type = '" . $recieverType . "' ORDER BY m.id DESC LIMIT 1";
        $sqlResult = $this->db->query($sqlQuery)->row_array();
        return $sqlResult;
    }

    public function getInboxLastMessageStatus($messageID, $receiverID, $recieverType) {
        $sqlQuery = "SELECT m.to_status FROM messages m WHERE (m.id = " . $messageID . " OR m.parent_id = " . $messageID . ") AND m.to_id = " . $receiverID . " AND m.receiver_type = '" . $recieverType . "' ORDER BY m.id DESC LIMIT 1";
        $sqlResult = $this->db->query($sqlQuery)->row_array();
        if ($sqlResult && $sqlResult['to_status'] == 'unread') {
            return true;
        }
        return false;
    }

    public function deleteMessage($messageID, $userID, $userType) {
        $message = $this->getMessageByID($messageID);
        if ($message) {
            $sqlQuery = "UPDATE messages m SET to_status = 'deleted' WHERE (m.id  = " . $message['id'] . " OR m.parent_id = " . $message['id'] . ") AND m.to_id = " . $userID . " AND m.receiver_type = '" . $userType . "'";
            $this->db->query($sqlQuery);
            $sqlQuery = "UPDATE messages m SET from_status = 'deleted' WHERE (m.id  = " . $message['id'] . " OR m.parent_id = " . $message['id'] . ") AND m.from_id = " . $userID . " AND m.sender_type = '" . $userType . "'";
            $this->db->query($sqlQuery);
        }
    }

    public function getUnreadMessageCount($receiverID, $recieverType) {
        $sqlQuery = "SELECT COUNT(DISTINCT(CASE WHEN m.parent_id = 0 THEN m.id ELSE m.parent_id END)) as CNT_ FROM messages m WHERE m.to_id = " . $receiverID . " AND receiver_type = '" . $recieverType . "' AND m.to_status = 'unread'";
        $sqlResults = $this->db->query($sqlQuery)->row_array();
        return $sqlResults['CNT_'];
    }
    
    
    
    
    
    //messages
    public function getUserName($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->get('users')->row()->username;
    }
    
    public function getUserimage($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->get('users')->row()->user_pic;
    }
    
    public function insertnotifications($data) {
        $this->db->insert('notifications', $data);
        return $this->db->insert_id();
    }
    
    /************************************   */
     public function tester($date,$user_id){
        
        $extraWhere= "";
        if(!empty($brand_id)){
          $extraWhere = ' and post_feeds_id in(  
                          SELECT pf.post_feeds_id FROM post_feeds pf 
                          JOIN campaigns c ON pf.campaigns_id = c.campaigns_id
                          WHERE c.user_id = "'.$brand_id.'" AND pf.users_id = "'.$user_id.'"
                         )';
        }else{
          $sql ="SELECT * FROM user_stats WHERE created LIKE'".$date. "%' AND user_id='".$user_id."' $extraWhere  ";
        }
        $query = $this->db->query($sql);
		return $query->result_array();
		
		/*$query = 'SELECT * from user_stats where user_stats_id IN ( SELECT max(user_stats_id) FROM `user_stats` where `user_id` = '.$user_id.' AND created LIKE \'%'.$date.'%\' group by post_feeds_id )';	
		$query = $this->db->query($query);
		return $query->result_array();*/
        
    }
    
    public function return_ambs($user_id){
        
        $this->db->where('brand_id',$user_id);
		$this->db->where('accepted',1);
        return $this->db->get('ambassador_brand')->result_array(); 
        
    }
    
    public function miniPosts($temp,$user_id,$brand_id){
        
        $datestring = "%Y-%m-%d ";
        $today = mdate($datestring);  
        $date = strtotime($temp, strtotime($today));
        $date = date("Y-m-d", $date);//exit;
        
        $sql ="SELECT COUNT(*) as total FROM post_feeds pf
        join campaigns c on c.campaigns_id=pf.campaigns_id
        WHERE pf.created LIKE'".$date. "%' AND pf.users_id='".$user_id."' and c.user_id='".$brand_id."' ";
        
        $query = $this->db->query($sql);
        $ar = $query->result_array();
        if(count($ar) > 0)
        {
          //  echo $ar[0]['total']; exit;
            return $ar[0]['total'];
        }   
        else
        {
            return '0';
        }    
    }
    
    
    
    public function userdata($data) { // sends users data based on his user_id alone
        $user_id = $data['user_id'];
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('users');
            return $query->result_array();
        
    }
    
    
    public function campaign_data($amb_id,$brand_id){
        
        $myquery = "SELECT c.*,pf.post_feeds_id FROM campaigns AS c 
                    JOIN post_feeds pf ON pf.campaigns_id = c.campaigns_id
                    WHERE c.user_id = '".$brand_id."' AND pf.users_id = '".$amb_id."' " ;
        $my_result = $this->db->query($myquery)->result_array();
        return $my_result;
        
    }
    
    public function campaign_name($campaign_id){
        
        $this->db->where('campaigns_id',$campaign_id);
        return $this->db->get('campaigns')->row()->title ;
        
        
    }
    
    
    public function check_platform($user_networks_id){
        
        $this->db->where('user_networks_id',$user_networks_id);
        return $this->db->get('user_networks')->row()->social_networks_id;
        
    }
    
    public function brand_campaigns($brand_id){
      $this->db->where('is_remove',0);  
      $this->db->where('user_id',$brand_id);
        return $this->db->get('campaigns')->result_array();
        
    }
    
    public function ambassador_pf($amb_id,$campaigns_id){
        $this->db->where('users_id',$amb_id);
        $this->db->where('campaigns_id',$campaigns_id);
        return $this->db->get('post_feeds')->result_array();
    }
    
    public function check_network($pf_id){
      $this->db->where('post_feeds_id',$pf_id);
      return $this->db->get('user_stats')->row()->user_networks_id; 
    }
    
    
    public function pf_stats($pf_id){
        $this->db->where('post_feeds_id',$pf_id);
        return $this->db->get('user_stats')->result_array();
        
    }
    
    
    public function network_data($userID){
        
        $this->db->where('users_id',$userID);
        return $this->db->get('user_networks')->result_array();
        
    }
    
     public function get_user_network_by_platform($platform){
        
        $this->db->where('social_networks_id',$platform);
        return $this->db->get('user_networks')->result_array();
        
    }
     public function get_user_network_by_id($user_networks_id){
        
        $this->db->where('user_networks_id',$user_networks_id);
        return $this->db->get('user_networks')->result_array();
        
    }
    public function get_posts_tracking_id($tracking_id) {
        $this->db->where('tracking_id', $tracking_id);
        $this->db->from('post_feeds');
        $query = $this->db->count_all_results();
        return $query;
    }
     public function add_instgram_posts($postFeed) {
        $this->db->insert('post_feeds', $postFeed);
        return $this->db->insert_id();
    }
    
      public function network_data_by_user_and_platform($userID,$social_network_id){
        
        $this->db->where('users_id',$userID);
		$this->db->where('social_networks_id',$social_network_id);
        return $this->db->get('user_networks')->result_array();
        
    }
    
    public function getPostFeedRecord($user_id,$brand_id=""){
      
      if(empty($brand_id)){
        $sql ="SELECT pf.* FROM post_feeds pf WHERE pf.users_id='".$user_id."'  group by pf.campaigns_id,pf.users_id ";
      }else{
       $sql ="SELECT pf.* FROM post_feeds pf
             join campaigns c on c.campaigns_id=pf.campaigns_id
             WHERE pf.users_id='".$user_id."' and c.user_id='".$brand_id."' group by pf.campaigns_id,pf.users_id ";
      }
      $query = $this->db->query($sql);
      return  $query->result();
    }
    
    public function isAmbassadorBrandRecord($ambd_id,$userID){
        
        $this->db->where('ambassador_id',$ambd_id);
        $this->db->where('brand_id',$userID);
	    return $this->db->get('ambassador_brand')->result_array();
    }
    
    public function updateAmbdbrand($data, $id) {
        $this->db->where('id', $id);
        return $this->db->update('ambassador_brand', $data);
    }
    
     public function getTeamMembers($user_id,$team_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('team_id', $team_id);
        return $this->db->get('team_members')->result_array();
    }
}
