<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
/**
 * Khai báo schema "Cấu hình hệ thống" — NGUỒN CHÂN LÝ DUY NHẤT.
 *
 * Thêm/bớt/đổi field chỉ sửa file này: KHÔNG migration, KHÔNG sửa form,
 * KHÔNG sửa .tpl. Form admin và mọi getter đều đọc lại từ đây.
 *
 * Cấu trúc 3 tầng:
 *   '<group_key>' => [
 *       'label'       => tiêu đề nhóm                     (bắt buộc)
 *       'description' => mô tả cột trái                   (bắt buộc)
 *       'slug'        => id neo CỐ ĐỊNH, độc lập label (đổi tên nhóm không gãy anchor)
 *       'icon'        => tên icon FontAwesome cạnh tiêu đề
 *       'permission'  => key quyền; thiếu quyền thì ẩn nhóm LẪN field và bỏ qua khi POST
 *       'hidden'      => true → không render nhưng giữ nguyên dữ liệu trong DB
 *       'value'       => [ '<field_key>' => [ ... ] ]
 *   ]
 *
 * Field option:
 *   type        text|textarea|editor|images|files|select|select2|select_pair|color|number|checkbox|time
 *               → chỉ quyết định WIDGET, mọi giá trị đều lưu dạng TEXT
 *   label       nhãn field                       required    bắt input không được rỗng
 *   default     giá trị mặc định khi chưa có row  placeholder gợi ý trong ô nhập
 *   attention   ghi chú màu đỏ                    help        chú thích xám dưới field
 *   help_html   help được in ra dạng HTML (chèn link) thay vì escape nguyên văn
 *   raw         field chứa HTML/script thô        rows        số dòng cho textarea
 *   title/link  chữ + url link hướng dẫn
 *   select      [k => v], bắt buộc với select/select2
 *   source      tên nguồn option động do controller nạp vào 'select' (không query trong .tpl)
 *   json        giá trị là mảng → lưu json_encode, đọc ra json_decode
 *   multiple    select nhận nhiều giá trị
 *   sub_keyword (select_pair) tên sub-key JSON của ô chính
 *   pair        (select_pair) ô phụ: ['keyword' => sub-key JSON, 'source'|'select' => option,
 *               'placeholder' => .., 'show_when' => giá trị ô chính khiến ô phụ hiện ra]
 *   width       (images) bề ngang gợi ý sẵn trong ô nhập, px — mặc định DEFAULT_IMAGE_SIZE
 *   height      (images) chiều cao gợi ý sẵn trong ô nhập, px — mặc định DEFAULT_IMAGE_SIZE
 *   demo        (images) ảnh minh hoạ hiện khi chưa chọn ảnh nào
 *
 * Khóa lưu trữ là chuỗi phẳng, dùng đúng key đang có trên live (không prefix)
 * nên module này thay thẳng màn hình cũ mà không cần migrate dữ liệu.
 *
 * KHÔNG dùng typed property / return type: host chạy PHP 7 nhưng không rõ bản
 * minor, và toàn bộ codebase này không có chỗ nào dùng cú pháp 7.4+.
 */
class ConfigDeclaration {
	/** Kích thước điền sẵn cho field ảnh khi schema không khai width/height. */
	const DEFAULT_IMAGE_SIZE = 40;
	/** Kích thước ảnh lưu thành 2 key phụ cạnh key ảnh: <key>_width / <key>_height. */
	const WIDTH_SUFFIX = '_width';
	const HEIGHT_SUFFIX = '_height';

	/** @var array|null Schema là hằng trong 1 request — dựng 1 lần rồi tái dùng. */
	private static $system = null;

	/** @return array */
	public function system(){
		if(self::$system === null){
			self::$system = self::declaration();
		}
		return self::$system;
	}

	/**
	 * Danh sách key hợp lệ của toàn schema (kể cả key phụ của field ghép đôi).
	 * Dùng để chặn request giả gửi key không khai báo.
	 * @return array
	 */
	public function keywords(){
		$keywords = array();
		foreach($this->system() as $group){
			if(empty($group['value'])){
				continue;
			}
			foreach($group['value'] as $keyword => $field){
				$keywords[] = $keyword;
				if(!empty($field['pair_keyword'])){
					$keywords[] = $field['pair_keyword'];
				}
			}
		}
		return $keywords;
	}

	/**
	 * Nhóm chứa key này — cho Configuration::getGroup() và tra ngược khi debug.
	 * @return string
	 */
	public function findGroup($keyword){
		foreach($this->system() as $groupKey => $group){
			if(isset($group['value'][$keyword])){
				return $groupKey;
			}
		}
		return '';
	}

	/**
	 * Chuẩn hóa danh sách nhóm cho view: lọc permission/hidden, tính sẵn slug,
	 * nạp option động. .tpl chỉ còn foreach.
	 *
	 * Thứ tự hiển thị = đúng thứ tự khai trong declaration(): muốn đổi vị trí
	 * nhóm thì di chuyển khối khai báo, không có key sắp xếp riêng.
	 *
	 * @param array $sources ['<source_name>' => [k => v]] — option động do controller nạp
	 * @return array
	 */
	public function normalize($sources = array()){
		$groups = array();
		foreach($this->system() as $groupKey => $group){
			if(!empty($group['hidden'])){
				continue;
			}
			if(!$this->allow($group)){
				continue;
			}
			$fields = $this->normalizeFields($group, $sources);
			if(empty($fields)){
				continue;
			}
			$icon = isset($group['icon']) ? $group['icon'] : 'cog';
			$groups[] = array(
				'key'         => $groupKey,
				'label'       => isset($group['label']) ? $group['label'] : $groupKey,
				'description' => isset($group['description']) ? $group['description'] : '',
				'slug'        => $this->slug($groupKey, $group),
				'icon'        => $icon,
				// Dựng sẵn class icon để .tpl chỉ truyền biến vào makeIcon(),
				// không phải ghép chuỗi bằng modifier trong template.
				'icon_class'  => $icon.' mr-5',
				'fields'      => $fields
			);
		}
		return $groups;
	}

	/**
	 * Gom giá trị POST theo whitelist schema → mảng phẳng cho Configuration::saveBatch().
	 * Field không khai báo bị bỏ qua; field thuộc nhóm không đủ quyền không có mặt
	 * trong $groups nên cũng không thể bị ghi đè bằng request giả.
	 *
	 * @param array $groups Kết quả normalize()
	 * @param array $posted $_POST['config'] thô — KHÔNG lọc trước, hàm này tự lọc
	 * @return array
	 */
	public function collect($groups, $posted){
		$data = array();
		if(empty($groups) || !is_array($posted)){
			return $data;
		}
		foreach($groups as $group){
			foreach($group['fields'] as $field){
				$this->collectField($field, $posted, $data);
			}
		}
		return $data;
	}

	/** Gom 1 field (và key phụ nếu là field ghép đôi / field ảnh) vào $data. */
	private function collectField($field, $posted, &$data){
		$keywords = array($field['keyword']);
		if(!empty($field['pair_keyword'])){
			$keywords[] = $field['pair_keyword'];
		}
		if(!empty($field['size_keywords'])){
			$keywords = array_merge($keywords, $field['size_keywords']);
		}
		foreach($keywords as $keyword){
			if(!array_key_exists($keyword, $posted)){
				// Thiếu field trong POST (JS lỗi, input disabled) → giữ nguyên giá trị cũ,
				// tuyệt đối không ghi rỗng đè lên dữ liệu đang có.
				continue;
			}
			$value = $posted[$keyword];
			if(!empty($field['size_keywords']) && in_array($keyword, $field['size_keywords'], true)){
				// Kích thước luôn là số px; chặn chuỗi rác lọt vào thuộc tính width/height.
				$data[$keyword] = (string) max(0, (int) $value);
				continue;
			}
			if($field['type'] === 'select_pair'){
				$data[$keyword] = json_encode(self::pairValue($field, $value), JSON_UNESCAPED_UNICODE);
				continue;
			}
			if(!empty($field['json'])){
				$data[$keyword] = json_encode(self::cleanList($value), JSON_UNESCAPED_UNICODE);
				continue;
			}
			if(is_array($value)){
				$value = implode(',', $value);
			}
			$data[$keyword] = (string) $value;
		}
	}

	/**
	 * Giá trị field select_pair: LUÔN đủ 2 sub-key khai trong schema, đều là chuỗi.
	 * Không dùng cleanList() như field json khác vì nơi đọc (vd: duyệt giao dịch ở
	 * home) truy thẳng $value['name'] — thiếu key là notice và so sánh sai.
	 * @return array
	 */
	private static function pairValue($field, $value){
		$pair = array();
		if(!is_array($value)){
			$value = array();
		}
		foreach(self::pairKeywords($field) as $subKeyword){
			$item = isset($value[$subKeyword]) ? $value[$subKeyword] : '';
			$pair[$subKeyword] = is_scalar($item) ? (string) $item : '';
		}
		return $pair;
	}

	/**
	 * 2 sub-key JSON của field select_pair: ô chính trước, ô phụ sau.
	 * @return array
	 */
	public static function pairKeywords($field){
		$main = !empty($field['sub_keyword']) ? $field['sub_keyword'] : 'value';
		$extra = !empty($field['pair']['keyword']) ? $field['pair']['keyword'] : 'extra';
		return array($main, $extra);
	}

	/**
	 * Bỏ phần tử rỗng khỏi giá trị dạng mảng.
	 * Select rỗng và ô đánh dấu ẩn (giúp nhận biết "người dùng đã bỏ chọn hết")
	 * đều gửi lên chuỗi rỗng — không lưu chúng vào JSON.
	 * @return array
	 */
	private static function cleanList($value){
		if(!is_array($value)){
			return array();
		}
		$clean = array();
		foreach($value as $key => $item){
			if(is_array($item)){
				$item = self::cleanList($item);
			}
			if($item === '' || $item === null || $item === array()){
				continue;
			}
			if(is_int($key)){
				$clean[] = $item;
				continue;
			}
			$clean[$key] = $item;
		}
		return $clean;
	}

	/**
	 * Chuẩn hóa field trong 1 nhóm: gắn keyword, nạp option động, bỏ field rỗng nguồn.
	 * @return array
	 */
	private function normalizeFields($group, $sources){
		$fields = array();
		if(empty($group['value'])){
			return $fields;
		}
		foreach($group['value'] as $keyword => $field){
			$field['keyword'] = $keyword;
			$field['type'] = isset($field['type']) ? $field['type'] : 'text';
			$field['label'] = isset($field['label']) ? $field['label'] : $keyword;
			// Type tự dựng cả khối (nhãn nằm cạnh input, hoặc nhiều dòng) →
			// view không bọc thêm form-group chuẩn quanh nó.
			$field['bare'] = self::isBare($field['type']);
			$field['help_display'] = self::helpDisplay($field);
			if(!empty($field['source'])){
				$field['select'] = isset($sources[$field['source']]) ? $sources[$field['source']] : array();
			}
			if(!empty($field['pair']['source'])){
				$pairSource = $field['pair']['source'];
				$field['pair']['select'] = isset($sources[$pairSource]) ? $sources[$pairSource] : array();
			}
			if(self::needSelect($field['type']) && empty($field['select'])){
				// Không có option nào để chọn → ẩn hẳn, giống {if !empty($list_block_types)} của bản cũ.
				continue;
			}
			if($field['type'] === 'images'){
				$field = self::withImageSize($field);
			}
			$fields[] = $field;
		}
		return $fields;
	}

	/**
	 * @return string
	 */
	private static function helpDisplay($field){
		if(empty($field['help'])){
			return '';
		}
		$help = (string) $field['help'];
		if(empty($field['help_html'])){
			return htmlspecialchars($help, ENT_QUOTES, 'UTF-8');
		}
		return self::safeHtml($help);
	}

	/**
	 * @return string
	 */
	private static function safeHtml($html){
		$html = strip_tags($html, '<a><b><strong><i><em><u><small><code><span><br>');
		$html = preg_replace_callback('#<a\b[^>]*>#i', function($matches){
			return self::safeAnchor($matches[0]);
		}, $html);
		return preg_replace('#<(/?)(b|strong|i|em|u|small|code|span|br)\b[^>]*>#i', '<$1$2>', $html);
	}

	/**
	 * Dựng lại thẻ mở <a>: href phải là http/https/mailto/tel hoặc đường dẫn nội bộ.
	 * Sai giao thức thì trả thẻ trống để chữ bên trong vẫn hiện, chỉ mất link.
	 * @return string
	 */
	private static function safeAnchor($tag){
		if(!preg_match('#\bhref\s*=\s*("|\')(.*?)\1#is', $tag, $matches)){
			return '<a>';
		}
		$url = trim(html_entity_decode($matches[2], ENT_QUOTES, 'UTF-8'));
		if(!preg_match('#^(https?://|mailto:|tel:|/|\#)#i', $url)){
			return '<a>';
		}
		// Help nằm giữa form cấu hình: mở tab mới để người dùng không mất phần đang nhập.
		return '<a href="'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').'" target="_blank" rel="noopener">';
	}

	/**
	 * Phần phụ của field ảnh: ảnh demo, kích thước mặc định và tên 2 key phụ.
	 * Luôn có mặt để .tpl đọc thẳng mà không phải isset() từng key.
	 * @return array
	 */
	private static function withImageSize($field){
		$field['default_width'] = !empty($field['width']) ? (int) $field['width'] : self::DEFAULT_IMAGE_SIZE;
		$field['default_height'] = !empty($field['height']) ? (int) $field['height'] : self::DEFAULT_IMAGE_SIZE;
		$field['demo'] = isset($field['demo']) ? (string) $field['demo'] : '';
		// Người dùng sửa được kích thước ngay trên form → 2 key phụ đi kèm key ảnh.
		$field['width_keyword'] = $field['keyword'].self::WIDTH_SUFFIX;
		$field['height_keyword'] = $field['keyword'].self::HEIGHT_SUFFIX;
		$field['size_keywords'] = array($field['width_keyword'], $field['height_keyword']);
		return $field;
	}

	/** @return bool */
	private static function needSelect($type){
		return in_array($type, array('select', 'select2', 'select_pair', 'stock_support'), true);
	}

	/** @return bool */
	private static function isBare($type){
		return in_array($type, array('checkbox', 'stock_support'), true);
	}

	/**
	 * Slug neo tab: ưu tiên khai báo, thiếu thì lấy chính group key.
	 * @return string
	 */
	private function slug($groupKey, $group){
		$slug = !empty($group['slug']) ? $group['slug'] : $groupKey;
		$slug = strtolower(preg_replace('/[^a-zA-Z0-9_-]+/', '-', $slug));
		return 'cfg-'.trim($slug, '-');
	}

	/**
	 * Quyền xem nhóm. Không khai 'permission' nghĩa là ai vào được trang thì xem được.
	 * @return bool
	 */
	private function allow($group){
		global $clsISO;
		if(empty($group['permission'])){
			return true;
		}
		if(!is_object($clsISO)){
			return false;
		}
		if($group['permission'] === 'dev'){
			return (int) $clsISO->_DEV() === 1;
		}
		return (int) $clsISO->checkPermission($group['permission']) === 1;
	}

	/**
	 * Khai báo thật. Chỉ sửa hàm này khi thêm/bớt cấu hình.
	 * Key giữ nguyên như dữ liệu đang chạy trên live để không phải migrate.
	 * @return array
	 */
	private static function declaration(){
		return array(
			'general' => array(
				'label'       => 'Cấu hình chung',
				'description' => 'Thông tin website được dùng để hiển thị và để khách hàng liên hệ đến bạn.',
				'slug'        => 'general',
				'icon'        => 'cog',
				'value'       => array(
					'site_name' => array(
						'type'        => 'text',
						'label'       => 'Tên website',
						'placeholder' => 'Nhập tên website',
						'required'    => true
					),
					'meta_title' => array(
						'type'        => 'text',
						'label'       => 'Tiêu đề trang chủ',
						'placeholder' => 'Nhập tiêu đề trang chủ',
						'required'    => true
					),
					'meta_description' => array(
						'type'        => 'textarea',
						'label'       => 'Mô tả trang chủ',
						'placeholder' => 'Nhập một mô tả để nâng cao xếp hạng trên công cụ tìm kiếm như Google.',
						'required'    => true,
						'rows'        => 2
					),
					'meta_keyword' => array(
						'type'        => 'textarea',
						'label'       => 'Thẻ từ khóa',
						'placeholder' => 'Nhập một danh sách từ khóa nâng cao xếp hạng trên công cụ tìm kiếm như Google',
						'rows'        => 2
					),
					'CompanyLogo' => array(
						'type'        => 'images',
						'label'       => 'Company Logo',
						'placeholder' => 'Company Logo',
						'width'       => 100,
						'height'      => 50,
						'demo'        => '/application/themes/images/no-image.jpg'
					),
					'HeaderLogo' => array(
						'type'        => 'images',
						'label'       => 'Header Logo',
						'placeholder' => 'Header Logo',
						'width'       => 100,
						'height'      => 50,
						'demo'        => '/application/themes/images/no-image.jpg'
					),
					'LogoWhite' => array(
						'type'        => 'images',
						'label'       => 'Logo màu trắng',
						'placeholder' => 'Company Logo',
						'width'       => 100,
						'height'      => 50,
						'demo'        => '/application/themes/images/no-image.jpg'
					),
					'Favicon' => array(
						'type'        => 'images',
						'label'       => 'Favicon',
						'placeholder' => 'Favicon',
						'width'       => 100,
						'height'      => 50,
						'demo'        => '/application/themes/images/no-image.jpg'
					),
					'BgHomeMobile' => array(
						'type'    => 'images',
						'label'   => 'Ảnh nền trang chủ bản mobile',
						'width'       => 882,
						'height'      => 853,
						'demo'        => '/application/themes/images/no-image.jpg',
						'help'        => 'Click để xem ví dụ <a href="https://ace.c-a.vn/application/themes/images/demo.png">nơi hiển thị</a>.',
						'help_html'   => true
					),
				)
			),
			'organization' => array(
				'label'       => 'Cấu hình tổ chức',
				'description' => 'Cấu trúc phòng ban và mô hình bán hàng của công ty.',
				'slug'        => 'organization',
				'icon'        => 'sitemap',
				'value'       => array(
					'has_business_area' => array(
						'type'    => 'checkbox',
						'label'   => 'Có Khối kinh doanh',
						'default' => '1',
						'help'    => 'Bật khi tổ chức theo Khối kinh doanh > Phòng kinh doanh. Tắt khi Phòng kinh doanh trực thuộc thẳng gốc kinh doanh (không có tầng Khối).',
					),
					'has_co_sale' => array(
						'type'    => 'checkbox',
						'label'   => 'Cho phép Co-sale (nhiều sale chia 1 giao dịch)',
						'default' => '1',
						'help'    => 'Bật để một giao dịch được chia cho nhiều sale theo tỷ lệ (sale chính + sale phụ). Tắt khi mỗi giao dịch chỉ có một sale.',
					)
				)
			),
			/** Gộp từ màn "Thông tin công ty" cũ (act=profile, nay chỉ còn redirect).
			    Key giữ y hệt bản cũ nên dữ liệu đang chạy dùng lại được, không migrate. */
			'brand' => array(
				'label'       => 'Nhận diện thương hiệu',
				'description' => 'Slogan, tên thương hiệu và màu chủ đạo dùng chung cho toàn hệ thống.',
				'slug'        => 'brand',
				'icon'        => 'star',
				'value'       => array(
					'slogan' => array(
						'type'        => 'text',
						'label'       => 'Slogan',
						'placeholder' => 'Nhập slogan công ty'
					),
					'checkin_brand_name' => array(
						'type'        => 'text',
						'label'       => 'Tên thương hiệu Check-in',
						'placeholder' => 'Vd: Future Way',
					),
					'BrandColor' => array(
						'type'    => 'color',
						'label'   => 'Màu thương hiệu',
						'default' => '#696cff'
					),
					'mail_it' => array(
						'type'        => 'text',
						'label'       => 'Email phòng công nghệ',
						'placeholder' => 'Chọn hiển thị email phòng công nghệ màn login'
					),
					'profile_default_pass' => array(
						'type'        => 'text',
						'label'       => 'Mật khẩu mặc định cho tài khoản mới',
						'placeholder' => 'Bỏ trống thì phải tự nhập mỗi lần import',
					),
				)
			),
			/** Thư mục ảnh của các khối trang chủ — nguồn duy nhất, không còn hằng số
			    dự phòng trong config.php: bỏ trống là khối đó không hiện ảnh nào. */
			'gdrive' => array(
				'label'       => 'Thư mục Google Drive',
				'description' => 'Thư mục chứa ảnh cho các khối ngoài trang chủ. Dán ID hoặc nguyên link thư mục Drive. Thư mục phải được chia sẻ cho tài khoản Drive của hệ thống, nếu không khối sẽ trống. Bỏ trống ô nào thì khối đó không hiển thị ảnh.',
				'slug'        => 'gdrive',
				'icon'        => 'folder-open',
				'value'       => array(
					'gdrive_folder_birthday' => array(
						'type'        => 'text',
						'label'       => 'Khối Chúc mừng Sinh nhật',
						'placeholder' => 'ID thư mục hoặc link, vd: 18VqAi5ifXOcwp_OX6pWbTB5UPuRGDnkX',
						'help'        => 'Thư mục chứa thiệp sinh nhật nhân viên.'
					),
					'gdrive_folder_wellcome' => array(
						'type'        => 'text',
						'label'       => 'Khối Chào đón thành viên mới',
						'placeholder' => 'ID thư mục hoặc link, vd: 1UeNKzHt4EU9xg66aSe9o9BUl8X8Fdo2Y',
						'help'        => 'Thư mục chứa ảnh giới thiệu nhân sự mới gia nhập.'
					),
					'gdrive_folder_checkin' => array(
						'type'        => 'text',
						'label'       => 'Check-in',
						'placeholder' => 'ID thư mục hoặc link, vd: 1UeNKzHt4EU9xg66aSe9o9BUl8X8Fdo2Y',
						'help'        => 'Thư mục chứa ảnh check-in, check-out.'
					),
					'gdrive_folder_setapp' => array(
						'type'        => 'text',
						'label'       => 'Set app',
						'placeholder' => 'Link google driver',
						'help'        => 'Thư mục hướng dẫn cài đặt app'
					),
					'gdrive_agency' => array(
						'type'        => 'text',
						'label'       => 'Đại lý thấp tầng',
						'placeholder' => 'ID thư mục hoặc link, vd: 1UeNKzHt4EU9xg66aSe9o9BUl8X8Fdo2Y',
						'help'        => 'Link driver nhập đại lý thấp tầng'
					)
				)
			),
			'company' => array(
				'label'       => 'Thông tin công ty',
				'description' => 'Tên, địa chỉ và đầu mối liên hệ in ra ở chân trang, email và các biểu mẫu.',
				'slug'        => 'company',
				'icon'        => 'building-o',
				'value'       => array(
					'CompanyName' => array(
						'type'        => 'text',
						'label'       => 'Tên công ty đầy đủ',
						'placeholder' => 'Nhập tên đầy đủ trên giấy phép'
					),
					'CompanyNameBrief' => array(
						'type'        => 'text',
						'label'       => 'Tên công ty viết tắt',
						'placeholder' => 'Nhập tên viết tắt'
					),
					'CompanyAddress1' => array(
						'type'        => 'text',
						'label'       => 'Địa chỉ trụ sở',
						'placeholder' => 'Nhập địa chỉ trụ sở chính'
					),
					'CompanyAddress' => array(
						'type'        => 'text',
						'label'       => 'Địa chỉ 2',
						'placeholder' => 'Nhập địa chỉ chi nhánh/văn phòng khác'
					),
					'CompanyPhone' => array(
						'type'        => 'text',
						'label'       => 'Điện thoại',
						'placeholder' => 'Nhập số điện thoại'
					),
					'CompanyFax' => array(
						'type'        => 'text',
						'label'       => 'Fax',
						'placeholder' => 'Nhập số fax'
					),
					'CompanyHotline' => array(
						'type'        => 'text',
						'label'       => 'Hotline',
						'placeholder' => 'Nhập số hotline'
					),
					'CompanyEmail' => array(
						'type'        => 'text',
						'label'       => 'Email liên hệ',
						'placeholder' => 'Nhập email chính'
					),
					'CompanyWebsite' => array(
						'type'        => 'text',
						'label'       => 'Website chính thức',
						'placeholder' => 'https://'
					),
					'CompanyCAWebsite' => array(
						'type'        => 'text',
						'label'       => 'Website CA',
						'placeholder' => 'https://'
					),
					'Copyright' => array(
						'type'        => 'text',
						'label'       => 'Dòng bản quyền',
						'placeholder' => 'Nhập dòng bản quyền cuối trang'
					),
					/** Toạ độ để ô text chứ không phải number: giá trị có dấu chấm thập phân
					    và độ dài vượt giới hạn số nguyên. */
					'CompanyMapLa' => array(
						'type'        => 'text',
						'label'       => 'Vĩ độ (latitude)',
						'placeholder' => 'Vd: 20.988668210459167',
						'help'        => 'Lấy từ Google Maps: chuột phải vào vị trí → toạ độ là cặp số hiện ra, số đầu là vĩ độ.'
					),
					'CompanyMapLo' => array(
						'type'        => 'text',
						'label'       => 'Kinh độ (longitude)',
						'placeholder' => 'Vd: 105.86727258378903',
						'help'        => 'Số thứ hai trong cặp toạ độ Google Maps.'
					),
					/** Gộp từ khối "Cài đặt" của màn profile cũ. Option lấy từ danh mục
					    _OFFICE (bảng setting) chứ không phải phòng ban. */
					'_OFFICE_CALENDAR_DEFAULT_ID' => array(
						'type'        => 'select',
						'label'       => 'Trụ sở chính',
						'placeholder' => 'Chọn văn phòng',
						'source'      => 'office',
						'help'        => 'Văn phòng mặc định của hệ thống. Danh sách khai ở Danh mục → Văn phòng.'
					),
					'agency' => array(
						'type'        => 'select2',
						'label'       => 'Đại lý',
						'placeholder' => 'Chọn đại lý',
						'multiple'    => true,
						'json'        => true,
						'source'      => 'agency',
						'help'        => 'Đại lý mặc định của hệ thống. Danh sách khai ở Danh mục → Đại lý.'
					)
				)
			),
			/** Gộp từ khối "Giao dịch" của màn profile cũ (act=profile, nay chỉ còn
			    redirect). Key giữ y hệt bản cũ nên không phải migrate dữ liệu. */
			'transaction' => array(
				'label'       => 'Giao dịch',
				'description' => 'Người duyệt giao dịch và các nhóm bị loại khỏi bảng xếp hạng.',
				'slug'        => 'transaction',
				'icon'        => 'money',
				'value'       => array(
					'confirm_billing' => array(
						'type'        => 'select_pair',
						'label'       => 'Duyệt giao dịch',
						'placeholder' => 'Chọn đối tượng duyệt',
						'json'        => true,
						'sub_keyword' => 'name',
						'select'      => array(
							'admin'           => 'Admin duyệt',
							'project_manager' => 'Giám đốc dự án duyệt'
						),
						'pair'        => array(
							'keyword'     => 'staff_id',
							'placeholder' => 'Chọn admin duyệt',
							/** Đúng danh sách admin của màn profile cũ; người đang được lưu
							    luôn được giữ lại trong option dù đã rời vai trò đó. */
							'source'      => 'profile_admin',
							'show_when'   => 'admin'
						),
						'help'        => 'Chọn "Admin duyệt" thì phải chỉ đích danh một admin nhận phiếu duyệt; bỏ trống thì giao dịch không qua bước duyệt nào.'
					),
					'department_not_rankking' => array(
						'type'        => 'select2',
						'label'       => 'Phòng ban không xếp hạng',
						'placeholder' => 'Chọn phòng ban',
						'multiple'    => true,
						'json'        => true,
						'source'      => 'department',
						'help'        => 'Áp dụng cho cả bảng xếp hạng cá nhân lẫn phòng ban.'
					),
					'role_not_rankking' => array(
						'type'        => 'select2',
						'label'       => 'Vai trò không xếp hạng',
						'placeholder' => 'Chọn vai trò',
						'multiple'    => true,
						'json'        => true,
						'source'      => 'role',
						'help'        => 'Áp dụng cho cả bảng xếp hạng cá nhân lẫn phòng ban.'
					),
					'role_request_ptg' => array(
						'type'        => 'select2',
						'label'       => 'Vai trò nhận thông báo yêu cầu PTG',
						'placeholder' => 'Chọn vai trò',
						'multiple'    => true,
						'json'        => true,
						'source'      => 'role'
					)
				)
			),
			'social' => array(
				'label'       => 'Mạng xã hội',
				'description' => 'Link trang mạng xã hội của công ty. Bỏ trống ô nào thì không hiện icon đó.',
				'slug'        => 'social',
				'icon'        => 'share-alt',
				'value'       => array(
					'SiteFacebookLink' => array(
						'type'        => 'text',
						'label'       => 'Facebook',
						'placeholder' => 'https://facebook.com/...'
					),
					'SiteTwitterLink' => array(
						'type'        => 'text',
						'label'       => 'Twitter',
						'placeholder' => 'https://twitter.com/...'
					),
					'SiteGoogleLink' => array(
						'type'        => 'text',
						'label'       => 'Google+',
						'placeholder' => 'https://plus.google.com/...'
					),
					'SiteLikedinLink' => array(
						'type'        => 'text',
						'label'       => 'LinkedIn',
						'placeholder' => 'https://linkedin.com/...'
					),
					'SitePrintestLink' => array(
						'type'        => 'text',
						'label'       => 'Pinterest',
						'placeholder' => 'https://pinterest.com/...'
					)
				)
			),
			'target' => array(
				'label'       => 'Cấu hình mục tiêu',
				'description' => 'Cấu hình điểm số cá nhân và toàn công ty.',
				'slug'        => 'target',
				'icon'        => 'check-circle',
				'value'       => array(
					'total_transactions' => array(
						'type'  => 'number',
						'label' => 'Tổng giao dịch toàn công ty'
					),
					'total_score' => array(
						'type'  => 'number',
						'label' => 'Mục tiêu điểm số cá nhân'
					)
				)
			),
			/** Bản đồ vai trò → màn hình trang chủ, thay cho chuỗi elseif hardcode trong
			    default.tpl. Thứ tự xét nằm trong ISO::getHomeScreen() và KHÔNG cấu hình
			    được: một người khớp nhiều màn hình thì màn hình xét trước thắng. */
			'home_screen' => array(
				'label'       => 'Màn hình trang chủ',
				'description' => 'Chọn vai trò được dùng từng màn hình trang chủ. Thứ tự xét cố định: GĐ Kinh doanh → Ban lãnh đạo → Admin dự án → Kế toán; ai không khớp màn hình nào thì dùng màn hình mặc định của nhân viên. Bỏ trống ô nào thì màn hình đó giữ nguyên vai trò mặc định của hệ thống.',
				'slug'        => 'home-screen',
				'icon'        => 'desktop',
				'value'       => array(
					'home_screen_sale_roles' => array(
						'type'        => 'select2',
						'label'       => 'Màn hình GĐ Kinh doanh',
						'placeholder' => 'Chọn vai trò',
						'multiple'    => true,
						'json'        => true,
						'source'      => 'role',
						'help'        => 'Mặc định: GĐ Dự án, GĐ Kinh doanh, Trưởng phòng Kinh doanh, GĐ Vùng.'
					),
					'home_screen_director_roles' => array(
						'type'        => 'select2',
						'label'       => 'Màn hình Ban lãnh đạo',
						'placeholder' => 'Chọn vai trò',
						'multiple'    => true,
						'json'        => true,
						'source'      => 'role',
						'help'        => 'Mặc định: Ban lãnh đạo, Tổng GĐ, Phó Tổng GĐ, Chủ tịch HĐQT.'
					),
					'home_screen_admin_roles' => array(
						'type'        => 'select2',
						'label'       => 'Màn hình Admin dự án — theo vai trò',
						'placeholder' => 'Chọn vai trò',
						'multiple'    => true,
						'json'        => true,
						'source'      => 'role',
						'help'        => 'Mặc định màn hình này chỉ xét phòng ban, chưa xét vai trò nào.'
					),
					'home_screen_admin_departments' => array(
						'type'        => 'select2',
						'label'       => 'Màn hình Admin dự án — theo phòng ban',
						'placeholder' => 'Chọn phòng ban',
						'multiple'    => true,
						'json'        => true,
						'source'      => 'department',
						'help'        => 'Mặc định: phòng Marketing. Khớp vai trò HOẶC phòng ban đều vào được màn hình này.'
					),
					'home_screen_accountant_roles' => array(
						'type'        => 'select2',
						'label'       => 'Màn hình Kế toán',
						'placeholder' => 'Chọn vai trò',
						'multiple'    => true,
						'json'        => true,
						'source'      => 'role',
						'help'        => 'Mặc định: các vai trò kế toán khai trong _ROLE_ACCOUNTANT.'
					),
					'home_screen_bypass_profiles' => array(
						'type'        => 'select2',
						'label'       => 'Luôn dùng màn hình mặc định',
						'placeholder' => 'Chọn nhân viên',
						'multiple'    => true,
						'json'        => true,
						'source'      => 'profile',
						'help'        => 'Nhân sự trong danh sách này bỏ qua toàn bộ màn hình theo vai trò ở trên.'
					)
				)
			),
			'robots' => array(
				'label'       => 'Meta Robot',
				'description' => 'Meta Robot cho phép Google bot thu thập nội dung website của bạn.',
				'slug'        => 'robots',
				'icon'        => 'search',
				'value'       => array(
					'robots' => array(
						'type'  => 'text',
						'label' => 'Meta Robot'
					),
					'googlebot' => array(
						'type'  => 'text',
						'label' => 'Googlebot'
					)
				)
			),
			'notify' => array(
				'label'       => 'Thông báo Zalo',
				'description' => 'Nhận thông báo Zalo khi có yêu cầu mua gói data khách hàng.',
				'slug'        => 'notify',
				'icon'        => 'cloud',
				'value'       => array(
					'notify_zalo_recipient' => array(
						'type'        => 'select2',
						'label'       => 'Thành viên nhận thông báo',
						'placeholder' => 'Chọn nhân viên',
						'multiple'    => true,
						'json'        => true,
						'source'      => 'profile'
					)
				)
			),
			'tracking' => array(
				'label'       => 'Tích hợp & Mã theo dõi',
				'description' => 'Mã nhúng của bên thứ ba: Google, Facebook, LiveChat. Dán nguyên đoạn script được cấp.',
				'slug'        => 'tracking',
				'icon'        => 'line-chart',
				'value'       => array(
					'google_verity_key' => array(
						'type'  => 'text',
						'label' => 'Mã Google Verify Key',
						'help'  => '<meta name="google-site-verification" value="google_verity_key" />'
					),
					'google_analytic' => array(
						'type'        => 'textarea',
						'label'       => 'Script Google Analytics',
						'placeholder' => 'Nhập mã Google Analytics tại đây',
						'rows'        => 3,
						'raw'         => true
					),
					'facebook_pixel' => array(
						'type'        => 'textarea',
						'label'       => 'Facebook Pixel',
						'placeholder' => 'Nhập Facebook Pixel tại đây',
						'rows'        => 3,
						'raw'         => true
					),
					'livechat' => array(
						'type'        => 'textarea',
						'label'       => 'Script LiveChat',
						'placeholder' => 'Nhập Script LiveChat, vd: Subiz, Talk.to, Zalo, Facebook Messenger',
						'rows'        => 3,
						'raw'         => true
					)
				)
			),
//			'payment' => array(
//				'label'       => 'Thanh toán & Tín dụng',
//				'description' => 'Liên hệ tín dụng, tài khoản hỗ trợ MOC và tài khoản nhận thanh toán nâng cấp gói.',
//				'slug'        => 'payment',
//				'icon'        => 'credit-card',
//				'value'       => array(
//					'zalo_credit' => array(
//						'type'  => 'text',
//						'label' => 'Zalo tín dụng'
//					),
//					'phone_credit' => array(
//						'type'  => 'text',
//						'label' => 'Điện thoại tín dụng'
//					),
//					'stock_support_configs' => array(
//						'type'         => 'stock_support',
//						'label'        => 'Tài khoản hỗ trợ MOC',
//						'placeholder'  => 'Gõ tên [OR] email để tìm kiếm',
//						'json'         => true,
//						'source'       => 'block_type',
//						'pair_keyword' => 'stock_support_extra_configs'
//					),
//					'bank_name_MOC' => array(
//						'type'  => 'text',
//						'label' => 'Tên ngân hàng'
//					),
//					/** Số tài khoản là chuỗi: có thể bắt đầu bằng số 0 và dài hơn giới hạn số nguyên. */
//					'bank_number_MOC' => array(
//						'type'  => 'text',
//						'label' => 'Tài khoản ngân hàng'
//					),
//					'bank_user_name_MOC' => array(
//						'type'  => 'text',
//						'label' => 'Tên chủ tài khoản'
//					),
//					'email_support_MOC' => array(
//						'type'  => 'text',
//						'label' => 'Email hỗ trợ'
//					),
//					'phone_support_MOC' => array(
//						'type'  => 'text',
//						'label' => 'Điện thoại hỗ trợ'
//					)
//				)
//			),
			/** Giao diện do bản triển khai quyết định, không đổi từ admin nữa.
			    Ẩn thay vì xoá để giữ nguyên giá trị đang có trong DB. */
			'theme' => array(
				'label'       => 'Giao diện',
				'description' => 'Lựa chọn giao diện phù hợp cho website.',
				'slug'        => 'theme',
				'icon'        => 'list',
				'permission'  => 'dev',
				'hidden'      => true,
				'value'       => array(
					'SiteTemplate' => array(
						'type'   => 'select',
						'label'  => 'Giao diện đang sử dụng',
						'source' => 'app_template'
					)
				)
			),
			'worktime' => array(
				'label'       => 'Thời gian cập nhật bảng hàng',
				'description' => 'Khung giờ cho phép cập nhật bảng hàng trong ngày.',
				'slug'        => 'worktime',
				'icon'        => 'bars',
				'hidden'      => true,
				'value'       => array(
					'morning_start' => array(
						'type'  => 'time',
						'label' => 'Buổi sáng — bắt đầu'
					),
					'morning_end' => array(
						'type'  => 'time',
						'label' => 'Buổi sáng — kết thúc'
					),
					'afternoon_start' => array(
						'type'  => 'time',
						'label' => 'Buổi chiều — bắt đầu'
					),
					'afternoon_end' => array(
						'type'  => 'time',
						'label' => 'Buổi chiều — kết thúc'
					)
				)
			),
			'docquyen' => array(
				'label'       => 'Trang độc quyền',
				'description' => 'SEO, ảnh chia sẻ và nội dung chân trang cho trang Quỹ hàng độc quyền (/docquyen).',
				'slug'        => 'docquyen',
				'icon'        => 'certificate',
				'value'       => array(
					'docquyen_meta_title' => array(
						'type'        => 'text',
						'label'       => 'Meta title',
						'placeholder' => 'Tiêu đề trang độc quyền (thẻ title)'
					),
					'docquyen_meta_description' => array(
						'type'        => 'textarea',
						'label'       => 'Meta description',
						'placeholder' => 'Mô tả ngắn cho SEO và khi chia sẻ liên kết',
						'rows'        => 2
					),
					'docquyen_image_share' => array(
						'type'        => 'images',
						'label'       => 'Ảnh chia sẻ (og:image)',
						'placeholder' => 'Ảnh hiển thị khi chia sẻ liên kết trang độc quyền',
						'width'       => 120,
						'height'      => 63,
						'demo'        => '/application/themes/images/no-image.jpg'
					),
					'docquyen_footer_content' => array(
						'type'        => 'editor',
						'label'       => 'Nội dung chân trang',
						'placeholder' => 'Nội dung hiển thị ở chân trang độc quyền (hỗ trợ HTML)',
						'rows'        => 6
					)
				)
			),
			'customer_support' => array(
				'label'       => 'Hỗ trợ khách hàng 24/7',
				'description' => 'Thông tin hiển thị ở khối "Hỗ trợ khách hàng 24/7" tại chân trang website.',
				'slug'        => 'customer-support',
				'icon'        => 'headphones',
				'value'       => array(
					'support_hotline' => array(
						'type'        => 'text',
						'label'       => 'Hotline',
						'placeholder' => 'Số hotline hỗ trợ, vd: 0983 886 538'
					),
					'support_email' => array(
						'type'        => 'text',
						'label'       => 'Email',
						'placeholder' => 'Email hỗ trợ khách hàng, vd: hotro@fivestar.vn'
					),
					'support_zalo' => array(
						'type'        => 'text',
						'label'       => 'Zalo',
						'placeholder' => 'Số hoặc link Zalo OA, vd: https://zalo.me/0983886538'
					)
				)
			),
			'theme' => array(
				'label'       => 'Giao diện',
				'description' => 'Lựa chọn giao diện phù hợp cho website.',
				'slug'        => 'theme',
				'icon'        => 'list',
				'permission'  => 'dev',
				'hidden'      => true,
				'value'       => array(
					'SiteTemplate' => array(
						'type'   => 'select',
						'label'  => 'Giao diện đang sử dụng',
						'source' => 'app_template'
					)
				)
			),
			'worktime' => array(
				'label'       => 'Thời gian cập nhật bảng hàng',
				'description' => 'Khung giờ cho phép cập nhật bảng hàng trong ngày.',
				'slug'        => 'worktime',
				'icon'        => 'bars',
				'hidden'      => true,
				'value'       => array(
					'morning_start' => array(
						'type'  => 'time',
						'label' => 'Buổi sáng — bắt đầu'
					),
					'morning_end' => array(
						'type'  => 'time',
						'label' => 'Buổi sáng — kết thúc'
					),
					'afternoon_start' => array(
						'type'  => 'time',
						'label' => 'Buổi chiều — bắt đầu'
					),
					'afternoon_end' => array(
						'type'  => 'time',
						'label' => 'Buổi chiều — kết thúc'
					)
				)
			)
		);
	}
}
?>
