<?php

/**
 * A helper file for Dcat Admin, to provide autocomplete information to your IDE
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author jqh <841324345@qq.com>
 */
namespace Dcat\Admin {
    use Illuminate\Support\Collection;

    /**
     * @property Grid\Column|Collection id
     * @property Grid\Column|Collection name
     * @property Grid\Column|Collection type
     * @property Grid\Column|Collection version
     * @property Grid\Column|Collection detail
     * @property Grid\Column|Collection created_at
     * @property Grid\Column|Collection updated_at
     * @property Grid\Column|Collection is_enabled
     * @property Grid\Column|Collection parent_id
     * @property Grid\Column|Collection order
     * @property Grid\Column|Collection icon
     * @property Grid\Column|Collection uri
     * @property Grid\Column|Collection extension
     * @property Grid\Column|Collection permission_id
     * @property Grid\Column|Collection menu_id
     * @property Grid\Column|Collection slug
     * @property Grid\Column|Collection http_method
     * @property Grid\Column|Collection http_path
     * @property Grid\Column|Collection role_id
     * @property Grid\Column|Collection user_id
     * @property Grid\Column|Collection value
     * @property Grid\Column|Collection username
     * @property Grid\Column|Collection password
     * @property Grid\Column|Collection avatar
     * @property Grid\Column|Collection remember_token
     * @property Grid\Column|Collection uuid
     * @property Grid\Column|Collection connection
     * @property Grid\Column|Collection queue
     * @property Grid\Column|Collection payload
     * @property Grid\Column|Collection exception
     * @property Grid\Column|Collection failed_at
     * @property Grid\Column|Collection email
     * @property Grid\Column|Collection token
     * @property Grid\Column|Collection tokenable_type
     * @property Grid\Column|Collection tokenable_id
     * @property Grid\Column|Collection abilities
     * @property Grid\Column|Collection last_used_at
     * @property Grid\Column|Collection expires_at
     * @property Grid\Column|Collection email_verified_at
     * @property Grid\Column|Collection bank_country
     * @property Grid\Column|Collection bank_swift
     * @property Grid\Column|Collection bank_name
     * @property Grid\Column|Collection bank_code
     * @property Grid\Column|Collection bank_branch_code
     * @property Grid\Column|Collection bank_branch_addr
     * @property Grid\Column|Collection bank_account
     * @property Grid\Column|Collection bank_number
     * @property Grid\Column|Collection book_id
     * @property Grid\Column|Collection status
     * @property Grid\Column|Collection content
     * @property Grid\Column|Collection user_input
     * @property Grid\Column|Collection step
     * @property Grid\Column|Collection page
     * @property Grid\Column|Collection user_pic_input_who
     * @property Grid\Column|Collection user_pic_input_where
     * @property Grid\Column|Collection user_pic_input_what
     * @property Grid\Column|Collection story_pic_ai_1
     * @property Grid\Column|Collection story_pic_ai_2
     * @property Grid\Column|Collection story_pic_ai_3
     * @property Grid\Column|Collection story_pic_ai_4
     * @property Grid\Column|Collection user_pic_select
     * @property Grid\Column|Collection position_input
     * @property Grid\Column|Collection ch_story_ai
     * @property Grid\Column|Collection en_story_ai
     * @property Grid\Column|Collection en_user_input
     * @property Grid\Column|Collection auth_id
     * @property Grid\Column|Collection book_point
     * @property Grid\Column|Collection book_income
     * @property Grid\Column|Collection book_fee
     * @property Grid\Column|Collection auth_point_before
     * @property Grid\Column|Collection auth_point_after
     * @property Grid\Column|Collection user_point_before
     * @property Grid\Column|Collection user_point_after
     * @property Grid\Column|Collection stars
     * @property Grid\Column|Collection book_frontcover
     * @property Grid\Column|Collection book_name_ch
     * @property Grid\Column|Collection book_name_en
     * @property Grid\Column|Collection book_author
     * @property Grid\Column|Collection book_author_id
     * @property Grid\Column|Collection style
     * @property Grid\Column|Collection book_verify
     * @property Grid\Column|Collection book_shelf
     * @property Grid\Column|Collection book_state
     * @property Grid\Column|Collection cover_image_1
     * @property Grid\Column|Collection cover_image_2
     * @property Grid\Column|Collection cover_image_3
     * @property Grid\Column|Collection cover_image_4
     * @property Grid\Column|Collection preface
     * @property Grid\Column|Collection book_backcover
     * @property Grid\Column|Collection story_main
     * @property Grid\Column|Collection book_share
     * @property Grid\Column|Collection book_free
     * @property Grid\Column|Collection book_like
     * @property Grid\Column|Collection book_recommend
     * @property Grid\Column|Collection preview_page
     * @property Grid\Column|Collection CheckMacValue
     * @property Grid\Column|Collection users_id
     * @property Grid\Column|Collection MerchantTradeNo
     * @property Grid\Column|Collection MerchantTradeDate
     * @property Grid\Column|Collection TradeDesc
     * @property Grid\Column|Collection ItemName
     * @property Grid\Column|Collection return_state
     * @property Grid\Column|Collection book_list
     * @property Grid\Column|Collection reset_code
     * @property Grid\Column|Collection user_order_no
     * @property Grid\Column|Collection user_point
     * @property Grid\Column|Collection user_payment
     * @property Grid\Column|Collection user_payment_firm
     * @property Grid\Column|Collection user_payment_status
     * @property Grid\Column|Collection gender
     * @property Grid\Column|Collection birthday
     * @property Grid\Column|Collection age
     * @property Grid\Column|Collection point
     * @property Grid\Column|Collection acc_type
     * @property Grid\Column|Collection bank_id
     * @property Grid\Column|Collection phone
     * @property Grid\Column|Collection payment
     *
     * @method Grid\Column|Collection id(string $label = null)
     * @method Grid\Column|Collection name(string $label = null)
     * @method Grid\Column|Collection type(string $label = null)
     * @method Grid\Column|Collection version(string $label = null)
     * @method Grid\Column|Collection detail(string $label = null)
     * @method Grid\Column|Collection created_at(string $label = null)
     * @method Grid\Column|Collection updated_at(string $label = null)
     * @method Grid\Column|Collection is_enabled(string $label = null)
     * @method Grid\Column|Collection parent_id(string $label = null)
     * @method Grid\Column|Collection order(string $label = null)
     * @method Grid\Column|Collection icon(string $label = null)
     * @method Grid\Column|Collection uri(string $label = null)
     * @method Grid\Column|Collection extension(string $label = null)
     * @method Grid\Column|Collection permission_id(string $label = null)
     * @method Grid\Column|Collection menu_id(string $label = null)
     * @method Grid\Column|Collection slug(string $label = null)
     * @method Grid\Column|Collection http_method(string $label = null)
     * @method Grid\Column|Collection http_path(string $label = null)
     * @method Grid\Column|Collection role_id(string $label = null)
     * @method Grid\Column|Collection user_id(string $label = null)
     * @method Grid\Column|Collection value(string $label = null)
     * @method Grid\Column|Collection username(string $label = null)
     * @method Grid\Column|Collection password(string $label = null)
     * @method Grid\Column|Collection avatar(string $label = null)
     * @method Grid\Column|Collection remember_token(string $label = null)
     * @method Grid\Column|Collection uuid(string $label = null)
     * @method Grid\Column|Collection connection(string $label = null)
     * @method Grid\Column|Collection queue(string $label = null)
     * @method Grid\Column|Collection payload(string $label = null)
     * @method Grid\Column|Collection exception(string $label = null)
     * @method Grid\Column|Collection failed_at(string $label = null)
     * @method Grid\Column|Collection email(string $label = null)
     * @method Grid\Column|Collection token(string $label = null)
     * @method Grid\Column|Collection tokenable_type(string $label = null)
     * @method Grid\Column|Collection tokenable_id(string $label = null)
     * @method Grid\Column|Collection abilities(string $label = null)
     * @method Grid\Column|Collection last_used_at(string $label = null)
     * @method Grid\Column|Collection expires_at(string $label = null)
     * @method Grid\Column|Collection email_verified_at(string $label = null)
     * @method Grid\Column|Collection bank_country(string $label = null)
     * @method Grid\Column|Collection bank_swift(string $label = null)
     * @method Grid\Column|Collection bank_name(string $label = null)
     * @method Grid\Column|Collection bank_code(string $label = null)
     * @method Grid\Column|Collection bank_branch_code(string $label = null)
     * @method Grid\Column|Collection bank_branch_addr(string $label = null)
     * @method Grid\Column|Collection bank_account(string $label = null)
     * @method Grid\Column|Collection bank_number(string $label = null)
     * @method Grid\Column|Collection book_id(string $label = null)
     * @method Grid\Column|Collection status(string $label = null)
     * @method Grid\Column|Collection content(string $label = null)
     * @method Grid\Column|Collection user_input(string $label = null)
     * @method Grid\Column|Collection step(string $label = null)
     * @method Grid\Column|Collection page(string $label = null)
     * @method Grid\Column|Collection user_pic_input_who(string $label = null)
     * @method Grid\Column|Collection user_pic_input_where(string $label = null)
     * @method Grid\Column|Collection user_pic_input_what(string $label = null)
     * @method Grid\Column|Collection story_pic_ai_1(string $label = null)
     * @method Grid\Column|Collection story_pic_ai_2(string $label = null)
     * @method Grid\Column|Collection story_pic_ai_3(string $label = null)
     * @method Grid\Column|Collection story_pic_ai_4(string $label = null)
     * @method Grid\Column|Collection user_pic_select(string $label = null)
     * @method Grid\Column|Collection position_input(string $label = null)
     * @method Grid\Column|Collection ch_story_ai(string $label = null)
     * @method Grid\Column|Collection en_story_ai(string $label = null)
     * @method Grid\Column|Collection en_user_input(string $label = null)
     * @method Grid\Column|Collection auth_id(string $label = null)
     * @method Grid\Column|Collection book_point(string $label = null)
     * @method Grid\Column|Collection book_income(string $label = null)
     * @method Grid\Column|Collection book_fee(string $label = null)
     * @method Grid\Column|Collection auth_point_before(string $label = null)
     * @method Grid\Column|Collection auth_point_after(string $label = null)
     * @method Grid\Column|Collection user_point_before(string $label = null)
     * @method Grid\Column|Collection user_point_after(string $label = null)
     * @method Grid\Column|Collection stars(string $label = null)
     * @method Grid\Column|Collection book_frontcover(string $label = null)
     * @method Grid\Column|Collection book_name_ch(string $label = null)
     * @method Grid\Column|Collection book_name_en(string $label = null)
     * @method Grid\Column|Collection book_author(string $label = null)
     * @method Grid\Column|Collection book_author_id(string $label = null)
     * @method Grid\Column|Collection style(string $label = null)
     * @method Grid\Column|Collection book_verify(string $label = null)
     * @method Grid\Column|Collection book_shelf(string $label = null)
     * @method Grid\Column|Collection book_state(string $label = null)
     * @method Grid\Column|Collection cover_image_1(string $label = null)
     * @method Grid\Column|Collection cover_image_2(string $label = null)
     * @method Grid\Column|Collection cover_image_3(string $label = null)
     * @method Grid\Column|Collection cover_image_4(string $label = null)
     * @method Grid\Column|Collection preface(string $label = null)
     * @method Grid\Column|Collection book_backcover(string $label = null)
     * @method Grid\Column|Collection story_main(string $label = null)
     * @method Grid\Column|Collection book_share(string $label = null)
     * @method Grid\Column|Collection book_free(string $label = null)
     * @method Grid\Column|Collection book_like(string $label = null)
     * @method Grid\Column|Collection book_recommend(string $label = null)
     * @method Grid\Column|Collection preview_page(string $label = null)
     * @method Grid\Column|Collection CheckMacValue(string $label = null)
     * @method Grid\Column|Collection users_id(string $label = null)
     * @method Grid\Column|Collection MerchantTradeNo(string $label = null)
     * @method Grid\Column|Collection MerchantTradeDate(string $label = null)
     * @method Grid\Column|Collection TradeDesc(string $label = null)
     * @method Grid\Column|Collection ItemName(string $label = null)
     * @method Grid\Column|Collection return_state(string $label = null)
     * @method Grid\Column|Collection book_list(string $label = null)
     * @method Grid\Column|Collection reset_code(string $label = null)
     * @method Grid\Column|Collection user_order_no(string $label = null)
     * @method Grid\Column|Collection user_point(string $label = null)
     * @method Grid\Column|Collection user_payment(string $label = null)
     * @method Grid\Column|Collection user_payment_firm(string $label = null)
     * @method Grid\Column|Collection user_payment_status(string $label = null)
     * @method Grid\Column|Collection gender(string $label = null)
     * @method Grid\Column|Collection birthday(string $label = null)
     * @method Grid\Column|Collection age(string $label = null)
     * @method Grid\Column|Collection point(string $label = null)
     * @method Grid\Column|Collection acc_type(string $label = null)
     * @method Grid\Column|Collection bank_id(string $label = null)
     * @method Grid\Column|Collection phone(string $label = null)
     * @method Grid\Column|Collection payment(string $label = null)
     */
    class Grid {}

    class MiniGrid extends Grid {}

    /**
     * @property Show\Field|Collection id
     * @property Show\Field|Collection name
     * @property Show\Field|Collection type
     * @property Show\Field|Collection version
     * @property Show\Field|Collection detail
     * @property Show\Field|Collection created_at
     * @property Show\Field|Collection updated_at
     * @property Show\Field|Collection is_enabled
     * @property Show\Field|Collection parent_id
     * @property Show\Field|Collection order
     * @property Show\Field|Collection icon
     * @property Show\Field|Collection uri
     * @property Show\Field|Collection extension
     * @property Show\Field|Collection permission_id
     * @property Show\Field|Collection menu_id
     * @property Show\Field|Collection slug
     * @property Show\Field|Collection http_method
     * @property Show\Field|Collection http_path
     * @property Show\Field|Collection role_id
     * @property Show\Field|Collection user_id
     * @property Show\Field|Collection value
     * @property Show\Field|Collection username
     * @property Show\Field|Collection password
     * @property Show\Field|Collection avatar
     * @property Show\Field|Collection remember_token
     * @property Show\Field|Collection uuid
     * @property Show\Field|Collection connection
     * @property Show\Field|Collection queue
     * @property Show\Field|Collection payload
     * @property Show\Field|Collection exception
     * @property Show\Field|Collection failed_at
     * @property Show\Field|Collection email
     * @property Show\Field|Collection token
     * @property Show\Field|Collection tokenable_type
     * @property Show\Field|Collection tokenable_id
     * @property Show\Field|Collection abilities
     * @property Show\Field|Collection last_used_at
     * @property Show\Field|Collection expires_at
     * @property Show\Field|Collection email_verified_at
     * @property Show\Field|Collection bank_country
     * @property Show\Field|Collection bank_swift
     * @property Show\Field|Collection bank_name
     * @property Show\Field|Collection bank_code
     * @property Show\Field|Collection bank_branch_code
     * @property Show\Field|Collection bank_branch_addr
     * @property Show\Field|Collection bank_account
     * @property Show\Field|Collection bank_number
     * @property Show\Field|Collection book_id
     * @property Show\Field|Collection status
     * @property Show\Field|Collection content
     * @property Show\Field|Collection user_input
     * @property Show\Field|Collection step
     * @property Show\Field|Collection page
     * @property Show\Field|Collection user_pic_input_who
     * @property Show\Field|Collection user_pic_input_where
     * @property Show\Field|Collection user_pic_input_what
     * @property Show\Field|Collection story_pic_ai_1
     * @property Show\Field|Collection story_pic_ai_2
     * @property Show\Field|Collection story_pic_ai_3
     * @property Show\Field|Collection story_pic_ai_4
     * @property Show\Field|Collection user_pic_select
     * @property Show\Field|Collection position_input
     * @property Show\Field|Collection ch_story_ai
     * @property Show\Field|Collection en_story_ai
     * @property Show\Field|Collection en_user_input
     * @property Show\Field|Collection auth_id
     * @property Show\Field|Collection book_point
     * @property Show\Field|Collection book_income
     * @property Show\Field|Collection book_fee
     * @property Show\Field|Collection auth_point_before
     * @property Show\Field|Collection auth_point_after
     * @property Show\Field|Collection user_point_before
     * @property Show\Field|Collection user_point_after
     * @property Show\Field|Collection stars
     * @property Show\Field|Collection book_frontcover
     * @property Show\Field|Collection book_name_ch
     * @property Show\Field|Collection book_name_en
     * @property Show\Field|Collection book_author
     * @property Show\Field|Collection book_author_id
     * @property Show\Field|Collection style
     * @property Show\Field|Collection book_verify
     * @property Show\Field|Collection book_shelf
     * @property Show\Field|Collection book_state
     * @property Show\Field|Collection cover_image_1
     * @property Show\Field|Collection cover_image_2
     * @property Show\Field|Collection cover_image_3
     * @property Show\Field|Collection cover_image_4
     * @property Show\Field|Collection preface
     * @property Show\Field|Collection book_backcover
     * @property Show\Field|Collection story_main
     * @property Show\Field|Collection book_share
     * @property Show\Field|Collection book_free
     * @property Show\Field|Collection book_like
     * @property Show\Field|Collection book_recommend
     * @property Show\Field|Collection preview_page
     * @property Show\Field|Collection CheckMacValue
     * @property Show\Field|Collection users_id
     * @property Show\Field|Collection MerchantTradeNo
     * @property Show\Field|Collection MerchantTradeDate
     * @property Show\Field|Collection TradeDesc
     * @property Show\Field|Collection ItemName
     * @property Show\Field|Collection return_state
     * @property Show\Field|Collection book_list
     * @property Show\Field|Collection reset_code
     * @property Show\Field|Collection user_order_no
     * @property Show\Field|Collection user_point
     * @property Show\Field|Collection user_payment
     * @property Show\Field|Collection user_payment_firm
     * @property Show\Field|Collection user_payment_status
     * @property Show\Field|Collection gender
     * @property Show\Field|Collection birthday
     * @property Show\Field|Collection age
     * @property Show\Field|Collection point
     * @property Show\Field|Collection acc_type
     * @property Show\Field|Collection bank_id
     * @property Show\Field|Collection phone
     * @property Show\Field|Collection payment
     *
     * @method Show\Field|Collection id(string $label = null)
     * @method Show\Field|Collection name(string $label = null)
     * @method Show\Field|Collection type(string $label = null)
     * @method Show\Field|Collection version(string $label = null)
     * @method Show\Field|Collection detail(string $label = null)
     * @method Show\Field|Collection created_at(string $label = null)
     * @method Show\Field|Collection updated_at(string $label = null)
     * @method Show\Field|Collection is_enabled(string $label = null)
     * @method Show\Field|Collection parent_id(string $label = null)
     * @method Show\Field|Collection order(string $label = null)
     * @method Show\Field|Collection icon(string $label = null)
     * @method Show\Field|Collection uri(string $label = null)
     * @method Show\Field|Collection extension(string $label = null)
     * @method Show\Field|Collection permission_id(string $label = null)
     * @method Show\Field|Collection menu_id(string $label = null)
     * @method Show\Field|Collection slug(string $label = null)
     * @method Show\Field|Collection http_method(string $label = null)
     * @method Show\Field|Collection http_path(string $label = null)
     * @method Show\Field|Collection role_id(string $label = null)
     * @method Show\Field|Collection user_id(string $label = null)
     * @method Show\Field|Collection value(string $label = null)
     * @method Show\Field|Collection username(string $label = null)
     * @method Show\Field|Collection password(string $label = null)
     * @method Show\Field|Collection avatar(string $label = null)
     * @method Show\Field|Collection remember_token(string $label = null)
     * @method Show\Field|Collection uuid(string $label = null)
     * @method Show\Field|Collection connection(string $label = null)
     * @method Show\Field|Collection queue(string $label = null)
     * @method Show\Field|Collection payload(string $label = null)
     * @method Show\Field|Collection exception(string $label = null)
     * @method Show\Field|Collection failed_at(string $label = null)
     * @method Show\Field|Collection email(string $label = null)
     * @method Show\Field|Collection token(string $label = null)
     * @method Show\Field|Collection tokenable_type(string $label = null)
     * @method Show\Field|Collection tokenable_id(string $label = null)
     * @method Show\Field|Collection abilities(string $label = null)
     * @method Show\Field|Collection last_used_at(string $label = null)
     * @method Show\Field|Collection expires_at(string $label = null)
     * @method Show\Field|Collection email_verified_at(string $label = null)
     * @method Show\Field|Collection bank_country(string $label = null)
     * @method Show\Field|Collection bank_swift(string $label = null)
     * @method Show\Field|Collection bank_name(string $label = null)
     * @method Show\Field|Collection bank_code(string $label = null)
     * @method Show\Field|Collection bank_branch_code(string $label = null)
     * @method Show\Field|Collection bank_branch_addr(string $label = null)
     * @method Show\Field|Collection bank_account(string $label = null)
     * @method Show\Field|Collection bank_number(string $label = null)
     * @method Show\Field|Collection book_id(string $label = null)
     * @method Show\Field|Collection status(string $label = null)
     * @method Show\Field|Collection content(string $label = null)
     * @method Show\Field|Collection user_input(string $label = null)
     * @method Show\Field|Collection step(string $label = null)
     * @method Show\Field|Collection page(string $label = null)
     * @method Show\Field|Collection user_pic_input_who(string $label = null)
     * @method Show\Field|Collection user_pic_input_where(string $label = null)
     * @method Show\Field|Collection user_pic_input_what(string $label = null)
     * @method Show\Field|Collection story_pic_ai_1(string $label = null)
     * @method Show\Field|Collection story_pic_ai_2(string $label = null)
     * @method Show\Field|Collection story_pic_ai_3(string $label = null)
     * @method Show\Field|Collection story_pic_ai_4(string $label = null)
     * @method Show\Field|Collection user_pic_select(string $label = null)
     * @method Show\Field|Collection position_input(string $label = null)
     * @method Show\Field|Collection ch_story_ai(string $label = null)
     * @method Show\Field|Collection en_story_ai(string $label = null)
     * @method Show\Field|Collection en_user_input(string $label = null)
     * @method Show\Field|Collection auth_id(string $label = null)
     * @method Show\Field|Collection book_point(string $label = null)
     * @method Show\Field|Collection book_income(string $label = null)
     * @method Show\Field|Collection book_fee(string $label = null)
     * @method Show\Field|Collection auth_point_before(string $label = null)
     * @method Show\Field|Collection auth_point_after(string $label = null)
     * @method Show\Field|Collection user_point_before(string $label = null)
     * @method Show\Field|Collection user_point_after(string $label = null)
     * @method Show\Field|Collection stars(string $label = null)
     * @method Show\Field|Collection book_frontcover(string $label = null)
     * @method Show\Field|Collection book_name_ch(string $label = null)
     * @method Show\Field|Collection book_name_en(string $label = null)
     * @method Show\Field|Collection book_author(string $label = null)
     * @method Show\Field|Collection book_author_id(string $label = null)
     * @method Show\Field|Collection style(string $label = null)
     * @method Show\Field|Collection book_verify(string $label = null)
     * @method Show\Field|Collection book_shelf(string $label = null)
     * @method Show\Field|Collection book_state(string $label = null)
     * @method Show\Field|Collection cover_image_1(string $label = null)
     * @method Show\Field|Collection cover_image_2(string $label = null)
     * @method Show\Field|Collection cover_image_3(string $label = null)
     * @method Show\Field|Collection cover_image_4(string $label = null)
     * @method Show\Field|Collection preface(string $label = null)
     * @method Show\Field|Collection book_backcover(string $label = null)
     * @method Show\Field|Collection story_main(string $label = null)
     * @method Show\Field|Collection book_share(string $label = null)
     * @method Show\Field|Collection book_free(string $label = null)
     * @method Show\Field|Collection book_like(string $label = null)
     * @method Show\Field|Collection book_recommend(string $label = null)
     * @method Show\Field|Collection preview_page(string $label = null)
     * @method Show\Field|Collection CheckMacValue(string $label = null)
     * @method Show\Field|Collection users_id(string $label = null)
     * @method Show\Field|Collection MerchantTradeNo(string $label = null)
     * @method Show\Field|Collection MerchantTradeDate(string $label = null)
     * @method Show\Field|Collection TradeDesc(string $label = null)
     * @method Show\Field|Collection ItemName(string $label = null)
     * @method Show\Field|Collection return_state(string $label = null)
     * @method Show\Field|Collection book_list(string $label = null)
     * @method Show\Field|Collection reset_code(string $label = null)
     * @method Show\Field|Collection user_order_no(string $label = null)
     * @method Show\Field|Collection user_point(string $label = null)
     * @method Show\Field|Collection user_payment(string $label = null)
     * @method Show\Field|Collection user_payment_firm(string $label = null)
     * @method Show\Field|Collection user_payment_status(string $label = null)
     * @method Show\Field|Collection gender(string $label = null)
     * @method Show\Field|Collection birthday(string $label = null)
     * @method Show\Field|Collection age(string $label = null)
     * @method Show\Field|Collection point(string $label = null)
     * @method Show\Field|Collection acc_type(string $label = null)
     * @method Show\Field|Collection bank_id(string $label = null)
     * @method Show\Field|Collection phone(string $label = null)
     * @method Show\Field|Collection payment(string $label = null)
     */
    class Show {}

    /**
     
     */
    class Form {}

}

namespace Dcat\Admin\Grid {
    /**
     
     */
    class Column {}

    /**
     
     */
    class Filter {}
}

namespace Dcat\Admin\Show {
    /**
     
     */
    class Field {}
}
