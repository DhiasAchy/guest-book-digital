<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('_mkdir')) {
    function _mkdir($arg = null)
    {
        // create folder storage if not exist
        $root   = './storage';
        if (!is_dir($root)) {
            mkdir($root);
        }
        // create folder promo if not exist
        $path   = $root . '/promo';
        if (!is_dir($path)) {
            mkdir($path);
        }
        // create folder product if not exist
        $product   = $root . '/product';
        if (!is_dir($product)) {
            mkdir($product);
        }
        $collection   = $product . '/collection';
        if (!is_dir($collection)) {
            mkdir($collection);
        }
        $brand   = $product . '/brand';
        if (!is_dir($brand)) {
            mkdir($brand);
        }
        //create folder news
        $news   = $root . '/news';
        if (!is_dir($news)) {
            mkdir($news);
        }
        //create folder news
        $news   = $root . '/achievement';
        if (!is_dir($news)) {
            mkdir($news);
        }
    }
}
if (!function_exists('adminby')) {
    function adminby($id)
    {
        $CI = &get_instance();
        return $CI->db->get_where('admin', ['admin_id' => $id])->row();
    }
}
if (!function_exists('akses_menu')) {
    function akses_menu($uri = null)
    {
        $CI = &get_instance();
        $CI->db->join('menu_akses', 'menu.menu_id=menu_akses.menu_id')
            ->where('menu.deleted_at', null)
            ->where('menu_akses.admin_id', __sess_admin_id())
            ->where('menu.controller !=', '#');
        $listMenu   = $CI->db->get('menu')->result();

        $menu       = array_column($listMenu, 'controller');
        $segment    = [];
        for ($i = 1; $i <= $uri; $i++) :
            $segment[] = strtolower($CI->uri->segment($i));
        endfor;
        // $uriSubHead = $CI->uri->segment(1) . '/' . $CI->uri->segment(2) . '/' . $CI->uri->segment(3);
        // $uriSubHead = strtolower($uriSubHead);
        // $explode    = array_filter(explode('/', $uriSubHead));
        $implode    = implode('/', $segment);
        if (!in_array($implode, $menu)) { // jika tidak ada dilist maka redirect ke halaman 404
            redirect(base_url('admin/Error_403'));
        }
    }
}

if (!function_exists('total_employees')) {
    function total_employees()
    {
        $list = [
            '0-10',
            '10-50',
            '50-100',
            '100-500',
            '500-1000',
            '1000-5000',
            '5000-10000',
            '10.0000+',
        ];
        return $list;
    }
}

if (!function_exists('color_status_claim')) {
    function color_status_claim()
    {
        return [
            'rejected'   => 'danger',
            'waiting'   => 'warning',
            'approved'   => 'success',
        ];
    }
}
if (!function_exists('color_status_verified')) {
    function color_status_verified()
    {
        return [
            'unverified'   => 'danger',
            'review'        => 'warning',
            'verified'     => 'success',
        ];
    }
}
if (!function_exists('generate_url_slug')) {
    function generate_url_slug($string)
    {
        $t = &get_instance();
        $slug = url_title($string, '-', true);

        return $slug;
    }
}
if (!function_exists('getNotificationClaim')) {
    function getNotificationClaim()
    {
        $CI = &get_instance();
        $sql = $CI->db->get_where('company_claim_request', ['status' => 'waiting', 'deleted_at' => null])->num_rows();
        return $sql;
    }
}
if (!function_exists('getNotificationVerified')) {
    function getNotificationVerified()
    {
        $CI = &get_instance();
        $sql = $CI->db->get_where('company_verified_request', ['status' => 'waiting', 'deleted_at' => null])->num_rows();
        return $sql;
    }
}
if (!function_exists('lang_site')) {
    function lang_site($param)
    {
        $CI = &get_instance();
        return $CI->lang->line($param);
    }
}
if (!function_exists('log_activity')) {
    function log_activity($username, $role, $content, $contentID = null, $contentType = null)
    {
        $CI = &get_instance();
        $rows       = array(
            'role'          => $role,
            'username'      => $username,
            // 'name'          => $name,
            'log'           => $content,
            'content_id'    => $contentID,
            'content_type'  => $contentType,
            'created_at'    => _timestamp(),
        );
        $CI->db->insert('log_activity', $rows);
    }
}
if (!function_exists('language_active')) {
    function language_active()
    {
        $CI = &get_instance();
        $languageDb       = $CI->db->get('language_active')->row();
        $languageSess     = $CI->session->userdata('language');
        $langList         = ['indonesia', 'english'];
        $setLang          = $languageDb->language;
        if ($languageSess) :
            if (in_array($languageSess, $langList)) :
                $setLang = $languageSess;
            endif;
        endif;
        return $setLang;
    }
}
if (!function_exists('sidebar_menu_dashboard')) {
    function sidebar_menu_dashboard()
    {
        return array(
            'dashboard' => array('fa-user-o', lang_site('dcm1')),
            'list_review_company' => array('fa-star-o', lang_site('dcm2')),
            // 'dispute_chat' => array('fa-star-o','Dispute Chat')
        );
    }
}

if (!function_exists('check_dispute_company')) {
    // ini digunakan untuk melakukan update otomatis jika company tidak melakukan dispute review dalam waktu 24 jam
    function check_dispute_company()
    {
        $CI = &get_instance();
        if (__sess_company_status() == 'logged_in') :
            $company_id = __sess_company_id();
            $check = $CI->db->query("SELECT *, CASE 
                WHEN  CURRENT_TIMESTAMP >= DATE_ADD(created_at,INTERVAL 1 DAY) THEN 'update'
                ELSE 'unupdate'
                END AS validasi
                FROM review WHERE company_id='" . $company_id . "' AND STATUS ='2' AND deleted_at IS NULL
                HAVING validasi='update'")->num_rows();
            if (!empty($check)) :
                $CI->db->query("UPDATE review AS rv, 
                (SELECT *,DATE_ADD(created_at,INTERVAL 1 DAY) AS besok,CURRENT_TIMESTAMP AS sekarang,
                CASE 
                WHEN  CURRENT_TIMESTAMP >= DATE_ADD(created_at,INTERVAL 1 DAY) THEN 'update'
                ELSE 'unupdate'
                END AS validasi
                FROM review WHERE company_id='" . $company_id . "' AND STATUS ='2' AND deleted_at IS NULL
                ) AS BAYANGAN SET rv.STATUS = '1' WHERE rv.review_id=BAYANGAN.review_id AND BAYANGAN.validasi = 'update'");
            endif;
        endif;
    }
}
if (!function_exists('check_dispute_reviewer')) {
    // ini digunakan untuk melakukan update otomatis jika reviewer tidak melakukan approve dispute yang telah dikirim oleh company dalam waktu 24 jam
    function check_dispute_reviewer()
    {
        $CI = &get_instance();
        if (__sess_reviewers_status() == 'logged_in') :
            $reviewerID = __sess_reviewers_id();
            $check = $CI->db->query("SELECT *, CASE 
                    WHEN  CURRENT_TIMESTAMP >= DATE_ADD(updated_at,INTERVAL 1 DAY) THEN 'update'
                    ELSE 'unupdate'
                    END AS validasi
                    FROM review WHERE reviewers_id='" . $reviewerID . "' AND STATUS ='3' AND updated_at IS NOT NULL AND deleted_at IS NULL
                    HAVING validasi='update'
                    ")->num_rows();
            if (!empty($check)) :
                $CI->db->query("UPDATE review AS rv, 
                        (SELECT *,DATE_ADD(created_at,INTERVAL 1 DAY) AS besok,CURRENT_TIMESTAMP AS sekarang,
                            CASE 
                            WHEN  CURRENT_TIMESTAMP >= DATE_ADD(created_at,INTERVAL 1 DAY) THEN 'update'
                            ELSE 'unupdate'
                            END AS validasi
                            FROM review WHERE reviewers_id='" . $reviewerID . "' AND STATUS ='3' AND updated_at IS NOT NULL AND deleted_at IS NULL
                        ) AS BAYANGAN SET rv.STATUS = '0',rv.deleted_at='" . _timestamp() . "' WHERE rv.review_id=BAYANGAN.review_id AND BAYANGAN.validasi = 'update'");
            endif;
        endif;
    }
}
if (!function_exists('check_last_chat_dispute')) {
    // ini digunakan untuk melakukan update otomatis jika reviewer / company tidak me-reply chat dalam waktu 1x24 jam
    function check_last_chat_dispute()
    {
        $CI = &get_instance();
        if (__sess_reviewers_status() == 'logged_in' || __sess_company_status() == 'logged_in') :
            $check = $CI->db->query("SELECT review_discuss.review_discuss_id,review_discuss.review_id,review_discuss.company_id,review_discuss.user,review_discuss.created_by,review_discuss.created_at, DATE_ADD(review_discuss.created_at,INTERVAL 1 DAY) AS tomorrow,
                CASE 
                WHEN  CURRENT_TIMESTAMP >= DATE_ADD(review_discuss.created_at,INTERVAL 1 DAY) THEN 'update'
                ELSE 'unupdate'
                END AS validasi
                FROM review_discuss
                LEFT JOIN review ON review.review_id=review_discuss.review_id
                WHERE review_discuss_id IN (
                    SELECT MAX(review_discuss_id)
                    FROM review_discuss
                    WHERE USER !='admin'
                    GROUP BY review_id
                )
                AND 
                review.`status`='4'
                AND 
                review_discuss.deleted_at IS NULL
                ")->result_array();

            if (in_array('update', array_column($check, 'validasi'))) :
                foreach ($check as $key => $val) :
                    if ($val['user'] == 'reviewer') :
                        $CI->db->update('review', ['status' => '1'], ['review_id' => $val['review_id']]);
                        $CI->db->insert('review_discuss_history', ['status' => '1', 'review_id' => $val['review_id']]);
                    else :
                        $CI->db->update('review', ['status' => '5', 'deleted_at' => _timestamp()], ['review_id' => $val['review_id']]);
                        $CI->db->insert('review_discuss_history', ['status' => '0', 'review_id' => $val['review_id']]);
                    endif;
                endforeach;
            endif;

        endif;
    }
}

if (!function_exists('generate_image')) {
    function generate_image($reviewID, $companyID)
    {
        $CI = &get_instance();
        $CI->load->model('Model_review');
        if ($reviewID != "") :
            $review = $CI->Model_review->getby($reviewID, $companyID, null)->row_array();
            if (!empty($review)) :
                // ====== penghitungan rata2 rating ===
                $rating             = $CI->Model_review->get_review_by_company($review['company_id']);
                $rating_1 = 0;
                $rating_2 = 0;
                $rating_3 = 0;
                $rating_4 = 0;
                $rating_5 = 0;
                if (!empty($rating)) :
                    foreach ($rating as $k_rating => $v_rating) {
                        if ($v_rating['rating'] == '1') :
                            $rating_1 += 1;
                        endif;
                        if ($v_rating['rating'] == '2') :
                            $rating_2 += 1;
                        endif;
                        if ($v_rating['rating'] == '3') :
                            $rating_3 += 1;
                        endif;
                        if ($v_rating['rating'] == '4') :
                            $rating_4 += 1;
                        endif;
                        if ($v_rating['rating'] == '5') :
                            $rating_5 += 1;
                        endif;
                    }
                endif;
                $jumlah   = ($rating_1 + $rating_2 + $rating_3 + $rating_4 + $rating_5);
                $ratarata = ((1 * $rating_1) + (2 * $rating_2) + (3 * $rating_3) + (4 * $rating_4) + (5 * $rating_5));
                $hasil    = $ratarata == 0 ? 0 : $ratarata / $jumlah;
                // ====== end rating ===

                $all_review = $CI->Model_review->getby(null, $review['company_id'], null)->result_array();
                // rating
                $cekreview  = imagecreatefrompng(base_url('assets/images/template-logo-yellow.png'));
                $star_act   = imagecreatefrompng(base_url('assets/images/template-star-active.png'));
                $star_inact = imagecreatefrompng(base_url('assets/images/template-star-inactive.png'));

                $font_file = 'C:\Windows\Fonts\arial.ttf'; // digunakan dilocal
                // $font_file = './assets/arial.ttf'; // digunakan di hosting
                // $font_file = dirname(__FILE__).'/OpenSans-SemiBold.ttf'; // 
                $custom_text = strip_tags($review['reason']);
                $textRating = 'Rate ' . round($hasil, 2) . '/5 | ' . count($all_review) . ' Review';
                $text_length = 65;
                $sig = wordwrap($custom_text, $text_length, "<br />", true);
                $fontsize = 21;
                $fontfile = $font_file;


                $img = imagecreatefromjpeg(base_url('assets/images/1200x627.jpg'));
                $c = imagecolorallocate($img, 0, 0, 0);

                //Just wrap text by newlines
                $text = strlen(str_replace('<br />', "\n", $sig)) > 500 ? substr(str_replace('<br />', "\n", $sig), 0, 500) . "..." : str_replace('<br />', "\n", $sig);
                $text = '" ' . $text . ' "';
                // First we create our bounding box
                $bbox = imageftbbox(10, 0, $fontfile, $text);
                // This is our cordinates for X and Y
                $x = $bbox[0] + (imagesx($img) / 2) - ($bbox[4] / 2) - 300;
                $y = $bbox[1] + (imagesy($img) / 2) - ($bbox[5] / 2) - 350;
                imagefttext($img, 26, 0, $x, 100, $c, $fontfile, $text);


                // rating
                $wt_image_w = imagesx($star_act);
                $wt_image_h = imagesy($star_act);

                // rating
                $logo_wt_image_w = imagesx($cekreview);
                $logo_wt_image_h = imagesy($cekreview);


                // $dst_x1 = 100;
                // $dst_y1 =imagesy($img) - 150;
                // $dst_x2 = 150;
                // $dst_y2 =imagesy($img) - 150;
                // $dst_x = [100,150,200,]
                $ratinggiven = $review['rating'];
                $dst_y      = imagesy($img) - 150;


                for ($x1 = 0; $x1 < $ratinggiven; $x1++) {
                    $dst_x = 100 + ($x1 * 50);
                    imagecopy($img, $star_act, $dst_x, $dst_y, 0, 0, $wt_image_w, $wt_image_h);
                }
                for ($x2 = $ratinggiven; $x2 < 5; $x2++) {
                    $dst_x = 100 + ($x2 * 50);
                    // $dst_x = 500;
                    imagecopy($img, $star_inact, $dst_x, $dst_y, 0, 0, $wt_image_w, $wt_image_h);
                    // imagecopy($img, $cekreview, $dst_x, $dst_y, 0, 0, $wt_image_w, $wt_image_h);
                }
                // rating by    
                $xRatingBy = $bbox[0] + (imagesx($img) / 2) - ($bbox[4] / 2) - 5;
                $yRatingBy = imagesy($img) - 110;
                imagefttext($img, 22, 0, 380, $yRatingBy, $c, $fontfile, ' By ' . $review['reviewer_name']);

                // summary rating
                $xRating = $bbox[0] + (imagesx($img) / 2) - ($bbox[4] / 2) - 5;
                $yRating = imagesy($img) - 15;

                imagefttext($img, 18, 0, 100, $yRating, $c, $fontfile, $textRating);
                // logo
                $xLogo = imagesx($img) - 330;
                $yLogo = imagesy($img) - 185;
                // $xLogo = imagesx($img) - 250;
                // $yLogo = imagesy($img) - 125;
                imagecopy($img, $cekreview, $xLogo, $yLogo, 0, 0, $logo_wt_image_w, $logo_wt_image_h);
                imagejpeg($img, './assets/review/' . $review['review_id'] . '.jpg', 100);
                imagedestroy($img);
            endif;
        endif;
    }
}

// -- tambahan -- //
if (!function_exists('event_status')) {
    function event_status($event_date) 
    {
        $CI = &get_instance();
        // $query = $CI->db->query("SELECT * FROM `app_event` WHERE `event_code`='$code'")->result_array();
        $date = date('Ymd', strtotime($event_date));
        $date_ = strtotime($event_date);
        $date_today = date('Ymd');
        $remaining = $date_ - time();
        $days_remaining = floor($remaining / 86400);
        $hours_remaining = floor(($remaining % 86400) / 3600);

        if($date>$date_today) {
            $status_color = "primary";
            $status_text = "Event Belum Mulai, Masih ".$days_remaining." hari, ".$hours_remaining." jam lagi";
        } else if($date<$date_today) {
            $status_color = "danger";
            $status_text = "Event Sudah Selesai";
        } else {
            $status_color = "success";
            $status_text = "Event Sedang Berlangsung";
        }

        return $button = '<button type="button" class="btn btn-outline-'.$status_color.'">'.$status_text.'</button>';
    }
}
