<?php

// use Detection\Exception\MobileDetectException;
// use Detection\MobileDetectStandalone;

// require_once 'Mobile-Detect/standalone/autoloader.php';
// require_once 'Mobile-Detect/src/MobileDetectStandalone.php';
// $detection = new MobileDetectStandalone();

// define('IS_MOBILE', $detection->isMobile() && !$detection->isTablet());

// tạm thời define ở đây:
define('IS_MOBILE', wp_is_mobile());

function get_full_content($post_id)     
{
    $post = get_post($post_id);
    if (!$post) return '';
    return apply_filters('the_content', $post->post_content);
}


function ration_add_featured_image_html($html)
{
    $screen = get_current_screen();

    $post = [
        'post' => '<p></p>',
    ];

    $page = [
        // page ID => thông báo
    ];

    $post_type = get_post_type();

    if (array_key_exists($post_type, $post)) {
        $html .= $post[$post_type];
    } elseif (is_admin() && ($screen->id == 'page')) {
        global $post;
        $id = $post->ID;
        if (array_key_exists($id, $page)) {
            $html .= $page[$id];
        }
    }

    return $html;
}
add_filter('admin_post_thumbnail_html', 'ration_add_featured_image_html');
add_filter('big_image_size_threshold', '__return_false');

function validate_phone_number_cf7($result, $tag) {
  if ($tag->name !== 'phone-number') {
    return $result;
  }

  $phone = trim($_POST['phone-number'] ?? '');

  /**
   * Chấp nhận:
   *  - Phone numbers with digits, spaces, hyphens, plus, parentheses, 8-20 characters
   */
  $pattern = '/^[\d\s\-\+\(\)]{8,20}$/';

  if (!preg_match($pattern, $phone)) {
    $result->invalidate($tag, __('Invalid phone number', 'textdomain'));
  }

  return $result;
}

add_filter('wpcf7_validate_tel*', 'validate_phone_number_cf7', 10, 2);
add_filter('wpcf7_validate_tel', 'validate_phone_number_cf7', 10, 2);

function isMobileDevice()
{
	if (!isset($_SERVER['HTTP_USER_AGENT'])) {
		return true;
	}
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);

	// Nhận diện nhanh một số tablet phổ biến
	if (strpos($useragent, 'ipad') !== false || strpos($useragent, 'tablet') !== false || strpos($useragent, 'kindle') !== false || strpos($useragent, 'silk') !== false || strpos($useragent, 'playbook') !== false) {
		return true;
	}

	return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent)
		|| preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));
}

add_filter('woocommerce_shop_order_search_fields', function ($fields) {
    $fields[] = '_billing_phone';
    $fields[] = '_shipping_phone';
    return $fields;
});

/**
 * Force search.php template when is_search() is true,
 * even when post_type=product (WooCommerce overrides with archive-product.php).
 * Priority 20 to run AFTER WooCommerce's template_loader (priority 10).
 */
add_filter('template_include', function ($template) {
    if (is_search()) {
        $search_template = locate_template('search.php');
        if ($search_template) {
            return $search_template;
        }
    }
    return $template;
}, 20);

/**
 * WooCommerce: không redirect thẳng tới trang sản phẩm khi tìm kiếm chỉ ra 1 kết quả.
 * (Mặc định WC dùng filter `woocommerce_redirect_single_search_result` = true.)
 * Trang `search.php` của theme cần hiển thị danh sách + tab, không nhảy permalink.
 */
add_filter('woocommerce_redirect_single_search_result', '__return_false');




add_action('admin_footer-post.php', 'okhub_service_category_radio_ui');
add_action('admin_footer-post-new.php', 'okhub_service_category_radio_ui');

function okhub_service_category_radio_ui() {
    global $post;

    if (!$post || get_post_type($post) !== 'service') return;
    ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const taxonomy = 'service_category';
    const taxonomyBox = document.querySelector('#taxonomy-service_category');

    if (!taxonomyBox) return;

    const inputs = taxonomyBox.querySelectorAll('input[type="checkbox"]');

    inputs.forEach(function(input) {
        input.type = 'radio';
        input.name = 'tax_input[' + taxonomy + '][]';
    });

    taxonomyBox.addEventListener('change', function(e) {
        const current = e.target;

        if (!current.matches('input[type="radio"]')) return;

        const currentValue = current.value;

        taxonomyBox.querySelectorAll('input[type="radio"]').forEach(function(input) {
            input.checked = input.value === currentValue;
        });
    });
});
</script>

<style>
#taxonomy-service_category input[type="radio"] {
    margin-right: 6px;
}
</style>
<?php
}

add_action('admin_footer-post.php', 'okhub_required_service_fields_notice');
add_action('admin_footer-post-new.php', 'okhub_required_service_fields_notice');

function okhub_required_service_fields_notice() {
    global $post;

    if (!$post || get_post_type($post) !== 'service') return;
    ?>
<style>
.okhub-acf-notice {
    margin: 10px 0 20px;
}

.okhub-acf-notice p {
    margin: 0;
}

.okhub-acf-notice ul {
    margin: 8px 0 0 18px;
    list-style: disc;
}

.okhub-field-error {
    border: 2px solid #d63638 !important;
}

.okhub-taxonomy-error {
    border: 2px solid #d63638 !important;
    padding: 8px !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const publishBtn = document.querySelector('#publish');
    const titleInput = document.querySelector('#title');
    const taxonomyBox = document.querySelector('#taxonomy-service_category');

    if (!publishBtn) return;

    function getSelectedCategory() {
        if (!taxonomyBox) return null;

        return taxonomyBox.querySelector(
            'input[name="tax_input[service_category][]"]:checked:not([value="0"])'
        );
    }

    function getValidationErrors() {
        const errors = [];

        const titleValue = titleInput ? titleInput.value.trim() : '';

        if (!titleValue) {
            errors.push({
                key: 'title',
                label: 'Tiêu đề',
                message: 'Vui lòng nhập tiêu đề.'
            });
        }

        if (!getSelectedCategory()) {
            errors.push({
                key: 'category',
                label: 'Service Category',
                message: 'Vui lòng chọn Service Category.'
            });
        }

        return errors;
    }

    function clearAcfNotice() {
        document.querySelectorAll('.okhub-acf-notice').forEach(function(el) {
            el.remove();
        });
    }

    function showAcfNotice(errors) {
        const wrap = document.querySelector('#wpbody-content .wrap');

        if (!wrap) {
            alert(errors.map(function(error) {
                return error.message;
            }).join('\n'));
            return;
        }

        clearAcfNotice();

        const notice = document.createElement('div');
        notice.className = 'acf-notice -error acf-error-message okhub-acf-notice';

        const titleMessage = document.createElement('p');
        titleMessage.innerHTML = '<strong>Validation failed. Please check:</strong>';
        notice.appendChild(titleMessage);

        const ul = document.createElement('ul');

        errors.forEach(function(error) {
            const li = document.createElement('li');
            li.innerHTML = '<strong>' + error.label + ':</strong> ' + error.message;
            ul.appendChild(li);
        });

        notice.appendChild(ul);

        const title = wrap.querySelector('h1');

        if (title) {
            title.insertAdjacentElement('afterend', notice);
        } else {
            wrap.prepend(notice);
        }

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function resetPublishBtn() {
        publishBtn.disabled = false;
        publishBtn.classList.remove('disabled', 'button-primary-disabled');

        const spinner = document.querySelector('#publishing-action .spinner');

        if (spinner) {
            spinner.classList.remove('is-active');
            spinner.style.visibility = 'hidden';
        }
    }

    function setTitleError() {
        if (!titleInput) return;
        titleInput.classList.add('okhub-field-error');
    }

    function removeTitleError() {
        if (!titleInput) return;
        titleInput.classList.remove('okhub-field-error');
    }

    function setCategoryError() {
        if (!taxonomyBox) return;
        taxonomyBox.classList.add('okhub-taxonomy-error');
    }

    function removeCategoryError() {
        if (!taxonomyBox) return;
        taxonomyBox.classList.remove('okhub-taxonomy-error');
    }

    function removeNoticeIfValid() {
        const errors = getValidationErrors();

        if (!errors.length) {
            clearAcfNotice();
        }
    }

    if (titleInput) {
        titleInput.addEventListener('input', function() {
            if (titleInput.value.trim()) {
                removeTitleError();
                removeNoticeIfValid();
            }
        });
    }

    if (taxonomyBox) {
        taxonomyBox.addEventListener('change', function() {
            if (getSelectedCategory()) {
                removeCategoryError();
                removeNoticeIfValid();
            }
        });
    }

    publishBtn.addEventListener('click', function(e) {
        const errors = getValidationErrors();

        if (!errors.length) return true;

        e.preventDefault();
        e.stopImmediatePropagation();

        showAcfNotice(errors);

        const hasTitleError = errors.some(function(error) {
            return error.key === 'title';
        });

        const hasCategoryError = errors.some(function(error) {
            return error.key === 'category';
        });

        if (hasTitleError) {
            setTitleError();

            if (titleInput) {
                titleInput.focus();
            }
        } else {
            removeTitleError();
        }

        if (hasCategoryError) {
            setCategoryError();

            if (!hasTitleError && taxonomyBox) {
                taxonomyBox.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        } else {
            removeCategoryError();
        }

        resetPublishBtn();

        return false;
    }, true);
});
</script>
<?php
}

add_action('save_post_service', 'okhub_limit_one_service_category', 99);

function okhub_limit_one_service_category($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $taxonomy = 'service_category';

    if (empty($_POST['tax_input'][$taxonomy])) return;

    $terms = array_map('absint', (array) $_POST['tax_input'][$taxonomy]);
    $terms = array_filter($terms);
    $terms = array_values(array_unique($terms));

    if (count($terms) > 1) {
        $last_term = end($terms);
        wp_set_post_terms($post_id, [$last_term], $taxonomy, false);
    }
}