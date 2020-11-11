<?php
namespace App\Helpers;

use App\Models\Translate;

class BaseTranslate
{
    const db_version = 193514;
    /**
    * Set and update base language
    *
    * @version 1.0.0
    * @since 1.1.3
    * @param  string $force
    */
    public static function import_translate($force=false){
        $base_contents = self::base_translate_content();
        foreach ($base_contents as $key => $data) {
            if(!empty($data['text'])) {
                $key_exist = self::is_key_exist($key);
                $add_update = ($key_exist==false) ? 'add' : 'update';
                if ($key_exist===false||$force===true) {
                    $trans_base = [
                        'key' => $key,
                        'name' => 'base',
                        'text' => $data['text'],
                        'pages' => $data['pages'] ?? 'global',
                        'group' => $data['group'] ?? 'system',
                        'panel' => $data['panel'] ?? 'any',
                        'load' => $data['load'] ?? 0
                    ];
                    if($add_update=='add'){
                        Translate::create($trans_base);
                    } else {
                        Translate::updateOrCreate(['key' => $key, 'name' => 'base'], $trans_base);
                    }
                }
            }
        }
    }

    /**
    * Check key already exist or not in table
    *
    * @version 1.0.0
    * @since 1.1.3
    * @param  string $key
    */
    public static function filterableNames()
    {
      return [
          "Messages" => "messages",
          "Pagination" => "pagination",
          "Passwords" => "passwords",
          "Authentication" => "auth",
          "Validation" => "validation",
          "Dashboard" => "dashboard",
          "User Profile" => "user_profile",
          "User Activity" => "user_profile,user_activity",
          "User Transaction" => "user_profile,user_transaction",
          "KYC" => "kyc",
          "KYC Form" => "kyc,kyc_form",
          "Buy token" => "buy_token",
          "Payment" => "payment",
          "Payment Order" => "payment,order",
          "Payment Cancel" => "payment,cancel",
          "Payment Online" => "payment,online",
          "Payment Offline" => "payment,offline",
          "Payment Manual" => "payment,manual",
          "Payment Bank" => "payment,bank",
          "Transaction" => "transaction",
          "User Wallet" => "user_wallet",
          "Status" => "status",
          "User 2FA" => "user_2fa",
          "Referral" => "referral",
        ];
    }

    /**
    * Check key already exist or not in table
    *
    * @version 1.0.0
    * @since 1.1.3
    * @param  string $key
    */
    private static function is_key_exist($key) {
        $get_key = Translate::where(['key' => $key, 'name' => 'base'])->first();
        return (!empty($get_key)) ? true : false;
    }

    /**
    * Global Translatable Text @20191005
    *
    * @version 1.0.0
    * @since 1.1.3
    */
    public static function base_translate_content() {
        $global = [
            ///// MESSAGES /////
              "messages.email_exist" => [
                  "text" => "Email is already exist!",
                  "pages" => "messages, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "messages.email.unique" => [
                "text" => "Email address should be unique!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.email.reset" => [
                "text" => "Somthing is wrong! We are unable to send reset link to your email. Please! contact with administrator via :email.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.email.verify" => [
                "text" => "Somthing is wrong! We are unable to send the verification link to your email. Please! contact with administrator via :email.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.email.password_change" => [
                "text" => "Somthing is wrong! We are unable to send the confirmation link to your email. Please! contact with administrator via :email.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.email.failed" => [
                "text" => "But email was not send to user. Please check your mail setting credential.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.form.invalid" => [
                  "text" => "Invalid form data!",
                  "pages" => "messages, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "messages.form.wrong" => [
                "text" => "Something wrong in form submission!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.wrong" => [
                  "text" => "Something is wrong!",
                  "pages" => "messages, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "messages.nothing" => [
                  "text" => "Nothing to do!",
                  "pages" => "messages, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "messages.agree" => [
                  "text" => "You should agree our terms and policy.",
                  "pages" => "messages, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "messages.errors" => [
                  "text" => "An error occurred. Please try again.",
                  "pages" => "messages, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "messages.login.email_verify" => [
                "text" => "Please login to verify you email address.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.login.inactive" => [
                "text" => "Your account may inactive or suspended. Please contact us if something wrong.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.register.success.heading" => [
                "text" => "Thank you!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.register.success.subhead" => [
                "text" => "Your sign-up process is almost done.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.register.success.msg" => [
                "text" => "Please check your email and verify your account.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.verify.verified" => [
                "text" => "Email address is already verified.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.verify.not_found" => [
                "text" => "User Account is not found!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.verify.expired" => [
                "text" => "Your verification link is expired!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.verify.invalid" => [
                "text" => "Your verification link is invalid!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.verify.confirmed" => [
                "text" => "Your email is verified now!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.verify.success.heading" => [
                "text" => "Congratulations!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.verify.success.subhead" => [
                "text" => "You've successfully verified your email address and your account is now active.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.verify.success.msg" => [
                "text" => "Please sign-in to start token purchase.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.created" => [
                "text" => "Transaction successful, You will redirect to payment page.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.wrong" => [
                "text" => "Something is wrong!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.required" => [
                "text" => "Transaction id is required!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.canceled" => [
                "text" => "Transaction failed! Try again.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.notfound" => [
                "text" => "Transaction id is not found",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.reviewing" => [
                "text" => "We are reviewing your payment!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.canceled_own" => [
                "text" => "You had canceled your order",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.require_currency" => [
                "text" => "Currency is required!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.require_token" => [
                "text" => "Token amount is required!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.select_method" => [
                "text" => "Select payment method!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.minimum_token" => [
                "text" => "You have to purchase more than 1 token.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.purchase_token" => [
                "text" => "Tokens Purchase",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.referral_bonus" => [
                "text" => "Referral Bonus",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.payments.not_available" => [
                "text" => "Sorry! Currently payment method not available in your selected currency!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.manual.success" => [
                "text" => "Transaction successful!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.manual.failed" => [
                "text" => "Transaction Failed!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.admin.approved" => [
                "text" => "Transaction approved and token added to user.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.admin.canceled" => [
                "text" => "Transaction canceled.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.admin.deleted" => [
                "text" => "Transaction Deleted.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.admin.already_deleted" => [
                "text" => "This transaction is already deleted.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.admin.already_approved" => [
                "text" => "This transaction is already approved.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              // v1.1.4
              "messages.trnx.admin.already_canceled" => [
                "text" => "This transaction is already canceled.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.trnx.admin.already_updated" => [
                "text" => "This transaction is already updated to :status.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.token.success" => [
                "text" => "Token added to the user account!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.token.failed" => [
                "text" => "Failed to add token!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.insert.success" => [
                "text" => ":what insert successful!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.insert.warning" => [
                "text" => "Something is wrong!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.insert.failed" => [
                "text" => ":what insert failed!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.stage.expired" => [
                "text" => "Sorry, this stage is expired!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.stage.inactive" => [
                "text" => "Currently no active stage found!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.stage.notice" => [
                "text" => "Please create a new stage or update stage date, because this stage is expired!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.stage.upcoming" => [
                "text" => "Stage will start at :time",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.stage.delete_failed" => [
                "text" => "You can not remove the last stage.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.stage.not_started" => [
                "text" => "Our sell have not started yet. Please check after some times.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.stage.completed" => [
                "text" => "Our token sales has been finished. Thank you very much for your contribution.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.update.success" => [
                "text" => ":what has been updated!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.update.warning" => [
                "text" => "Something is wrong!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.update.failed" => [
                "text" => ":what updating failed!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.password.old_err" => [
                "text" => "Your old password is incorrect.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.password.success" => [
                "text" => "Password successfully changed!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.password.changed" => [
                "text" => "We have sent a verification code to your email please confirm and change.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.password.failed" => [
                "text" => "Varification link has expired!!! try again",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.password.token" => [
                "text" => "Invalid link/token!!! try again",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.delete.delete" => [
                "text" => ":what is deleted!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.delete.delete_failed" => [
                "text" => ":what is deletion failed!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.kyc.approved" => [
                "text" => "KYC application approved successfully!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.kyc.missing" => [
                "text" => "KYC application is missing!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.kyc.rejected" => [
                "text" => "KYC application is rejected!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.kyc.wait" => [
                "text" => "Your KYC Application is placed, please wait for our review.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.kyc.mandatory" => [
                "text" => "Identity verification (KYC/AML) is mandatory to participate in our token sale.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.kyc.forms.submitted" => [
                "text" => "You have successfully submitted your application for identity verification.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.kyc.forms.failed" => [
                "text" => "We weren't able to process the application submission for identity verification. Please reload this page and fill the form again and submit. ",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.kyc.forms.document" => [
                "text" => ":NAME is required, Please upload your document.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.upload.success" => [
                "text" => ":what has been uploaded!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.upload.warning" => [
                "text" => "Something is wrong!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.upload.invalid" => [
                "text" => "This type of file is not supported!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.upload.failed" => [
                "text" => ":what uploading failed!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.invalid.address" => [
                "text" => "Enter a valid wallet address.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.invalid.address_is" => [
                "text" => "Enter a valid :is wallet address.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.invalid.social" => [
                "text" => "Sorry, Social login is not available now.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.mail.send" => [
                "text" => "Email has been send successfully.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.mail.failed" => [
                "text" => "Failed to send email.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.mail.issues" => [
                "text" => "Unable to send email! Please check your mail setting credential.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.wallet.change" => [
                "text" => "Wallet address change request submitted.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.wallet.cancel" => [
                "text" => "Wallet address change request is canceled.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.wallet.approved" => [
                "text" => "Wallet address change request is approved.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.wallet.failed" => [
                "text" => "Wallet address change request is failed.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.ico_not_setup" => [
                "text" => "ICO Sales opening soon, Please check after sometimes.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.demo_payment_note" => [
                "text" => "All the <span class=\"badge badge-xs badge-purple ucap\">Add-ons</span> type payment modules is NOT part of main product. You've to purchase separately from CodeCanyon to get those. <strong><a href=\"https://codecanyon.net/user/softnio/portfolio\" target=\"_blank\">Check out here</a></strong>.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.demo_user" => [
                "text" => "Your action can't perform as you login with a Demo Account. For full-access, please send an email at info@softnio.com.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.demo_preview" => [
                "text" => "You can't perform this action as this is preview purpose.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.stage_update" => [
                "text" => "Successfully :status the stage!!",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.payment_method_update" => [
                "text" => "Payment method :status",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
              ],
              "messages.required_app" => [
                "text" => "The :what payment module required minimum :version version of application. Please update your core application to latest version.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 1
               ],
              "messages.permission" => [
                "text" => "You do not have enough permissions to perform requested operation.",
                "pages" => "messages, global",
                "panel" => "any",
                "load" => 0
               ],

            ///// PAGINATE /////
              "pagination.previous" => [
                  "text" => "&laquo; Previous",
                  "pages" => "pagination, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "pagination.next" => [
                  "text" => "Next &raquo;",
                  "pages" => "pagination, global",
                  "panel" => "any",
                  "load" => 1
              ],

            ///// PASSWORDS /////
              "passwords.password" => [
                  "text" => "Passwords must be at least six characters and match the confirmation.",
                  "pages" => "passwords, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "passwords.reset" => [
                  "text" => "Your password has been reset!",
                  "pages" => "passwords, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "passwords.sent" => [
                  "text" => "We have e-mailed your password reset link!",
                  "pages" => "passwords, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "passwords.token" => [
                  "text" => "This password reset token is invalid.",
                  "pages" => "passwords, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "passwords.user" => [
                  "text" => "We can't find a user with that e-mail address.",
                  "pages" => "passwords, global",
                  "panel" => "any",
                  "load" => 1
              ],

            ///// AUTH /////
              "auth.failed" => [
                  "text" => "These credentials do not match our records.",
                  "pages" => "auth, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "auth.throttle" => [
                  "text" => "Too many login attempts. Please try again in :seconds",
                  "pages" => "auth, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "auth.recaptcha" => [
                  "text" => "Your request failed to complete as bot detected.",
                  "pages" => "auth, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "auth.health.save_action" => [
                  "text" => "Please register and activate the application to perform the action.",
                  "pages" => "auth, global",
                  "panel" => "any",
                  "load" => 1
              ],
              "auth.health.fail" => [
                  "text" => "Invalidated-the-license-due-to-wrong-key",
                  "pages" => "auth, global",
                  "panel" => "any",
                  "load" => 1
              ],

            ///// VALIDATION /////
              "validation.accepted" => [
                "text" => "The :attribute must be accepted.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.active_url" => [
                "text" => "The :attribute is not a valid URL.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.after" => [
                "text" => "The :attribute must be a date after :date.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.after_or_equal" => [
                "text" => "The :attribute must be a date after or equal to :date.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.alpha" => [
                "text" => "The :attribute may only contain letters.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.alpha_dash" => [
                "text" => "The :attribute may only contain letters, numbers, dashes and underscores.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.alpha_num" => [
                "text" => "The :attribute may only contain letters and numbers.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.array" => [
                "text" => "The :attribute must be an array.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.before" => [
                "text" => "The :attribute must be a date before :date.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.before_or_equal" => [
                "text" => "The :attribute must be a date before or equal to :date.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.between.numeric" => [
                "text" => "The :attribute must be between :min and :max.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.between.file" => [
                "text" => "The :attribute must be between :min and :max kilobytes.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.between.string" => [
                "text" => "The :attribute must be between :min and :max characters.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.between.array" => [
                "text" => "The :attribute must have between :min and :max items.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.boolean" => [
                "text" => "The :attribute field must be true or false.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.confirmed" => [
                "text" => "The :attribute confirmation does not match.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.date" => [
                "text" => "The :attribute is not a valid date.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.date_equals" => [
                "text" => "The :attribute must be a date equal to :date.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.date_format" => [
                "text" => "The :attribute does not match the format :format.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.different" => [
                "text" => "The :attribute and :other must be different.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.digits" => [
                "text" => "The :attribute must be :digits digits.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.digits_between" => [
                "text" => "The :attribute must be between :min and :max digits.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.dimensions" => [
                "text" => "The :attribute has invalid image dimensions.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.distinct" => [
                "text" => "The :attribute field has a duplicate value.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.email" => [
                "text" => "The :attribute must be a valid email address.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.exists" => [
                "text" => "The selected :attribute is invalid.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.file" => [
                "text" => "The :attribute must be a file.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.filled" => [
                "text" => "The :attribute field must have a value.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.gt.numeric" => [
                "text" => "The :attribute must be greater than :value.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.gt.file" => [
                "text" => "The :attribute must be greater than :value kilobytes.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.gt.string" => [
                "text" => "The :attribute must be greater than :value characters.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.gt.array" => [
                "text" => "The :attribute must have more than :value items.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.gte.numeric" => [
                "text" => "The :attribute must be greater than or equal :value.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.gte.file" => [
                "text" => "The :attribute must be greater than or equal :value kilobytes.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.gte.string" => [
                "text" => "The :attribute must be greater than or equal :value characters.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.gte.array" => [
                "text" => "The :attribute must have :value items or more.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.image" => [
                "text" => "The :attribute must be an image.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.in" => [
                "text" => "The selected :attribute is invalid.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.in_array" => [
                "text" => "The :attribute field does not exist in :other.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.integer" => [
                "text" => "The :attribute must be an integer.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.ip" => [
                "text" => "The :attribute must be a valid IP address.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.ipv4" => [
                "text" => "The :attribute must be a valid IPv4 address.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.ipv6" => [
                "text" => "The :attribute must be a valid IPv6 address.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.json" => [
                "text" => "The :attribute must be a valid JSON string.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.lt.numeric" => [
                "text" => "The :attribute must be less than :value.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.lt.file" => [
                "text" => "The :attribute must be less than :value kilobytes.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.lt.string" => [
                "text" => "The :attribute must be less than :value characters.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.lt.array" => [
                "text" => "The :attribute must have less than :value items.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.lte.numeric" => [
                "text" => "The :attribute must be less than or equal :value.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.lte.file" => [
                "text" => "The :attribute must be less than or equal :value kilobytes.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.lte.string" => [
                "text" => "The :attribute must be less than or equal :value characters.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.lte.array" => [
                "text" => "The :attribute must not have more than :value items.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.max.numeric" => [
                "text" => "The :attribute may not be greater than :max.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.max.file" => [
                "text" => "The :attribute may not be greater than :max kilobytes.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.max.string" => [
                "text" => "The :attribute may not be greater than :max characters.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.max.array" => [
                "text" => "The :attribute may not have more than :max items.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.mimes" => [
                "text" => "The :attribute must be a file of type: :values.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.mimetypes" => [
                "text" => "The :attribute must be a file of type: :values.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.min.numeric" => [
                "text" => "The :attribute must be at least :min.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.min.file" => [
                "text" => "The :attribute must be at least :min kilobytes.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.min.string" => [
                "text" => "The :attribute must be at least :min characters.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.min.array" => [
                "text" => "The :attribute must have at least :min items.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.not_in" => [
                "text" => "The selected :attribute is invalid.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.not_regex" => [
                "text" => "The :attribute format is invalid.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.numeric" => [
                "text" => "The :attribute must be a number.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.present" => [
                "text" => "The :attribute field must be present.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.regex" => [
                "text" => "The :attribute format is invalid.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.required" => [
                "text" => "The :attribute field is required.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.required_if" => [
                "text" => "The :attribute field is required when :other is :value.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.required_unless" => [
                "text" => "The :attribute field is required unless :other is in :values.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.required_with" => [
                "text" => "The :attribute field is required when :values is present.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.required_with_all" => [
                "text" => "The :attribute field is required when :values are present.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.required_without" => [
                "text" => "The :attribute field is required when :values is not present.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.required_without_all" => [
                "text" => "The :attribute field is required when none of :values are present.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.same" => [
                "text" => "The :attribute and :other must match.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.size.numeric" => [
                "text" => "The :attribute must be :size.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.size.file" => [
                "text" => "The :attribute must be :size kilobytes.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.size.string" => [
                "text" => "The :attribute must be :size characters.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.size.array" => [
                "text" => "The :attribute must contain :size items.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.starts_with" => [
                "text" => "The :attribute must start with one of the following: :values",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.string" => [
                "text" => "The :attribute must be a string.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.timezone" => [
                "text" => "The :attribute must be a valid zone.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.unique" => [
                "text" => "The :attribute has already been taken.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.uploaded" => [
                "text" => "The :attribute failed to upload.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.url" => [
                "text" => "The :attribute format is invalid.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.uuid" => [
                "text" => "The :attribute must be a valid UUID.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.custom.attribute-name.rule-name" => [
                "text" => "custom-message",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              "validation.attributes" => [
                "text" => [],
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 1
              ],
              // v1.1.4
              "validation.only.required" => [
                "text" => "Required.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "validation.min.char" => [
                "text" => "At least :num chars.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "validation.max.char" => [
                "text" => "Maximum :num chars.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "validation.same.value" => [
                "text" => "Enter the same value.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "validation.email.valid" => [
                "text" => "Enter valid email.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 0
              ],
              "validation.issue" => [
                "text" => "Currently we are facing some technical issue, please try again after sometime.",
                "pages" => "validation, global",
                "panel" => "any",
                "load" => 0
              ],

            ///// AUTH PUBLIC /////
              "auth.signin" => [
                "text" => "Sign-in",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "auth.sign_in_btn" => [
                "text" => "Sign In",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.sign_in" => [
                "text" => "Sign in",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.with_your" => [
                "text" => "with your",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.account" => [
                "text" => "Account",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.your_email" => [
                "text" => "Your Email",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.password" => [
                "text" => "Password",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.remember_me" => [
                "text" => "Remember Me",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.forgot_password" => [
                "text" => "Forgot password?",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.no_account" => [
                "text" => "Don’t have an account?",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.sign_with" => [
                "text" => "Or Sign in with",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.facebook" => [
                "text" => "Facebook",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.google" => [
                "text" => "Google",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.sign_here" => [
                "text" => "Sign up here",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.welcome" => [
                "text" => "Welcome!",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.logout" => [
                "text" => "Logout",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.sign_up" => [
                "text" => "Sign up",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.create_new" => [
                "text" => "Create New",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.email_verified" => [
                "text" => "Email Verified",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.resend_email" => [
                "text" => "Resend Email",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.set_admin_account" => [
                "text" => "Please setup admin account.",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.repeat_password" => [
                "text" => "Repeat Password",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.reset_password" => [
                "text" => "Reset password",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.forgot_password_note" => [
                "text" => "If you forgot your password, well, then we'll email you instructions to reset your password.",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.your_email_address" => [
                "text" => "Your Email Address",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "auth.your_name" => [
                "text" => "Your Name",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.your_full_name" => [
                "text" => "Your Full Name",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.your_mobile" => [
                "text" => "Your Mobile Number",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.enter_full_name" => [
                "text" => "Enter Full Name",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.enter_email_address" => [
                "text" => "Enter Email Address",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.enter_mobile" => [
                "text" => "Enter Mobile Number",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.enter_password" => [
                "text" => "Enter Password",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.reset_link" => [
                "text" => "Send Reset Link",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.return_login" => [
                "text" => "Return to login",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.agree" => [
                "text" => "I agree to the",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.agree_and" => [
                "text" => "and",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.agree_terms" => [
                "text" => "By registering you agree to the terms and conditions.",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.agree_confirm" => [
                "text" => "You should accept our terms and policy.",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.create_account" => [
                "text" => "Create Account",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.already_account" => [
                "text" => "Already have an account ?",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.sign_instead" => [
                "text" => "Sign in instead",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.verify_email" => [
                "text" => "Please verify your email address.",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.cancel_signup" => [
                "text" => "Cancel Signup",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],
              "auth.sign_out" => [
                "text" => "Sign Out",
                "pages" => "auth, global",
                "panel" => "any",
                "load" => 0
              ],

            ///// DASHBOARD USER /////
              "dashboard.main_site" => [
                "text" => "Main Site",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.title_user_dashboard" => [
                "text" => "User Dashboard",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.title_user_transactions" => [
                "text" => "User Transactions",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.title_user_account" => [
                "text" => "User Account",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.title_user_token" => [
                "text" => ":symbol Token Balance",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.title_token" => [
                "text" => "My Token",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.title_kyc" => [
                "text" => "KYC Application",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.title_dashboard" => [
                "text" => "Dashboard",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.title_transactions" => [
                "text" => "Transactions",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.title_profile" => [
                "text" => "Profile",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.kyc_details" => [
                "text" => "User KYC Details",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.id_verification" => [
                "text" => "Begin ID-Verification",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.buy_token" => [
                "text" => "Buy Token",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.current_price" => [
                "text" => "Current Price",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.buy_token_now" => [
                "text" => "Buy Token Now",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.kyc_approved" => [
                "text" => "KYC Approved",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.kyc_application" => [
                "text" => "KYC Application",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.kyc_pending" => [
                "text" => "KYC Pending",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.submit_kyc" => [
                "text" => "Submit KYC",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.your_contribution_in" => [
                "text" => "Your Contribution in",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.token_balance" => [
                "text" => "Token Balance",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.token_name" => [
                "text" => "Token Name",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.token_symbol" => [
                "text" => "Token Symbol",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.white_paper" => [
                "text" => "White Paper",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.contact_support" => [
                "text" => "Contact Support",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.my_profile" => [
                "text" => "My Profile",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.referral" => [
                "text" => "Referral",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.referral_url" => [
                "text" => "Referral URL",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.referral_lists" => [
                "text" => "Referral Lists",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.activity" => [
                "text" => "Activity",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.download_whitepaper" => [
                "text" => "Download Whitepaper",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.add_wallet_befor" => [
                "text" => "Add your wallet address before buy",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.account_status" => [
                "text" => "Your Account Status",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.receiving_wallet" => [
                "text" => "Receiving Wallet",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.new_address" => [
                "text" => "New address under review for approve.",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.your_wallet" => [
                "text" => "Add Your Wallet Address",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.earn_with_referral" => [
                "text" => "Earn with Referral",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.invite_friends" => [
                "text" => "Invite your friends & family.",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.refer_link" => [
                "text" => "Use above link to refer your friend and get referral bonus.",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.contact_support_team" => [
                "text" => "Contact our support team via email",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.current_bonus" => [
                "text" => "Current Bonus",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.start_date" => [
                "text" => "Start Date",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.end_date" => [
                "text" => "End Date",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.bonus_start_in" => [
                "text" => "The Bonus Start in",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.bonus_end_in" => [
                "text" => "The Bonus End in",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.token_sales_progress" => [
                "text" => "Token Sales Progress",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.raised" => [
                "text" => "Raised",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.total" => [
                "text" => "Total",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.sales_end_in" => [
                "text" => "Sales End in",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.sales_end_at" => [
                "text" => "Sales End at",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.sales_start_in" => [
                "text" => "Sales Start in",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.sales_start_at" => [
                "text" => "Sales Start at",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.raised_amount" => [
                "text" => "Raised Amount",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.total_token" => [
                "text" => "Total Token",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.hard_cap" => [
                "text" => "Hard Cap",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.hardcap" => [
                "text" => "Hardcap",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.soft_cap" => [
                "text" => "Soft Cap",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.softcap" => [
                "text" => "Softcap",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.token_sales_finished" => [
                "text" => "Our token sales has been finished. Thank you very much for your contribution.",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.view_transaction" => [
                "text" => "View Transaction",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.tranx_types" => [
                "text" => "Types",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.tranx_type" => [
                "text" => "Type",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "dashboard.purchased_title" => [
                "text" => "Purchased",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.user_name" => [
                "text" => "User Name",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.title_note" => [
                "text" => "Note:",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.title_details" => [
                "text" => "Details",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              "dashboard.oops" => [
                "text" => "Oops!!!",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.token_title" => [
                "text" => "My :symbol Token",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.buy_more" => [
                "text" => "Buy More Token",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.equivalent_to" => [
                "text" => "Equivalent to",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.total_token_amount" => [
                "text" => "Total Token Amount",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.purchased_token" => [
                "text" => "Purchased Token",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.referral_token" => [
                "text" => "Referral Token",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.bonuses_token" => [
                "text" => "Bonuses Token",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],
              // v1.1.4
              "dashboard.total_contributed" => [
                "text" => "Total Contributed",
                "pages" => "dashboard, global",
                "panel" => "any",
                "load" => 0
              ],

            ///// USER PROFILE /////
              "profile.profile_details" => [
                "text" => "Profile Details",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.email_confirm" => [
                "text" => "Your password will only change after your confirmation by email.",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.personal_data" => [
                "text" => "Personal Data",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.settings" => [
                "text" => "Settings",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.full_name" => [
                "text" => "Full Name",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.email_address" => [
                "text" => "Email Address",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.mobile_number" => [
                "text" => "Mobile Number",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.date_of_birth" => [
                "text" => "Date of Birth",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.nationality" => [
                "text" => "Nationality",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.select_country" => [
                "text" => "Select Country",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.update_profile" => [
                "text" => "Update Profile",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.security_settings" => [
                "text" => "Security Settings",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.save_activities_log" => [
                "text" => "Save my activities log",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.confirm_email" => [
                "text" => "Confirm me through email before password change",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.manage_notification" => [
                "text" => "Manage Notification",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.resumption_notify" => [
                "text" => "Notify me by email about resumption of sales",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.sales_notify" => [
                "text" => "Notify me by email about sales and latest news",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.unusual_activity" => [
                "text" => "Alert me by email in case of unusual activity in my account",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.update" => [
                "text" => "Update",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.old_password" => [
                "text" => "Old Password",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.new_password" => [
                "text" => "New Password",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.confirm_password" => [
                "text" => "Confirm New Password",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.min_6_digit" => [
                "text" => "Password should be a minimum of 6 digits and include lower and uppercase letter.",
                "pages" => "user_profile, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.activitiy_log" => [
                "text" => "Account Activities Log",
                "pages" => "user_profile, user_activity, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.activitiy_clear" => [
                "text" => "Clear All",
                "pages" => "user_profile, user_activity, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.activitiy_note" => [
                "text" => "Here is your recent activities. You can clear this log as well as disable the feature from profile settings tabs.",
                "pages" => "user_profile, user_activity, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.activitiy_date" => [
                "text" => "Date",
                "pages" => "user_profile, user_activity, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.activitiy_device" => [
                "text" => "Device",
                "pages" => "user_profile, user_activity, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.activitiy_browser" => [
                "text" => "Browser",
                "pages" => "user_profile, user_activity, global",
                "panel" => "user",
                "load" => 0
              ],
              "profile.activitiy_ip" => [
                "text" => "IP",
                "pages" => "user_profile, user_activity, global",
                "panel" => "user",
                "load" => 0
              ],

            ///// USER TRANSACTION /////
              "profile.tranx_list" => [
                "text" => "Transactions list",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              "profile.tranx_amount" => [
                "text" => "Amount",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              "profile.tranx_from" => [
                "text" => "From",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              "profile.tranx_to" => [
                "text" => "To",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              "profile.tranx_pay" => [
                "text" => "Pay",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              "profile.token_types" => [
                "text" => "Token Types",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.tokens" => [
                "text" => "Tokens",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.tranx_no" => [
                "text" => "Tranx NO",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.no_data" => [
                "text" => "No data available in table",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.no_records" => [
                "text" => "No records",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.search_placeholder" => [
                "text" => "Type in to Search",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.cap_types" => [
                "text" => "TYPES",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.any_type" => [
                "text" => "Any Type",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.purchase" => [
                "text" => "Purchase",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.cap_status" => [
                "text" => "STATUS",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.cap_show_all" => [
                "text" => "Show All",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.approved" => [
                "text" => "Approved",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.pending" => [
                "text" => "Pending",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "profile.canceled" => [
                "text" => "Canceled",
                "pages" => "user_profile, user_transaction",
                "panel" => "user",
                "load" => 0
              ],

            ///// USER KYC /////
              "kyc.verify_title" => [
                "text" => "KYC Verification",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.verify_title_sub" => [
                "text" => "To comply with regulations each participant is required to go through identity verification (KYC/AML) to prevent fraud, money laundering operations, transactions banned under the sanctions regime or those which fund terrorism. Please, complete our fast and secure verification process to participate in token offerings.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.identity_title" => [
                "text" => "Identity Verification - KYC",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.identity_desc" => [
                "text" => "To comply with regulation, participant will have to go through identity verification.",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form_not_submitted" => [
                "text" => "You have not submitted your necessary documents to verify your identity.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form_submit" => [
                "text" => "It would great if you please submit the form. If you have any question, please feel free to contact our support team.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.complete_kyc" => [
                "text" => "Click here to complete your KYC",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.completed_kyc" => [
                "text" => "You have completed the process of KYC",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.waiting_id_verify" => [
                "text" => "We are still waiting for your identity verification. Once our team verified your identity, you will be notified by email. You can also check your KYC compliance status from your profile page.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.back_to_profile" => [
                "text" => "Back to Profile",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.under_process" => [
                "text" => "Your application verification under process.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.still_working" => [
                "text" => "We are still working on your identity verification. Once our team verified your identity, you will be notified by email.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.resubmit_form" => [
                "text" => "In our verification process, we found information that is incorrect or missing. Please resubmit the form. In case of any issues with the submission please contact our support team.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.submit_again" => [
                "text" => "Submit Again",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.verified_title" => [
                "text" => "Your identity verified successfully.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.verified_desc" => [
                "text" => "One of our team members verified your identity. Now you can participate in our token sale. Thank you.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.info_missing" => [
                "text" => "We found some information to be missing.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form_rejected" => [
                "text" => "Sorry! Your application was rejected.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.verify_head" => [
                "text" => "Begin your ID-Verification",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.verify_text" => [
                "text" => "In order to purchase our tokens, please verify your identity.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.verify_text_token" => [
                "text" => "Verify your identity to participate in token sale.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.not_submitted" => [
                "text" => "You have not submitted your documents to verify your identity (KYC).",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.click_proceed" => [
                "text" => "Click to Proceed",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.document_received" => [
                "text" => "We have received your document.",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.review_information" => [
                "text" => "We will review your information and if all is in order will approve your identity. You will be notified by email once we verified your identity (KYC).",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.kyc_rejected" => [
                "text" => "KYC Application has been rejected!",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.resubmit_application" => [
                "text" => "We were having difficulties verifying your identity. In our verification process, we found information are incorrect or missing. Please re-submit the application again and verify your identity.",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.resubmit" => [
                "text" => "Resubmit",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.kyc_verified" => [
                "text" => "Identity (KYC) has been verified.",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.token_sale" => [
                "text" => "One for our team verified your identity. You are eligible to participate in our token sale.",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.purchase_token" => [
                "text" => "Purchase Token",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.kyc_verification_required" => [
                "text" => "KYC verification required for purchase token",
                "pages" => "dashboard, global",
                "panel" => "user",
                "load" => 0
              ],

            ///// USER KYC FORM /////
              "kyc.form.personal_details" => [
                "text" => "Personal Details",
                "pages" => "kyc, kyc_form, global",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.personal_details_sub" => [
                "text" => "Your basic personal information is required for identification purposes.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.personal_details_note" => [
                "text" => "Please type carefully and fill out the form with your personal details. You are not allowed to edit the details once you have submitted the application.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.first_name" => [
                "text" => "First Name",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.last_name" => [
                "text" => "Last Name",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.phone_number" => [
                "text" => "Phone Number",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.gender" => [
                "text" => "Gender",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.select_gender" => [
                "text" => "Select Gender",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.male" => [
                "text" => "Male",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.female" => [
                "text" => "Female",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.other" => [
                "text" => "Other",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.telegram_username" => [
                "text" => "Telegram Username",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.your_address" => [
                "text" => "Your Address",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.country" => [
                "text" => "Country",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.state" => [
                "text" => "State",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.city" => [
                "text" => "City",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.postal_code" => [
                "text" => "Zip / Postal Code",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.address_line_1" => [
                "text" => "Address Line 1",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.address_line_2" => [
                "text" => "Address Line 2",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.document_upload" => [
                "text" => "Document Upload",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.scan_documents" => [
                "text" => "To verify your identity, we ask you to upload high-quality scans or photos of your official identification documents issued by the government.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.upload_documents" => [
                "text" => "In order to complete, please upload any of the following personal documents.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.passport" => [
                "text" => "Passport",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.national_id_card" => [
                "text" => "National ID Card",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.driver_license" => [
                "text" => "Driver’s License",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.check_requirements" => [
                "text" => "To avoid delays with verification process, please double-check to ensure the below requirements are fully met:",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.visible_documents" => [
                "text" => "Document should be in good condition and clearly visible.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.no_glare" => [
                "text" => "There is no light glare or reflections on the card.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.not_expire_credentials" => [
                "text" => "Chosen credential must not be expired.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.file_limitation" => [
                "text" => "File is at least 1 MB in size and has at least 300 dpi resolution.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.drag_and_drop_file" => [
                "text" => "Drag and drop file",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.select" => [
                "text" => "Select",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.or" => [
                "text" => "or",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.upload_doc_copy" => [
                "text" => "Upload Here Your :doctype Copy",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.nid_back_side" => [
                "text" => "Upload Here Your National ID Back Side",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.photo_selfie" => [
                "text" => "Upload a selfie as a Photo Proof while holding document in your hand",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.paying_wallet" => [
                "text" => "Your Paying Wallet",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.paying_wallet_submit" => [
                "text" => "Submit your wallet address that you are going to send funds",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.paying_wallet_note" => [
                "text" => "DO NOT USE your exchange wallet address such as Kraken, Bitfinex, Bithumb, Binance etc.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.select_wallet" => [
                "text" => "Select Wallet",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.enter_your_wallet" => [
                "text" => "Enter your wallet address",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.your_wallet_address" => [
                "text" => "Your personal wallet address",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.agree_terms" => [
                "text" => "I have read the",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.agree_info" => [
                "text" => "All the personal information I have entered is correct.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.agree_individual" => [
                "text" => "I certify that, I am registering to participate in the token distribution event(s) in the capacity of an individual (and beneficial owner) and not as an agent or representative of a third party corporate entity.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.agree_final" => [
                "text" => "I understand that, I can participate in the token distribution event(s) only with the wallet address that was entered in the application form.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.proceed" => [
                "text" => "Proceed to Verify",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.confirm_terms" => [
                "text" => "You should read our terms and policy.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.confirm_info" => [
                "text" => "Confirm that all information is correct.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.confirm_individual" => [
                "text" => "Certify that you are individual.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],
              "kyc.form.confirm_final" => [
                "text" => "Confirm that you understand.",
                "pages" => "kyc",
                "panel" => "user",
                "load" => 0
              ],

            ///// USER BUY TOKEN /////
              "buy_token.sale_start_soon" => [
                "text" => "Our sale will start soon. Please check back at a later date/time or feel free to contact us.",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.calculate" => [
                "text" => "Choose currency and calculate :SYMBOL token price",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.choose_currency" => [
                "text" => "You can buy our :SYMBOL token using the below currency choices to become part of our project.",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.amount_of_contribute" => [
                "text" => "Amount of contribute",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.contribute_amout" => [
                "text" => "Enter the amount you would like to contribute in order to calculate the amount of tokens you will receive. The calculator below helps to convert the required quantity of tokens into the amount of your selected currency.",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.minimum_amount" => [
                "text" => "Minimum contribution amount is required.",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.bonus" => [
                "text" => "Bonus",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.on_sale" => [
                "text" => "On Sale",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.sale_bonus" => [
                "text" => "Sale Bonus",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.amount_bonus" => [
                "text" => "Amount Bonus",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.exchange_note" => [
                "text" => "Your contribution will be calculated based on exchange rate at the moment when your transaction is confirmed.",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.payment_button" => [
                "text" => "Make Payment",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.token_note" => [
                "text" => "Tokens will appear in your account after payment successfully made and approved by our team. Please note that, :SYMBOL token will be distributed after the token sales end-date.",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.token_sales" => [
                "text" => "Token Sales",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.token_price" => [
                "text" => "Token Price",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.exchange_rate" => [
                "text" => "Exchange Rate",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.end_at" => [
                "text" => "End at",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.choose_method" => [
                "text" => "You can choose any of following payment method to make your payment. The token balance will appear in your account after successful payment.", // v1.1.4
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.select_method" => [
                "text" => "Select payment method:",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.appear_address" => [
                "text" => "Our payment address will appear or redirect you for payment after your order placed.",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],
              "buy_token.charge_fee" => [
                "text" => "Payment gateway may charge you a processing fees.",
                "pages" => "buy_token",
                "panel" => "user",
                "load" => 0
              ],

            ///// ORDER PROCEDD /////
              "payment.order.title" => [
                "text" => "Confirmation Your Payment",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.order.cancel_title" => [
                "text" => "Oops! Payment Canceled!",
                "pages" => "payment, cancel",
                "panel" => "user",
                "load" => 0
              ],
              "payment.order.cancel_desc" => [
                "text" => "You have canceled your payment. If you continue to have issues, please contact us with order no. :orderid.",
                "pages" => "payment, cancel",
                "panel" => "user",
                "load" => 0
              ],
              "payment.order.close" => [
                "text" => "Close",
                "pages" => "payment, cancel",
                "panel" => "user",
                "load" => 0
              ],
              "payment.order.placed" => [
                "text" => "Your Order no. :orderid has been placed successfully.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.order.pending" => [
                "text" => "Your Order no. :orderid has been placed & waiting for payment.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.order.onhold" => [
                "text" => "Your Order no. :orderid has been placed & waiting for team approval.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.info.crypto_amount" => [
                "text" => "Please send :amount :currency to the address below. The token balance will appear in your account only after transaction gets :num confirmation and approved by our team.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.info.crypto_receive" => [
                "text" => "To receiving :token :symbol token, please send :amount :currency to the address below. The token balance will appear in your account only after transaction gets :num confirmation and approved by our team.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.info.bank_amount" => [
                "text" => "Please make your payment of :amount :currency through bank to the below bank address. The token balance will appear in your account only after your transaction gets approved by our team.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.info.bank_receive" => [
                "text" => "To receiving :token :symbol token, please make your payment of :amount :currency through bank to the below bank address. The token balance will appear in your account only after your transaction gets approved by our team.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.info.gateway_amount" => [
                "text" => "The token balance will appear in your account only after you transaction gets :num confirmations and approved by :gateway.",
                "pages" => "payment, online",
                "panel" => "user",
                "load" => 0
              ],
              "payment.info.gateway_receive" => [
                "text" => "To receiving :token :symbol token, please make your payment of :amount :currency through :gateway. The token balance will appear in your account after we received your payment.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.info.wallet_verify" => [
                "text" => "To speed up verification process please enter your wallet address from where you'll transferring your amount to our address.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.info.hash_verify" => [
                "text" => "To speed up verification process, please enter your transaction hash or payment id.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.wallet_address" => [
                "text" => "Enter Your Wallet Address",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.payment_address" => [
                "text" => "Insert your payment address",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.cancel_order" => [
                "text" => "Cancel Order",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.select_method" => [
                "text" => "Select your payment method.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.note_hint" => [
                "text" => "Do not make payment through exchange (Kraken, Bitfinex). You can use MyEtherWallet, MetaMask, Mist wallets etc.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.note_alert" => [
                "text" => "In case you send a different amount, number of :SYMBOL token will update accordingly.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.note_redirect" => [
                "text" => "Our payment address will appear or redirect you for payment after the order is placed.",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.payment_process" => [
                "text" => "Payment Process",
                "pages" => "payment, global",
                "panel" => "user",
                "load" => 0
              ],
              "payment.payment_confirm" => [
                "text" => "Confirm Payment",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.skip_address" => [
                "text" => "I'll provide wallet address later",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],
              "payment.gas_limit" => [
                "text" => "SET GAS LIMIT:",
                "pages" => "payment, offline",
                "panel" => "user",
                "load" => 0
              ],
              "payment.gas_price" => [
                "text" => "SET GAS PRICE:",
                "pages" => "payment, offline",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "payment.send_amount_to" => [
                "text" => "Send Amount:",
                "pages" => "payment, order",
                "panel" => "user",
                "load" => 0
              ],

            ///// PAYMENT MANUAL CRYPTO /////
              "payment.amount_with_bonus" => [
                "text" => "Please make payment of :amount to receive :token_amount token including bonus :token_bonus token.",
                "pages" => "payment, manual",
                "panel" => "user",
                "load" => 0
              ],
              "payment.amount_no_bonus" => [
                "text" => "Please make payment of :amount to receive :token_amount token.",
                "pages" => "payment, manual",
                "panel" => "user",
                "load" => 0
              ],
              "payment.deposit_title" => [
                "text" => "Payment Address for Deposit",
                "pages" => "payment, manual",
                "panel" => "user",
                "load" => 0
              ],
              "payment.deposit_address" => [
                "text" => "Payment to the following :Name Wallet Address",
                "pages" => "payment, manual",
                "panel" => "user",
                "load" => 0
              ],
              "payment.deposit_address_or_scan" => [
                "text" => "Payment to the following :Name Wallet Address Or scan the QR code",
                "pages" => "payment, manual",
                "panel" => "user",
                "load" => 0
              ],
              "payment.scan_code" => [
                "text" => "Scan QR code to payment.",
                "pages" => "payment, manual",
                "panel" => "user",
                "load" => 0
              ],
              "payment.agree_terms" => [
                "text" => "I hereby agree to the token purchase agreement and token sale term.",
                "pages" => "payment, manual",
                "panel" => "user",
                "load" => 0
              ],
              "payment.no_method" => [
                "text" => "Sorry! There is no payment method available for this currency. Please choose another currency or contact our support team.",
                "pages" => "payment, manual",
                "panel" => "user",
                "load" => 0
              ],
              "payment.review_title" => [
                "text" => "We're reviewing your payment.",
                "pages" => "payment, manual",
                "panel" => "user",
                "load" => 0
              ],
              "payment.review_desc" => [
                "text" => "We'll review your transaction and get back to your within 6 hours. You'll receive an email with the details of your contribution.",
                "pages" => "payment, manual",
                "panel" => "user",
                "load" => 0
              ],

            ///// PAYMENT ONLINE /////
              "payment.already_paid" => [
                "text" => "Click here if you already paid",
                "pages" => "payment, online",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bellow_address" => [
                "text" => "Make your payment to the below address",
                "pages" => "payment, online",
                "panel" => "user",
                "load" => 0
              ],
              "payment.check_status" => [
                "text" => "Check Status",
                "pages" => "payment, online",
                "panel" => "user",
                "load" => 0
              ],
              "payment.gateway_status" => [
                "text" => "Check status on :gateway",
                "pages" => "payment, online",
                "panel" => "user",
                "load" => 0
              ],
              "payment.send_amount" => [
                "text" => "Please send :amount :currency to the address below.",
                "pages" => "payment, online",
                "panel" => "user",
                "load" => 0
              ],
              "payment.pay_or_cancel" => [
                "text" => "Click the Pay button below to make payment for this transaction, or you can cancel this transaction.",
                "pages" => "payment, online",
                "panel" => "user",
                "load" => 0
              ],
              "payment.refunded_token" => [
                "text" => "Refunded Token",
                "pages" => "payment, online",
                "panel" => "user",
                "load" => 0
              ],
              "payment.refunded_amount" => [
                "text" => "Refunded Amount",
                "pages" => "payment, online",
                "panel" => "user",
                "load" => 0
              ],
              "payment.refund_note" => [
                "text" => "Refund Note",
                "pages" => "payment, online",
                "panel" => "user",
                "load" => 0
              ],

            ///// PAYMENT BANK TRANSFER /////
              "payment.bank_details" => [
                "text" => "Bank Details for Payment",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bank_note" => [
                "text" => "The token balance will appear in your account only after your transaction gets approved by our team.",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bank_iban" => [
                "text" => "IBAN",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bank_name" => [
                "text" => "Bank Name",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bank_address" => [
                "text" => "Bank Address",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bank_routing" => [
                "text" => "Routing Number",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bank_swift" => [
                "text" => "Swift/BIC",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bank_account_name" => [
                "text" => "Account Name",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bank_account_number" => [
                "text" => "Account Number",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bank_account_address" => [
                "text" => "Account Holder Address",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bank_make_payment" => [
                "text" => "Make Payment to the Following Bank Account",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.bank_referance" => [
                "text" => "Use this transaction id (#:orderid) as reference. Make your payment within 24 hours, If we will not received your payment within 24 hours, then we will cancel the transaction.",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],
              "payment.pay_via" => [
                "text" => "Pay via",
                "pages" => "payment, bank",
                "panel" => "user",
                "load" => 0
              ],

            ///// TRANSACTION /////
              "tranx.details" => [
                "text" => "Transaction Details",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.order_no" => [
                "text" => "The order no. :orderid was placed on :datetime.",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.successfully_paid" => [
                "text" => "You have successfully paid this transaction",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.via" => [
                "text" => "via",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.wallet" => [
                "text" => "wallet",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.order_failed" => [
                "text" => "Sorry! Your order has been :status due to payment.",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.canceled_transfer_request" => [
                "text" => "The transfer request was canceled at :time.",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.under_review" => [
                "text" => "The transaction is currently under review. We will send you an email once our review is complete.",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.canceled_admin" => [
                "text" => "The transaction was canceled by Administrator at :time.",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.canceled_user" => [
                "text" => "You have canceled this transaction.",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.approved_admin" => [
                "text" => "Transaction has been approved at :time.",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.token_details" => [
                "text" => "Token Details",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.token_of_stage" => [
                "text" => "Token of Stage",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.token_amount" => [
                "text" => "Token Amount (T)",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.bonus_token" => [
                "text" => "Bonus Token (B)",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.offline_payment" => [
                "text" => "Offline Payment",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.online_payment" => [
                "text" => "Online Payment",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],
              "tranx.issue_occured" => [
                "text" => "Sorry, seems there is an issues occurred and we couldn’t process your request. Please contact us with your order no. :orderid, if you continue to having the issues.",
                "pages" => "transaction, global",
                "panel" => "user",
                "load" => 0
              ],

            ///// USER WALLET /////
              "wallet.do_not_use" => [
                "text" => "DO NOT USE your exchange wallet address OR if you don't have a private key of the your address. You WILL NOT receive your token and WILL LOSE YOUR FUNDS if you do.",
                "pages" => "user_wallet",
                "panel" => "user",
                "load" => 0
              ],
              "wallet.erc_address" => [
                "text" => "Address should be ERC20-compliant.",
                "pages" => "user_wallet",
                "panel" => "user",
                "load" => 0
              ],
              "wallet.receive_info" => [
                "text" => "In order to receive your :SYMBOL token, please select your wallet address and you have to put the address below input box. You will receive :SYMBOL token to this address after the token sale end.",
                "pages" => "user_wallet",
                "panel" => "user",
                "load" => 0
              ],

              "wallet.current" => [
                "text" => "Current Wallet",
                "pages" => "user_wallet",
                "panel" => "user",
                "load" => 0
              ],
              "wallet.type" => [
                "text" => "Wallet Type",
                "pages" => "user_wallet",
                "panel" => "user",
                "load" => 0
              ],
              "wallet.receive_address" => [
                "text" => ":Name Wallet Address for receiving token",
                "pages" => "user_wallet",
                "panel" => "user",
                "load" => 0
              ],
              "wallet.add_wallet" => [
                "text" => "Add Wallet",
                "pages" => "user_wallet",
                "panel" => "user",
                "load" => 0
              ],
              "wallet.enter_wallet" => [
                "text" => "Enter your :Name wallet address",
                "pages" => "user_wallet",
                "panel" => "user",
                "load" => 0
              ],
              "wallet.enter_new_wallet" => [
                "text" => "Enter your new :Name wallet address",
                "pages" => "user_wallet",
                "panel" => "user",
                "load" => 0
              ],
              "wallet.request" => [
                "text" => "Request for change",
                "pages" => "user_wallet",
                "panel" => "user",
                "load" => 0
              ],
              "wallet.request_update" => [
                "text" => "Request for Update Wallet",
                "pages" => "user_wallet",
                "panel" => "user",
                "load" => 0
              ],

            ///// STATUS /////
              "status.approved" => [
                "text" => "Approved",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.pending" => [
                "text" => "Pending",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.progress" => [
                "text" => "Progress",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.rejected" => [
                "text" => "Rejected",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.warning" => [
                "text" => "Warning",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.success" => [
                "text" => "Success",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.running" => [
                "text" => "Running",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.upcoming" => [
                "text" => "Upcoming",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.completed" => [
                "text" => "Completed",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.expired" => [
                "text" => "Expired",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.pause" => [
                "text" => "Pause",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.inactive" => [
                "text" => "Inactive",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.edit" => [
                "text" => "Edit",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.add" => [
                "text" => "Add",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.see" => [
                "text" => "See",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.more" => [
                "text" => "More",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.view" => [
                "text" => "View",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.enable" => [
                "text" => "Enable",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.enabled" => [
                "text" => "Enabled",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.disable" => [
                "text" => "Disable",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],
              "status.disabled" => [
                "text" => "Disabled",
                "pages" => "status, global",
                "panel" => "user",
                "load" => 0
              ],

            ///// AUTH 2FA /////
              "auth.2fa.hello" => [
                "text" => "Hello",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.title" => [
                "text" => "2FA",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.reset_2fa" => [
                "text" => "Reset 2FA",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.enable_2fa" => [
                "text" => "Enable 2FA",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.confirm_2fa" => [
                "text" => "Confirm 2FA",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.disable_2fa" => [
                "text" => "Disable 2FA",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.current_status" => [
                "text" => "Current Status:",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.authentication" => [
                "text" => "2FA Authentication",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.two-factor_verification" => [
                "text" => "Two-Factor Verification",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.verify_code" => [
                "text" => "Enter the Code to verify",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.reset_authentication" => [
                "text" => "Reset 2FA Authentication",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.authentication_code" => [
                "text" => "Enter your authentication code",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.google_code" => [
                "text" => "Enter Google Authenticator Code",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.auth" => [
                "text" => "Two-factor authentication is a method for protection of your account. When it is activated you are required to enter not only your password, but also a special code. You can receive this code in mobile app. Even if third party gets access to your password, they still won't be able to access your account without the 2FA code.",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.lost_access" => [
                "text" => "If you lost your phone or uninstall the Google Authenticator app, then you will lost access of your account.",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.step_2" => [
                "text" => "Step 2:",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.scan_qr_code" => [
                "text" => "Scan the below QR code by your Google Authenticator app, or you can add account manually.",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.manually_add_account" => [
                "text" => "Manually add Account:",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.account_name" => [
                "text" => "Account Name:",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.key" => [
                "text" => "Key:",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],

              "auth.2fa.verification_code" => [
                "text" => "Enter the verification code generated by your mobile application (Google Authenticator).",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.contact_us" => [
                "text" => "If you lost your phone or Uninstall the Google Authenticator app and enable to access your account please contact with us.",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.step_1" => [
                "text" => "Step 1:",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.install_this_app_from" => [
                "text" => "Install this app from",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.google_play" => [
                "text" => "Google Play",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.store_or" => [
                "text" => "store or",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.app_store" => [
                "text" => "App Store",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.reset_auth" => [
                "text" => "Please enter your account password to reset 2FA authentication.",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.invalid" => [
                "text" => "You have provide a invalid 2FA authentication code!",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.wrong" => [
                "text" => "Please enter a valid authentication code!",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.enabled" => [
                "text" => "Successfully enable 2FA security in your account.",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],
              "auth.2fa.disabled" => [
                "text" => "Successfully disable 2FA security in your account.",
                "pages" => "user_2fa",
                "panel" => "user",
                "load" => 0
              ],

            ///// REFERRAL /////
              "referral.referee" => [
                "text" => "Referee",
                "pages" => "referral",
                "panel" => "user",
                "load" => 0
              ],
              "referral.earn_token" => [
                "text" => "Earn Token",
                "pages" => "referral",
                "panel" => "user",
                "load" => 0
              ],
              "referral.bought_token" => [
                "text" => "Bought Token",
                "pages" => "referral",
                "panel" => "user",
                "load" => 0
              ],
              "referral.register_date" => [
                "text" => "Register Date",
                "pages" => "referral",
                "panel" => "user",
                "load" => 0
              ],
              "referral.no_one_join_yet!" => [
                "text" => "No one join yet!",
                "pages" => "referral",
                "panel" => "user",
                "load" => 0
              ],
              "referral.not_purchased" => [
                "text" => "Not purchased yet",
                "pages" => "referral",
                "panel" => "user",
                "load" => 0
              ],
              "referral.token_purchase" => [
                "text" => "Token purchase by",
                "pages" => "referral",
                "panel" => "user",
                "load" => 0
              ],
              "referral.referred_by" => [
                "text" => "You are referred by",
                "pages" => "referral",
                "panel" => "user",
                "load" => 0
              ],
              "referral.received_token" => [
                "text" => "You have received bonus token.",
                "pages" => "referral",
                "panel" => "user",
                "load" => 0
              ],
              // v1.1.4
              "referral.refered_by" => [
                "text" => "Your were invited by :userid",
                "pages" => "referral",
                "panel" => "user",
                "load" => 0
              ] 
        ];
        
        return $global;
    }
}
