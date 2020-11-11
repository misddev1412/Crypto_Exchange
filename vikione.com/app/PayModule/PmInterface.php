<?php
namespace App\PayModule;

/**
 * Pay Module Interface
 */

use Illuminate\Http\Request;

interface PmInterface
{
    /**
     * Declare the necessary routes here
     */
    public function routes();
    /**
     * Show the Payment Card in Admin panel
     */
    public function admin_views();
    /**
     * Return the transaction payment details for email template
     */
    public function email_details($transaction);
    /**
     * Show card details and edit
     */
    public function admin_views_details();
    /**
     * Show action in buy token page
     */
    public function show_action();
    /**
     * Check currency is active
     */
    public function check_currency();
    /**
     * Show transaction details in user panel
     */
    public function transaction_details($transaction);
    /**
     * Create the actual transaction
     */
    public function create_transaction(Request $request);
    /**
     * Save data in Admin panel
     */
    public function save_data(Request $request);
    /**
     * Insert the necessary data in DB
     */
    public function demo_data();
}
