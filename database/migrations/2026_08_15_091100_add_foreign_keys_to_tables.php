<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign('employee_id', 'attendances_employee_id_foreign')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->foreign('employee_id', 'contracts_employee_id_foreign')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::table('credittransactions', function (Blueprint $table) {
            $table->foreign('branchs_id', 'credittransactions_branchs_id_foreign')->references('id')->on('branchs')->onDelete('cascade');
            $table->foreign('user_id', 'credittransactions_user_id_foreign')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('custodies', function (Blueprint $table) {
            $table->foreign('employee_id', 'custodies_employee_id_foreign')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::table('daily_records', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_daily_user')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->foreign('customer_id', 'fk_customer')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id', 'fk_product')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('delivery_product_to_the_customers', function (Blueprint $table) {
            $table->foreign('invoice_id', 'delivery_product_to_the_customers_invoice_id_foreign')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('product_id', 'delivery_product_to_the_customers_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('end_of_services', function (Blueprint $table) {
            $table->foreign('employee_id', 'end_of_services_employee_id_foreign')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreign('reasonId_id', 'expenses_reasonid_id_foreign')->references('id')->on('expenses_reasons')->onDelete('cascade');
            $table->foreign('user_id', 'expenses_user_id_foreign')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('increaseـor_deduction_employees', function (Blueprint $table) {
            $table->foreign('employee_id', 'increaseـor_deduction_employees_employee_id_foreign')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('user_id', 'invoices_user_id_foreign')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->foreign('employee_id', 'leaves_employee_id_foreign')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->foreign('permission_id', 'model_has_permissions_permission_id_foreign')->references('id')->on('permissions')->onDelete('cascade');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->foreign('role_id', 'model_has_roles_role_id_foreign')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::table('offer_price_to_customers', function (Blueprint $table) {
            $table->foreign('customer_id', 'offer_price_to_customers_customer_id_foreign')->references('id')->on('customers')->onDelete('cascade');
        });

        Schema::table('offer_price_to_customer_items', function (Blueprint $table) {
            $table->foreign('order_id', 'offer_price_to_customer_items_order_id_foreign')->references('id')->on('offer_price_to_customers')->onDelete('cascade');
            $table->foreign('product_id', 'offer_price_to_customer_items_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->foreign('order_owner', 'order_details_order_owner_foreign')->references('id')->on('order_tosuplliers')->onDelete('cascade');
            $table->foreign('product_id', 'order_details_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('order_price_from_suppliers', function (Blueprint $table) {
            $table->foreign('suplier_id', 'order_price_from_suppliers_suplier_id_foreign')->references('id')->on('suplliers')->onDelete('cascade');
        });

        Schema::table('order_price_from_supplier_items', function (Blueprint $table) {
            $table->foreign('order_id', 'order_price_from_supplier_items_order_id_foreign')->references('id')->on('order_price_from_suppliers')->onDelete('cascade');
            $table->foreign('product_id', 'order_price_from_supplier_items_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('order_tosuplliers', function (Blueprint $table) {
            $table->foreign('suplier_id', 'order_tosuplliers_suplier_id_foreign')->references('id')->on('suplliers')->onDelete('cascade');
            $table->foreign('user_id', 'order_tosuplliers_user_id_foreign')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign('attendances_employee_id_foreign');
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign('contracts_employee_id_foreign');
        });

        Schema::table('credittransactions', function (Blueprint $table) {
            $table->dropForeign('credittransactions_branchs_id_foreign');
            $table->dropForeign('credittransactions_user_id_foreign');
        });

        Schema::table('custodies', function (Blueprint $table) {
            $table->dropForeign('custodies_employee_id_foreign');
        });

        Schema::table('daily_records', function (Blueprint $table) {
            $table->dropForeign('fk_daily_user');
        });

        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->dropForeign('fk_customer');
            $table->dropForeign('fk_product');
        });

        Schema::table('delivery_product_to_the_customers', function (Blueprint $table) {
            $table->dropForeign('delivery_product_to_the_customers_invoice_id_foreign');
            $table->dropForeign('delivery_product_to_the_customers_product_id_foreign');
        });

        Schema::table('end_of_services', function (Blueprint $table) {
            $table->dropForeign('end_of_services_employee_id_foreign');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign('expenses_reasonid_id_foreign');
            $table->dropForeign('expenses_user_id_foreign');
        });

        Schema::table('increaseـor_deduction_employees', function (Blueprint $table) {
            $table->dropForeign('increaseـor_deduction_employees_employee_id_foreign');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('invoices_user_id_foreign');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->dropForeign('leaves_employee_id_foreign');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropForeign('model_has_permissions_permission_id_foreign');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropForeign('model_has_roles_role_id_foreign');
        });

        Schema::table('offer_price_to_customers', function (Blueprint $table) {
            $table->dropForeign('offer_price_to_customers_customer_id_foreign');
        });

        Schema::table('offer_price_to_customer_items', function (Blueprint $table) {
            $table->dropForeign('offer_price_to_customer_items_order_id_foreign');
            $table->dropForeign('offer_price_to_customer_items_product_id_foreign');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign('order_details_order_owner_foreign');
            $table->dropForeign('order_details_product_id_foreign');
        });

        Schema::table('order_price_from_suppliers', function (Blueprint $table) {
            $table->dropForeign('order_price_from_suppliers_suplier_id_foreign');
        });

        Schema::table('order_price_from_supplier_items', function (Blueprint $table) {
            $table->dropForeign('order_price_from_supplier_items_order_id_foreign');
            $table->dropForeign('order_price_from_supplier_items_product_id_foreign');
        });

        Schema::table('order_tosuplliers', function (Blueprint $table) {
            $table->dropForeign('order_tosuplliers_suplier_id_foreign');
            $table->dropForeign('order_tosuplliers_user_id_foreign');
        });
    }
};
