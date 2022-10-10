<?php
defined('BASEPATH') OR exit('No direct script access allowed');

## User
$route['api/user/login'] = "api_user/Api_user_login/user_login";
$route['api/user/updateStripeConnection'] = "api_user/Api_user_login/updateStripeConnection";
$route['api/user/verify'] = "api_user/Api_user_login/user_verify";
$route['api/user/register'] = "api_user/Api_user_login/user_register";
$route['api/user/reset-user'] = "api_user/Api_user_login/user_reset_password";
$route['api/user/send-otp'] = "api_user/Api_user_login/send_otp";
$route['api/user/facebook-login'] = "api_user/Api_user_login/facebook_login";
$route['api/user/details'] = "api_user/Api_user_login/get_user_detail";

$route['api/user/student-profile'] = "api_user/Api_user_login/student_profile";
$route['api/user/tutor-profile'] = "api_user/Api_user_login/tutor_profile";


$route['api/cat/what-would-learn'] = "api_categories/Api_categories/what_would_learn";
$route['api/cat/tutor-list'] = "api_categories/Api_categories/tutor_list";

##categories
$route['api/cat/get-category'] = "api_categories/Api_categories/get_category";
$route['api/cat/get-topics'] = "api_categories/Api_categories/get_topics";
$route['api/help'] = "api_categories/Api_categories/help_send";
$route['api/contact-us'] = "api_categories/Api_categories/contact_us";
$route['api/cat/get-sub-cat'] = "api_categories/Api_categories/get_sub_cat";

$route['api/cat/get-sub-cat-detail'] = "api_categories/Api_categories/get_sub_cat_detail";

$route['api/cat/get-detail-by-topic'] = "api_categories/Api_categories/get_detail_by_topic";

## connection
$route['api/cat/add-connection'] = "api_categories/Api_categories/add_connection";

$route['api/cat/get-connection'] = "api_categories/Api_categories/get_connection";
$route['api/cat/update-connection'] = "api_categories/Api_categories/update_connection";
$route['api/cat/approve-connection'] = "api_categories/Api_categories/approve_connection";
$route['api/cat/remove-connection'] = "api_categories/Api_categories/remove_connection";

$route['api/cat/list-connection'] = "api_categories/Api_categories/list_connection";


##notification
$route['api/cat/remove-notifications'] = "api_categories/Api_categories/remove_notification";
$route['api/cat/read-notifications'] = "api_categories/Api_categories/read_notification";

$route['api/cat/show-notifications'] = "api_categories/Api_categories/show_notification";

#tc faq
$route['api/cat/tc'] = "api_categories/Api_categories/terms_cond";
$route['api/cat/faq'] = "api_categories/Api_categories/faq";
$route['api/cat/pp'] = "api_categories/Api_categories/pp";

## cat sub cat get
$route['api/cat/cat-sub-cat'] = "api_categories/Api_categories/cat_sub_cat";


$route['api/cat/check-connection'] = "api_categories/Api_categories/check_connection";

##cart
$route['api/cat/add_cart'] = "api_categories/Api_categories/add_cart";
$route['api/cat/remove-cart'] = "api_categories/Api_categories/remove_cart";
$route['api/cat/remove-single-cart'] = "api_categories/Api_categories/remove_single_cart";
$route['api/cat/list_cart'] = "api_categories/Api_categories/list_cart";
$route['api/cat/cart-checkout'] = "api_categories/Api_categories/checkout";


$route['api/cat/list_paid_cart'] = "api_categories/Api_categories/list_paid_cart";



$route['api/cat/get-transaction'] = "api_categories/Api_categories/get_transaction";

## Card

$route['api/cat/add-card'] = "api_categories/Api_categories/add_card";
$route['api/cat/get-card'] = "api_categories/Api_categories/get_card";
$route['api/cat/get-card-details'] = "api_categories/Api_categories/get_card_detail";


## dashboard
$route['api/cat/get-overall-earning'] = "api_categories/Api_categories/get_overall_earning";



##schedule
$route['api/schedule/add'] = "api_schedule/Api_schedule/add_schedule";
$route['api/schedule/is-connected'] = "api_schedule/Api_schedule/is_connected";
$route['api/schedule/list'] = "api_schedule/Api_schedule/list_schedule";
$route['api/schedule/list-course'] = "api_schedule/Api_schedule/list_course";

$route['api/schedule/list-s-by-user'] = "api_schedule/Api_schedule/list_s_user";
$route['api/schedule/list-c-by-user'] = "api_schedule/Api_schedule/list_c_user";

$route['api/schedule/list-by-user'] = "api_schedule/Api_schedule/list_s_by_user";
$route['api/schedule/list-course-by-user'] = "api_schedule/Api_schedule/list_c_by_user";


$route['api/schedule/list-course-by-user-id'] = "api_schedule/Api_schedule/list_course_by_user_id";
// $route['api/schedule/list-course-by-user-id-and-type'] = "api_schedule/Api_schedule/list_course_by_user_id_and_type";

$route['api/schedule/add-one2one'] = "api_schedule/Api_schedule/add_one2one";
$route['api/schedule/add-course'] = "api_schedule/Api_schedule/add_course";

$route['api/schedule/set-one2one'] = "api_schedule/Api_schedule/set_one2one";
$route['api/schedule/update-one2one'] = "api_schedule/Api_schedule/update_one2one";
$route['api/schedule/get-one2one'] = "api_schedule/Api_schedule/get_one2one";
$route['api/schedule/my-call'] = "api_schedule/Api_schedule/my_call";
$route['api/schedule/payment_history'] = "api_schedule/Api_schedule/user_payment_history";



$route['api/schedule/list-calender'] = "api_schedule/Api_schedule/list_schedule_for_calender";

$route['api/schedule/get-by-id'] = "api_schedule/Api_schedule/get_by_id";
$route['api/schedule/update-by-id'] = "api_schedule/Api_schedule/update_by_id";

$route['api/schedule/edit-schedule'] = "api_schedule/Api_schedule/edit_schedule";
$route['api/schedule/edit-course'] = "api_schedule/Api_schedule/edit_course";
$route['api/schedule/show-one2one'] = "api_schedule/Api_schedule/show_one2one";

$route['api/schedule/get-one2one-byId'] = "api_schedule/Api_schedule/get_one2one_byId";

$route['api/schedule/get-user-meeting'] = "api_schedule/Api_schedule/get_user_meeting";

$route['api/schedule/add-watch'] = "api_schedule/Api_schedule/add_watch";
$route['api/schedule/watch-history'] = "api_schedule/Api_schedule/watch_history";

##upload
$route['api/schedule/upload'] = "api_schedule/Api_schedule/uploadFile";

#chat
$route['api/chat/get-chat-users'] = "api_schedule/Api_schedule/chat_user_list";
$route['api/chat/get-chat-message'] = "api_schedule/Api_schedule/chat_user_message";
$route['api/chat/get-meeting-message'] = "api_schedule/Api_schedule/meeting_user_message";
$route['api/chat/remove-group'] = "api_schedule/Api_schedule/remove_group";



##review

$route['api/schedule/add-review'] = "api_schedule/Api_schedule/add_review";
$route['api/schedule/show-review'] = "api_schedule/Api_schedule/show_review";
$route['api/schedule/get-review-by-id'] = "api_schedule/Api_schedule/get_review_by_id";


##account
$route['api/user/updateAccount'] = "api_user/Api_user_login/updateAccount";
$route['api/user/compareOldPassword'] = "api_user/Api_user_login/compareOldPassword";

$route['api/user/profileImgUpdate'] = "api_user/Api_user_login/profileImgUpdate";


$route['api/user/logout'] = "api_user/Api_user_login/user_logout";
$route['api/user/get_user'] = "api_user/Api_user/get_user";
$route['api/get-user-list'] = "api_user/Api_user/get_user_list";



$route['api/user/get_user_group'] = "api_user/Api_user/get_user_group";
$route['api/user/add_user_group'] = "api_user/Api_user/add_user_group";
$route['api/user/delete_group'] = "api_user/Api_user/delete_group";
$route['api/user/change_user_status'] = "api_user/Api_user/change_user_status";

$route['api/user/search_user'] = "api_user/Api_user/search_user";
$route['api/user/get_edit_user'] = "api_user/Api_user/get_edit_user";
$route['api/user/registration'] = "api_user/Api_user_registration/user_registration";
$route['api/user/delete_user'] = "api_user/Api_user_registration/delete_user";
$route['api/user/update_registration'] = "api_user/Api_user_registration/update_user_registration";

##forgot user
$route['api/user/forgot_user'] = "api_user/Api_user/forgot_password";


$route['api/cat/remove-connection-notification'] = "api_categories/Api_categories/remove_connection_notification";




