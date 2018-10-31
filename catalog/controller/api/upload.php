//*********************************************************************************************************************************//

<?php
class ControllerApiupload extends Controller {
public function index() {

//upload file
$log=new Log("upload.log");
$log->write($this->request->post);
$log->write($this->request->files);
//log to table

$this->load->model('account/activity');
$activity_data = $this->request->post;
$this->model_account_activity->addActivity('upload', $activity_data);

//
$this->load->language('api/upload');
$json = array();
if (!$json) {
if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
// Sanitize the filename
$filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');

if ((utf8_strlen($filename) < 1) || (utf8_strlen($filename) > 128)) {
$json['error'] = $this->language->get('error_filename');
}

// Allowed file extension types
$allowed = array();
$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));
$filetypes = explode("\n", $extension_allowed);

foreach ($filetypes as $filetype) {
$allowed[] = trim($filetype);
}

/* if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
$json['error'] = $this->language->get('error_filetype');
}*/

// Allowed file mime types
$allowed = array();
$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));
$filetypes = explode("\n", $mime_allowed);
$log->write($filetypes);
foreach ($filetypes as $filetype) {
$allowed[] = trim($filetype);
}

/*if (!in_array($this->request->files['file']['type'], $allowed)) {
$json['error'] = $this->language->get('error_filetype');
}*/

// Check to see if any PHP files are trying to be uploaded
$content = file_get_contents($this->request->files['file']['tmp_name']);

if (preg_match('/\<\?php/i', $content)) {
$json['error'] = $this->language->get('error_filetype');
}

// Return any upload error
if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
}
} else {
$json['error'] = $this->language->get('error_upload');
}
}

if (!$json) {
$file = md5(mt_rand()).'#'.$filename;
move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);
// Hide the uploaded file name so people can not link to it directly.
$json['success'] = $this->language->get('text_upload');
}

$this->response->addHeader('Content-Type: application/json');
$this->response->setOutput(json_encode($json));
//
}

}

//*********************************************************************************************************************************//