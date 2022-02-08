<?php

class ProductApisTestCest
{
    // Create Product Test
    public function createProductViaAPI(\ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/create-product', [
            'name' => 'Test Product',
            'category_id' => '1',
            'sku' => '234234234',
            'price' => '12.90'
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":true,"message":"Product has been added successfully."}');
    }

    // Update Product Test
    public function updateProductViaAPI(\ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/update-product/2', [
            'name' => 'Test Product Update',
            'category_id' => '2',
            'sku' => '1212312',
            'price' => '16.00'
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":true,"message":"Product has been updated successfully."}');
    }

    // Delete Product Test
    public function deleteProductViaAPI(\ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/delete-product/2');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":true,"message":"Product deleted successfully."}');
    }
}
