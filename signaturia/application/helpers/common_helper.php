<?php

/**
 * Common Helper
 * Common functions used
 * @author KU
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Print array/string.
 * @param array $data - data which is going to be printed
 * @param boolean $is_die - if set to true then excecution will stop after print. 
 */
function p($data, $is_die = false) {

    if (is_array($data)) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    } else {
        echo $data;
    }

    if ($is_die)
        die;
}

/**
 * Print last executed query
 * @param boolean $bool - if set to true then excecution will stop after print
 */
function qry($bool = false) {
    $CI = & get_instance();
    echo $CI->db->last_query();
    if ($bool)
        die;
}

/**
 * Uploads image
 * @param string $image_name
 * @param string $image_path
 * @return array - Either name of the image if uploaded successfully or Array of errors if image is not uploaded successfully
 */
function upload_image($image_name, $image_path) {
    $CI = & get_instance();
    $extension = explode('/', $_FILES[$image_name]['type']);
    p($extension);
    $randname = uniqid() . time() . '.' . end($extension);
    $config = array(
        'upload_path' => $image_path,
        'allowed_types' => "png|jpg|jpeg|gif",
        'max_size' => "10240",
    );

    //--Load the upload library
    $CI->load->library('upload');
    $CI->upload->initialize($config);
    if ($CI->upload->do_upload($image_name)) {
        $img_data = $CI->upload->data();
        $imgname = $img_data['file_name'];
    } else {
        $imgname = array('errors' => $CI->upload->display_errors());
    }
    return $imgname;
}

function upload_image_new($image_name, $image_path) {
    $CI = & get_instance();
    $extension = explode('/', $_FILES[$image_name]['type']);
    $randname = uniqid() . time() . '.' . $extension[1];
    $config = array('upload_path' => $image_path,
        'allowed_types' => "*",
        'file_name' => $randname
    );
    #Load the upload library
    $CI->load->library('upload');
    $CI->upload->initialize($config);
    if ($CI->upload->do_upload($image_name)) {
        $img_data = $CI->upload->data();
        $imgname = $img_data['file_name'];
    } else {
        $imgname = array('errors' => $CI->upload->display_errors());
    }
    return $imgname;
}

/**
 * Set up configuration array for pagination
 * @return array - Configuration array for pagination
 */
function front_pagination() {
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['first_link'] = 'First';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li style="display:none"></li><li class="active"><a data-type="checked" style="background-color:#62a0b4;color:#ffffff; pointer-events: none;">';
    $config['cur_tag_close'] = '</a></li><li style="display:none"></li>';
    $config['prev_link'] = '&laquo;';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '&raquo;';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['last_link'] = 'Last';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    return $config;
}

/**
 * Resise image to specified dimensions
 * @param string $src - Source of image
 * @param string $dest - Destination of image
 * @param int $width - Width of image
 * @param int $height - Height of image
 */
function resize_image($src, $dest, $width, $height) {
    $CI = & get_instance();
    $CI->load->library('image_lib');
    $CI->image_lib->clear();
    $config['image_library'] = 'gd2';
    $config['source_image'] = $src;
    $config['maintain_ratio'] = FALSE;
    $config['width'] = $width;
    $config['height'] = $height;
    $config['new_image'] = $dest;
    $CI->image_lib->initialize($config);
    $CI->image_lib->resize();
}

/**
 * Returns all the categories
 */
function get_all_cats() {
    echo "here";
    exit;
    $CI = & get_instance();
    p(1, 1);
    $CI->load->model('categories_model');
    $data = $this->categories_model->get_all_active_cats();
    return $data;
}

/**
 * Return verfication code with check already exit or not for business user signup
 */
function verification_code() {
    $CI = & get_instance();
    $CI->load->model('users_model');
    for ($i = 0; $i < 1; $i++) {
        $verification_string = 'abcdefghijk123' . time();
        $verification_code = str_shuffle($verification_string);
        $check_code = $CI->users_model->check_verification_code($verification_code);
        if (sizeof($check_code) > 0) {
            $i--;
        } else {
            return $verification_code;
        }
    }
}

/**
 * Returns file size in GB/MB or KB
 * @param int $bytes
 * @return string
 */
function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}

/**
 * Blurs image
 * @param string $image_name
 */
function blur_image($image_name, $output_path) {
    ini_set('memory_limit', '500M');
    $extension = pathinfo($image_name, PATHINFO_EXTENSION);
    $filename = pathinfo($image_name, PATHINFO_FILENAME);

    $blurs = 3;
    $jpg_quality = 30;
    $png_quality = 3;
    if ($extension == 'jpg' || $extension == 'jpeg') {
        $image = imagecreatefromjpeg($image_name);
        $exif = exif_read_data($image_name);
        $rotate = $image;
        switch ($exif['Orientation']) {
            case 3:
                $rotate = imagerotate($image, 180, 0);
                break;
            case 6:
                $rotate = imagerotate($image, - 90, 0);
                break;
            case 8:
                $rotate = imagerotate($image, 90, 0);
                break;
        }

        for ($i = 0; $i < $blurs; $i++) {
            imagefilter($rotate, IMG_FILTER_GAUSSIAN_BLUR);
        }
        imagejpeg($rotate, $output_path . $filename . '.' . $extension, $jpg_quality);
        imagedestroy($rotate);
    } else if ($extension == 'png') {
        $image = imagecreatefrompng($image_name);
        $exif = exif_read_data($image_name);
        $rotate = $image;
        switch ($exif['Orientation']) {
            case 3:
                $rotate = imagerotate($image, 180, 0);
                break;
            case 6:
                $rotate = imagerotate($image, - 90, 0);
                break;
            case 8:
                $rotate = imagerotate($image, 90, 0);
                break;
        }
        for ($i = 0; $i < $blurs; $i++) {
            imagefilter($rotate, IMG_FILTER_GAUSSIAN_BLUR);
        }
        imagepng($rotate, $output_path . $filename . '.' . $extension, $png_quality);
        imagedestroy($rotate);
    }
}

/**
 * Crops the image
 * @param int $source_x
 * @param int $source_y
 * @param int $width
 * @param int $height
 * @param string $image_name
 */
function crop_image($source_x, $source_y, $width, $height, $image_name) {
    ini_set('memory_limit', '500M');

    $output_path = CROP_FACES;
    $extension = pathinfo($image_name, PATHINFO_EXTENSION);
    $filename = pathinfo($image_name, PATHINFO_FILENAME);


    if ($extension == 'jpg' || $extension == 'jpeg') {
        $image = imagecreatefromjpeg($image_name);
    } else if ($extension == 'png') {
        $image = imagecreatefrompng($image_name);
    }

    $new_image = imagecreatetruecolor($width, $height);
    imagecopy($new_image, $image, 0, 0, $source_x, $source_y, $width, $height);
    // Now $new_image has the portion cropped from the source and you can output or save it.
    if ($extension == 'jpg' || $extension == 'jpeg') {
        imagejpeg($new_image, $output_path . $filename . '.' . $extension);
    } else if ($extension == 'png') {
        imagepng($new_image, $output_path . $filename . '.' . $extension);
    }
}

/**
 * Resise the image to 800x600
 * @param string $src - Source of the image
 * @param type $dest - Destination of the image
 */
function thumbnail_image($src, $dest) {
    $extension = pathinfo($src, PATHINFO_EXTENSION);

    if ($extension == 'jpg' || $extension == 'jpeg') {
        $image = imagecreatefromjpeg($src);
        $exif = exif_read_data($src);
        $rotate = $image;
        switch ($exif['Orientation']) {
            case 3:
                $rotate = imagerotate($image, 180, 0);
                break;
            case 6:
                $rotate = imagerotate($image, - 90, 0);
                break;
            case 8:
                $rotate = imagerotate($image, 90, 0);
                break;
        }
        imagejpeg($rotate, $dest);
        imagedestroy($rotate);
    } else if ($extension == 'png') {
        $image = imagecreatefrompng($src);
        $exif = exif_read_data($src);
        $rotate = $image;
        switch ($exif['Orientation']) {
            case 3:
                $rotate = imagerotate($image, 180, 0);
                break;
            case 6:
                $rotate = imagerotate($image, - 90, 0);
                break;
            case 8:
                $rotate = imagerotate($image, 90, 0);
                break;
        }

        imagepng($rotate, $dest);
        imagedestroy($rotate);
    }
    $size = getimagesize($dest);
    $CI = & get_instance();
    $CI->image_lib->clear();
    $config['image_library'] = 'gd2';
    $config['source_image'] = $dest;
    $config['maintain_ratio'] = TRUE;

    if ($size[0] > 800 || $size[1] > 600) {
        $config['width'] = 800;
        $config['height'] = 600;
    } else {
        $config['width'] = $size[0];
        $config['height'] = $size[1];
    }
    $CI->image_lib->initialize($config);
    $CI->image_lib->resize();
}

function resizeImage($file, $destination, $crop = FALSE) {
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    $w = 800;
    $h = 600;
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width - ($width * abs($r - $w / $h)));
        } else {
            $height = ceil($height - ($height * abs($r - $w / $h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w / $h > $r) {
            $newwidth = $h * $r;
            $newheight = $h;
        } else {
            $newheight = $w / $r;
            $newwidth = $w;
        }
    }
    if ($extension == 'jpg' || $extension == 'jpeg') {
        $src = imagecreatefromjpeg($file);
        $function = 'imagejpeg';
    } else if ($extension == 'png') {
        $src = imagecreatefrompng($file);
        $function = 'imagepng';
    }

    $exif = exif_read_data($file);
    $rotate = $src;
    switch ($exif['Orientation']) {
        case 3:
            $rotate = imagerotate($src, 180, 0);
            break;
        case 6:
            $rotate = imagerotate($src, - 90, 0);
            break;
        case 8:
            $rotate = imagerotate($src, 90, 0);
            break;
    }
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $rotate, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    $function($dst, $destination);
}

/**
 * This function used to send email.
 * @param Array $email_values
 * */
function common_email_send($email_values = array()) {
    $type = $email_values ['mail_type'];
    $subject = $email_values ['subject_message'];
    $to = $email_values ['to_mail_id'];
    $from = $email_values ['from_mail_id'];
    $from_name = $email_values ['from_mail_name'];
    $CI = & get_instance();
    $CI->load->library('email');
    $CI->email->set_newline("\r\n");
    $CI->email->set_mailtype($type);
    $CI->email->subject($subject);
    $CI->email->from($from, $from_name);
    $CI->email->to($to);
    if ($email_values['cc_mail_id'] != '') {
        $CI->email->cc($email_values['cc_mail_id']);
    }
    $CI->email->message(stripslashes($email_values ['body_messages']));
    if (!$CI->email->send()) {
        echo $CI->email->print_debugger();
    }
    return 'success';
}

#--- This function used create unique coupon code

function create_coupon() {
    $coupon_code = 'SIGN' . uniqid();
    $CI = & get_instance();
    $CI->load->model('admin/users_model');
    for ($i = 0; $i < 1; $i++) {
        $result = $CI->users_model->check_coupon_code($coupon_code);
        if ($result) {
            $i--;
        } else {
            return $coupon_code;
        }
    }
}

#--- check offers are exist or not. If not exist then no need to display offer menu else menu will appear on header

function check_offers() {
    $CI = & get_instance();
    $CI->load->model('common_model');
    $select = 'c.*, p.*, o.*';
    $where = '(now() >= c.start_datetime AND now() <= c.end_datetime) AND c.is_delete = 0 and o.show_on_offer_page = 1 and o.is_active = 1 and o.is_delete = 0';
    $option = array(
        'join' => array(
            array(
                'table' => TBL_COUPONS . ' c',
                'condition' => 'c.id = o.coupon_id'
            ),
            array(
                'table' => TBL_PACKAGES . ' p',
                'condition' => 'c.package_id = p.id'
            ),
        )
    );
    return $CI->common_model->sql_select(TBL_OFFERS . ' o', $select, $where, $option);
}

#--- This function used create unique passcode code

function create_passcode() {
    $passcode = 'PS' . uniqid();
    $CI = & get_instance();
    for ($i = 0; $i < 1; $i++) {
        $where = array('where' => array('passcode' => $passcode));
        $result = $CI->common_model->sql_select(TBL_EMPLOYEES_PASSCODES, 'id', $where, null);
        if ($result) {
            $i--;
        } else {
            return $passcode;
        }
    }
}

function encode($value) {
    $ci = &get_instance();
    return str_replace('/', '@', $ci->encrypt->encode($value));
}

function decode($value) {
    $ci = &get_instance();
    return $ci->encrypt->decode(str_replace('@', '/', $value));
}
